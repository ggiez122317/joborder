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

class UserTypeController extends MasterController
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
        $this->module           = 'User Types';
        $this->controller       = 'user-types';
        $this->logTitle         = 'User Type';
        $this->table            = 'UserTypes';
        $this->tablePrimaryKey  = 'userTypeID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'     => 12, 
            'PrintList' => 19, 
            'Insert'    => 20, 
            'View'      => 21, 
            'Audit'     => 22, 
            'Update'    => 23, 
            'Delete'    => 24, 
        ];
        // id => [label, table, field]
        $this->auditFieldValues = [
            'name'          => ['User Type Name', '', ''], 
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
                'name'      => 'Name', 
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

    public function audit2(string $id)
    {

        // initialize variables
        $this->page = 'Audit User Type Accesses';
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
                    'name'          => 'likeboth', 
                    'description'   => 'likeboth', 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'name'          => 'UserTypes', 
                    'description'   => 'UserTypes', 
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
                            'userTypeID'    => Crypt::encryptString("{$tr->userTypeID}"), 
                            'name'          => $tr->name, 
                            'description'   => $tr->description, 
                        ];
                    }
                }
                
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
                $items['hasButtonPrint']    = $this->_checkAccess($token_userID, $this->moduleActionIDs['PrintList']);
                $items['hasButtonAdd']      = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $items['hasButtonView']     = $this->_checkAccess($token_userID, $this->moduleActionIDs['View']);
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
                
                /** paging */
                $filters['limit']       = isset($_GET['limit']) ? trim($_GET['limit']) : 10;
                $filters['page']        = $request->query('page', 1);
                $filters['sortField']   = $request->query('sortField', '');
                $filters['sortBy']      = $request->query('sortBy', '');

                /** conditions */
                $conditions = [
                    'name'          => 'likeboth', 
                    'description'   => 'likeboth', 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'name'          => 'UserTypes', 
                    'description'   => 'UserTypes', 
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
                            'userTypeID'    => Crypt::encryptString("{$tr->userTypeID}"), 
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
                $modules = [];

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

                $items['hasButtonAdd'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $items['modules'] = $modules;

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
                $request_fields     = ['name', 'description', 'appModuleActionIDs'];
        
                /** variables */
                $id                 = '';
                $request_data       = [];
                $hasError           = 0;
                $requires           = '';
                $required_fields    = [
                    'name' => 'Name', 
                    'appModuleActionIDs' => 'Accesses', 
                ];
        
                /** data */
                if ($request_fields) {
                    foreach ($request_fields as $field) {
                        $request_data[$field] = $request->input($field) ?: '';
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
        
                // duplicate name
                if (!$hasError) {
                    $hasDuplicate = DB::table($this->table);
                    $hasDuplicate = $hasDuplicate->where('name', $request_data['name']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Name already exist.', 'Invalid!');
                    }
                }
        
                /** query */
                if (!$hasError) {
                    $insert_arr = $request_data;
                    unset($insert_arr['appModuleActionIDs']);
                    $pkID = DB::table($this->table)->insertGetId($insert_arr);
        
                    if ($pkID) {

                        // insert audit logs 
                        $logFields = ['name', 'description'];
                        $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], $this->table, $this->tablePrimaryKey, $pkID, $request_data, "Inserted {$this->logTitle} Record", $logFields, 1);

                        // return encrypted id
                        $id = Crypt::encryptString("{$pkID}");

                        // accesses
                        if ($request_data['appModuleActionIDs']) { 
                            $dataAdditionals = [];
                            foreach ($request_data['appModuleActionIDs'] as $appModuleActionID) { 

                                $dataAdditionals = [
                                    'userTypeID'        => $pkID, 
                                    'appModuleActionID' => $appModuleActionID, 
                                    'status'            => 1, 
                                ];
                                $query = UserTypeAccess::create($dataAdditionals); 

                                // insert audit logs 
                                $logFields = ['userTypeID', 'appModuleActionID', 'status'];
                                $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], 'UserTypeAccesses', 'userTypeAccessID', $query->userTypeAccessID, $dataAdditionals, "Inserted User Type Access Record", $logFields, 1);

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
                $isSuperAdmin   = 0;
                $row            = []; 
                $hasError       = 0;
                $modules        = [];
                $accesses       = [];
        
                /** check errors */
                // primary key value 
                $decrypted_id = $this->_decryptID($id);
                if (!$decrypted_id) {
                    $hasError = 1;
                    $data = $this->response->status(400);
                }

                if ($decrypted_id == 1) $isSuperAdmin = 1;
        
                /** query */
                if (!$hasError) {
                    $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id)->first();
                    if ($query) {
                        $row = [
                            'name'          => $query->name, 
                            'description'   => $query->description, 
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
                        $query = UserTypeAccess::where('userTypeID', $decrypted_id)->where('status', 1)->get();
                        if ($query) {
                            foreach ($query as $q) {
                                $accesses[] = $q->appModuleActionID;
                            }
                        }

                        /** final variables */
                        $items['hasButtonAdd']      = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                        $items['hasButtonAudit']    = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
                        $items['hasButtonEdit']     = $this->_checkAccess($token_userID, $this->moduleActionIDs['Update']);
                        $items['hasButtonDelete']   = $this->_checkAccess($token_userID, $this->moduleActionIDs['Delete']);
                        $items['isSuperAdmin']      = $isSuperAdmin;
                        $items['row'] = $row;
                        $items['modules'] = $modules;
                        $items['accesses'] = $accesses;
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

    public function audit_page_UserTypeAccess(Request $request, string $id)
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

                    $table      = 'UserTypeAccesses';
                    $tablePK    = 'userTypeAccessID';

                    $query = DB::table($table);
                    $query = $query->select(
                        'UserTypeAccesses.userTypeAccessID', 
                        'AppModules.name as amName', 
                        'AppActions.name as aaName', 
                    );
                    $query = $query->leftJoin('AppModuleActions', "{$table}.appModuleActionID", '=', 'AppModuleActions.appModuleActionID');
                    $query = $query->leftJoin('AppModules', "AppModuleActions.appModuleID", '=', 'AppModules.appModuleID');
                    $query = $query->leftJoin('AppActions', "AppModuleActions.appActionID", '=', 'AppActions.appActionID');
                    $query = $query->where($this->tablePrimaryKey, $decrypted_id);
                    $query = $query->get();

                    if ($query) {
                        $userTypeAccessIDs = [];
                        $fieldNames = [];
                        foreach ($query as $q) {
                            if (!in_array($q->userTypeAccessID, $userTypeAccessIDs)) $userTypeAccessIDs[] = $q->userTypeAccessID;
                            $fieldNames[$q->userTypeAccessID] = "{$q->amName} ({$q->aaName})";
                        }

                        if ($userTypeAccessIDs) {

                            // audit log details 
                            $query = AuditLogDetail::leftJoin('AuditLogs', 'AuditLogDetails.auditLogID', '=', 'AuditLogs.auditLogID');
                            $query = $query->leftJoin('AppModuleActions', 'AuditLogs.appModuleActionID', '=', 'AppModuleActions.appModuleActionID');
                            $query = $query->leftJoin('AppActions', 'AppModuleActions.appActionID', '=', 'AppActions.appActionID');
                            $query = $query->whereIn('AuditLogs.primaryKeyID', $userTypeAccessIDs);
                            $query = $query->where('AuditLogs.tableName', $table);
                            $query = $query->where('AuditLogs.primaryKey', $tablePK);
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

    public function put_page(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];
            $decrypted_id = $this->_decryptID($id);

            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Update']) && $decrypted_id != 1) {
                /** variables */
                $row        = [];
                $hasError   = 0;
                $modules    = [];
                $accesses   = [];
        
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
                            'name'          => $query->name, 
                            'description'   => $query->description, 
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
                        $query = UserTypeAccess::where('userTypeID', $decrypted_id)->where('status', 1)->get();
                        if ($query) {
                            foreach ($query as $q) {
                                $accesses[] = $q->appModuleActionID;
                            }
                        }

                        /** final variables */
                        $items['hasButtonEdit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Update']);
                        $items['row'] = $row;
                        $items['modules'] = $modules;
                        $items['accesses'] = $accesses;
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
            $decrypted_id = $this->_decryptID($id);

            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Update']) && $decrypted_id != 1) {
                /** fields */
                $request_fields = ['name', 'description', 'appModuleActionIDs'];
        
                /** variables */
                $request_data       = [];
                $hasError           = 0; 
                $requires           = '';
                $required_fields    = [
                    'name' => 'Name', 
                    'appModuleActionIDs' => 'Accesses', 
                ];
        
                /** data */
                if ($request_fields) {
                    foreach ($request_fields as $field) {
                        $request_data[$field] = $request->input($field);
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
        
                // duplicate name
                if (!$hasError) {
                    $hasDuplicate = DB::table($this->table);
                    $hasDuplicate = $hasDuplicate->whereNot($this->tablePrimaryKey, $decrypted_id);
                    $hasDuplicate = $hasDuplicate->where('name', $request_data['name']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Name already exist.', 'Invalid!');
                    }
                }
        
                // query
                if (!$hasError) {
                    $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                    if ($query) {
                        $update_arr = [];
                        foreach ($request_fields as $field) {
                            if ($request->filled($field) && !in_array($field, ['appModuleActionIDs'])) { 
                                $update_arr[$field] = $request->input($field);
                            }
                        }
                        if ($update_arr) {
                            $query->update($update_arr);

                            // update audit logs
                            $logFields = ['name', 'description'];
                            $this->_auditLog($request_token['data'], $this->moduleActionIDs['Update'], $this->table, $this->tablePrimaryKey, $decrypted_id, $request_data, "Updated {$this->logTitle} Record", $logFields, 1);

                            if ($request_data['appModuleActionIDs']) {

                                $query = DB::table('UserTypeAccesses')->where('userTypeID', $decrypted_id)->get();

                                $appModuleActionIDs = $request_data['appModuleActionIDs'];
                                if ($query) {
                                    foreach ($query as $q) {

                                        $status = 0;
                                        if (!$q->status && in_array($q->appModuleActionID, $appModuleActionIDs)) {
                                            $status = 1;
                                        }

                                        if (($q->status && !in_array($q->appModuleActionID, $appModuleActionIDs)) || 
                                            (!$q->status && in_array($q->appModuleActionID, $appModuleActionIDs))) {

                                            DB::table('UserTypeAccesses')->where('userTypeAccessID', $q->userTypeAccessID)->update(['status' => $status]);
                                            // update audit logs 
                                            $logFields = ['status'];
                                            $dataAdditionals = [ 'status' => $status ];
                                            $this->_auditLog($request_token['data'], $this->moduleActionIDs['Update'], 'UserTypeAccesses', 'userTypeAccessID', $q->userTypeAccessID, $dataAdditionals, "Updated User Type Access Record", $logFields, 1);

                                        }
                                        $appModuleActionIDs = array_diff($appModuleActionIDs, [$q->appModuleActionID]);
                                    }
                                }

                                if ($appModuleActionIDs) {
                                    foreach ($appModuleActionIDs as $appModuleActionID) {

                                        $dataAdditionals = [
                                            'userTypeID' => $decrypted_id, 
                                            'appModuleActionID' => $appModuleActionID, 
                                            'status' => 1, 
                                        ];
                                        $query = UserTypeAccess::create($dataAdditionals);

                                        // insert audit logs 
                                        $logFields = ['userTypeID', 'appModuleActionID', 'status'];
                                        $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], 'UserTypeAccesses', 'userTypeAccessID', $query->userTypeAccessID, $dataAdditionals, "Inserted User Type Access Record", $logFields, 1);
    
                                    }
                                }

                            }
                        }
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

    public function delete(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];
            $decrypted_id = $this->_decryptID($id);
            
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Delete']) && $decrypted_id != 1) {
                $decrypted_id = $this->_decryptID($id);
                $count = User::where('userTypeID', $decrypted_id)->count();
                if (!$count) {

                    // UserTypeAccesses
                    $table = 'UserTypeAccesses';
                    $tablePrimaryKey = 'userTypeAccessID';

                    $query = DB::table($table)->where('userTypeID', $decrypted_id)->get();
                    if ($query) {
                        foreach ($query as $q) {
                            // delete audit logs
                            $this->_auditLog($request_token['data'], $this->moduleActionIDs['Delete'], $table, $tablePrimaryKey, $q->userTypeAccessID, [], "Deleted User Type Access Record", []);
                        }
                    }
                    DB::table($table)->where('userTypeID', $decrypted_id)->delete();

                    // UserTypes
                    DB::table($this->table)->where('userTypeID', $decrypted_id)->delete();
                    // delete audit logs
                    $this->_auditLog($request_token['data'], $this->moduleActionIDs['Delete'], $this->table, $this->tablePrimaryKey, $decrypted_id, [], "Deleted {$this->logTitle} Record", []);

                } else {
                    $data = $this->response->status(409, 'User type is in use and cannot be deleted.', 'Invalid!');
                }
            } else {
                $data = $this->response->status(401, 'Access denied.');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 
    
}


