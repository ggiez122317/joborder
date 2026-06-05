<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

use App\Libraries\Response;

use App\Models\App;
use App\Models\AppModule;
use App\Models\AppAction;
use App\Models\AppModuleAction;
use App\Models\AppModuleGroup;
use App\Models\AuditLog;
use App\Models\AuditLogDetail;
use App\Models\AuthenticationLog;
use App\Models\Configuration;
use App\Models\JobPosition;
use App\Models\Token;
use App\Models\User;
use App\Models\UserAccess;
use App\Models\UserType;
use App\Models\UserTypeAccess;

class AuthenticationLogController extends MasterController
{

    protected $response;
    protected $module;
    protected $controller;
    protected $logTitle;
    protected $table;
    protected $tablePrimaryKey;
    protected $page;
    protected $view_path;
    protected $moduleActionIDs;
    protected $auditFieldValues;
    protected $data;

    public function __construct()
    {
        $this->response         = new Response();
        $this->module           = 'Authentication Logs';
        $this->controller       = 'authentication-logs';
        $this->logTitle         = 'Authentication Log';
        $this->table            = 'AuthenticationLogs';
        $this->tablePrimaryKey  = 'authenticationLogID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'     => 17, 
            'PrintList' => 55, 
            'Insert'    => 0, 
            'View'      => 0, 
            'Audit'     => 0, 
            'Update'    => 0, 
            'Delete'    => 0, 
        ];
        // id => [label, table, field]
        $this->auditFieldValues = [];
    }

    private function _setVariables()
    {
        $this->data['controller'] = $this->controller;
        $this->data['title'] = $this->module;
        $this->data['page'] = $this->page;
    }

    // ******************** Views ********************
    public function index()
    {

        // initialize variables
        $this->page = '';
        $this->_setVariables();
        $data = $this->data;

        return view($this->view_path."/".($this->page?strtolower($this->page):'index'), $data);

    }

    public function print_list(Request $request)
    {

        // initialize variables
        $this->page = 'print';
        $this->_setVariables();
        $data = $this->data;


        $queryParams = request()->all();
        
        $filter_variables = [
            [
                'name'      => 'Username', 
                'connector' => 'has', 
                'variable'  => 'username', 
            ], 
            [
                'name'      => 'IP Address', 
                'connector' => 'has', 
                'variable'  => 'ipAddress', 
            ], 
            [
                'name'      => 'User Agent', 
                'connector' => 'has', 
                'variable'  => 'userAgent', 
            ], 
            [
                'name'      => 'Remarks', 
                'connector' => 'has', 
                'variable'  => 'remarks', 
            ], 
            [
                'name'      => 'Status', 
                'connector' => 'is', 
                'variable'  => 'status', 
            ], 
        ];
        $sortField = "";

        $filters = [];
        if ($filter_variables) {
            $statusNames = ['Failed', 'Logout', 'Success'];
            foreach ($filter_variables as $fv) {
                if (isset($queryParams['sortField'])) {
                    if ($queryParams['sortField'] == $fv['variable']) $sortField = $fv['name'];
                }
                if (isset($queryParams[$fv['variable']])) {
                    $value = " {$fv['connector']} \"{$queryParams[$fv['variable']]}\"";
                    if ($fv['connector'] == 'is' && $fv['variable'] == 'status') {
                        $value = " {$fv['connector']} \"{$statusNames[$queryParams[$fv['variable']]+1]}\"";
                    }
                    $filters[] = [
                        'start' => "", 
                        'name'  => "- {$fv['name']}", 
                        'value' => $value, 
                    ];
                }
            }
        }
        if ($sortField && $queryParams['sortBy']) {
            $orders = [
                'asc'   => ' in ascending order (A to Z, 0 to 9)', 
                'desc'  => ' in descending order (Z to A, 9 to 0)', 
            ];
            $filters[] = [
                'start' => " - Sorted by ", 
                'name'  => $sortField, 
                'value' => $orders[$queryParams['sortBy']], 
            ];
        }

        $data['title'] = strtoupper($this->logTitle);
        $data['filters'] = json_encode($filters);
        $data['query'] = $request->getQueryString();
        $data['headerImage'] = $this->_convertImageToBase64('assets/img/banner/logo_banner.jpg');

        return view($this->view_path."/".($this->page?strtolower($this->page):'index'), $data);

    }

    // ******************** APIs ********************
    public function items(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Index'])) {

                /** variables */
                $records = [];
                $filters = [];
                $row_shown_first    = 0;
                $row_shown_last     = 0;

                $dateInsertedFrom   = '';
                $dateInsertedTo     = '';
                $dateInserted       = isset($_GET['dateInserted']) ? $_GET['dateInserted'] : '';
                if ($dateInserted) {
                    if (strpos($_GET['dateInserted'], " to ") !== false) {
                        $dates = explode(" to ", $_GET['dateInserted']);
                        if ($dates) {
                            $dateInsertedFrom   = trim($dates[0]);
                            $dateInsertedTo     = trim($dates[1]);
                        }
                    } else {
                        $dateInsertedFrom   = $_GET['dateInserted'];
                        $dateInsertedTo     = $_GET['dateInserted'];
                    }
                }

                /** paging */
                $filters['dateInsertedFrom']    = $dateInsertedFrom;
                $filters['dateInsertedTo']      = $dateInsertedTo;
                $filters['limit']               = isset($_GET['limit']) ? trim($_GET['limit']) : 10;
                $filters['page']                = $request->query('page', 1);
                $filters['sortField']           = $request->query('sortField', '');
                $filters['sortBy']              = $request->query('sortBy', '');

                /** conditions */
                $conditions = [
                    'username'  => [$this->table, 'likeboth'], 
                    'ipAddress' => [$this->table, 'likeboth'], 
                    'userAgent' => [$this->table, 'likeboth'], 
                    'remarks'   => [$this->table, 'likeboth'], 
                    'status'    => [$this->table, 'where'], 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'username'      => [$this->table, 'username'], 
                    'ipAddress'     => [$this->table, 'ipAddress'], 
                    'userAgent'     => [$this->table, 'userAgent'], 
                    'remarks'       => [$this->table, 'remarks'], 
                    'dateInserted'  => [$this->table, 'dateInserted'], 
                    'status'        => [$this->table, 'status'], 
                ];

                // query count
                $query = DB::table($this->table);
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = isset($_GET[$cField]) ? $_GET[$cField] : '';
                        if (!in_array($value, ['', null]) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if (!in_array($value, ['', null]) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                    }
                    if ($dateInsertedFrom && $dateInsertedTo) {
                        $dateInsertedFrom   = date('Y-m-d', strtotime($dateInsertedFrom));
                        $dateInsertedTo     = date('Y-m-d', strtotime($dateInsertedTo));
                        $query = $query->where("{$this->table}.dateInserted", '>=', "{$dateInsertedFrom} 00:00:00");
                        $query = $query->where("{$this->table}.dateInserted", '<=', "{$dateInsertedTo} 23:59:59");
                    }
                }
                $filters['row_total'] = $query->count();

                $pages = 1;
                if ($filters['limit'] && $filters['row_total']) {
                    $pages = ceil($filters['row_total']/$filters['limit']);
                }
                $filters['pages'] = $pages;

                if ($filters['page'] > $filters['pages']) $filters['page'] = 1; 
        
                /** query */
                $query = DB::table($this->table);
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = isset($_GET[$cField]) ? $_GET[$cField] : '';
                        if (!in_array($value, ['', null]) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if (!in_array($value, ['', null]) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                        // filters 
                        $filters[$cField] = $value;
                    }
                    if ($dateInsertedFrom && $dateInsertedTo) {
                        $dateInsertedFrom   = date('Y-m-d', strtotime($dateInsertedFrom));
                        $dateInsertedTo     = date('Y-m-d', strtotime($dateInsertedTo));
                        $query = $query->where("{$this->table}.dateInserted", '>=', "{$dateInsertedFrom} 00:00:00");
                        $query = $query->where("{$this->table}.dateInserted", '<=', "{$dateInsertedTo} 23:59:59");
                    }
                    if ($filters['sortField']) $query = $query->orderBy($sort_tables[$filters['sortField']][0].".".$sort_tables[$filters['sortField']][1], $filters['sortBy']);
                    $query = $query->orderBy($this->table.".".$this->tablePrimaryKey, 'desc');
                }
                # limit
                $offset = 0;
                if ($filters['limit'] && $filters['row_total']) {
                    $offset = ($filters['limit']*$filters['page'])-$filters['limit'];
                    $query = $query->offset($offset)->limit($filters['limit']);
                }
                $temp_records = $query->get();

                $row_shown_first = count($temp_records) ? ($offset+1) : 0;
                $row_shown_last  = $offset+count($temp_records);
        
                if ($temp_records) {
                    foreach ($temp_records as $tr) {
                        $records[] = [
                            'authenticationLogID'   => Crypt::encryptString("{$tr->authenticationLogID}"), 
                            'username'              => $tr->username, 
                            'ipAddress'             => $tr->ipAddress, 
                            'userAgent'             => $tr->userAgent, 
                            'remarks'               => $tr->remarks, 
                            'dateInserted'          => date('m/d/y h:ia', strtotime($tr->dateInserted)), 
                            'status'                => $tr->status, 
                        ];
                    }
                }
                
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
                $items['hasButtonPrint']    = $this->_checkAccess($token_userID, $this->moduleActionIDs['PrintList']);
                $items['hasButtonAdd']      = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $items['hasButtonView']     = $this->_checkAccess($token_userID, $this->moduleActionIDs['View']);
                $items['statuses'] = [['status'=>1,'name'=>'Success'], ['status'=>0,'name'=>'Logout'], ['status'=>-1,'name'=>'Failed'] ];
                $items['statusNames'] = ['Failed', 'Logout', 'Success'];
                $items['records'] = $records;
                $items['filters'] = $filters;

                $data['items'] = $items;

            } else {
                $data = $this->response->status(401, 'Access denied.');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    public function print_items(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['PrintList'])) {
                /** variables */
                $records = [];
                $filters = [];
                $row_shown_first    = 0;
                $row_shown_last     = 0;

                $dateInsertedFrom   = '';
                $dateInsertedTo     = '';
                $dateInserted       = isset($_GET['dateInserted']) ? $_GET['dateInserted'] : '';
                if ($dateInserted) {
                    if (strpos($_GET['dateInserted'], " to ") !== false) {
                        $dates = explode(" to ", $_GET['dateInserted']);
                        if ($dates) {
                            $dateInsertedFrom   = trim($dates[0]);
                            $dateInsertedTo     = trim($dates[1]);
                        }
                    } else {
                        $dateInsertedFrom   = $_GET['dateInserted'];
                        $dateInsertedTo     = $_GET['dateInserted'];
                    }
                }

                /** paging */
                $filters['dateInsertedFrom']    = $dateInsertedFrom;
                $filters['dateInsertedTo']      = $dateInsertedTo;
                $filters['limit']       = isset($_GET['limit']) ? trim($_GET['limit']) : 10;
                $filters['page']        = $request->query('page', 1);
                $filters['sortField']   = $request->query('sortField', '');
                $filters['sortBy']      = $request->query('sortBy', '');

                /** conditions */
                $conditions = [
                    'username'  => [$this->table, 'likeboth'], 
                    'ipAddress' => [$this->table, 'likeboth'], 
                    'userAgent' => [$this->table, 'likeboth'], 
                    'remarks'   => [$this->table, 'likeboth'], 
                    'status'    => [$this->table, 'where'], 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'username'      => [$this->table, 'username'], 
                    'ipAddress'     => [$this->table, 'ipAddress'], 
                    'userAgent'     => [$this->table, 'userAgent'], 
                    'remarks'       => [$this->table, 'remarks'], 
                    'dateInserted'  => [$this->table, 'dateInserted'], 
                    'status'        => [$this->table, 'status'], 
                ];

                // query count
                $query = DB::table($this->table);
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = isset($_GET[$cField]) ? $_GET[$cField] : '';
                        if (!in_array($value, ['', null]) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if (!in_array($value, ['', null]) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                    }
                    if ($dateInsertedFrom && $dateInsertedTo) {
                        $dateInsertedFrom   = date('Y-m-d', strtotime($dateInsertedFrom));
                        $dateInsertedTo     = date('Y-m-d', strtotime($dateInsertedTo));
                        $query = $query->where("{$this->table}.dateInserted", '>=', "{$dateInsertedFrom} 00:00:00");
                        $query = $query->where("{$this->table}.dateInserted", '<=', "{$dateInsertedTo} 23:59:59");
                    }
                }
                $filters['row_total'] = $query->count();

                $pages = 1;
                if ($filters['limit'] && $filters['row_total']) {
                    $pages = ceil($filters['row_total']/$filters['limit']);
                }
                $filters['pages'] = $pages;

                if ($filters['page'] > $filters['pages']) $filters['page'] = 1; 
        
                /** query */
                $query = DB::table($this->table);
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = isset($_GET[$cField]) ? $_GET[$cField] : '';
                        if (!in_array($value, ['', null]) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if (!in_array($value, ['', null]) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                        // filters 
                        $filters[$cField] = $value;
                    }
                    if ($dateInsertedFrom && $dateInsertedTo) {
                        $dateInsertedFrom   = date('Y-m-d', strtotime($dateInsertedFrom));
                        $dateInsertedTo     = date('Y-m-d', strtotime($dateInsertedTo));
                        $query = $query->where("{$this->table}.dateInserted", '>=', "{$dateInsertedFrom} 00:00:00");
                        $query = $query->where("{$this->table}.dateInserted", '<=', "{$dateInsertedTo} 23:59:59");
                    }
                    if ($filters['sortField']) $query = $query->orderBy($sort_tables[$filters['sortField']][0].".".$sort_tables[$filters['sortField']][1], $filters['sortBy']);
                    $query = $query->orderBy($this->table.".".$this->tablePrimaryKey, 'desc');
                }
                // # limit
                $offset = 0;
                // if ($filters['limit'] && $filters['row_total']) {
                //     $offset = ($filters['limit']*$filters['page'])-$filters['limit'];
                //     $query = $query->offset($offset)->limit($filters['limit']);
                // }
                $temp_records = $query->get();

                $row_shown_first = count($temp_records) ? ($offset+1) : 0;
                $row_shown_last  = $offset+count($temp_records);
        
                if ($temp_records) {
                    foreach ($temp_records as $tr) {
                        $records[] = [
                            'authenticationLogID'   => Crypt::encryptString("{$tr->authenticationLogID}"), 
                            'username'              => $tr->username, 
                            'ipAddress'             => $tr->ipAddress, 
                            'userAgent'             => $tr->userAgent, 
                            'remarks'               => $tr->remarks, 
                            'dateInserted'          => date('m/d/y h:ia', strtotime($tr->dateInserted)), 
                            'status'                => $tr->status, 
                        ];
                    }
                }
                
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
                $items['statusNames'] = ['Failed', 'Logout', 'Success'];
                $items['records'] = $records;
                $items['filters'] = $filters;

                $data['items'] = $items;

            } else {
                $data = $this->response->status(401, 'Access denied.');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 
    
}


