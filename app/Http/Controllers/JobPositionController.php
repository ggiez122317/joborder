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

class JobPositionController extends MasterController
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
        $this->module           = 'Job Positions';
        $this->controller       = 'job-positions';
        $this->logTitle         = 'Job Position';
        $this->table            = 'JobPositions';
        $this->tablePrimaryKey  = 'jobPositionID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'     => 11, 
            'PrintList' => 31, 
            'Insert'    => 32, 
            'View'      => 33, 
            'Audit'     => 34, 
            'Update'    => 35, 
            'Delete'    => 0, 
        ];
        // id => [label, table, field]
        $this->auditFieldValues = [
            'code'          => ['Job Position Code', '', ''], 
            'name'          => ['Job Position Name', '', ''], 
            'description'   => ['Description', '', ''], 
        ];
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
                'name'      => 'Job Position Code', 
                'connector' => 'has', 
                'variable'  => 'code', 
            ], 
            [
                'name'      => 'Job Position Name', 
                'connector' => 'has', 
                'variable'  => 'name', 
            ], 
            [
                'name'      => 'Description', 
                'connector' => 'has', 
                'variable'  => 'description', 
            ], 
        ];
        $sortField = "";

        $filters = [];
        if ($filter_variables) {
            foreach ($filter_variables as $fv) {
                if (isset($queryParams['sortField'])) {
                    if ($queryParams['sortField'] == $fv['variable']) $sortField = $fv['name'];
                }
                if (isset($queryParams[$fv['variable']])) {
                    $value = " {$fv['connector']} \"{$queryParams[$fv['variable']]}\"";
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

    public function add()
    {

        // initialize variables
        $this->page = 'Add';
        $this->_setVariables();
        $data = $this->data;

        return view($this->view_path."/".($this->page?strtolower($this->page):'index'), $data);

    }

    public function view(string $id)
    {

        // initialize variables
        $this->page = 'View';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower($this->page):'index'), $data);

    }

    public function audit(string $id)
    {

        // initialize variables
        $this->page = 'Audit';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower($this->page):'index'), $data);

    }

    public function edit(string $id)
    {

        // initialize variables
        $this->page = 'Edit';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

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
                
                /** paging */
                $filters['limit']       = isset($_GET['limit']) ? trim($_GET['limit']) : 10;
                $filters['page']        = $request->query('page', 1);
                $filters['sortField']   = $request->query('sortField', '');
                $filters['sortBy']      = $request->query('sortBy', '');

                /** conditions */
                $conditions = [
                    'code'              => [$this->table, 'likeboth'], 
                    'name'              => [$this->table, 'likeboth'], 
                    'head_positionID'   => [$this->table, 'where'], 
                    'officeID'          => [$this->table, 'where'], 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'code'      => [$this->table, 'code'], 
                    'name'      => [$this->table, 'name'], 
                    'superior' => ['sp', 'code'], 
                    'office'    => ['offices', 'code'], 
                ];

                // query count
                $query = DB::table($this->table);
                $query = $query->leftjoin('JobPositions as sp', "{$this->table}.head_positionID", '=', 'sp.jobPositionID');
                $query = $query->leftjoin('offices', "{$this->table}.officeID", '=', 'offices.officeID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = '';
                        if (!in_array($cField, ['dateInserted'])) {
                            $value = isset($_GET[$cField]) ? $_GET[$cField] : '';
                            if (!in_array($value, ['', null]) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                            if (!in_array($value, ['', null]) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                        } else {
                            $value = isset($_GET[$cField]) ? $_GET[$cField]!=''? date('Y-m-d', strtotime($_GET[$cField])) : null : null;
                            if (!in_array($value, ['', null])) $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                        }
                        // filters 
                        $filters[$cField] = $value;
                    }
                    if ($filters['sortField']) $query = $query->orderBy($sort_tables[$filters['sortField']][0].".".$sort_tables[$filters['sortField']][1], $filters['sortBy']);
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
                $query = $query->select(
                    "{$this->table}.*", 
                    "sp.code as soCode", 
                    "sp.name as soName", 
                    "offices.code as oCode", 
                    "offices.name as oName", 
                );
                $query = $query->leftjoin('JobPositions as sp', "{$this->table}.head_positionID", '=', 'sp.jobPositionID');
                $query = $query->leftjoin('offices', "{$this->table}.officeID", '=', 'offices.officeID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = '';
                        if (!in_array($cField, ['dateInserted'])) {
                            $value = isset($_GET[$cField]) ? $_GET[$cField] : '';
                            if (!in_array($value, ['', null]) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                            if (!in_array($value, ['', null]) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                        } else {
                            $value = isset($_GET[$cField]) ? $_GET[$cField]!=''? date('Y-m-d', strtotime($_GET[$cField])) : null : null;
                            if (!in_array($value, ['', null])) $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                        }
                        // filters 
                        $filters[$cField] = $value;
                    }
                    if ($filters['sortField']) $query = $query->orderBy($sort_tables[$filters['sortField']][0].".".$sort_tables[$filters['sortField']][1], $filters['sortBy']);
                    $query = $query->orderBy($this->table.".code", 'asc');
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
                            'jobPositionID' => Crypt::encryptString("{$tr->jobPositionID}"), 
                            'code'          => $tr->code, 
                            'name'          => $tr->name, 
                            'superior'      => "$tr->soCode".($tr->soName?" - $tr->soName":''), 
                            'office'        => "$tr->oCode".($tr->oName?" - $tr->oName":''), 
                            'isMayor'       => $tr->isMayor, 
                            'isHrHead'      => $tr->isHrHead, 
                        ];
                    }
                }
                
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
                $items['hasButtonPrint']    = $this->_checkAccess($token_userID, $this->moduleActionIDs['PrintList']);
                $items['hasButtonAdd']      = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $items['hasButtonView']     = $this->_checkAccess($token_userID, $this->moduleActionIDs['View']);
                $items['offices']           = DB::table('offices')->orderBy('code', 'asc')->get();
                $items['JobPositions']     = DB::table('JobPositions')->orderBy('code', 'asc')->get();
                $items['records']           = $records;
                $items['filters']           = $filters;

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
                
                /** paging */
                $filters['limit']       = isset($_GET['limit']) ? trim($_GET['limit']) : 10;
                $filters['page']        = $request->query('page', 1);
                $filters['sortField']   = $request->query('sortField', '');
                $filters['sortBy']      = $request->query('sortBy', '');

                /** conditions */
                $conditions = [
                    'code' => 'likeboth', 
                    'name' => 'likeboth', 
                    'description' => 'likeboth', 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'code' => $this->table, 
                    'name' => $this->table, 
                    'description' => $this->table, 
                ];

                // query count
                $query = DB::table($this->table);
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField, '');
                        if ($cType == 'likeboth') $query = $query->where($cField, 'like', "%{$value}%");
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
                        $value = $request->query($cField, '');
                        if ($cType == 'likeboth') $query = $query->where($cField, 'like', "%{$value}%");
                        // filters 
                        $filters[$cField] = $value;
                    }
                    if ($filters['sortField']) $query = $query->orderBy($sort_tables[$filters['sortField']].".".$filters['sortField'], $filters['sortBy']);
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
                            'jobPositionID' => Crypt::encryptString("{$tr->jobPositionID}"), 
                            'code'          => $tr->code, 
                            'name'          => $tr->name, 
                            'description'   => $tr->description, 
                        ];
                    }
                }
                
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
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

    public function post_page(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Insert'])) {
                $items['hasButtonAdd']  = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $data['items']          = $items;
                $data['offices']        = DB::table('offices')->orderBy('code', 'asc')->get();
                $data['JobPositions']  = DB::table('JobPositions')->orderBy('code', 'asc')->get();
            } else {
                $data = $this->response->status(401, 'Access denied.');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);

    } 

    public function post(Request $request)
    {

        $data = $this->response->status(200);
        
        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Insert'])) {
                /** fields */
                $request_fields     = ['code', 'name', 'description', 'officeID', 'head_positionID'];
        
                /** variables */
                $id                 = '';
                $request_data       = [];
                $hasError           = 0;
                $requires           = '';
                $required_fields    = [
                    'code'      => 'Job Position Code', 
                    'name'      => 'Job Position Name', 
                    'officeID'  => 'Office', 
                ];

                /** data */
                if ($request_fields) {
                    foreach ($request_fields as $field) {
                        $request_data[$field] = $_POST[$field];
                    }
                }
                $request_fields[] = 'isMayor';
                $request_fields[] = 'isHrHead';
                $request_data['isMayor'] = isset($_POST['isMayor']) ? 1 : 0;
                $request_data['isHrHead'] = isset($_POST['isHrHead']) ? 1 : 0;

                if ($request_data['isMayor']) $request_data['head_positionID'] = 0;

                /** check errors */
                // required fields 
                if ($required_fields) {
                    foreach ($required_fields as $fieldName => $fieldLabel) {
                        if (!$request_data[$fieldName]) {
                            $hasError = 1;
                            $requires .= ($requires ? ", " : "")."$fieldLabel";
                        }
                    }
                    if (!$request_data['isMayor']) {
                        if (!$request_data['head_positionID']) {
                            $hasError = 1;
                            $requires .= ($requires ? ", " : "")."Immediate Superior";
                        }
                    }
                }
                if ($hasError) $data = $this->response->status(401, $requires, 'Required field(s):');
        
                // duplicate code
                if (!$hasError) {
                    $hasDuplicate = DB::table($this->table);
                    $hasDuplicate = $hasDuplicate->where('code', $request_data['code']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Job position code already exist.', 'Invalid!');
                    }
                }
        
                // already has mayor
                if (!$hasError && $request_data['isMayor']) {
                    $hasDuplicate = DB::table($this->table);
                    $hasDuplicate = $hasDuplicate->where('isMayor', 1);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Mayor job position already inserted.', 'Invalid!');
                    }
                }
        
                // already has mayor
                if (!$hasError && $request_data['isHrHead']) {
                    $hasDuplicate = DB::table($this->table);
                    $hasDuplicate = $hasDuplicate->where('isHrHead', 1);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'HR Head job position already inserted.', 'Invalid!');
                    }
                }
        
                /** query */
                if (!$hasError) {
                    $pkID = DB::table($this->table)->insertGetId($request_data);
                    if ($pkID) {
                        // return encrypted id
                        $id = Crypt::encryptString("{$pkID}");
                        // insert audit logs
                        $logFields = $request_fields;
                        $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], $this->table, $this->tablePrimaryKey, $pkID, $request_data, "Inserted {$this->logTitle} Record", $logFields, 1);
                    }
        
                }
        
                /** final variables */
                $items['id'] = $id;

                $data['items'] = $items;

            } else {
                $data = $this->response->status(401, 'Access denied.');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);
    } 

    public function get(Request $request, string $id)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['View'])) {
                $row        = []; 
                $hasError   = 0;
        
                /** check errors */
                // primary key value 
                $decrypted_id = $this->_decryptID($id);
                if (!$decrypted_id) {
                    $hasError = 1;
                    $data = $this->response->status(400);
                }
        
                /** query */
                if (!$hasError) {
                    $query = DB::table($this->table);
                    $query = $query->select(
                        "{$this->table}.*", 
                        "sp.code as soCode", 
                        "sp.name as soName", 
                        "offices.code as oCode", 
                        "offices.name as oName", 
                    );
                    $query = $query->leftjoin('JobPositions as sp', "{$this->table}.head_positionID", '=', 'sp.jobPositionID');
                    $query = $query->leftjoin('offices', "{$this->table}.officeID", '=', 'offices.officeID');
                    $query = $query->where("{$this->table}.{$this->tablePrimaryKey}", $decrypted_id)->first();
                    if ($query) {
                        $row = [
                            'code'          => $query->code, 
                            'name'          => $query->name, 
                            'description'   => $query->description, 
                            'superior'      => "$query->soCode".($query->soName?" - $query->soName":''), 
                            'office'        => "$query->oCode".($query->oName?" - $query->oName":''), 
                            'isMayor'       => $query->isMayor, 
                            'isHrHead'      => $query->isHrHead, 
                        ];

                        /** final variables */
                        $items['hasButtonAdd']      = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                        $items['hasButtonAudit']    = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
                        $items['hasButtonEdit']     = $this->_checkAccess($token_userID, $this->moduleActionIDs['Update']);
                        $items['hasButtonDelete']   = $this->_checkAccess($token_userID, $this->moduleActionIDs['Delete']);
                        $items['row'] = $row;
                        $data['items'] = $items;
        
                    } else {
                        $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                    }
                }
            } else {
                $data = $this->response->status(401, 'Access denied.');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 

    public function audit_page(Request $request, string $id)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Audit'])) {

                $hasError = 0;

                /** check errors */
                // primary key value 
                $decrypted_id = $this->_decryptID($id);
                if (!$decrypted_id) {
                    $hasError = 1;
                    $data = $this->response->status(400);
                }
        
                /** query */
                if (!$hasError) {
                    $items = $this->_auditLogGet($decrypted_id, $this->table, $this->tablePrimaryKey, $this->auditFieldValues); 
                    $data['items'] = $items;
                }
            } else {
                $data = $this->response->status(401, 'Access denied.');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);

    } 

    public function put_page(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Update'])) {
                /** variables */
                $row        = [];
                $hasError   = 0;
        
                /** check errors */
                // primary key value 
                $decrypted_id = $this->_decryptID($id);
                if (!$decrypted_id) {
                    $hasError = 1;
                    $data = $this->response->status(400);
                }
        
                /** query */
                if (!$hasError) {
                    $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id)->first();
                    if ($query) {
                        $row = [
                            'officeID' => $query->officeID, 
                            'code' => $query->code, 
                            'name' => $query->name, 
                            'description' => $query->description, 
                            'head_positionID' => $query->head_positionID, 
                            'isMayor' => $query->isMayor, 
                            'isHrHead' => $query->isHrHead, 
                        ];
        
                        /** final variables */
                        $items['hasButtonEdit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Update']);
                        $items['row'] = $row;
                        $data['items'] = $items;
                        $data['offices']        = DB::table('offices')->orderBy('code', 'asc')->get();
                        $data['JobPositions']  = DB::table('JobPositions')->orderBy('code', 'asc')->get();

                    } else {
                        $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                    }
                }
            } else {
                $data = $this->response->status(401, 'Access denied.');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 

    public function put(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Update'])) {
                /** fields */
                $request_fields     = ['code', 'name', 'description', 'officeID', 'head_positionID'];
        
                /** variables */
                $decrypted_id       = 0;
                $request_data       = [];
                $hasError           = 0; 
                $requires           = '';
                $required_fields    = [
                    'code' => 'Job Position Code', 
                    'name' => 'Job Position Name', 
                    'officeID' => 'Office', 
                ];
        
                /** data */
                if ($request_fields) {
                    foreach ($request_fields as $field) {
                        $request_data[$field] = $_POST[$field];
                    }
                }
                $request_fields[] = 'isMayor';
                $request_fields[] = 'isHrHead';
                $request_data['isMayor'] = isset($_POST['isMayor']) ? 1 : 0;
                $request_data['isHrHead'] = isset($_POST['isHrHead']) ? 1 : 0;

                if ($request_data['isMayor']) $request_data['head_positionID'] = 0;

                /** check errors */
                // primary key value 
                $decrypted_id = $this->_decryptID($id);
                if (!$decrypted_id) {
                    $hasError = 1;
                    $data = $this->response->status(400);
                }
        
                // required fields 
                if (!$hasError) {
                    if ($required_fields) {
                        foreach ($required_fields as $fieldName => $fieldLabel) {
                            if (!$request_data[$fieldName]) {
                                $hasError = 1;
                                $requires .= ($requires ? ", " : "")."$fieldLabel";
                            }
                        }
                        if (!$request_data['isMayor']) {
                            if (!$request_data['head_positionID']) {
                                $hasError = 1;
                                $requires .= ($requires ? ", " : "")."Immediate Superior";
                            }
                        }
                    }
                    if ($hasError) $data = $this->response->status(401, $requires, 'Required field(s):');
                }
        
                // duplicate code
                if (!$hasError) {
                    $hasDuplicate = DB::table($this->table);
                    $hasDuplicate = $hasDuplicate->whereNot($this->tablePrimaryKey, $decrypted_id);
                    $hasDuplicate = $hasDuplicate->where('code', $request_data['code']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Job position code already exist.', 'Invalid!');
                    }
                }
        
                // already has mayor
                if (!$hasError && $request_data['isMayor']) {
                    $hasDuplicate = DB::table($this->table);
                    $hasDuplicate = $hasDuplicate->whereNot($this->tablePrimaryKey, $decrypted_id);
                    $hasDuplicate = $hasDuplicate->where('isMayor', 1);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Mayor job position already inserted.', 'Invalid!');
                    }
                }
        
                // already has isHrHead
                if (!$hasError && $request_data['isHrHead']) {
                    $hasDuplicate = DB::table($this->table);
                    $hasDuplicate = $hasDuplicate->whereNot($this->tablePrimaryKey, $decrypted_id);
                    $hasDuplicate = $hasDuplicate->where('isHrHead', 1);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'HR Head job position already inserted.', 'Invalid!');
                    }
                }
        
                // query
                if (!$hasError) {
                    $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                    if ($query) {
                        $query->update($request_data);
                        // update audit logs
                        $logFields = $request_fields;
                        $this->_auditLog($request_token['data'], $this->moduleActionIDs['Update'], $this->table, $this->tablePrimaryKey, $decrypted_id, $request_data, "Updated {$this->logTitle} Record", $logFields, 1);
                    } else {
                        $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                    }
                } 
        
                $items['id'] = $id;
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


