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

class EmploymentController extends MasterController
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
        $this->module           = 'Employments';
        $this->controller       = 'employments';
        $this->logTitle         = 'Employment';
        $this->table            = 'user_employments';
        $this->tablePrimaryKey  = 'userEmploymentID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'         => 7, 
            'PrintList'     => 57, 
            'View'          => 59, 
            'Audit'         => 60, 
            'Update'        => 61, 
            'New Record'    => 128, 
        ];
        // id => [label, table, field]
        $this->auditFieldValues = [
            'name'      => ['Barangay Name', '', ''], 
            'cityID'    => ['City ID', 'cities', 'name'], 
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
                'name'      => 'City', 
                'connector' => 'is', 
                'variable'  => 'cityID', 
                'table'     => 'cities', 
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

    public function view(string $id)
    {

        // initialize variables
        $this->page = 'View';
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

    public function new(string $id)
    {

        // initialize variables
        $this->page = 'New Record';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

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
                $cities = [];
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
                    'dateAppointed'         => [$this->table, 'where'], 
                    'idNumber'              => [$this->table, 'likeboth'], 
                    'lname'                 => ['user_personal_informations', 'likeboth'], 
                    'officeID'              => ['offices', 'where'], 
                    'jobPositionID'         => ['JobPositions', 'where'], 
                    'userEmploymentTypeID'  => ['user_employment_types', 'where'], 
                    'status'                => [$this->table, 'where'], 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'dateAppointed'         => [$this->table, 'dateAppointed'], 
                    'idNumber'              => [$this->table, 'idNumber'], 
                    'employee'              => ['user_personal_informations', 'lname'], 
                    'office'                => ['offices', 'name'], 
                    'jobPosition'           => ['JobPositions', 'name'], 
                    'salary'                => [$this->table, 'salaryMonthly'], 
                    'userEmploymentType'    => ['user_employment_types', 'name'], 
                    'status'                => [$this->table, 'status'], 
                ];

                // query count
                $query = DB::table($this->table);
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                $query = $query->leftjoin('user_employment_types', "{$this->table}.userEmploymentTypeID", '=', 'user_employment_types.userEmploymentTypeID');
                $query = $query->leftjoin('offices', "{$this->table}.officeID", '=', 'offices.officeID');
                $query = $query->leftjoin('JobPositions', "{$this->table}.jobPositionID", '=', 'JobPositions.jobPositionID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField)?$request->query($cField):'';
                        if ($cField=='status') $value = $request->query($cField);
                        if (!in_array($value, [null, '']) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if (!in_array($value, [null, '']) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
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
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                    "user_employment_types.name as uetName", 
                    "offices.code as oCode", 
                    "offices.name as oName", 
                    "JobPositions.code as jpCode", 
                    "JobPositions.name as jpName", 
                );
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                $query = $query->leftjoin('user_employment_types', "{$this->table}.userEmploymentTypeID", '=', 'user_employment_types.userEmploymentTypeID');
                $query = $query->leftjoin('offices', "{$this->table}.officeID", '=', 'offices.officeID');
                $query = $query->leftjoin('JobPositions', "{$this->table}.jobPositionID", '=', 'JobPositions.jobPositionID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField)?$request->query($cField):'';
                        if ($cField=='status') $value = $request->query($cField);
                        if (!in_array($value, [null, '']) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if (!in_array($value, [null, '']) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
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
                            'userEmploymentID'  => Crypt::encryptString("{$tr->userEmploymentID}"), 
                            'dateAppointed'     => $tr->dateAppointed ? date('M d/y', strtotime($tr->dateAppointed)) : '', 
                            'idNumber'          => $tr->idNumber, 
                            'employee'          => "{$tr->lname}, {$tr->fname} {$tr->mname}", 
                            'office'            => $tr->oCode?"{$tr->oCode}":'', 
                            'jobPosition'       => $tr->jpCode?"{$tr->jpCode}":'', 
                            'salaryBasic'       => number_format($tr->salaryMonthly, 2), 
                            'uetName'           => $tr->uetName?$tr->uetName:'', 
                            'status'            => $tr->status, 
                        ];
                    }
                }
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
                $items['hasButtonPrint']        = $this->_checkAccess($token_userID, $this->moduleActionIDs['PrintList']);
                $items['hasButtonView']         = $this->_checkAccess($token_userID, $this->moduleActionIDs['View']);
                $items['offices']               = DB::table('offices')->orderBy('name', 'asc')->get();
                $items['JobPositions']         = DB::table('JobPositions')->orderBy('name', 'asc')->get();
                $items['user_employment_types'] = DB::table('user_employment_types')->orderBy('name', 'asc')->get();
                $items['statuses']              = [['status'=>1,'name'=>'Active'],['status'=>0,'name'=>'Inactive']];
                $items['records']               = $records;
                $items['filters']               = $filters;

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
                    'dateAppointed'         => [$this->table, 'where'], 
                    'idNumber'              => [$this->table, 'likeboth'], 
                    'lname'                 => ['user_personal_informations', 'likeboth'], 
                    'officeID'              => ['offices', 'where'], 
                    'jobPositionID'         => ['JobPositions', 'where'], 
                    'userEmploymentTypeID'  => ['user_employment_types', 'where'], 
                    'status'                => [$this->table, 'where'], 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'dateAppointed'         => [$this->table, 'dateAppointed'], 
                    'idNumber'              => [$this->table, 'idNumber'], 
                    'employee'              => ['user_personal_informations', 'lname'], 
                    'office'                => ['offices', 'name'], 
                    'jobPosition'           => ['JobPositions', 'name'], 
                    'salary'                => [$this->table, 'salaryMonthly'], 
                    'userEmploymentType'    => ['user_employment_types', 'name'], 
                    'status'                => [$this->table, 'status'], 
                ];

                // query count
                $query = DB::table($this->table);
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                $query = $query->leftjoin('user_employment_types', "{$this->table}.userEmploymentTypeID", '=', 'user_employment_types.userEmploymentTypeID');
                $query = $query->leftjoin('offices', "{$this->table}.officeID", '=', 'offices.officeID');
                $query = $query->leftjoin('JobPositions', "{$this->table}.jobPositionID", '=', 'JobPositions.jobPositionID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField)?$request->query($cField):'';
                        if ($cField=='status') $value = $request->query($cField);
                        if (!in_array($value, [null, '']) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if (!in_array($value, [null, '']) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
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
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                    "user_employment_types.name as uetName", 
                    "offices.code as oCode", 
                    "offices.name as oName", 
                    "JobPositions.code as jpCode", 
                    "JobPositions.name as jpName", 
                );
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                $query = $query->leftjoin('user_employment_types', "{$this->table}.userEmploymentTypeID", '=', 'user_employment_types.userEmploymentTypeID');
                $query = $query->leftjoin('offices', "{$this->table}.officeID", '=', 'offices.officeID');
                $query = $query->leftjoin('JobPositions', "{$this->table}.jobPositionID", '=', 'JobPositions.jobPositionID');
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField)?$request->query($cField):'';
                        if ($cField=='status') $value = $request->query($cField);
                        if (!in_array($value, [null, '']) && $cType[1] == 'where') $query = $query->where("{$cType[0]}.{$cField}", $value);
                        if (!in_array($value, [null, '']) && $cType[1] == 'likeboth') $query = $query->where("{$cType[0]}.{$cField}", 'like', "%{$value}%");
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
                        $records[] = [
                            'dateAppointed'     => $tr->dateAppointed ? date('m/d/Y', strtotime($tr->dateAppointed)) : '', 
                            'idNumber'          => $tr->idNumber, 
                            'employee'          => "{$tr->lname}, {$tr->fname} {$tr->mname}", 
                            'office'            => $tr->oCode?"{$tr->oCode} - {$tr->oName}":'', 
                            'jobPosition'       => $tr->jpCode?"{$tr->jpCode} - {$tr->jpName}":'', 
                            'salaryMonthly'     => number_format($tr->salaryMonthly, 2), 
                            'type'              => $tr->uetName?$tr->uetName:'', 
                            'status'            => $tr->status?'Active':'Inactive', 
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
                $row            = []; 
                $isLatestEmploymentRecord = 0;
                $latestEmploymentRecordID = 0;
                $hasError       = 0;
        
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
                        "user_employment_types.name as uetName", 
                        "offices.code as oCode", 
                        "offices.name as oName", 
                        "JobPositions.code as jpCode", 
                        "JobPositions.name as jpName", 
                    );
                    $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                    $query = $query->leftjoin('user_employment_types', "{$this->table}.userEmploymentTypeID", '=', 'user_employment_types.userEmploymentTypeID');
                    $query = $query->leftjoin('offices', "{$this->table}.officeID", '=', 'offices.officeID');
                    $query = $query->leftjoin('JobPositions', "{$this->table}.jobPositionID", '=', 'JobPositions.jobPositionID');
                    $query = $query->where("{$this->table}.{$this->tablePrimaryKey}", $decrypted_id);
                    $query = $query->first();
                    if ($query) {

                        $causes = [1=>'End of Term','Optional','Mandatory','Death','Resignation'];

                        $row = [
                            'dateAppointed'     => $query->dateAppointed ? date('M d/y', strtotime($query->dateAppointed)) : '', 
                            'dateDismissed'    => $query->dateDismissed ? date('M d/y', strtotime($query->dateDismissed)) : '', 
                            'idNumber'          => $query->idNumber, 
                            'employee'          => "{$query->lname}, {$query->fname} {$query->mname}", 
                            'office'            => "{$query->oCode} - {$query->oName}", 
                            'jobPosition'       => "{$query->jpCode} - {$query->jpName}", 
                            'type'              => $query->uetName, 
                            'salaryMonthly'     => number_format($query->salaryMonthly, 2), 
                            'salaryYearly'      => number_format($query->salaryYearly, 2), 
                            'bankAccountName'   => $query->bankAccountName, 
                            'bankAccountNumber' => $query->bankAccountNumber, 
                            'remarks'           => $query->cause?$causes[$query->cause]:$query->remarks, 
                            'status'            => $query->status, 
                        ];

                        // 
                        $query = DB::table($this->table)->where('userID', $query->userID)->orderBy('dateAppointed', 'desc')->first();
                        if ($query) {
                            if ($query->userEmploymentID == $decrypted_id) $isLatestEmploymentRecord = 1;
                            $latestEmploymentRecordID = Crypt::encryptString("{$query->userEmploymentID}");
                        }
                        
                        /** final variables */
                        $items['hasButtonEdit']             = $this->_checkAccess($token_userID, $this->moduleActionIDs['Update']);
                        $items['hasButtonAudit']            = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
                        $items['isLatestEmploymentRecord']    = $isLatestEmploymentRecord;
                        $items['latestEmploymentRecordID']    = $latestEmploymentRecordID;
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

    public function put_page_edit(Request $request, string $id)
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
                    $query = DB::table($this->table);
                    $query = $query->select(
                        "{$this->table}.*", 
                        "user_personal_informations.lname", 
                        "user_personal_informations.fname", 
                        "user_personal_informations.mname", 
                        "user_employment_types.name as uetName", 
                        "offices.code as oCode", 
                        "offices.name as oName", 
                        "JobPositions.code as jpCode", 
                        "JobPositions.name as jpName", 
                    );
                    $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                    $query = $query->leftjoin('user_employment_types', "{$this->table}.userEmploymentTypeID", '=', 'user_employment_types.userEmploymentTypeID');
                    $query = $query->leftjoin('offices', "{$this->table}.officeID", '=', 'offices.officeID');
                    $query = $query->leftjoin('JobPositions', "{$this->table}.jobPositionID", '=', 'JobPositions.jobPositionID');
                    $query = $query->where("{$this->table}.{$this->tablePrimaryKey}", $decrypted_id);
                    $query = $query->first();
                    if ($query) {
                        $row = [ 
                            'dateAppointed'         => $query->dateAppointed, 
                            'dateAppointedFormat'   => $query->dateAppointed ? date('F d/y', strtotime($query->dateAppointed)) : '', 
                            'idNumber'              => $query->idNumber, 
                            'employee'              => "{$query->lname}, {$query->fname} {$query->mname}", 
                            'office'                => "{$query->oCode} - {$query->oName}", 
                            'jobPosition'           => "{$query->jpCode} - {$query->jpName}", 
                            'type'                  => $query->uetName, 
                            'salaryMonthly'         => $query->salaryMonthly, 
                            'salaryYearly'          => $query->salaryYearly, 
                            'salaryMonthlyFormat'   => number_format($query->salaryMonthly, 2), 
                            'bankAccountName'       => $query->bankAccountName, 
                            'bankAccountNumber'     => $query->bankAccountNumber, 
                            'officeID'              => $query->officeID, 
                            'jobPositionID'         => $query->jobPositionID, 
                            'userEmploymentTypeID'  => $query->userEmploymentTypeID, 
                            'status'                => $query->status, 
                        ];

                        /** final variables */
                        $items['hasButtonEdit']         = $this->_checkAccess($token_userID, $this->moduleActionIDs['Update']);
                        $items['offices']               = DB::table('offices')->orderBy('name', 'asc')->get();
                        $items['JobPositions']         = DB::table('JobPositions')->orderBy('name', 'asc')->get();
                        $items['user_employment_types'] = DB::table('user_employment_types')->orderBy('name', 'asc')->get();
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
    public function put_edit(Request $request, string $id)
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
                $request_fields = ['dateAppointed', 'idNumber', 'officeID', 'jobPositionID', 'userEmploymentTypeID', 'salaryMonthly', 'salaryYearly', 'bankAccountName', 'bankAccountNumber'];
        
                /** variables */
                $decrypted_id       = 0;
                $request_data       = [];
                $hasError           = 0; 
                $requires           = '';
                $required_fields    = [
                    'dateAppointed'         => 'Date Appointed', 
                    'idNumber'              => 'Employee ID', 
                    'officeID'              => 'Office', 
                    'jobPositionID'         => 'Job Position', 
                    'userEmploymentTypeID'  => 'Employment Type', 
                    'salaryMonthly'         => 'Salary Per Month', 
                    'salaryYearly'          => 'Salary Per Annum', 
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
        
                // query
                if (!$hasError) {
                    $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                    if ($query) {
                        $query->update($request_data);
                        // // update audit logs
                        // $logFields = $request_fields;
                        // $this->_auditLog($request_token['data'], $this->moduleActionIDs['Update'], $this->table, $this->tablePrimaryKey, $decrypted_id, $request_data, "Updated {$this->logTitle} Record", $logFields, 1);
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
    
    public function put_page_new(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['New Record'])) {
                /** variables */
                $row        = [];
                $hasError   = 0;
        
                /** check errors */
                // primary key value 
                $decrypted_id = $this->_decryptID($id);
        
                /** query */
                if (!$hasError) {

                    $records = DB::table('user_personal_informations')->orderBy('lname', 'asc')->orderBy('fname', 'asc')->get();
                    
                    $employees = [];
                    if ($records) {
                        foreach ($records as $rec) {

                            $isActive = 0;
                            $selected = 0;
                            $idNumber = 0;
                            
                            $dRow = DB::table('user_employments');
                            $dRow = $dRow->where('userID', $rec->userID);
                            $dRow = $dRow->orderBy('dateAppointed', 'desc');
                            $dRow = $dRow->first();
                            if ($dRow) {
                                $idNumber = $dRow->idNumber;
                                if (!$dRow->dateDismissed) $isActive = 1;
                                if ($id) {
                                    if ($decrypted_id == $dRow->userEmploymentID) $selected = 1;
                                }
                            }

                            if (!$isActive) {
                                $employees[] = [
                                    'userID'    => $rec->userID, 
                                    'name'      => "{$rec->lname}, {$rec->fname} {$rec->mname}", 
                                    'idNumber'  => $idNumber, 
                                    'selected'  => $selected, 
                                ];
                            }
                        }
                    }

                    /** final variables */
                    $items['hasButtonEdit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['New Record']);
                    $items['employees']             = $employees;
                    $items['offices']               = DB::table('offices')->orderBy('name', 'asc')->get();
                    $items['JobPositions']         = DB::table('JobPositions')->orderBy('name', 'asc')->get();
                    $items['user_employment_types'] = DB::table('user_employment_types')->orderBy('name', 'asc')->get();
                    $items['row'] = $row;

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
    public function put_new(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['New Record'])) {
                /** fields */
                $request_fields = ['userID', 'idNumber', 'dateAppointed', 'officeID', 'jobPositionID', 'userEmploymentTypeID', 'salaryMonthly', 'salaryYearly', 'bankAccountName', 'bankAccountNumber'];
        
                /** variables */
                $decrypted_id       = 0;
                $request_data       = [];
                $hasError           = 0; 
                $requires           = '';
                $required_fields    = [
                    'userID'                => 'Employee', 
                    'idNumber'              => 'Employee ID', 
                    'dateAppointed'         => 'Date Appointed', 
                    'userEmploymentTypeID'  => 'Employment Type', 
                    'officeID'              => 'Office', 
                    'jobPositionID'         => 'Job Position', 
                    'salaryMonthly'         => 'Salary Per Month', 
                    'salaryYearly'          => 'Salary Per Annum', 
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
        
                // check if date appointed conflicted
                if (!$hasError) {
                    $hasDuplicate = DB::table('user_employments');
                    $hasDuplicate = $hasDuplicate->where('userID', $request_data['userID']);
                    $hasDuplicate = $hasDuplicate->where('dateAppointed', '<=', $request_data['dateAppointed']);
                    $hasDuplicate = $hasDuplicate->where('dateDismissed', '>=', $request_data['dateAppointed']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'The date appointed conflicts with his/her other records.', 'Opps!');
                    }
                }
        
                // check if mayor and already has one
                if (!$hasError) {

                    $query = DB::table('JobPositions');
                    $query = $query->where('isMayor', 1);
                    $query = $query->where('jobPositionID', $request_data['jobPositionID']);
                    $isMayor = $query->count();

                    if ($isMayor) {
                        $query = DB::table('user_employments');
                        $query = $query->where('status', 1);
                        $query = $query->where('jobPositionID', $request_data['jobPositionID']);
                        $hasMayor = $query->count();

                        if ($hasMayor) {
                            $hasError = 1;
                            $data = $this->response->status(409, 'Cannot assign two Active Mayors.', 'Invalid!');
                        }
                    }
                }
        
                // check if hr heads and already has one
                if (!$hasError) {

                    $query = DB::table('JobPositions');
                    $query = $query->where('isHrHead', 1);
                    $query = $query->where('jobPositionID', $request_data['jobPositionID']);
                    $isHrHead = $query->count();

                    if ($isHrHead) {
                        $query = DB::table('user_employments');
                        $query = $query->where('status', 1);
                        $query = $query->where('jobPositionID', $request_data['jobPositionID']);
                        $hasHrHead = $query->count();

                        if ($hasHrHead) { 
                            $hasError = 1;
                            $data = $this->response->status(409, 'Cannot assign two Active HR Heads.', 'Invalid!');
                        }
                    }
                }

                // query
                if (!$hasError) {


                    $insert_arr = $request_data;
                    $insert_arr['dateDismissed'] = null;
                    $insert_arr['cause'] = 0;
                    $insert_arr['remarks'] = '';
                    $insert_arr['status'] = 1;

                    $pkID = DB::table($this->table)->insertGetId($insert_arr);
                    if ($pkID) {

                        // // insert audit logs 
                        // $logFields = ['name', 'description'];
                        // $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], $this->table, $this->tablePrimaryKey, $pkID, $request_data, "Inserted {$this->logTitle} Record", $logFields, 1);

                        // return encrypted id
                        $id = Crypt::encryptString("{$pkID}");

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

    public function put_dismiss(Request $request, string $id)
    {
        
        $data = $this->response->status(200);

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // // check access
            // if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Rehire'])) {
                /** fields */
                $request_fields = ['dateDismissed', 'type', 'dismissalType', 'remarks'];
        
                /** variables */
                $decrypted_id       = 0;
                $request_data       = [];
                $hasError           = 0; 
                $requires           = '';
                $required_fields    = [
                    'dateDismissed' => 'Date Dismissed', 
                    'type'          => 'Type', 
                ];
        
                /** data */
                if ($request_fields) {
                    foreach ($request_fields as $field) {
                        $request_data[$field] = $_POST[$field];
                    }
                }

                if ($request_data['type']) $required_fields['dismissalType'] = 'Cause';
        
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
                            if (in_array($request_data[$fieldName], ['', null])) {
                                $hasError = 1;
                                $requires .= ($requires ? ", " : "")."$fieldLabel";
                            }
                        }
                    }
                    if ($hasError) $data = $this->response->status(401, $requires, 'Required field(s):');
                }
        
                // query
                if (!$hasError) {

                    $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                    if ($query) {
                        // update
                        $req_fields = [
                            'dateDismissed', 
                            'cause', 
                            'remarks', 
                            'status', 
                        ];
                        $update_arr = [
                            'dateDismissed' => $request_data['dateDismissed'],
                            'cause'         => '', 
                            'remarks'       => '', 
                            'status'        => 0, 
                        ];
                        if ($request_data['type']) {
                            $update_arr['cause']    = $request_data['dismissalType'];
                            $update_arr['remarks']  = '';
                        } else {
                            $update_arr['cause']    = 0;
                            $update_arr['remarks']  = $request_data['remarks'];
                        }
                        $query->update($update_arr);
                    }

                }  
        
            // } else {
            //     $data = $this->response->status(401, 'Access denied.');
            // }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 
    
}


