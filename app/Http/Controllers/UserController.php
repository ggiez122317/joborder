<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

use App\Libraries\Response;
use App\Libraries\PasswordHelper;

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

class UserController extends MasterController
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
        $this->module           = 'Users';
        $this->controller       = 'users';
        $this->logTitle         = 'User';
        $this->table            = 'users';
        $this->tablePrimaryKey  = 'userID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'             => 9, 
            'PrintList'         => 62, 
            'Insert'            => 63, 
            'View'              => 64, 
            'Audit'             => 65, 
            'Update'            => 66, 
            'Delete'            => 67, 
            'Change Password'   => 68, 
        ];
        // id => [label, table, field]
        $this->auditFieldValues = [
            'username'          => ['Username', '', ''], 
            'password'          => ['Password', '', ''], 
            'userTypeID'        => ['User Type', 'UserTypes', 'name'], 
            'dateInserted'      => ['Date Inserted', '', ''], 
            'dateActivated'     => ['Date Activated', '', ''], 
            'dateDeactivated'   => ['Date Deactivated', '', ''], 
            'status'            => ['Status', '', ''], 
        ];
        $this->auditFieldValues2 = [
            'userID'                => ['Username', 'users', 'username'], 
            'username'              => ['Username (Temporary)', '', ''], 
            'password'              => ['Password (Temporary)', '', ''], 
            'fname'                 => ['First Name (Temporary)', '', ''], 
            'mname'                 => ['Middle Name (Temporary)', '', ''], 
            'lname'                 => ['Last Name (Temporary)', '', ''], 
            'idNumber'              => ['Employee ID', '', ''], 
            'userEmploymentTypeID'  => ['Employee Type', 'user_employment_types', 'name'], 
            'officeID'              => ['Office', 'offices', 'name'], 
            'jobPositionID'         => ['Job Position', 'JobPositions', 'name'], 
            'salaryBasic'           => ['Salary', '', ''], 
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
                'name'      => 'Name', 
                'connector' => 'is', 
                'variable'  => 'userID', 
                'table'     => 'user_personal_informations', 
            ], 
            [
                'name'      => 'Name', 
                'connector' => '', 
                'variable'  => 'lname', 
                'table'     => '', 
            ], 
            [
                'name'      => 'Username', 
                'connector' => 'has', 
                'variable'  => 'username', 
                'table'     => $this->table, 
            ], 
            [
                'name'      => 'User Type', 
                'connector' => 'is', 
                'variable'  => 'userTypeID', 
                'table'     => 'UserTypes', 
            ], 
            [
                'name'      => 'Date Inserted', 
                'connector' => 'has', 
                'variable'  => 'dateInserted', 
                'table'     => $this->table, 
            ], 
            [
                'name'      => 'Date Activated', 
                'connector' => 'has', 
                'variable'  => 'dateActivated', 
                'table'     => $this->table, 
            ], 
            [
                'name'      => 'Date Deactivated', 
                'connector' => 'has', 
                'variable'  => 'dateDeactivated', 
                'table'     => $this->table, 
            ], 
            [
                'name'      => 'Status', 
                'connector' => 'is', 
                'variable'  => 'status', 
                'table'     => $this->table, 
            ], 
        ];
        $sortField = "";

        $filters = [];
        if ($filter_variables) {
            foreach ($filter_variables as $fv) {
                if (isset($queryParams['sortField'])) {
                    if ($queryParams['sortField'] == $fv['variable']) $sortField = $fv['name'];
                }
                if ($fv['variable'] != 'lname') {
                    if (isset($queryParams[$fv['variable']])) {
                        $value = " {$fv['connector']} \"{$queryParams[$fv['variable']]}\"";
                        if ($fv['connector'] == 'is') {
                            if ($fv['variable'] != 'userID') {
                                $query = DB::table($fv['table']);
                                $query = $query->where("{$fv['table']}.{$fv['variable']}", $queryParams[$fv['variable']]);
                                $query = $query->first();
                                if ($query) $value = " {$fv['connector']} \"{$query->name}\"";
                            } else {
                                $query = DB::table($fv['table']);
                                $query = $query->where("{$fv['table']}.{$fv['variable']}", $queryParams[$fv['variable']]);
                                $query = $query->first();
                                if ($query) {
                                    $name = "{$query->lname}, {$query->fname} {$query->mname}";
                                    $value = " {$fv['connector']} \"{$name}\"";
                                }
                            }
                        }
                        $filters[] = [
                            'start' => "", 
                            'name'  => "- {$fv['name']}", 
                            'value' => $value, 
                        ];
                    }
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

    public function audit2(string $id)
    {

        // initialize variables
        $this->page = 'Audit User Accesses';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(" ", "_", $this->page)):'index'), $data);

    }

    public function audit3(string $id)
    {

        // initialize variables
        $this->page = 'Audit Starting Informations';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(" ", "_", $this->page)):'index'), $data);

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
                    'userID'            => [$this->table, 'where'], 
                    'username'          => [$this->table, 'likeboth'], 
                    'userTypeID'        => ['UserTypes', 'where'], 
                    'dateInserted'      => [$this->table, 'likeboth'], 
                    'dateActivated'     => [$this->table, 'likeboth'], 
                    'dateDeactivated'   => [$this->table, 'likeboth'], 
                    'status'            => [$this->table, 'where'], 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'lname'             => ['user_personal_informations', 'lname'], 
                    'username'          => [$this->table, 'username'], 
                    'utName'            => ['UserTypes', 'name'], 
                    'dateInserted'      => [$this->table, 'dateInserted'], 
                    'dateActivated'     => [$this->table, 'dateActivated'], 
                    'dateDeactivated'   => [$this->table, 'dateDeactivated'], 
                    'status'            => [$this->table, 'status'], 
                ];

                // query count
                $query = DB::table($this->table);
                $query = $query->leftjoin('UserTypes', "{$this->table}.userTypeID", '=', 'UserTypes.userTypeID');
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = isset($_GET[$cField]) ? $_GET[$cField] : '';
                        if (!in_array($cField, ['dateInserted','dateActivated','dateDeactivated'])) {
                            if (!in_array($value, ['', null]) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                            if (!in_array($value, ['', null]) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                        } else {
                            $value = date('Y-m-d', strtotime($value));
                            if (!in_array($value, ['', null])) $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                        }
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
                $query = $query->select(
                    "{$this->table}.*", 
                    "UserTypes.name as utName", 
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                );
                $query = $query->leftjoin('UserTypes', "{$this->table}.userTypeID", '=', 'UserTypes.userTypeID');
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = '';
                        if (!in_array($cField, ['dateInserted','dateActivated','dateDeactivated'])) {
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

                        $user = $tr->lname;
                        $user .= $tr->fname ? ", {$tr->fname}" : "";
                        $user .= $tr->mname ? ", {$tr->mname}" : "";

                        $records[] = [
                            'userID'            => Crypt::encryptString("{$tr->userID}"), 
                            'user'              => $user, 
                            'username'          => $tr->username, 
                            'utName'            => $tr->utName, 
                            'dateInserted'      => $tr->dateInserted?date('m/d/y h:ia', strtotime($tr->dateInserted)):'', 
                            'dateActivated'     => $tr->dateActivated?date('m/d/y h:ia', strtotime($tr->dateActivated)):'', 
                            'dateDeactivated'   => $tr->dateDeactivated?date('m/d/y h:ia', strtotime($tr->dateDeactivated)):'', 
                            'status'            => $tr->status, 
                        ];
                    }
                }
                
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
                $items['hasButtonPrint']    = $this->_checkAccess($token_userID, $this->moduleActionIDs['PrintList']);
                $items['hasButtonAdd']      = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $items['hasButtonView']     = $this->_checkAccess($token_userID, $this->moduleActionIDs['View']);
                $items['users']             = DB::table('user_personal_informations')->select("lname", "fname", "mname", "userID")->orderBy('lname', 'asc')->orderBy('fname', 'asc')->get();
                $items['UserTypes']        = DB::table('UserTypes')->orderBy('name', 'asc')->get();
                $items['statuses']          = [['status'=>'1','name'=>'Activated'], ['status'=>'0','name'=>'Pending'], ['status'=>'-1','name'=>'Deactivated'], ];
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
                    'userID'            => [$this->table, 'where'], 
                    'username'          => [$this->table, 'likeboth'], 
                    'userTypeID'        => ['UserTypes', 'where'], 
                    'dateInserted'      => [$this->table, 'likeboth'], 
                    'dateActivated'     => [$this->table, 'likeboth'], 
                    'dateDeactivated'   => [$this->table, 'likeboth'], 
                    'status'            => [$this->table, 'where'], 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'lname'             => ['user_personal_informations', 'lname'], 
                    'username'          => [$this->table, 'username'], 
                    'utName'            => ['UserTypes', 'name'], 
                    'dateInserted'      => [$this->table, 'dateInserted'], 
                    'dateActivated'     => [$this->table, 'dateActivated'], 
                    'dateDeactivated'   => [$this->table, 'dateDeactivated'], 
                    'status'            => [$this->table, 'status'], 
                ];

                // query count
                $query = DB::table($this->table);
                $query = $query->leftjoin('UserTypes', "{$this->table}.userTypeID", '=', 'UserTypes.userTypeID');
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = isset($_GET[$cField]) ? $_GET[$cField] : '';
                        if (!in_array($cField, ['dateInserted','dateActivated','dateDeactivated'])) {
                            if (!in_array($value, ['', null]) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                            if (!in_array($value, ['', null]) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                        } else {
                            $value = date('Y-m-d', strtotime($value));
                            if (!in_array($value, ['', null])) $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                        }
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
                $query = $query->select(
                    "{$this->table}.*", 
                    "UserTypes.name as utName", 
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                );
                $query = $query->leftjoin('UserTypes', "{$this->table}.userTypeID", '=', 'UserTypes.userTypeID');
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = '';
                        if (!in_array($cField, ['dateInserted','dateActivated','dateDeactivated'])) {
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
                    $query = $query->orderBy($this->table.".".$this->tablePrimaryKey, 'desc');
                }
                # limit
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

                        $user = $tr->lname;
                        $user .= $tr->fname ? ", {$tr->fname}" : "";
                        $user .= $tr->mname ? ", {$tr->mname}" : "";

                        $records[] = [
                            'user'              => $user, 
                            'username'          => $tr->username, 
                            'utName'            => $tr->utName, 
                            'dateInserted'      => $tr->dateInserted?date('m/d/y h:ia', strtotime($tr->dateInserted)):'', 
                            'dateActivated'     => $tr->dateActivated?date('m/d/y h:ia', strtotime($tr->dateActivated)):'', 
                            'dateDeactivated'   => $tr->dateDeactivated?date('m/d/y h:ia', strtotime($tr->dateDeactivated)):'', 
                            'status'            => $tr->status, 
                        ];
                    }
                }
                
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
                $items['records'] = $records;
                $items['filters'] = $filters;
                $items['statuses'] = ['Deactivated', 'Pending', 'Activated'];

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

                $items['hasButtonAdd']          = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $items['UserTypes']            = DB::table('UserTypes')->orderBy('name', 'asc')->get();
                $items['offices']               = DB::table('offices')->orderBy('name', 'asc')->get();
                $items['JobPositions']         = DB::table('JobPositions')->orderBy('name', 'asc')->get();
                $items['user_employment_types'] = DB::table('user_employment_types')->orderBy('name', 'asc')->get();

                $data['items'] = $items;
                
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
                // users
                $request_fields1     = ['username', 'password', 'userTypeID', 'biometricIdNumber'];
                // user_starting_informations
                $request_fields2    = [
                    'username', 'password', 'fname', 'mname', 'lname', 
                    'dateAppointed', 'idNumber', 'userEmploymentTypeID', 'officeID', 'jobPositionID', 'salaryMonthly', 'salaryYearly', 
                ];
        
                /** variables */
                $id                 = '';
                $request_data       = [];
                $request_data1      = [];
                $request_data2      = [];
                $hasError           = 0;
                $requires           = '';
                $required_fields    = [
                    'username'              => 'Username', 
                    'password'              => 'Password', 
                    'userTypeID'            => 'User Type', 
                    'fname'                 => 'First Name', 
                    'lname'                 => 'Last Name', 
                    'dateAppointed'         => 'Current Service Appointed Date', 
                    'idNumber'              => 'Employee ID', 
                    'userEmploymentTypeID'  => 'Employee Type', 
                    'officeID'              => 'Office', 
                    'jobPositionID'         => 'Job Position', 
                    'salaryMonthly'         => 'Salary Per Month', 
                    'salaryYearly'          => 'Salary Per Annum', 
                    'biometricIdNumber'     => 'Biometric ID Number', 
                ];
        
                /** data */
                if ($request_fields1) {
                    foreach ($request_fields1 as $field) {
                        $request_data[$field] = $request->input($field) ?: '';
                        $request_data1[$field] = $request->input($field) ?: '';
                    }
                }
                if ($request_fields2) {
                    foreach ($request_fields2 as $field2) {
                        $request_data[$field2] = $request->input($field2) ?: '';
                        $request_data2[$field2] = $request->input($field2) ?: '';
                    }
                }
        
                /** check errors */
                // required fields 
                if ($required_fields) {
                    foreach ($required_fields as $fieldName => $fieldLabel) {
                        if (!$request_data[$fieldName]) {
                            $hasError = 1;
                            $requires .= ($requires ? ", " : "")."$fieldLabel";
                        }
                    }
                }
                if ($hasError) $data = $this->response->status(401, $requires, 'Required field(s):');
        
                // duplicate username
                if (!$hasError) {
                    $hasDuplicate = DB::table($this->table);
                    $hasDuplicate = $hasDuplicate->where('username', $request_data['username']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Username already exist.', 'Invalid!');
                    }
                }

                // duplicate username
                if (!$hasError) {
                    $hasDuplicate = DB::table('user_starting_informations');
                    $hasDuplicate = $hasDuplicate->where('username', $request_data['username']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Username already exist.', 'Invalid!');
                    }
                }

                // duplicate Employee ID
                if (!$hasError) {
                    $hasDuplicate = DB::table('user_employments');
                    $hasDuplicate = $hasDuplicate->where('idNumber', $request_data['idNumber']);
                    $hasDuplicate = $hasDuplicate->where('status', 1);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Employee ID already exist.', 'Invalid!');
                    }
                }

                // duplicate Employee ID
                if (!$hasError) {
                    $hasDuplicate = DB::table('user_starting_informations');
                    $hasDuplicate = $hasDuplicate->where('idNumber', $request_data['idNumber']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Employee ID already exist.', 'Invalid!');
                    }
                }
        
                // duplicate user
                if (!$hasError) {
                    $hasDuplicate = DB::table('user_personal_informations');
                    $hasDuplicate = $hasDuplicate->where('fname', $request_data['fname']);
                    $hasDuplicate = $hasDuplicate->where('lname', $request_data['lname']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'First Name and Last Name already exist.', 'Invalid!');
                    }
                }

                // duplicate user
                if (!$hasError) {
                    $hasDuplicate = DB::table('user_starting_informations');
                    $hasDuplicate = $hasDuplicate->where('fname', $request_data['fname']);
                    $hasDuplicate = $hasDuplicate->where('lname', $request_data['lname']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'First Name and Last Name already exist.', 'Invalid!');
                    }
                }

                // duplicate Biometric ID
                if (!$hasError) {
                    $hasDuplicate = DB::table('users');
                    $hasDuplicate = $hasDuplicate->where('biometricIdNumber', $request_data['biometricIdNumber']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Biometric ID Number already exist.', 'Invalid!');
                    }
                }
        
                /** query */
                if (!$hasError) {

                    // addons
                    $field_addons = ['dateInserted', 'dateActivated', 'dateDeactivated', 'status'];
                    $field_addon_values = [
                        'dateInserted'      => date('Y-m-d H:i:s'), 
                        'dateActivated'     => null, 
                        'dateDeactivated'   => null, 
                        'status'            => 0
                    ];

                    // merge
                    $request_fields1    = array_merge($request_fields1, $field_addons);
                    $request_data1      = array_merge($request_data1, $field_addon_values);

                    $request_data1['password'] = bcrypt($request_data1['password']);

                    // insert
                    $pkID = DB::table($this->table)->insertGetId($request_data1);
                    if ($pkID) {

                        // return encrypted id
                        $id = Crypt::encryptString("{$pkID}");

                        // insert audit logs
                        $logFields = $request_fields1;
                        $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], $this->table, $this->tablePrimaryKey, $pkID, $request_data1, "Inserted {$this->logTitle} Record", $logFields, 1);

                        // addons
                        $request_fields2[] = 'userID';
                        $request_data2['userID'] = $pkID;
                        
                        // insert
                        $pkID2 = DB::table('user_starting_informations')->insertGetId($request_data2);

                        // insert audit logs
                        $logFields = $request_fields2;
                        $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], 'user_starting_informations', 'userStartingInformationID', $pkID2, $request_data2, "Inserted User Starting Information Record", $logFields, 1);
                    
                        // generate user accesses here...
                        if ($request_data['userTypeID']) {
                            $query = DB::table('UserTypeAccesses');
                            $query = $query->where('userTypeID', $request_data['userTypeID']);
                            $query = $query->where('status', 1);
                            $query = $query->get();
                            if ($query) {
                                foreach ($query as $q) {
                                    $request_fields3 = ['userID', 'appModuleActionID', 'status'];
                                    $request_data3 = [
                                        'userID'            => $pkID, 
                                        'appModuleActionID' => $q->appModuleActionID, 
                                        'status'            => 1, 
                                    ];
                                    $pkID3 = DB::table('UserAccesses')->insertGetId($request_data3);

                                    // insert audit logs 
                                    $logFields = $request_fields3;
                                    $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], 'UserAccesses', 'userAccessID', $pkID3, $request_data3, "Inserted User Access Record", $logFields, 1);
                                }
                            }
                        }
                    
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

                $accesses = [];
                $modules = []; 
                $row = []; 
                $startingInformation = []; 
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
                    $query = DB::table($this->table);
                    $query = $query->join('UserTypes', "{$this->table}.userTypeID", '=', 'UserTypes.userTypeID');
                    $query = $query->where("{$this->table}.{$this->tablePrimaryKey}", $decrypted_id);
                    $query = $query->select(
                        "{$this->table}.*", 
                        "UserTypes.name as utName", 
                    );
                    $query = $query->first();
                    if ($query) {

                        $statuses = ['Deactivated', 'Pending', 'Activated'];

                        $row = [
                            'biometricIdNumber' => $query->biometricIdNumber, 
                            'username'          => $query->username, 
                            'utName'            => $query->utName, 
                            'dateInserted'      => $query->dateInserted?date('M d/y h:i a', strtotime($query->dateInserted)):'', 
                            'dateActivated'     => $query->dateActivated?date('M d/y h:i a', strtotime($query->dateActivated)):'', 
                            'dateDeactivated'   => $query->dateDeactivated?date('M d/y h:i a', strtotime($query->dateDeactivated)):'', 
                            'status'            => $query->status, 
                            'statusName'        => $statuses[$query->status+1], 
                        ];

                        // modules 
                        $query = AppModuleAction::leftJoin('AppModules', 'AppModuleActions.appModuleID', '=', 'AppModules.appModuleID');
                        $query = $query->leftJoin('AppActions', 'AppModuleActions.appActionID', '=', 'AppActions.appActionID');
                        $query = $query->orderBy('AppModules.rank', 'asc');
                        $query = $query->orderBy('AppActions.rank', 'asc');
                        $query = $query->select(
                            'AppModuleActions.appModuleActionID', 
                            'AppModules.name as amName', 
                            'AppActions.name as acName', 
                        );
                        $query = $query->get();
        
                        $moduleName     = "";
                        $moduleActions  = [];
                        if ($query) {
                            foreach ($query as $q) {
        
                                if ($moduleName != $q->amName) {
                                    if ($moduleActions) {
                                        $modules[] = [
                                            'module'    => $moduleName, 
                                            'actions'   => $moduleActions, 
                                        ];
                                    }
                                    $moduleName = $q->amName;
                                    $moduleActions = [];
                                }
        
                                $moduleActions[] = [
                                    'id'        => $q->appModuleActionID,
                                    'action'    => $q->acName,
                                ];
        
        
                            }
                            if ($moduleActions) {
                                $modules[] = [
                                    'module'    => $moduleName, 
                                    'actions'   => $moduleActions, 
                                ];
                            }
                        }

                        // accesses 
                        $query = DB::table('UserAccesses')->where('userID', $decrypted_id)->where('status', 1)->get();
                        if ($query) {
                            foreach ($query as $q) {
                                $accesses[] = $q->appModuleActionID;
                            }
                        }

                        // starting informations
                        $query = DB::table('user_starting_informations');
                        $query = $query->join('user_employment_types', "user_starting_informations.userEmploymentTypeID", '=', 'user_employment_types.userEmploymentTypeID');
                        $query = $query->join('offices', "user_starting_informations.officeID", '=', 'offices.officeID');
                        $query = $query->join('JobPositions', "user_starting_informations.jobPositionID", '=', 'JobPositions.jobPositionID');
                        $query = $query->where('user_starting_informations.userID', $decrypted_id);
                        $query = $query->select(
                            "user_starting_informations.*", 
                            "user_employment_types.name as uetName", 
                            "offices.code as oCode", 
                            "offices.name as oName", 
                            "JobPositions.code as jpCode", 
                            "JobPositions.name as jpName", 
                        );
                        $query = $query->first();
                        if ($query) {
                            $startingInformation = [
                                'username'      => $query->username, 
                                'password'      => $query->password, 
                                'fname'         => $query->fname, 
                                'mname'         => $query->mname, 
                                'lname'         => $query->lname, 
                                'idNumber'      => $query->idNumber, 
                                'uetName'       => $query->uetName, 
                                'office'        => "{$query->oCode} - {$query->oName}", 
                                'jobPosition'   => "{$query->jpCode} - {$query->jpName}", 
                                'salaryMonthly' => number_format($query->salaryMonthly, 2), 
                                'salaryYearly'  => number_format($query->salaryYearly, 2), 
                            ];
                        }

                        /** final variables */
                        $items['hasButtonAdd']              = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                        $items['hasButtonAudit']            = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
                        $items['hasButtonEdit']             = $this->_checkAccess($token_userID, $this->moduleActionIDs['Update']);
                        $items['hasButtonDelete']           = $this->_checkAccess($token_userID, $this->moduleActionIDs['Delete']);
                        $items['hasButtonChangePassword']   = $this->_checkAccess($token_userID, $this->moduleActionIDs['Change Password']);
                        // $items['isSuperAdmin']              = $decrypted_id==1?1:0;
                        $items['isSuperAdmin']              = 0;
                        $items['modules']                   = $modules;
                        $items['accesses']                  = $accesses;
                        $items['row']                       = $row;
                        $items['startingInformation']       = $startingInformation;

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
                    $items['statuses'] = ['Deactivated', 'Pending', 'Activated'];
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

    public function audit_page_UserAccess(Request $request, string $id)
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

                $AuditLogDetails  = [];
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

                    $table              = 'UserAccesses';
                    $tablePrimaryKey    = 'userAccessID';
                    $primaryKeyID       = $decrypted_id;

                    $query = DB::table($table);
                    $query = $query->select(
                        'UserAccesses.userAccessID', 
                        'AppModules.name as amName', 
                        'AppActions.name as aaName', 
                    );
                    $query = $query->leftJoin('AppModuleActions', "{$table}.appModuleActionID", '=', 'AppModuleActions.appModuleActionID');
                    $query = $query->leftJoin('AppModules', "AppModuleActions.appModuleID", '=', 'AppModules.appModuleID');
                    $query = $query->leftJoin('AppActions', "AppModuleActions.appActionID", '=', 'AppActions.appActionID');
                    $query = $query->where("{$table}.userID", $primaryKeyID);
                    $query = $query->get();

                    if ($query) {
                        $userAccessIDs = [];
                        $fieldNames = [];
                        foreach ($query as $q) {
                            if (!in_array($q->userAccessID, $userAccessIDs)) $userAccessIDs[] = $q->userAccessID;
                            $fieldNames[$q->userAccessID] = "{$q->amName} ({$q->aaName})";
                        }

                        if ($userAccessIDs) {

                            // audit log details 
                            $query = AuditLogDetail::leftJoin('AuditLogs', 'AuditLogDetails.auditLogID', '=', 'AuditLogs.auditLogID');
                            $query = $query->leftJoin('AppModuleActions', 'AuditLogs.appModuleActionID', '=', 'AppModuleActions.appModuleActionID');
                            $query = $query->leftJoin('AppActions', 'AppModuleActions.appActionID', '=', 'AppActions.appActionID');
                            $query = $query->whereIn('AuditLogs.primaryKeyID', $userAccessIDs);
                            $query = $query->where('AuditLogs.tableName', $table);
                            $query = $query->where('AuditLogs.primaryKey', $tablePrimaryKey);
                            $query = $query->where('AuditLogDetails.field', 'status');
                            $query = $query->orderBy('AuditLogDetails.auditLogDetailID', 'desc');
                            $query = $query->select(
                                'AuditLogs.dateInserted', 
                                'AuditLogs.ipAddress', 
                                'AuditLogs.username', 
                                'AuditLogs.primaryKeyID', 
                                'AppActions.name as acName', 
                                'AuditLogDetails.field', 
                                'AuditLogDetails.valueOld', 
                                'AuditLogDetails.valueNew', 
                            );
                            $query = $query->get();
        
                            if ($query) {
                                foreach ($query as $q) {
                                    $AuditLogDetails[] = [
                                        'date'      => date('m/d/Y h:ia', strtotime($q->dateInserted)), 
                                        'ipAddress' => $q->ipAddress, 
                                        'user'      => "{$q->username}", 
                                        'action'    => "<span class='text-{$this->auditActionColors[strtolower($q->acName)]}'>$q->acName</span>", 
                                        'field'     => "{$fieldNames[$q->primaryKeyID]}: Status", 
                                        'valueOld'  => in_array($q->valueOld, [0,1])?$q->valueOld?'Active':'Inactive':'', 
                                        'valueNew'  => in_array($q->valueNew, [0,1])?$q->valueNew?'Active':'Inactive':'', 
                                    ];
                                }
                            }

                        }
                    }

                    $items['AuditLogDetails'] = $AuditLogDetails;

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

    public function audit_page_starting_information(Request $request, string $id)
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

                $AuditLogs  = [];
                $AuditLogDetails  = [];
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

                    $table = 'user_starting_informations';
                    $tablePrimaryKey = 'userStartingInformationID';
                    $primaryKeyID = 0;

                    // get primaryKeyID
                    $query = DB::table($table)->where('userID', $decrypted_id)->first();
                    if ($query) $primaryKeyID = $query->userStartingInformationID;

                    if ($primaryKeyID) {

                        // audit logs 
                        $query = AuditLog::leftJoin('users', 'AuditLogs.userID', '=', 'users.userID');
                        $query = $query->leftJoin('AppModuleActions', 'AuditLogs.appModuleActionID', '=', 'AppModuleActions.appModuleActionID');
                        $query = $query->leftJoin('AppModules', 'AppModuleActions.appModuleID', '=', 'AppModules.appModuleID');
                        $query = $query->leftJoin('apps', 'AppModules.appID', '=', 'apps.appID');
                        $query = $query->leftJoin('AppActions', 'AppModuleActions.appActionID', '=', 'AppActions.appActionID');
                        $query = $query->where('AuditLogs.primaryKeyID', $primaryKeyID);
                        $query = $query->where('AuditLogs.tableName', $table);
                        $query = $query->where('AuditLogs.primaryKey', $tablePrimaryKey);
                        $query = $query->orderBy('AuditLogs.auditLogID', 'desc');
                        $query = $query->select(
                            'AuditLogs.dateInserted', 
                            'AuditLogs.ipAddress', 
                            'AuditLogs.userAgent', 
                            'AuditLogs.username', 
                            'apps.name as aName', 
                            'AppModules.name as amName', 
                            'AppActions.name as acName', 
                            'AuditLogs.remarks', 
                        );
                        $query = $query->get();
    
                        if ($query) {
                            foreach ($query as $q) {
                                $AuditLogs[] = [
                                    'date'          => date('m/d/Y h:ia', strtotime($q->dateInserted)), 
                                    'ipAddress'     => $q->ipAddress, 
                                    'deviceInfo'    => $q->userAgent, 
                                    'user'          => "{$q->username}", 
                                    'module'        => "{$q->aName} - {$q->amName}", 
                                    'action'        => "<span class='text-{$this->auditActionColors[strtolower($q->acName)]}'>$q->acName</span>", 
                                    'remarks'       => $q->remarks, 
                                ];
                            }
                        }
    
                        // audit log details 
                        $query = AuditLogDetail::leftJoin('AuditLogs', 'AuditLogDetails.auditLogID', '=', 'AuditLogs.auditLogID');
                        $query = $query->leftJoin('AppModuleActions', 'AuditLogs.appModuleActionID', '=', 'AppModuleActions.appModuleActionID');
                        $query = $query->leftJoin('AppActions', 'AppModuleActions.appActionID', '=', 'AppActions.appActionID');
                        $query = $query->where('AuditLogs.primaryKeyID', $primaryKeyID);
                        $query = $query->where('AuditLogs.tableName', $table);
                        $query = $query->where('AuditLogs.primaryKey', $tablePrimaryKey);
                        $query = $query->orderBy('AuditLogDetails.auditLogDetailID', 'desc');
                        $query = $query->select(
                            'AuditLogs.dateInserted', 
                            'AuditLogs.ipAddress', 
                            'AuditLogs.username', 
                            'AppActions.name as acName', 
                            'AuditLogDetails.field', 
                            'AuditLogDetails.valueOld', 
                            'AuditLogDetails.valueNew', 
                        );
                        $query = $query->get();

                        if ($query) {
                            foreach ($query as $q) {

                                $tableName  = $this->auditFieldValues2[$q->field][1];
                                $tableField = $this->auditFieldValues2[$q->field][2];
                                $valueOld = $q->valueOld;
                                $valueNew = $q->valueNew;
                                if ($tableName && $tableField) {
                                    // get old value
                                    if ($valueOld) {
                                        $query = DB::table($tableName);
                                        $query = $query->select( "{$tableName}.{$tableField}" );
                                        $query = $query->first();
                                        if ($query) $valueOld = $query->$tableField;
                                    }
                                    // get new value
                                    if ($valueNew) {
                                        $query = DB::table($tableName);
                                        $query = $query->select( "{$tableName}.{$tableField}" );
                                        $query = $query->where( "{$tableName}.{$q->field}", $valueNew);
                                        $query = $query->first();
                                        if ($query) $valueNew = $query->$tableField;
                                    }
                                }

                                $AuditLogDetails[] = [
                                    'date'      => date('m/d/Y h:ia', strtotime($q->dateInserted)), 
                                    'ipAddress' => $q->ipAddress, 
                                    'user'      => "{$q->username}", 
                                    'action'    => "<span class='text-{$this->auditActionColors[strtolower($q->acName)]}'>$q->acName</span>", 
                                    'field'     => $this->auditFieldValues2[$q->field][0], 
                                    'valueOld'  => $valueOld, 
                                    'valueNew'  => $valueNew, 
                                ];
                            }
                        }

                    }



                    $items['AuditLogs'] = $AuditLogs;
                    $items['AuditLogDetails'] = $AuditLogDetails;

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

                $accesses   = [];
                $modules    = []; 
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

                    $isAdmin = $decrypted_id==1?1:0;

                    $query = DB::table('user_starting_informations');
                    $query = $query->select(
                        "user_starting_informations.*", 
                        "users.username as realUsername", 
                        "users.userTypeID", 
                        "users.biometricIdNumber", 
                        "users.status", 
                    );
                    $query = $query->leftJoin('users', "user_starting_informations.userID", '=', 'users.userID');
                    $query = $query->where("user_starting_informations.{$this->tablePrimaryKey}", $decrypted_id);
                    $query = $query->first();
                    if ($query) {
                        $row = [
                            'biometricIdNumber'     => $query->biometricIdNumber, 
                            'realUsername'          => $query->realUsername, 
                            'username'              => $query->username, 
                            'password'              => $query->password, 
                            'userTypeID'            => $query->userTypeID, 
                            'fname'                 => $query->fname, 
                            'mname'                 => $query->mname, 
                            'lname'                 => $query->lname, 
                            'idNumber'              => $query->idNumber, 
                            'userEmploymentTypeID'  => $query->userEmploymentTypeID, 
                            'officeID'              => $query->officeID, 
                            'jobPositionID'         => $query->jobPositionID, 
                            'salaryMonthly'         => $query->salaryMonthly, 
                            'salaryYearly'          => $query->salaryYearly, 
                            'status'                => $query->status, 
                        ];

                        // modules 
                        $query = AppModuleAction::leftJoin('AppModules', 'AppModuleActions.appModuleID', '=', 'AppModules.appModuleID');
                        $query = $query->leftJoin('AppActions', 'AppModuleActions.appActionID', '=', 'AppActions.appActionID');
                        $query = $query->orderBy('AppModules.rank', 'asc');
                        $query = $query->orderBy('AppActions.rank', 'asc');
                        $query = $query->select(
                            'AppModuleActions.appModuleActionID', 
                            'AppModules.isDefault', 
                            'AppModules.name as amName', 
                            'AppActions.name as acName', 
                        );
                        $query = $query->get();
        
                        $isDefault      = 0;
                        $moduleName     = "";
                        $moduleActions  = [];
                        if ($query) {
                            foreach ($query as $q) {

                                if ($moduleName != $q->amName) {
                                    if ($moduleActions) {
                                        $modules[] = [
                                            'module'    => $moduleName, 
                                            'actions'   => $moduleActions, 
                                        ];
                                    }
                                    $isDefault  = $q->isDefault;
                                    $moduleName = $q->amName;
                                    $moduleActions = [];
                                }

                                $moduleActions[] = [
                                    'id'        => $q->appModuleActionID,
                                    'action'    => $q->acName,
                                    'isDefault' => $isDefault,
                                ];


                            }
                            if ($moduleActions) {
                                $modules[] = [
                                    'module'    => $moduleName, 
                                    'actions'   => $moduleActions, 
                                ];
                            }
                        }
        
                        // accesses 
                        $query = UserAccess::where('userID', $decrypted_id)->where('status', 1)->get();
                        if ($query) {
                            foreach ($query as $q) {
                                $accesses[] = $q->appModuleActionID;
                            }
                        }

        
                        /** final variables */
                        $items['hasButtonEdit']         = $this->_checkAccess($token_userID, $this->moduleActionIDs['Update']);
                        $items['UserTypes']            = DB::table('UserTypes')->orderBy('name', 'asc')->get();
                        $items['offices']               = DB::table('offices')->orderBy('name', 'asc')->get();
                        $items['JobPositions']         = DB::table('JobPositions')->orderBy('name', 'asc')->get();
                        $items['user_employment_types'] = DB::table('user_employment_types')->orderBy('name', 'asc')->get();
                        $items['modules']               = $modules;
                        $items['accesses']              = $accesses;
                        $items['row']                   = $row;
                        $items['isAdmin']               = $isAdmin;

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
                // real
                $request_fields0     = ['realUserTypeID', 'realStatus'];
                // users
                $request_fields1     = ['username', 'password', 'userTypeID'];
                // user_starting_informations
                $request_fields3    = ['appModuleActionIDs'];
                // user_starting_informations
                $request_fields2    = ['username', 'password', 'fname', 'mname', 'lname', 'idNumber', 'userEmploymentTypeID', 'officeID', 'jobPositionID', 'salaryMonthly', 'salaryYearly'];
        
                /** variables */
                $decrypted_id       = $this->_decryptID($id);
                $request_data       = [];
                $request_data0      = [];
                $request_data1      = [];
                $request_data2      = [];
                $request_data3      = [];
                $hasError           = 0; 
                $requires           = '';
                $required_fields    = [
                    'realUserTypeID'    => 'User Type', 
                    'realStatus'        => 'Status', 
                    'biometricIdNumber' => 'Biometric ID Number', 
                ];
        
                $isPending = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id)->where('status', 0)->count();

                if ($isPending) {
                    $required_fields    = [
                        'username'              => 'Username', 
                        'password'              => 'Password', 
                        'userTypeID'            => 'User Type', 
                        'fname'                 => 'First Name', 
                        'lname'                 => 'Last Name', 
                        'idNumber'              => 'Employee ID', 
                        'userEmploymentTypeID'  => 'Employee Type', 
                        'officeID'              => 'Office', 
                        'jobPositionID'         => 'Job Position', 
                        'salaryMonthly'         => 'Salary Per Month', 
                        'salaryYearly'          => 'Salary Per Annum', 
                        'biometricIdNumber'     => 'Biometric ID Number', 
                    ];
                }

                /** data */
                if (!$isPending) {
                    $request_fields0[] = 'biometricIdNumber';
                    if ($request_fields0) {
                        foreach ($request_fields0 as $field) {
                            $request_data[$field] = $request->input($field) ?: '';
                            $request_data0[$field] = $request->input($field) ?: '';
                        }
                    }
                } else {
                    $request_fields1[] = 'biometricIdNumber';
                    if ($request_fields1) {
                        foreach ($request_fields1 as $field) {
                            $request_data[$field] = $request->input($field) ?: '';
                            $request_data1[$field] = $request->input($field) ?: '';
                        }
                    }
                    if ($request_fields2) {
                        foreach ($request_fields2 as $field2) {
                            $request_data[$field2] = $request->input($field2) ?: '';
                            $request_data2[$field2] = $request->input($field2) ?: '';
                        }
                    }
                }
                if ($request_fields3) {
                    foreach ($request_fields3 as $field) {
                        $request_data[$field] = $request->input($field) ?: '';
                        $request_data3[$field] = $request->input($field) ?: '';
                    }
                }
        
                /** check errors */
                // primary key value 
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
                    }
                    if ($hasError) $data = $this->response->status(401, $requires, 'Required field(s):');
                }

                if ($isPending) {
                    // duplicate username
                    if (!$hasError) {
                        $hasDuplicate = DB::table($this->table);
                        $hasDuplicate = $hasDuplicate->whereNot($this->tablePrimaryKey, $decrypted_id);
                        $hasDuplicate = $hasDuplicate->where('username', $request_data['username']);
                        $hasDuplicate = $hasDuplicate->count();
                        if ($hasDuplicate) {
                            $hasError = 1;
                            $data = $this->response->status(409, 'Username already exist.', 'Invalid!');
                        }
                    }
    
                    // duplicate username
                    if (!$hasError) {
                        $hasDuplicate = DB::table('user_starting_informations');
                        $hasDuplicate = $hasDuplicate->whereNot($this->tablePrimaryKey, $decrypted_id);
                        $hasDuplicate = $hasDuplicate->where('username', $request_data['username']);
                        $hasDuplicate = $hasDuplicate->count();
                        if ($hasDuplicate) {
                            $hasError = 1;
                            $data = $this->response->status(409, 'Username already exist.', 'Invalid!');
                        }
                    }
    
                    // duplicate Employee ID
                    if (!$hasError) {
                        $hasDuplicate = DB::table('user_employments');
                        $hasDuplicate = $hasDuplicate->whereNot($this->tablePrimaryKey, $decrypted_id);
                        $hasDuplicate = $hasDuplicate->where('idNumber', $request_data['idNumber']);
                        $hasDuplicate = $hasDuplicate->where('status', 1);
                        $hasDuplicate = $hasDuplicate->count();
                        if ($hasDuplicate) {
                            $hasError = 1;
                            $data = $this->response->status(409, 'Employee ID already exist.', 'Invalid!');
                        }
                    }
    
                    // duplicate Employee ID
                    if (!$hasError) {
                        $hasDuplicate = DB::table('user_starting_informations');
                        $hasDuplicate = $hasDuplicate->whereNot($this->tablePrimaryKey, $decrypted_id);
                        $hasDuplicate = $hasDuplicate->where('idNumber', $request_data['idNumber']);
                        $hasDuplicate = $hasDuplicate->count();
                        if ($hasDuplicate) {
                            $hasError = 1;
                            $data = $this->response->status(409, 'Employee ID already exist.', 'Invalid!');
                        }
                    }
            
                    // duplicate user
                    if (!$hasError) {
                        $hasDuplicate = DB::table('user_personal_informations');
                        $hasDuplicate = $hasDuplicate->whereNot($this->tablePrimaryKey, $decrypted_id);
                        $hasDuplicate = $hasDuplicate->where('fname', $request_data['fname']);
                        $hasDuplicate = $hasDuplicate->where('lname', $request_data['lname']);
                        $hasDuplicate = $hasDuplicate->count();
                        if ($hasDuplicate) {
                            $hasError = 1;
                            $data = $this->response->status(409, 'First Name and Last Name already exist.', 'Invalid!');
                        }
                    }
    
                    // duplicate user
                    if (!$hasError) {
                        $hasDuplicate = DB::table('user_starting_informations');
                        $hasDuplicate = $hasDuplicate->whereNot($this->tablePrimaryKey, $decrypted_id);
                        $hasDuplicate = $hasDuplicate->where('fname', $request_data['fname']);
                        $hasDuplicate = $hasDuplicate->where('lname', $request_data['lname']);
                        $hasDuplicate = $hasDuplicate->count();
                        if ($hasDuplicate) {
                            $hasError = 1;
                            $data = $this->response->status(409, 'First Name and Last Name already exist.', 'Invalid!');
                        }
                    }
                }

                // duplicate Biometric ID
                if (!$hasError) {
                    $hasDuplicate = DB::table('users');
                    $hasDuplicate = $hasDuplicate->whereNot($this->tablePrimaryKey, $decrypted_id);
                    $hasDuplicate = $hasDuplicate->where('biometricIdNumber', $request_data['biometricIdNumber']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Biometric ID Number already exist.', 'Invalid!');
                    }
                }

                // query
                if (!$hasError) {

                    $user = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id)->first();

                    $username           = '';
                    $password           = '';
                    $dateInserted       = null;
                    $dateActivated      = null;
                    $dateDeactivated    = null;
                    $status             = 0;
                    if ($user) {
                        $username           = $user->username;
                        $password           = $user->password;
                        $dateInserted       = $user->dateInserted;
                        $dateActivated      = $user->dateActivated;
                        $status             = $user->status;
                    }

                    $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                    if (!$isPending) {
                        if ($query) {
                            // update
                            $req_fields = ['username', 'password', 'userTypeID', 'dateInserted', 'dateActivated', 'dateDeactivated', 'status'];
                            $req = [
                                'userTypeID' => $request_data0['realUserTypeID'], 
                                'status' => $request_data0['realStatus'], 
                            ];
                            if ($request_data0['realStatus'] == 1 && $status == -1) $req['dateActivated'] = date('Y-m-d H:i:s');
                            if ($request_data0['realStatus'] == -1 && $status == 1) $req['dateDeactivated'] = date('Y-m-d H:i:s');
                            $query->update($req);
                            // update audit logs
                            $logFields = $req_fields;
                            $req['username'] = $username;
                            $req['password'] = $password;
                            $req['dateInserted'] = $dateInserted;
                            if (!array_key_exists('dateActivated', $req)) $req['dateActivated'] = $dateActivated;
                            if (!array_key_exists('dateDeactivated', $req)) $req['dateDeactivated'] = $dateDeactivated;
                            // 
                            $this->_auditLog($request_token['data'], $this->moduleActionIDs['Update'], $this->table, $this->tablePrimaryKey, $decrypted_id, $req, "Updated {$this->logTitle} Record", $logFields, 1);

                            // accesses 
                            if ($request_data['appModuleActionIDs']) {
    
                                $query = DB::table('UserAccesses')->where('userID', $decrypted_id)->get();
    
                                // update changed access
                                $appModuleActionIDs = $request_data['appModuleActionIDs'];
                                if ($query) {
                                    foreach ($query as $q) {
    
                                        $status = 0;
                                        if (!$q->status && in_array($q->appModuleActionID, $appModuleActionIDs)) {
                                            $status = 1;
                                        }
    
                                        if (($q->status && !in_array($q->appModuleActionID, $appModuleActionIDs)) || 
                                            (!$q->status && in_array($q->appModuleActionID, $appModuleActionIDs))) {
    
                                            DB::table('UserAccesses')->where('userAccessID', $q->userAccessID)->update(['status' => $status]);
                                            // update audit logs 
                                            $logFields = ['status'];
                                            $dataAdditionals = [ 'status' => $status ];
                                            $this->_auditLog($request_token['data'], $this->moduleActionIDs['Update'], 'UserAccesses', 'userAccessID', $q->userAccessID, $dataAdditionals, "Updated User Access Record", $logFields, 1);
    
                                        }
                                        $appModuleActionIDs = array_diff($appModuleActionIDs, [$q->appModuleActionID]);
                                    }
                                }
    
                                // insert new access
                                if ($appModuleActionIDs) {
                                    foreach ($appModuleActionIDs as $appModuleActionID) {
    
                                        $dataAdditionals = [
                                            'userID' => $decrypted_id, 
                                            'appModuleActionID' => $appModuleActionID, 
                                            'status' => 1, 
                                        ];
                                        $query = UserAccess::create($dataAdditionals);
    
                                        // insert audit logs 
                                        $logFields = ['userID', 'appModuleActionID', 'status'];
                                        $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], 'UserAccesses', 'userAccessID', $query->userAccessID, $dataAdditionals, "Inserted User Access Record", $logFields, 1);
    
                                    }
                                }
    
                            }

                        } else {
                            $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                        }
                    } else {
                        if ($query) {

                            $request_fields1 = ['username', 'password', 'userTypeID'];

                            // update
                            $request_data1['password']          = bcrypt($request_data1['password']);
                            $request_data1['dateInserted']      = $dateInserted;
                            $request_data1['dateActivated']     = $dateActivated;
                            $request_data1['dateDeactivated']   = $dateDeactivated;
                            $request_data1['status']            = 0;
                            $query->update($request_data1);
                            // update audit logs
                            $logFields = $request_fields1;
                            $logFields[] = 'dateInserted';
                            $logFields[] = 'dateActivated';
                            $logFields[] = 'dateDeactivated';
                            $logFields[] = 'status';
                            $this->_auditLog($request_token['data'], $this->moduleActionIDs['Update'], $this->table, $this->tablePrimaryKey, $decrypted_id, $request_data1, "Updated {$this->logTitle} Record", $logFields, 1);
    
                            // starting information 
                            $query = DB::table('user_starting_informations')->where($this->tablePrimaryKey, $decrypted_id)->first();
                            if ($query) {
                                $userStartingInformationID = $query->userStartingInformationID;
                                $query = DB::table('user_starting_informations')->where('userStartingInformationID', $userStartingInformationID)->update($request_data2);
                                // update audit logs
                                $logFields = $request_fields2;
                                $this->_auditLog($request_token['data'], $this->moduleActionIDs['Update'], 'user_starting_informations', 'userStartingInformationID', $userStartingInformationID, $request_data2, "Updated {$this->logTitle} Record", $logFields, 1);
                            }
    
                            // accesses 
                            if ($request_data['appModuleActionIDs']) {
    
                                $query = DB::table('UserAccesses')->where('userID', $decrypted_id)->get();
    
                                // update changed access
                                $appModuleActionIDs = $request_data['appModuleActionIDs'];
                                if ($query) {
                                    foreach ($query as $q) {
    
                                        $status = 0;
                                        if (!$q->status && in_array($q->appModuleActionID, $appModuleActionIDs)) {
                                            $status = 1;
                                        }
    
                                        if (($q->status && !in_array($q->appModuleActionID, $appModuleActionIDs)) || 
                                            (!$q->status && in_array($q->appModuleActionID, $appModuleActionIDs))) {
    
                                            DB::table('UserAccesses')->where('userAccessID', $q->userAccessID)->update(['status' => $status]);
                                            // update audit logs 
                                            $logFields = ['status'];
                                            $dataAdditionals = [ 'status' => $status ];
                                            $this->_auditLog($request_token['data'], $this->moduleActionIDs['Update'], 'UserAccesses', 'userAccessID', $q->userAccessID, $dataAdditionals, "Updated User Access Record", $logFields, 1);
    
                                        }
                                        $appModuleActionIDs = array_diff($appModuleActionIDs, [$q->appModuleActionID]);
                                    }
                                }
    
                                // insert new access
                                if ($appModuleActionIDs) {
                                    foreach ($appModuleActionIDs as $appModuleActionID) {
    
                                        $dataAdditionals = [
                                            'userID' => $decrypted_id, 
                                            'appModuleActionID' => $appModuleActionID, 
                                            'status' => 1, 
                                        ];
                                        $query = UserAccess::create($dataAdditionals);
    
                                        // insert audit logs 
                                        $logFields = ['userID', 'appModuleActionID', 'status'];
                                        $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], 'UserAccesses', 'userAccessID', $query->userAccessID, $dataAdditionals, "Inserted User Access Record", $logFields, 1);
    
                                    }
                                }
    
                            }
    
                        } else {
                            $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                        }
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

    public function delete(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Delete'])) {
                $decrypted_id = $this->_decryptID($id);

                // startinginformations
                $table = 'user_starting_informations';
                $tablePrimaryKey = 'userStartingInformationID';

                $query = DB::table($table)->where('userID', $decrypted_id)->first();
                if ($query) {
                    DB::table($table)->where('userID', $decrypted_id)->delete();
                    // delete audit logs
                    $this->_auditLog($request_token['data'], $this->moduleActionIDs['Delete'], $table, $tablePrimaryKey, $query->userStartingInformationID, [], "Deleted User Starting Information Record", []);
                }

                // accesses
                $table = 'UserAccesses';
                $tablePrimaryKey = 'userAccessID';

                $query = DB::table($table)->where('userID', $decrypted_id)->get();
                if ($query) {
                    foreach ($query as $q) {
                        // delete audit logs
                        $this->_auditLog($request_token['data'], $this->moduleActionIDs['Delete'], $table, $tablePrimaryKey, $q->userAccessID, [], "Deleted User Access Record", []);
                    }
                }
                DB::table($table)->where('userID', $decrypted_id)->delete();
                
                // users
                DB::table($this->table)->where('userID', $decrypted_id)->delete();
                // delete audit logs
                $this->_auditLog($request_token['data'], $this->moduleActionIDs['Delete'], $this->table, $this->tablePrimaryKey, $decrypted_id, [], "Deleted {$this->logTitle} Record", []);

            } else {
                $data = $this->response->status(401, 'Access denied.');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    public function change_password(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Change Password'])) {
                /** fields */
                $request_fields     = ['passwordNew', 'passwordCon'];
        
                /** variables */
                $decrypted_id       = 0;
                $request_data       = [];
                $hasError           = 0; 
                $requires           = '';
                $required_fields    = [
                    'passwordNew'   => 'New Password', 
                    'passwordCon'   => 'Confirm New Password', 
                ];
        
                /** data */
                if ($request_fields) {
                    foreach ($request_fields as $field) {
                        $request_data[$field] = $request->input($field) ?: '';
                    }
                }
        
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
                    }
                    if ($hasError) $data = $this->response->status(401, $requires, 'Required field(s):');
                }
    
                // password does not match 
                if (!$hasError) {
                    if ($request_data['passwordNew'] != $request_data['passwordCon']) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'New password does not match.', 'Invalid!');
                    }
                }
    
                // password requirements
                if (!$hasError) {
                    if (!PasswordHelper::_isValidPassword($request_data['passwordNew'])) {
                        $message = <<<EOT
                        Your password should be:
                        <br />✔️ At least 8 characters long
                        <br />✔️ Have at least one number (0-9)
                        <br />✔️ Include at least one uppercase letter (A-Z)
                        <br />✔️ Include at least one lowercase letter (a-z)
                        EOT;
                        $data = $this->response->status(400, $message, "Invalid!");
                        $hasError = 1;
                    } 
                }

                // query
                if (!$hasError) {

                    $user = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id)->first();

                    $username           = '';
                    $password           = '';
                    $dateInserted       = null;
                    $dateActivated      = null;
                    $dateDeactivated    = null;
                    $userTypeID             = 0;
                    $status             = 0;
                    if ($user) {
                        $username           = $user->username;
                        $password           = $user->password;
                        $dateInserted       = $user->dateInserted;
                        $dateActivated      = $user->dateActivated;
                        $dateDeactivated    = $user->dateDeactivated;
                        $userTypeID         = $user->userTypeID;
                        $status             = $user->status;
                    }

                    $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                    if ($query) {
                        // update
                        $request_data1 = [];
                        $request_data1['password'] = bcrypt($request_data['passwordNew']);
                        $query->update($request_data1);
                        // update audit logs
                        $logFields = ['status', 'password', 'username', 'userTypeID', 'dateInserted', 'dateActivated', 'dateDeactivated'];

                        $request_data1['username'] = $username;
                        $request_data1['userTypeID'] = $userTypeID;
                        $request_data1['dateInserted'] = $dateInserted;
                        $request_data1['dateActivated'] = $dateActivated;
                        $request_data1['dateDeactivated'] = $dateDeactivated;
                        $request_data1['status'] = $status;
                        $this->_auditLog($request_token['data'], $this->moduleActionIDs['Change Password'], $this->table, $this->tablePrimaryKey, $decrypted_id, $request_data1, "Change Password {$this->logTitle} Record", $logFields, 1);

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


