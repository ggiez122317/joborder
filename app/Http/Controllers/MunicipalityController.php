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

class MunicipalityController extends MasterController
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
        $this->module           = 'Municipalities';
        $this->controller       = 'municipalities';
        $this->logTitle         = 'Municipality';
        $this->table            = 'cities';
        $this->tablePrimaryKey  = 'cityID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'     => 14, 
            'PrintList' => 45, 
            'Insert'    => 46, 
            'View'      => 47, 
            'Audit'     => 48, 
            'Update'    => 49, 
            'Delete'    => 0, 
        ];
        // id => [label, table, field]
        $this->auditFieldValues = [
            'name'          => ['Municipality Name', '', ''], 
            'zipcode'       => ['Zipcode', '', ''], 
            'provinceID'    => ['Province ID', 'provinces', 'name'], 
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
                'table'     => $this->table, 
            ], 
            [
                'name'      => 'Zipcode', 
                'connector' => 'has', 
                'variable'  => 'zipcode', 
                'table'     => $this->table, 
            ], 
            [
                'name'      => 'Province', 
                'connector' => 'is', 
                'variable'  => 'provinceID', 
                'table'     => 'provinces', 
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
                    if ($fv['connector'] == 'is') {
                        $query = DB::table($fv['table']);
                        $query = $query->where("{$fv['table']}.{$fv['variable']}", $queryParams[$fv['variable']]);
                        $query = $query->first();
                        if ($query) $value = " {$fv['connector']} \"{$query->name}\"";
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
                $filters['sortField']   = $request->query('sortField', 'name');
                $filters['sortBy']      = $request->query('sortBy', 'asc');

                /** conditions */
                $conditions = [
                    'name'          => [$this->table, 'likeboth'], 
                    'zipcode'       => [$this->table, 'likeboth'], 
                    'provinceID'    => ['provinces', 'where'], 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'name'      => [$this->table, 'name'], 
                    'zipcode'   => [$this->table, 'zipcode'], 
                    'pName'     => ['provinces', 'name'], 
                ];

                // query count
                $query = DB::table($this->table);
                $query = $query->join('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField, '');
                        if ($value && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if ($value && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
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
                    "provinces.name as pName", 
                );
                $query = $query->join('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField, '');
                        if ($value && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if ($value && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
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
                        $records[] = [
                            'cityID'    => Crypt::encryptString("{$tr->cityID}"), 
                            'name'      => $tr->name, 
                            'zipcode'   => $tr->zipcode, 
                            'pName'   => $tr->pName, 
                        ];
                    }
                }
                
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
                $items['hasButtonPrint']    = $this->_checkAccess($token_userID, $this->moduleActionIDs['PrintList']);
                $items['hasButtonAdd']      = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $items['hasButtonView']     = $this->_checkAccess($token_userID, $this->moduleActionIDs['View']);
                $items['provinces'] = DB::table('provinces')->orderBy('name', 'asc')->get();
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
                $filters['sortField']   = $request->query('sortField', 'name');
                $filters['sortBy']      = $request->query('sortBy', 'asc');

                /** conditions */
                $conditions = [
                    'name'          => [$this->table, 'likeboth'], 
                    'zipcode'       => [$this->table, 'likeboth'], 
                    'provinceID'    => ['provinces', 'where'], 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'name'      => [$this->table, 'name'], 
                    'zipcode'   => [$this->table, 'zipcode'], 
                    'pName'     => ['provinces', 'name'], 
                ];

                // query count
                $query = DB::table($this->table);
                $query = $query->join('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField, '');
                        if ($value && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if ($value && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
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
                    "provinces.name as pName", 
                );
                $query = $query->join('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField, '');
                        if ($value && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if ($value && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
                        // filters 
                        $filters[$cField] = $value;
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
                            'name'      => $tr->name, 
                            'zipcode'   => $tr->zipcode, 
                            'pName'     => $tr->pName, 
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
                $items['hasButtonAdd'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $items['provinces'] = DB::table('provinces')->orderBy('name', 'asc')->get();
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
                $request_fields     = ['name', 'zipcode', 'provinceID'];
        
                /** variables */
                $id                 = '';
                $request_data       = [];
                $hasError           = 0;
                $requires           = '';
                $required_fields    = [
                    'name' => 'Name', 
                    'provinceID' => 'Province', 
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
                    $hasDuplicate = $hasDuplicate->where('provinceID', $request_data['provinceID']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Record already exist.', 'Invalid!');
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
                    $query = $query->join('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
                    $query = $query->where("{$this->table}.{$this->tablePrimaryKey}", $decrypted_id);
                    $query = $query->select(
                        "{$this->table}.name", 
                        "{$this->table}.zipcode", 
                        "provinces.name as pName", 
                    );
                    $query = $query->first();
                    if ($query) {
                        $row = [
                            'name' => $query->name, 
                            'zipcode' => $query->zipcode, 
                            'pName' => $query->pName, 
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
                            'name' => $query->name, 
                            'zipcode' => $query->zipcode, 
                            'provinceID' => $query->provinceID, 
                        ];
        
                        /** final variables */
                        $items['hasButtonEdit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Update']);
                        $items['provinces'] = DB::table('provinces')->orderBy('name', 'asc')->get();
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
                $request_fields = ['name', 'zipcode', 'provinceID'];
        
                /** variables */
                $decrypted_id       = 0;
                $request_data       = [];
                $hasError           = 0; 
                $requires           = '';
                $required_fields    = [
                    'name' => 'Name', 
                    'provinceID' => 'Province', 
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
                    $hasDuplicate = $hasDuplicate->where('provinceID', $request_data['provinceID']);
                    $hasDuplicate = $hasDuplicate->where('name', $request_data['name']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Record already exist.', 'Invalid!');
                    }
                }
        
                // query
                if (!$hasError) {
                    $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                    if ($query) {
                        $update_arr = [];
                        foreach ($request_fields as $field) {
                            if ($request->filled($field)) { 
                                $update_arr[$field] = $request->input($field);
                            }
                        }
                        if ($update_arr) {
                            $query->update($update_arr);
                            // update audit logs
                            $logFields = $request_fields;
                            $this->_auditLog($request_token['data'], $this->moduleActionIDs['Update'], $this->table, $this->tablePrimaryKey, $decrypted_id, $request_data, "Updated {$this->logTitle} Record", $logFields, 1);
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

    // public function delete(Request $request, string $id)
    // {

    //     $data = $this->response->status(200);

    //     $items = [];

    //     // validate token
    //     $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
    //     if ($request_token['code'] == 200) {
    //         $token_userID = $request_token['data'];
    //         // check access
    //         if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Delete'])) {
    //             $decrypted_id = $this->_decryptID($id);
    //             $count = User::where('userTypeID', $decrypted_id)->count();
    //             if (!$count) {
    //                 // delete
    //                 UserTypeAccess::where('userTypeID', $decrypted_id)->delete();
    //                 DB::table($this->table)->where('userTypeID', $decrypted_id)->delete();

    //                 // delete audit logs
    //                 $this->_auditLog($request_token['data'], $this->moduleActionIDs['Delete'], $this->table, $this->tablePrimaryKey, $decrypted_id, [], "Deleted {$this->logTitle} Record", []);

    //             } else {
    //                 $data = $this->response->status(409, 'User type is in use and cannot be deleted.', 'Invalid!');
    //             }
    //         } else {
    //             $data = $this->response->status(401, 'Access denied.');
    //         }
    //     } else {
    //         $data = $this->response->status($request_token['code'], $request_token['message']);
    //     }

    //     return response()->json($data);
    // } 
    
}


