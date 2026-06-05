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

class LeaveCreditController extends MasterController
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
        $this->module           = 'Leave Credits';
        $this->controller       = 'leave-credits';
        $this->logTitle         = 'Leave Credit';
        $this->table            = 'user_leave_credits';
        $this->tablePrimaryKey  = 'userLeaveCreditID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'                     => 146, 
            'PrintList'                 => 0, 
            'Insert'                    => 0, 
            'View'                      => 148, 
            'Audit'                     => 0, 
            'Update'                    => 0, 
            'Delete'                    => 0, 
            'Print Leave Ledger Card'   => 147, 
        ];
        // id => [label, table, field]
        $this->auditFieldValues = [
            'userID'            => ['Employee', '', ''], 
            'creditsVacation'   => ['Vacation Credits', '', ''], 
            'creditsSick'       => ['Sick Credits', '', ''], 
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

    public function print_leave_ledger_card(Request $request, string $id)
    {

        // initialize variables
        $this->page = 'Print Leave Ledger Card';
        $this->_setVariables();
        $data = $this->data;

        $data['headerImage1'] = $this->_convertImageToBase64('assets/img/logos/lgu.png');
        $data['headerImage2'] = $this->_convertImageToBase64('assets/img/logos/hr.png');
        $data['imageOpaque'] = $this->_convertImageToBase64('assets/img/logos/hr_opaque.png');

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

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
                    'userID'        => [$this->table, 'where'], 
                    'jobPositionID' => ['user_personal_informations', 'where'], 
                    'officeID'      => ['offices', 'where'], 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'office'            => ['offices', 'name'], 
                    'employee'          => ['user_personal_informations', 'lname'], 
                    'jobPosition'       => ['JobPositions', 'name'], 
                    'creditsVacation'   => [$this->table, 'creditsVacation'], 
                    'creditsSick'       => [$this->table, 'creditsSick'], 
                ];

                // query count
                $query = DB::table($this->table);
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
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
                    $query = $query->orderBy($this->table.".".$this->tablePrimaryKey, 'desc');
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
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                );
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
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

                        $employee = $tr->lname;
                        $employee .= $tr->fname ? ", {$tr->fname}" : "";
                        $employee .= $tr->mname ? " {$tr->mname}" : "";

                        $office = '';
                        $jobPosition = '';

                        $records[] = [
                            'userLeaveCreditID' => Crypt::encryptString("{$tr->userLeaveCreditID}"), 
                            'employee'          => $employee, 
                            'office'            => $office, 
                            'jobPosition'       => $jobPosition, 
                            'creditsVacation'   => $tr->creditsVacation?number_format($tr->creditsVacation, 2):0, 
                            'creditsSick'       => $tr->creditsSick?number_format($tr->creditsSick, 2):0, 
                        ];
                    }
                }
                
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
                $items['hasButtonPrint']    = $this->_checkAccess($token_userID, $this->moduleActionIDs['PrintList']);
                $items['hasButtonView']     = $this->_checkAccess($token_userID, $this->moduleActionIDs['View']);
                $items['users']             = DB::table('user_personal_informations')->select("lname", "fname", "mname", "userID")->orderBy('lname', 'asc')->orderBy('fname', 'asc')->get();
                $items['offices']           = DB::table('offices')->orderBy('code', 'asc')->get();
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
                $items['hasButtonAdd'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
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
                $request_fields     = ['code', 'name', 'description'];
        
                /** variables */
                $id                 = '';
                $request_data       = [];
                $hasError           = 0;
                $requires           = '';
                $required_fields    = [
                    'code' => 'Job Position Code', 
                    'name' => 'Job Position Name', 
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
                        "user_personal_informations.lname", 
                        "user_personal_informations.fname", 
                        "user_personal_informations.mname", 
                    );
                    $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                    $query = $query->where("{$this->table}.{$this->tablePrimaryKey}", $decrypted_id);
                    $query = $query->first();
                    if ($query) {

                        $query2 = DB::table('user_leave_credit_details');
                        $query2 = $query2->where($this->tablePrimaryKey, $decrypted_id);
                        $query2 = $query2->orderBy('userLeaveCreditDetailID', 'asc');
                        $query2 = $query2->get();

                        $records = [];
                        if ($query2) {
                            foreach ($query2 as $q) {
                                $records[] = [
                                    'period'                        => $q->period, 
                                    'particulars'                   => $q->particulars, 
                                    'vacationEarned'                => $q->vacationEarned+0 ? number_format($q->vacationEarned, 3) : '', 
                                    'vacationUndertimeWithPay'      => $q->vacationUndertimeWithPay+0 ? number_format($q->vacationUndertimeWithPay, 3) : '', 
                                    'vacationBalance'               => $q->vacationBalance+0 ? number_format($q->vacationBalance, 3) : '', 
                                    'vacationUndertimeWithoutPay'   => $q->vacationUndertimeWithoutPay+0 ? number_format($q->vacationUndertimeWithoutPay, 3) : '', 
                                    'sickEarned'                    => $q->sickEarned+0 ? number_format($q->sickEarned, 3) : '', 
                                    'sickUndertimeWithPay'          => $q->sickUndertimeWithPay+0 ? number_format($q->sickUndertimeWithPay, 3) : '', 
                                    'sickBalance'                   => $q->sickBalance+0 ? number_format($q->sickBalance, 3) : '', 
                                    'sickUndertimeWithoutPay'       => $q->sickUndertimeWithoutPay+0 ? number_format($q->sickUndertimeWithoutPay, 3) : '', 
                                    'remarks'                       => $q->remarks, 
                                ];
                            }
                        }

                        $row = [
                            'employee'  => "$query->lname, $query->fname $query->mname", 
                            'vacation'  => number_format($query->creditsVacation, 3), 
                            'sick'      => number_format($query->creditsSick, 3), 
                            'records'   => $records, 
                        ];

                        /** final variables */
                        $items['hasButtonAdd']      = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                        $items['hasButtonAudit']    = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
                        $items['hasButtonEdit']     = $this->_checkAccess($token_userID, $this->moduleActionIDs['Update']);
                        $items['hasButtonCard']     = $this->_checkAccess($token_userID, $this->moduleActionIDs['Print Leave Ledger Card']);
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
                            'code' => $query->code, 
                            'name' => $query->name, 
                            'description' => $query->description, 
                        ];
        
                        /** final variables */
                        $items['hasButtonEdit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Update']);
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
                $request_fields     = ['code', 'name', 'description'];
        
                /** variables */
                $decrypted_id       = 0;
                $request_data       = [];
                $hasError           = 0; 
                $requires           = '';
                $required_fields    = [
                    'code' => 'Job Position Code', 
                    'name' => 'Job Position Name', 
                ];
        
                /** data */
                if ($request_fields) {
                    foreach ($request_fields as $field) {
                        $request_data[$field] = $_POST[$field];
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

    //  
    public function print_leave_ledger_card_data(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        $preparer       = "";
        $preparerPos    = "";
        $approver       = "";
        $approverPos    = "";

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];
            
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Print Leave Ledger Card'])) {

                $decrypted_id = $this->_decryptID($id);

                $pages    = [];

                $query = DB::table('user_leave_credit_details');
                $query = $query->select(
                    "user_leave_credit_details.*", 
                    "user_employments.dateAppointed", 
                    "user_employments.userEmploymentID", 
                    "user_employments.salaryMonthly", 
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                    "offices.name as oName", 
                    "JobPositions.name as jpName", 
                );
                $query = $query->leftjoin('user_employments', "user_leave_credit_details.userEmploymentID", '=', 'user_employments.userEmploymentID'); 
                $query = $query->leftjoin('user_personal_informations', "user_employments.userID", '=', 'user_personal_informations.userID'); 
                $query = $query->leftjoin('offices', "user_employments.officeID", '=', 'offices.officeID'); 
                $query = $query->leftjoin('JobPositions', "user_employments.jobPositionID", '=', 'JobPositions.jobPositionID'); 
                $query = $query->where("user_leave_credit_details.userLeaveCreditID", $decrypted_id);
                $query = $query->orderBy("user_employments.dateAppointed", 'asc');
                $query = $query->orderBy("user_leave_credit_details.userLeaveCreditDetailID", 'asc');
                $query = $query->get();

                if ($query) {

                    $userEmploymentID   = 0;
                    $dateAppointed      = '';
                    $name               = '';
                    $office             = '';
                    $position           = '';
                    $salary             = '';
                    $records            = [];

                    foreach ($query as $q) {

                        if ($userEmploymentID) {
                            if ($userEmploymentID != $q->userEmploymentID) {
                                $pages[] = [
                                    'dateAppointed' => $dateAppointed, 
                                    'name'      => $name, 
                                    'office'    => $office, 
                                    'position'  => $position, 
                                    'salary'    => $salary, 
                                    'records'   => $records, 
                                ];
                                $records    = [];
                            }
                        }

                        $userEmploymentID = $q->userEmploymentID;
                        $office     = $q->oName;
                        $position   = $q->jpName;
                        $salary     = number_format($q->salaryMonthly, 2);

                        $name = "$q->fname";
                        if ($q->mname) $name .= " $q->mname";
                        if ($q->lname) $name .= " $q->lname";
                        
                        if ($q->salaryMonthly+0) $salary = "P $salary per month";
                        if (!$dateAppointed) $dateAppointed = $q->dateAppointed ? date('F d, Y', strtotime($q->dateAppointed)) : '';

                        $records[] = [
                            'period'                        => $q->period, 
                            'particulars'                   => $q->particulars, 
                            'vacationEarned'                => $q->vacationEarned+0?number_format($q->vacationEarned, 3):'', 
                            'vacationUndertimeWithPay'      => $q->vacationUndertimeWithPay+0?number_format($q->vacationUndertimeWithPay, 3):'', 
                            'vacationBalance'               => $q->vacationBalance+0?number_format($q->vacationBalance, 3):'', 
                            'vacationUndertimeWithoutPay'   => $q->vacationUndertimeWithoutPay+0?number_format($q->vacationUndertimeWithoutPay, 3):'', 
                            'sickEarned'                    => $q->sickEarned+0?number_format($q->sickEarned, 3):'', 
                            'sickUndertimeWithPay'          => $q->sickUndertimeWithPay+0?number_format($q->sickUndertimeWithPay, 3):'', 
                            'sickBalance'                   => $q->sickBalance+0?number_format($q->sickBalance, 3):'', 
                            'sickUndertimeWithoutPay'       => $q->sickUndertimeWithoutPay+0?number_format($q->sickUndertimeWithoutPay, 3):'', 
                            'remarks'                       => $q->remarks, 
                        ];

                    }

                    $pages[] = [
                        'dateAppointed' => $dateAppointed, 
                        'name'      => $name, 
                        'office'    => $office, 
                        'position'  => $position, 
                        'salary'    => $salary, 
                        'records'   => $records, 
                    ];
                }

                // PREPARER
                $query = DB::table('user_employments');
                $query = $query->select(
                    "user_employments.*", 
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                    "JobPositions.name as jpName", 
                );
                $query = $query->leftjoin('user_personal_informations', "user_employments.userID", '=', 'user_personal_informations.userID');
                $query = $query->leftjoin('JobPositions', "user_employments.jobPositionID", '=', 'JobPositions.jobPositionID');
                $query = $query->where('user_employments.userID', $token_userID);
                $query = $query->where('user_employments.status', 1);
                $query = $query->first();
                if ($query) {
                    if ($query->jpName) {
                        $preparer       = ucwords($query->fname).($query->mname?" ".ucwords($query->mname):'')." ".ucwords($query->lname);
                        $preparerPos    = $query->jpName;
                    }
                }

                // APPROVER
                $query = DB::table('user_employments');
                $query = $query->select(
                    "user_employments.*", 
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                    "JobPositions.name as jpName", 
                );
                $query = $query->leftjoin('user_personal_informations', "user_employments.userID", '=', 'user_personal_informations.userID');
                $query = $query->leftjoin('JobPositions', "user_employments.jobPositionID", '=', 'JobPositions.jobPositionID');
                $query = $query->where('JobPositions.isHrHead', 1);
                $query = $query->where('user_employments.status', 1);
                $query = $query->first();
                if ($query) {
                    if ($query->jpName) {
                        $approver       = ucwords($query->fname).($query->mname?" ".ucwords($query->mname):'')." ".ucwords($query->lname);
                        $approverPos    = $query->jpName;
                    }
                }

                $items['preparer']      = $preparer;
                $items['preparerPos']   = $preparerPos;
                $items['approver']      = $approver;
                $items['approverPos']   = $approverPos;

                $items['hasButtonPDS'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Print Leave Ledger Card']);
                $items['pages'] = $pages;
                $items['printID'] = md5($this->_printDocument(3, $token_userID));

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


