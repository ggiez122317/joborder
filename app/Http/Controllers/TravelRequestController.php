<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\File;

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

class TravelRequestController extends MasterController
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
        $this->module           = 'Travel Orders';
        $this->controller       = 'travel-requests';
        $this->logTitle         = 'Travel Order';
        $this->table            = 'travel_orders';
        $this->tablePrimaryKey  = 'travelOrderID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'     => 6, 
            'PrintList' => 168, 
            'Show Related Records Only' => 145, 
            'Insert'    => 0, 
            'View'      => 134, 
            'Audit'     => 0, 
            'Update'    => 0, 
            'Delete'    => 0, 
            'Recommend' => 131, 
            'Check'     => 132, 
            'Approve'   => 133, 
            'Print Travel Report'   => 138, 
            'Print Travel Order'    => 137, 
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
                'name'      => 'Date Inserted', 
                'connector' => 'is', 
                'variable'  => 'dateInserted', 
            ], 
            [
                'name'      => 'Code', 
                'connector' => 'has', 
                'variable'  => 'code', 
            ], 
            [
                'name'      => 'Employee', 
                'connector' => 'has', 
                'variable'  => 'employee', 
            ], 
            [
                'name'      => 'Destination', 
                'connector' => 'has', 
                'variable'  => 'destination', 
            ], 
            [
                'name'      => 'Purpose', 
                'connector' => 'has', 
                'variable'  => 'purpose', 
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

    public function print_travel_report(Request $request)
    {

        // initialize variables
        $this->page = 'Print Travel Report'; 
        $this->_setVariables(); 
        $data = $this->data; 
        
        $data['headerImage1'] = $this->_convertImageToBase64('assets/img/logos/lgu.png');
        $data['headerImage2'] = $this->_convertImageToBase64('assets/img/logos/hr_opaque.png');

        $data['qString'] = $request->getQueryString();
        
        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

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

    public function print_travel_order(Request $request, string $id)
    {

        // initialize variables
        $this->page = 'Print Travel Order';
        $this->_setVariables();
        $data = $this->data;

        $data['headerImage1'] = $this->_convertImageToBase64('assets/img/logos/lgu.png');
        $data['headerImage2'] = $this->_convertImageToBase64('assets/img/logos/hr_opaque.png'); 
        
        $data['id'] = $id;
        $data['show'] = $request->input('show');

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

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

                $isRelatedRecords   = $this->_checkAccess($token_userID, $this->moduleActionIDs['Show Related Records Only']);
                $isChecker          = $this->_checkAccess($token_userID, $this->moduleActionIDs['Check']);
                $isApprover         = $this->_checkAccess($token_userID, $this->moduleActionIDs['Approve']);

                $agusanDelSurProvinceID = 3;
                $isMayor = 0;

                $query = DB::table('JobPositions');
                $query = $query->leftjoin('user_employments', "JobPositions.jobPositionID", '=', 'user_employments.jobPositionID');
                $query = $query->where('JobPositions.isMayor', 1);
                $query = $query->where('user_employments.userID', $token_userID);
                $query = $query->where('user_employments.status', 1);
                $query = $query->first();
                if ($query) $isMayor = 1;

                /** paging */
                $filters['limit']       = isset($_GET['limit']) ? trim($_GET['limit']) : 10;
                $filters['page']        = $request->query('page', 1);
                $filters['sortField']   = $request->query('sortField', '');
                $filters['sortBy']      = $request->query('sortBy', '');

                /** conditions */
                $conditions = [
                    'dateInserted'  => 'likeboth', 
                    'code'          => 'likeboth', 
                    'dateFrom'      => 'where', 
                    'dateTo'        => 'where', 
                    'destination'   => 'likeboth', 
                    'purpose'       => 'likeboth', 
                    'status'        => 'where', 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'dateInserted'  => $this->table, 
                    'code'          => $this->table, 
                    'lname'         => 'user_personal_informations', 
                    'destination'   => $this->table, 
                    'status'        => $this->table, 
                ];

                // query count
                $query = DB::table($this->table);
                $query = $query->leftjoin('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
                $query = $query->leftjoin('cities', "{$this->table}.cityID", '=', 'cities.cityID');
                $query = $query->leftJoin("user_personal_informations", "{$this->table}.userID", '=', "user_personal_informations.userID");
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField, '');
                        if ($cType == 'likeboth') $query = $query->where($cField, 'like', "%{$value}%");
                    }
                    if ($isRelatedRecords) {
                        $query->where(function ($subQuery) use ($token_userID, $isChecker, $isApprover, $isMayor, $agusanDelSurProvinceID) { 
                            // recommender
                            $subQuery->orWhere('recommendedBy', $token_userID);
                            // checker 
                            if ($isChecker) {
                                $subQuery->orWhere(function($subQuery2) use ($token_userID) {
                                    $subQuery2->orWhere('checkedBy', $token_userID);
                                    $subQuery2->orWhere(function($subQuery3) {
                                        $subQuery3->where('status', '!=', 0);
                                        $subQuery3->whereNotNull('dateRecommended');
                                    });
                                });
                            }
                            // approver
                            if ($isApprover) {
                                $subQuery->orWhere(function($subQuery2) use ($token_userID, $isMayor, $agusanDelSurProvinceID) {
                                    $subQuery2->orWhere('approvedBy', $token_userID);
                                    if ($isMayor) {
                                        $subQuery2->orWhere(function($subQuery3) {
                                            $subQuery3->whereNotNull('dateChecked');
                                        });
                                    } else {
                                        $subQuery2->orWhere(function($subQuery3) use ($agusanDelSurProvinceID) {
                                            $subQuery3->where("travel_orders.provinceID", $agusanDelSurProvinceID);
                                        });
                                    }
                                });
                            }
                        });
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
                    "provinces.name as pName", 
                    "cities.name as cName", 
                );
                $query = $query->leftjoin('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
                $query = $query->leftjoin('cities', "{$this->table}.cityID", '=', 'cities.cityID');
                $query = $query->leftJoin("user_personal_informations", "{$this->table}.userID", '=', "user_personal_informations.userID");
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField, '');
                        if ($cType == 'likeboth') $query = $query->where($cField, 'like', "%{$value}%");
                        // filters 
                        $filters[$cField] = $value;
                    }
                    if ($isRelatedRecords) {
                        $query->where(function ($subQuery) use ($token_userID, $isChecker, $isApprover, $isMayor, $agusanDelSurProvinceID) { 
                            // recommender
                            $subQuery->orWhere('recommendedBy', $token_userID);
                            // checker 
                            if ($isChecker) {
                                $subQuery->orWhere(function($subQuery2) use ($token_userID) {
                                    $subQuery2->orWhere('checkedBy', $token_userID);
                                    $subQuery2->orWhere(function($subQuery3) {
                                        $subQuery3->where('status', '!=', 0);
                                        $subQuery3->whereNotNull('dateRecommended');
                                    });
                                });
                            }
                            // approver
                            if ($isApprover) {
                                $subQuery->orWhere(function($subQuery2) use ($token_userID, $isMayor, $agusanDelSurProvinceID) {
                                    $subQuery2->orWhere('approvedBy', $token_userID);
                                    if ($isMayor) {
                                        $subQuery2->orWhere(function($subQuery3) {
                                            $subQuery3->whereNotNull('dateChecked');
                                        });
                                    } else {
                                        $subQuery2->orWhere(function($subQuery3) use ($agusanDelSurProvinceID) {
                                            $subQuery3->where("travel_orders.provinceID", $agusanDelSurProvinceID);
                                        });
                                    }
                                });
                            }
                        });
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

                        $destination = $tr->destination;
                        if ($tr->cName) $destination = $destination ? "$destination, $tr->cName" : $tr->cName;
                        if ($tr->pName) $destination = $destination ? "$destination, $tr->pName" : $tr->pName;

                        $hasNotif = 0;
                        // recommender 
                        if ($tr->recommendedBy==$token_userID && $tr->status==0) $hasNotif = 1;
                        // checker 
                        if ($isChecker && $tr->status==1) $hasNotif = 1;
                        // approver
                        if ($isApprover) {
                            if ($isMayor && $tr->status==2) $hasNotif = 1;
                            if (!$isMayor && $tr->status==2 && $agusanDelSurProvinceID==$tr->provinceID) $hasNotif = 1;
                        } 

                        $records[] = [
                            'travelOrderID' => Crypt::encryptString("{$tr->travelOrderID}"), 
                            'dateInserted'  => $tr->dateInserted?date('m/d/Y h:ia', strtotime($tr->dateInserted)):'', 
                            'code'          => $tr->code, 
                            'employee'      => "$tr->lname, $tr->fname $tr->mname", 
                            'destination'   => $destination, 
                            'purpose'       => $tr->purpose, 
                            'status'        => $tr->status, 
                            'hasNotif'      => $hasNotif, 
                        ];
                    }
                }
                
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
                $items['hasButtonPrint']    = $this->_checkAccess($token_userID, $this->moduleActionIDs['PrintList']);
                $items['hasButtonAdd']      = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $items['hasButtonView']     = $this->_checkAccess($token_userID, $this->moduleActionIDs['View']);
                $items['hasButtonPrintReport']  = $this->_checkAccess($token_userID, $this->moduleActionIDs['Print Travel Report']);
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

                $isRelatedRecords   = $this->_checkAccess($token_userID, $this->moduleActionIDs['Show Related Records Only']);
                $isChecker          = $this->_checkAccess($token_userID, $this->moduleActionIDs['Check']);
                $isApprover         = $this->_checkAccess($token_userID, $this->moduleActionIDs['Approve']);

                $agusanDelSurProvinceID = 3;
                $isMayor = 0;

                $query = DB::table('JobPositions');
                $query = $query->leftjoin('user_employments', "JobPositions.jobPositionID", '=', 'user_employments.jobPositionID');
                $query = $query->where('JobPositions.isMayor', 1);
                $query = $query->where('user_employments.userID', $token_userID);
                $query = $query->where('user_employments.status', 1);
                $query = $query->first();
                if ($query) $isMayor = 1;

                /** paging */
                $filters['limit']       = isset($_GET['limit']) ? trim($_GET['limit']) : 10;
                $filters['page']        = $request->query('page', 1);
                $filters['sortField']   = $request->query('sortField', '');
                $filters['sortBy']      = $request->query('sortBy', '');

                /** conditions */
                $conditions = [
                    'dateInserted'  => 'likeboth', 
                    'code'          => 'likeboth', 
                    'dateFrom'      => 'where', 
                    'dateTo'        => 'where', 
                    'destination'   => 'likeboth', 
                    'purpose'       => 'likeboth', 
                    'status'        => 'where', 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'dateInserted'  => $this->table, 
                    'code'          => $this->table, 
                    'lname'         => 'user_personal_informations', 
                    'destination'   => $this->table, 
                    'status'        => $this->table, 
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
                $query = $query->select(
                    "{$this->table}.*", 
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                    "provinces.name as pName", 
                    "cities.name as cName", 
                );
                $query = $query->leftjoin('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
                $query = $query->leftjoin('cities', "{$this->table}.cityID", '=', 'cities.cityID');
                $query = $query->leftJoin("user_personal_informations", "{$this->table}.userID", '=', "user_personal_informations.userID");
                if ($conditions) {
                    foreach ($conditions as $cField => $cType) {
                        $value = $request->query($cField, '');
                        if ($cType == 'likeboth') $query = $query->where($cField, 'like', "%{$value}%");
                        // filters 
                        $filters[$cField] = $value;
                    }
                    if ($isRelatedRecords) {
                        $query->where(function ($subQuery) use ($token_userID, $isChecker, $isApprover, $isMayor, $agusanDelSurProvinceID) { 
                            // recommender
                            $subQuery->orWhere('recommendedBy', $token_userID);
                            // checker 
                            if ($isChecker) {
                                $subQuery->orWhere(function($subQuery2) use ($token_userID) {
                                    $subQuery2->orWhere('checkedBy', $token_userID);
                                    $subQuery2->orWhere(function($subQuery3) {
                                        $subQuery3->where('status', '!=', 0);
                                        $subQuery3->whereNotNull('dateRecommended');
                                    });
                                });
                            }
                            // approver
                            if ($isApprover) {
                                $subQuery->orWhere(function($subQuery2) use ($token_userID, $isMayor, $agusanDelSurProvinceID) {
                                    $subQuery2->orWhere('approvedBy', $token_userID);
                                    if ($isMayor) {
                                        $subQuery2->orWhere(function($subQuery3) {
                                            $subQuery3->whereNotNull('dateChecked');
                                        });
                                    } else {
                                        $subQuery2->orWhere(function($subQuery3) use ($agusanDelSurProvinceID) {
                                            $subQuery3->where("travel_orders.provinceID", $agusanDelSurProvinceID);
                                        });
                                    }
                                });
                            }
                        });
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

                        $destination = $tr->destination;
                        if ($tr->cName) $destination = $destination ? "$destination, $tr->cName" : $tr->cName;
                        if ($tr->pName) $destination = $destination ? "$destination, $tr->pName" : $tr->pName;

                        $records[] = [
                            'dateInserted'  => $tr->dateInserted?date('m/d/Y h:ia', strtotime($tr->dateInserted)):'', 
                            'code'          => $tr->code, 
                            'employee'      => "$tr->lname, $tr->fname $tr->mname", 
                            'destination'   => $destination, 
                            'purpose'       => $tr->purpose, 
                            'status'        => $tr->status, 
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

    public function print_travel_report_page(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];
        $records = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];
            
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Print Travel Report'])) {

                $dates      = [];
                $dateStart  = '';
                $dateEnd    = '';

                $dateFrom = DB::table('travel_orders')->orderBy('dateInserted', 'asc')->first();
                if ($dateFrom) $dateStart = date('Y-m-d', strtotime($dateFrom->dateInserted));
                
                $dateTo = DB::table('travel_orders')->orderBy('dateInserted', 'desc')->first();
                if ($dateTo) $dateEnd = date('Y-m-d', strtotime($dateTo->dateInserted));

                if ($dateStart || $dateEnd) {
                    if (!$dateStart) $dateStart = $dateEnd;
                    if (!$dateEnd) $dateEnd = $dateStart;

                    $dateFormatStart    = date('Y-m', strtotime($dateStart));
                    $dateFormatEnd      = date('Y-m', strtotime($dateEnd));

                    while (true) {
                        $dates[] = [
                            'date'      => $dateFormatStart, 
                            'format'    => date('Y F', strtotime($dateFormatStart.'-01')), 
                        ];
                        if ($dateFormatStart == $dateFormatEnd) break;
                        $dateFormatStart = date('Y-m', strtotime('+1 month', strtotime($dateFormatStart.'-01')));
                    }
                }

                $items['offices'] = DB::table('offices')->orderBy('name', 'asc')->get();
                $items['dates'] = array_reverse($dates);

                $data['items'] = $items;

            } else {
                $data = $this->response->status(401, 'Access denied.');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 
    public function print_travel_report_data(Request $request)
    {

        $officeIDs      = $request->query('officeIDs');
        $dateInserted   = $request->query('dateInserted');

        if ($officeIDs) $officeIDs = explode(',', $officeIDs);

        $data = $this->response->status(200);

        $items = [];
        $records = [];

        $preparer       = "";
        $preparerPos    = "";
        $approver       = "";
        $approverPos    = "";

        $filterDateInserted = '-';
        $filterOffices      = '-';

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];
            
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Print Travel Report'])) {

                /** variables */
                $records = [];
                $filters = [];
                $row_shown_first    = 0;
                $row_shown_last     = 0;

                $isRelatedRecords   = $this->_checkAccess($token_userID, $this->moduleActionIDs['Show Related Records Only']);
                $isChecker          = $this->_checkAccess($token_userID, $this->moduleActionIDs['Check']);
                $isApprover         = $this->_checkAccess($token_userID, $this->moduleActionIDs['Approve']);

                $agusanDelSurProvinceID = 3;
                $isMayor = 0;

                $query = DB::table('JobPositions');
                $query = $query->leftjoin('user_employments', "JobPositions.jobPositionID", '=', 'user_employments.jobPositionID');
                $query = $query->where('JobPositions.isMayor', 1);
                $query = $query->where('user_employments.userID', $token_userID);
                $query = $query->where('user_employments.status', 1);
                $query = $query->first();
                if ($query) $isMayor = 1;

                /** paging */
                $filters['limit']       = isset($_GET['limit']) ? trim($_GET['limit']) : 10;
                $filters['page']        = $request->query('page', 1);
                $filters['sortField']   = $request->query('sortField', '');
                $filters['sortBy']      = $request->query('sortBy', '');

                /** conditions */
                $conditions = [
                    'dateInserted'  => 'likeboth', 
                    'code'          => 'likeboth', 
                    'dateFrom'      => 'where', 
                    'dateTo'        => 'where', 
                    'destination'   => 'likeboth', 
                    'purpose'       => 'likeboth', 
                    'status'        => 'where', 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'dateInserted'  => $this->table, 
                    'code'          => $this->table, 
                    'lname'         => 'user_personal_informations', 
                    'destination'   => $this->table, 
                    'status'        => $this->table, 
                ];

                /** query */
                $query = DB::table($this->table);
                $query = $query->select(
                    "{$this->table}.*", 
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                    "provinces.name as pName", 
                    "cities.name as cName", 
                    "offices.code as oCode", 
                );
                $query = $query->leftjoin('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
                $query = $query->leftjoin('cities', "{$this->table}.cityID", '=', 'cities.cityID');
                $query = $query->leftJoin("user_personal_informations", "{$this->table}.userID", '=', "user_personal_informations.userID");
                $query = $query->leftJoin("user_employments", "{$this->table}.userEmploymentID", '=', "user_employments.userEmploymentID");
                $query = $query->leftJoin("offices", "user_employments.officeID", '=', "offices.officeID");
                $query = $query->where("{$this->table}.status", 3);
                if ($dateInserted) $query = $query->where("{$this->table}.dateInserted", 'like', "%{$dateInserted}%");
                if ($officeIDs) {
                    $query = $query->whereIn("user_employments.officeID", $officeIDs);
                } else {
                    $query = $query->where("user_employments.officeID", 0);
                }
                $temp_records = $query->get();

                if ($temp_records) {
                    foreach ($temp_records as $tr) {

                        $date = date('M d, Y', strtotime($tr->dateFrom));

                        if ($tr->dateTo > $tr->dateFrom) {

                            $m1 = date('M', strtotime($tr->dateFrom));
                            $m2 = date('M', strtotime($tr->dateTo));

                            if ($m1 != $m2) {
                                $date = date('M d', strtotime($tr->dateFrom));
                                $date .= date('-M d', strtotime($tr->dateTo));
                                $date .= date(', Y', strtotime($tr->dateFrom));
                            } else { // same month
                                $date = date('M ', strtotime($tr->dateFrom));
                                $date .= date('d', strtotime($tr->dateFrom))."-".date('d', strtotime($tr->dateTo));
                                $date .= date(', Y', strtotime($tr->dateFrom));
                            }

                        }

                        $destination = $tr->destination;
                        if ($tr->cName) $destination = $destination ? "$destination, $tr->cName" : $tr->cName;
                        if ($tr->pName) $destination = $destination ? "$destination, $tr->pName" : $tr->pName;

                        $records[] = [
                            'travelOrderID' => Crypt::encryptString("{$tr->travelOrderID}"), 
                            'dateInserted'  => $tr->dateInserted?date('m/d/Y h:ia', strtotime($tr->dateInserted)):'', 
                            'dateTravel'    => $date, 
                            'code'          => $tr->code, 
                            'employee'      => "$tr->lname, $tr->fname $tr->mname", 
                            'destination'   => $destination, 
                            'purpose'       => $tr->purpose, 
                            'office'        => $tr->oCode, 
                            'status'        => $tr->status, 
                        ];
                    }
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

                // 
                if ($officeIDs) {
                    $query = DB::table('offices');
                    $query = $query->whereIn('officeID', $officeIDs);
                    $query = $query->get();

                    if ($query) {
                        $filterOffices = '';
                        foreach ($query as $q) {
                            $filterOffices .= ($filterOffices?", $q->code": $q->code);
                        }
                    }
                }

                // 
                if ($dateInserted) $filterDateInserted = date('Y F', strtotime($dateInserted.'-01'));

                $items['preparer']      = $preparer;
                $items['preparerPos']   = $preparerPos;
                $items['approver']      = $approver;
                $items['approverPos']   = $approverPos;

                $items['filterDateInserted']    = $filterDateInserted;
                $items['filterOffices']         = $filterOffices;

                $items['records'] = $records;
                $items['printID'] = md5($this->_printDocument(6, $token_userID));

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
                        'applicant.lname as appLname', 
                        'applicant.fname as appFname', 
                        'applicant.mname as appMname', 
                        'recommender.lname as rLname', 
                        'recommender.fname as rFname', 
                        'recommender.mname as rMname', 
                        'checker.lname as cLname', 
                        'checker.fname as cFname', 
                        'checker.mname as cMname', 
                        'approver.lname as aLname', 
                        'approver.fname as aFname', 
                        'approver.mname as aMname', 
                        'disapprover.lname as dLname', 
                        'disapprover.fname as dFname', 
                        'disapprover.mname as dMname', 
                        "provinces.name as pName", 
                        "cities.name as cName", 
                    ); 
                    $query = $query->leftjoin('user_personal_informations as applicant', "{$this->table}.userID", '=', 'applicant.userID'); 
                    $query = $query->leftjoin('user_personal_informations as recommender', "{$this->table}.recommendedBy", '=', 'recommender.userID'); 
                    $query = $query->leftjoin('user_personal_informations as checker', "{$this->table}.checkedBy", '=', 'checker.userID'); 
                    $query = $query->leftjoin('user_personal_informations as approver', "{$this->table}.approvedBy", '=', 'approver.userID'); 
                    $query = $query->leftjoin('user_personal_informations as disapprover', "{$this->table}.disapprovedBy", '=', 'disapprover.userID'); 
                    $query = $query->leftjoin('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
                    $query = $query->leftjoin('cities', "{$this->table}.cityID", '=', 'cities.cityID');
                    $query = $query->where($this->tablePrimaryKey, $decrypted_id); 
                    $query = $query->first(); 
                    if ($query) {

                        $date = $query->dateFrom ? date('m/d/Y', strtotime($query->dateFrom)) : '';
                        if ($query->dateTo) {
                            if ($query->dateFrom != $query->dateTo) {
                                $date .= $query->dateTo ? " to ".date('m/d/Y', strtotime($query->dateTo)) : '';
                            }
                        }

                        // applicant
                        $applicant = '';
                        if ($query->appLname) $applicant = "{$query->appLname}, {$query->appFname} {$query->appMname}";

                        // recommender
                        $recommender = '';
                        if ($query->rLname) $recommender = "{$query->rLname}, {$query->rFname} {$query->rMname}";

                        // checker
                        $checker = '';
                        if ($query->cLname) $checker = "{$query->cLname}, {$query->cFname} {$query->cMname}";

                        // approver
                        $approver = '';
                        if ($query->aLname) $approver = "{$query->aLname}, {$query->aFname} {$query->aMname}";

                        // disapprover
                        $disapprover = '';
                        if ($query->dLname) $disapprover = "{$query->dLname}, {$query->dFname} {$query->dMname}";

                        // file 
                        $destinationPath = public_path('uploads/travel_orders/');
                        $destinationPath .= md5($decrypted_id);

                        $files = [];
                        if (File::exists($destinationPath)) {
                            $allFiles = File::files($destinationPath);
                    
                            foreach ($allFiles as $file) {
                                $files[] = [
                                    'name'  => $file->getFilename(),
                                    'url'   => asset('uploads/travel_orders/' . md5($decrypted_id) . '/' . $file->getFilename()) . '?t=' . time()
                                ];
                            }
                        }

                        // 
                        $destination = $query->destination?$query->destination:'';
                        if ($query->cName) $destination = $destination ? "$destination, $query->cName" : $query->cName;
                        if ($query->pName) $destination = $destination ? "$destination, $query->pName" : $query->pName;

                        $row = [
                            'code'              => $query->code, 
                            'date'              => $date.(($query->travelWorkingDays+0)?" — $query->travelWorkingDays day(s)":''), 
                            'destination'       => $destination, 
                            'purpose'           => $query->purpose, 
                            'appropriation'     => $query->appropriation, 
                            'remarks'           => $query->remarks, 
                            'dateInserted'      => ($query->dateInserted ? date('m/d/Y h:ia', strtotime($query->dateInserted)) : ''),
                            'applicant'         => $applicant, 
                            'recommender'       => $recommender, 
                            'dateRecommended'   => ($query->dateRecommended ? date('m/d/Y h:ia', strtotime($query->dateRecommended)) : ''),
                            'checker'           => $checker, 
                            'dateChecked'       => ($query->dateChecked ? date('m/d/Y h:ia', strtotime($query->dateChecked)) : ''),
                            'approver'          => $approver, 
                            'dateApproved'      => ($query->dateApproved ? date('m/d/Y h:ia', strtotime($query->dateApproved)) : ''),
                            'disapprover'       => $disapprover, 
                            'dateDisapproved'   => ($query->dateDisapproved ? date('m/d/Y h:ia', strtotime($query->dateDisapproved)) : ''),
                            'comment'           => $query->comment, 
                            'status'            => $query->status, 
                            'files'             => $files, 
                        ];

                        // 
                        $query2 = DB::table('JobPositions');
                        $query2 = $query2->leftjoin('user_employments', "JobPositions.jobPositionID", '=', 'user_employments.jobPositionID');
                        $query2 = $query2->where('JobPositions.isMayor', 1);
                        $query2 = $query2->where('user_employments.userID', $token_userID);
                        $query2 = $query2->where('user_employments.status', 1);
                        $query2 = $query2->first();
                        
                        $isMayor = 0;
                        $agusanDelSurProvinceID = 3;
                        if ($query2) $isMayor = 1;

                        $hasButtonApprove = 0;
                        if ($isMayor) $hasButtonApprove = 1;
                        if (!$isMayor && $agusanDelSurProvinceID==$query->provinceID) $hasButtonApprove = 1;

                        /** final variables */
                        $items['hasButtonRecommend']    = $token_userID!=1 && $query->recommendedBy == $token_userID ? 1 : 0;
                        $items['hasButtonCheck']        = $token_userID!=1 && $this->_checkAccess($token_userID, $this->moduleActionIDs['Check']);
                        $items['hasButtonApprove']      = $token_userID!=1 && $this->_checkAccess($token_userID, $this->moduleActionIDs['Approve']) && $hasButtonApprove;
                        $items['hasButtonAudit']        = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
                        $items['hasButtonPrint']        = $this->_checkAccess($token_userID, $this->moduleActionIDs['Print Travel Order']);
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

    public function recommend(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            
            /** fields */
            $request_fields     = [];
    
            /** variables */
            $decrypted_id       = 0;
            $request_data       = [];
            $hasError           = 0; 
            $requires           = '';
            $required_fields    = [];
    
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

                    $request_data['status'] = 1;
                    $request_data['dateRecommended'] = date('Y-m-d H:i:s');

                    $query->update($request_data);
                    // update audit logs
                    $logFields = $request_fields;
                    $this->_auditLog($request_token['data'], $this->moduleActionIDs['Recommend'], $this->table, $this->tablePrimaryKey, $decrypted_id, $request_data, "Updated {$this->logTitle} Record", $logFields, 1);
                } else {
                    $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                }
            } 
    
            $items['id'] = $id;
            $data['items'] = $items;
            
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    public function check(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            
            /** fields */
            $request_fields     = [];
    
            /** variables */
            $decrypted_id       = 0;
            $request_data       = [];
            $hasError           = 0; 
            $requires           = '';
            $required_fields    = [];
    
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

                $checkerUserEmploymentID = 0;
                if ($token_userID) {
                    $query = DB::table('user_employments')->where('userID', $token_userID)->where('status', 1)->first(); 
                    if ($query) $checkerUserEmploymentID = $query->userEmploymentID;
                }

                $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                if ($query) {

                    $request_data['checkedBy'] = $token_userID;
                    $request_data['status'] = 2;
                    $request_data['checkerUserEmploymentID'] = $checkerUserEmploymentID;
                    $request_data['dateChecked'] = date('Y-m-d H:i:s');

                    $query->update($request_data);
                    // update audit logs
                    $logFields = $request_fields;
                    $this->_auditLog($request_token['data'], $this->moduleActionIDs['Check'], $this->table, $this->tablePrimaryKey, $decrypted_id, $request_data, "Updated {$this->logTitle} Record", $logFields, 1);
                } else {
                    $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                }
            } 
    
            $items['id'] = $id;
            $data['items'] = $items;
            
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    public function approve(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            
            /** fields */
            $request_fields     = [];
    
            /** variables */
            $decrypted_id       = 0;
            $request_data       = [];
            $hasError           = 0; 
            $requires           = '';
            $required_fields    = [];
    
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

                $approverUserEmploymentID = 0;
                if ($token_userID) {
                    $query = DB::table('user_employments')->where('userID', $token_userID)->where('status', 1)->first(); 
                    if ($query) $approverUserEmploymentID = $query->userEmploymentID;
                }

                $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                if ($query) {

                    $request_data['approvedBy'] = $token_userID;
                    $request_data['status'] = 3;
                    $request_data['approverUserEmploymentID'] = $approverUserEmploymentID;
                    $request_data['dateApproved'] = date('Y-m-d H:i:s');

                    $query->update($request_data);
                    // update audit logs
                    $logFields = $request_fields;
                    $this->_auditLog($request_token['data'], $this->moduleActionIDs['Approve'], $this->table, $this->tablePrimaryKey, $decrypted_id, $request_data, "Updated {$this->logTitle} Record", $logFields, 1);
                } else {
                    $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                }
            } 
    
            $items['id'] = $id;
            $data['items'] = $items;
            
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    public function disapprove(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            
            /** fields */
            $request_fields     = ['comment'];
    
            /** variables */
            $decrypted_id       = 0;
            $request_data       = [];
            $hasError           = 0; 
            $requires           = '';
            $required_fields    = [];
    
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

                $disapproverUserEmploymentID = 0;
                if ($token_userID) {
                    $query = DB::table('user_employments')->where('userID', $token_userID)->where('status', 1)->first(); 
                    if ($query) $disapproverUserEmploymentID = $query->userEmploymentID;
                }

                $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                if ($query) {

                    $request_data['disapprovedBy'] = $token_userID;
                    $request_data['status'] = -1;
                    $request_data['disapproverUserEmploymentID'] = $disapproverUserEmploymentID;
                    $request_data['dateDisapproved'] = date('Y-m-d H:i:s');

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

    public function print_travel_order_data(Request $request, string $id)
    { 

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];
            
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Print Travel Order'])) {

                $decrypted_id = $this->_decryptID($id);

                $code                   = "";
                $name                   = "";
                $position               = "";
                $destination            = "";
                $date                   = "";
                $purpose                = "";
                $appropriation          = "";
                $remarks                = "";
                $recommenderNameSign    = "";
                $recommenderName        = "";
                $recommenderPosition    = "";
                $approverNameSign       = "";
                $approverName           = "";
                $approverPosition       = "";
                $dateRecommendedSign    = "";
                $dateRecommended        = "";
                $dateApprovedSign       = "";
                $dateApproved           = "";
                
                $row    = [];

                $query = DB::table('travel_orders');
                $query = $query->select(
                    "travel_orders.*", 
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                    "JobPositions.name as jpName", 
                    "recommender.lname as recommenderLname", 
                    "recommender.fname as recommenderFname", 
                    "recommender.mname as recommenderMname", 
                    "recomPos.name as recomPosName", 
                    "approver.lname as approverLname", 
                    "approver.fname as approverFname", 
                    "approver.mname as approverMname", 
                    "approvePos.name as approvePosName", 
                    "provinces.name as pName", 
                    "cities.name as cName", 
                );
                $query = $query->leftjoin('user_personal_informations', "travel_orders.userID", '=', 'user_personal_informations.userID'); 
                $query = $query->leftjoin('user_employments', "travel_orders.userEmploymentID", '=', 'user_employments.userEmploymentID'); 
                $query = $query->leftjoin('JobPositions', "user_employments.jobPositionID", '=', 'JobPositions.jobPositionID'); 
                $query = $query->leftjoin('user_employments as recomEmp', "travel_orders.recommenderUserEmploymentID", '=', 'recomEmp.userEmploymentID'); 
                $query = $query->leftjoin('user_personal_informations as recommender', "recomEmp.userID", '=', 'recommender.userID'); 
                $query = $query->leftjoin('JobPositions as recomPos', "recomEmp.jobPositionID", '=', 'recomPos.jobPositionID'); 
                $query = $query->leftjoin('user_employments as approveEmp', "travel_orders.approverUserEmploymentID", '=', 'approveEmp.userEmploymentID'); 
                $query = $query->leftjoin('user_personal_informations as approver', "approveEmp.userID", '=', 'approver.userID'); 
                $query = $query->leftjoin('JobPositions as approvePos', "approveEmp.jobPositionID", '=', 'approvePos.jobPositionID'); 
                $query = $query->leftjoin('provinces', "travel_orders.provinceID", '=', 'provinces.provinceID');
                $query = $query->leftjoin('cities', "travel_orders.cityID", '=', 'cities.cityID');
                $query = $query->where("travel_orders.travelOrderID", $decrypted_id);
                $query = $query->first();

                if ($query) {

                    // signatures
                    $row['signatureRecommender']    = (File::exists(public_path('uploads/signatures/').md5($query->recommendedBy).'.png') && $query->dateRecommended) ? $this->_convertImageToBase64('uploads/signatures/'.md5($query->recommendedBy).'.png') : '';
                    $row['signatureApprover']       = (File::exists(public_path('uploads/signatures/').md5($query->approvedBy).'.png')) ? $this->_convertImageToBase64('uploads/signatures/'.md5($query->approvedBy).'.png') : '';

                    $date = date('M d, Y', strtotime($query->dateFrom));

                    if ($query->dateTo > $query->dateFrom) {

                        $m1 = date('M', strtotime($query->dateFrom));
                        $m2 = date('M', strtotime($query->dateTo));

                        if ($m1 != $m2) {
                            $date = date('M d', strtotime($query->dateFrom));
                            $date .= date('-M d', strtotime($query->dateTo));
                            $date .= date(', Y', strtotime($query->dateFrom));
                        } else { // same month
                            $date = date('M ', strtotime($query->dateFrom));
                            $date .= date('d', strtotime($query->dateFrom))."-".date('d', strtotime($query->dateTo));
                            $date .= date(', Y', strtotime($query->dateFrom));
                        }

                    }

                    $destination = $query->destination?$query->destination:'';
                    if ($query->cName) $destination = $destination ? "$destination, $query->cName" : $query->cName;
                    if ($query->pName) $destination = $destination ? "$destination, $query->pName" : $query->pName;

                    $code                   = $query->code;
                    $name                   = strtoupper($query->fname)." ".strtoupper($query->mname)." ".strtoupper($query->lname);
                    $position               = $query->jpName;
                    $destination            = $destination;
                    $purpose                = $query->purpose;
                    $appropriation          = $query->appropriation;
                    $remarks                = $query->remarks;
                    $recommenderNameSign    = strtoupper($query->recommenderLname)." ".strtoupper($query->recommenderFname)." ".strtoupper($query->recommenderMname);
                    $recommenderName        = strtoupper($query->recommenderFname)." ".strtoupper($query->recommenderMname)." ".strtoupper($query->recommenderLname);
                    $recommenderPosition    = $query->recomPosName;
                    $approverNameSign       = strtoupper($query->approverLname)." ".strtoupper($query->approverFname)." ".strtoupper($query->approverMname);
                    $approverName           = strtoupper($query->approverFname)." ".strtoupper($query->approverMname)." ".strtoupper($query->approverLname);
                    $approverPosition       = $query->approvePosName;

                    $dateRecommendedSign    = $query->dateRecommended ? date('Y.m.d H:i:s', strtotime($query->dateRecommended)) : '';
                    $dateRecommended        = $query->dateRecommended ? date('M d, Y h:ia', strtotime($query->dateRecommended)) : '';
                    $dateApprovedSign       = $query->dateApproved ? date('Y.m.d H:i:s', strtotime($query->dateApproved)) : '';
                    $dateApproved           = $query->dateApproved ? date('M d, Y h:ia', strtotime($query->dateApproved)) : '';

                }

                
                $row['code']                = $code;
                $row['name']                = $name;
                $row['position']            = $position;
                $row['destination']         = $destination;
                $row['date']                = $date;
                $row['purpose']             = $purpose;
                $row['appropriation']       = $appropriation;
                $row['remarks']             = $remarks;
                $row['recommenderNameSign'] = $recommenderNameSign;
                $row['recommenderName']     = $recommenderName;
                $row['recommenderPosition'] = $recommenderPosition;
                $row['approverNameSign']    = $approverNameSign;
                $row['approverName']        = $approverName;
                $row['approverPosition']    = $approverPosition;
                $row['dateRecommendedSign'] = $dateRecommendedSign;
                $row['dateRecommended']     = $dateRecommended;
                $row['dateApprovedSign']    = $dateApprovedSign;
                $row['dateApproved']        = $dateApproved;


                $items['hasButtonTravelOrder'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Print Travel Order']);
                $items['printID'] = md5($this->_printDocument(5, $token_userID));
                $items['row'] = $row;

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


