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

class LeaveRequestController extends MasterController
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
        $this->module           = 'Leave Applications';
        $this->controller       = 'leave-requests';
        $this->logTitle         = 'Leave Application';
        $this->table            = 'leave_applications';
        $this->tablePrimaryKey  = 'leaveApplicationID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'     => 5, 
            'PrintList' => 169, 
            'Show Related Records Only' => 144, 
            'Insert'    => 0, 
            'View'      => 143, 
            'Audit'     => 0, 
            'Update'    => 0, 
            'Delete'    => 0, 
            'Recommend' => 139, 
            'Check'     => 140, 
            'Approve'   => 141, 
            'Print Leave Application'   => 142, 
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

    public function print_leave_application(Request $request, string $id)
    {

        // initialize variables
        $this->page = 'Print Leave Application';
        $this->_setVariables();
        $data = $this->data;

        $data['headerImage1'] = $this->_convertImageToBase64('assets/img/logos/lgu.png');
        $data['headerImage2'] = $this->_convertImageToBase64('assets/img/logos/hr.png');
        $data['headerImage3'] = $this->_convertImageToBase64('assets/img/logos/hr_opaque.png'); 
        $data['imageCheck'] = $this->_convertImageToBase64('assets/img/check.png'); 

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
                
                /** paging */
                $filters['limit']       = isset($_GET['limit']) ? trim($_GET['limit']) : 10;
                $filters['page']        = $request->query('page', 1);
                $filters['sortField']   = $request->query('sortField', '');
                $filters['sortBy']      = $request->query('sortBy', '');

                /** conditions */
                $conditions = [
                    'dateInserted'      => [$this->table, 'where'], 
                    'leaveTypeID'       => [$this->table, 'where'], 
                    'leaveWorkingDays'  => [$this->table, 'where'], 
                    'status'            => [$this->table, 'where'], 
                ];
        
                /** sort tables */
                $sort_tables = [
                    'dateInserted'      => [$this->table, 'dateInserted'], 
                    'leaveTypeID'       => ['leave_types', 'name'], 
                    'leaveWorkingDays'  => [$this->table, 'leaveWorkingDays'], 
                    'status'            => [$this->table, 'status'], 
                ];

                // query count
                $query = DB::table($this->table);
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                $query = $query->leftjoin('leave_types', "{$this->table}.leaveTypeID", '=', 'leave_types.leaveTypeID');
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
                    if ($isRelatedRecords) {
                        $query->where(function ($subQuery) use ($token_userID, $isChecker, $isApprover) { 
                            // recommender
                            $subQuery->orWhere(function($subQuery2) use ($token_userID) {
                                $subQuery2->orWhere(function($subQuery3) use ($token_userID) {
                                    $subQuery3->where('leave_types.flow', 1); 
                                    $subQuery3->where('leave_applications.recommendedBy', $token_userID);
                                });
                                $subQuery2->orWhere(function($subQuery3) use ($token_userID) {
                                    $subQuery3->where('leave_types.flow', 2); 
                                    $subQuery3->where('leave_applications.recommendedBy', $token_userID);
                                    $subQuery3->where('leave_applications.status', '!=', 0);
                                });
                            });

                            // checker 
                            if ($isChecker) {
                                $subQuery->orWhere(function($subQuery2) use ($token_userID) {
                                    $subQuery2->orWhere('checkedBy', $token_userID);
                                    $subQuery2->orWhere(function($subQuery3) {
                                        $subQuery3->where('leave_types.flow', 1); 
                                        $subQuery3->where('status', '!=', 0);
                                    });
                                    $subQuery2->orWhere(function($subQuery3) {
                                        $subQuery3->where('leave_types.flow', 2); 
                                    });
                                });
                            }
                            // approver
                            if ($isApprover) {
                                $subQuery->orWhere(function($subQuery2) use ($token_userID) {
                                    $subQuery2->orWhere('approvedBy', $token_userID);
                                    $subQuery2->orWhere(function($subQuery3) use ($token_userID) {
                                        $subQuery3->whereNotIn('status', [-1,0,1,2]);
                                    });
                                    $subQuery2->orWhere(function($subQuery3) use ($token_userID) {
                                        $subQuery3->where('disapprovedBy', $token_userID);
                                        $subQuery3->where('status', -1);
                                    });
                                });
                            }
                        });
                    }
                    if ($filters['sortField']) $query = $query->orderBy($sort_tables[$filters['sortField']][0].".".$sort_tables[$filters['sortField']][1], $filters['sortBy']);
                    $query = $query->orderByRaw("FIELD({$this->table}.status, 0, 1, 2, 3, -2, 4, -1)");
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
                    "leave_types.name as ltName", 
                    "leave_types.flow", 
                );
                $query = $query->leftjoin('user_personal_informations', "{$this->table}.userID", '=', 'user_personal_informations.userID');
                $query = $query->leftjoin('leave_types', "{$this->table}.leaveTypeID", '=', 'leave_types.leaveTypeID');
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
                    if ($isRelatedRecords) {
                        $query->where(function ($subQuery) use ($token_userID, $isChecker, $isApprover) { 
                            // recommender
                            $subQuery->orWhere(function($subQuery2) use ($token_userID) {
                                $subQuery2->orWhere(function($subQuery3) use ($token_userID) {
                                    $subQuery3->where('leave_types.flow', 1); 
                                    $subQuery3->where('leave_applications.recommendedBy', $token_userID);
                                });
                                $subQuery2->orWhere(function($subQuery3) use ($token_userID) {
                                    $subQuery3->where('leave_types.flow', 2); 
                                    $subQuery3->where('leave_applications.recommendedBy', $token_userID);
                                    $subQuery3->where('leave_applications.status', '!=', 0);
                                });
                            });

                            // checker 
                            if ($isChecker) {
                                $subQuery->orWhere(function($subQuery2) use ($token_userID) {
                                    $subQuery2->orWhere('checkedBy', $token_userID);
                                    $subQuery2->orWhere(function($subQuery3) {
                                        $subQuery3->where('leave_types.flow', 1); 
                                        $subQuery3->where('status', '!=', 0);
                                    });
                                    $subQuery2->orWhere(function($subQuery3) {
                                        $subQuery3->where('leave_types.flow', 2); 
                                    });
                                });
                            }
                            // approver
                            if ($isApprover) {
                                $subQuery->orWhere(function($subQuery2) use ($token_userID) {
                                    // $subQuery2->orWhere('approvedBy', $token_userID);
                                    $subQuery2->orWhere(function($subQuery3) use ($token_userID) {
                                        $subQuery3->whereNotIn('status', [-1,0,1,2]);
                                    });
                                    // $subQuery2->orWhere(function($subQuery3) use ($token_userID) {
                                    //     $subQuery3->where('disapprovedBy', $token_userID);
                                    //     $subQuery3->where('status', -1);
                                    // });
                                });
                            }
                        });
                    }
                    if ($filters['sortField']) $query = $query->orderBy($sort_tables[$filters['sortField']][0].".".$sort_tables[$filters['sortField']][1], $filters['sortBy']);
                    $query = $query->orderByRaw("FIELD({$this->table}.status, 0, 1, 2, 3, -2, 4, -1)");
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
                        $employee .= $tr->mname ? ", {$tr->mname}" : "";

                        $hasNotif = 0;
                        // recommender 
                        if ($tr->flow==1 && $tr->recommendedBy==$token_userID && $tr->status==0) $hasNotif = 1;
                        if ($tr->flow==2 && $tr->recommendedBy==$token_userID && $tr->status==1) $hasNotif = 1;
                        // checker 
                        if ($isChecker) {
                            if ($tr->flow==1 && $tr->status==2) $hasNotif = 1;
                            if ($tr->flow==2 && $tr->status==0) $hasNotif = 1;
                        }
                        // approver
                        if ($isApprover && $tr->status==3) $hasNotif = 1;

                        $records[] = [
                            'leaveApplicationID'    => Crypt::encryptString("{$tr->leaveApplicationID}"), 
                            'dateInserted'          => $tr->dateFiled ? date('m/d/Y h:i A', strtotime($tr->dateFiled)) : '', 
                            'employee'              => $employee, 
                            'ltName'                => $tr->ltName, 
                            'leaveWorkingDays'      => $tr->leaveWorkingDays, 
                            'status'                => $tr->status, 
                            'hasNotif'              => $hasNotif, 
                        ];
                    }
                }
                
                $filters['row_shown_first'] = $row_shown_first;
                $filters['row_shown_last']  = $row_shown_last;

                /** final variables */
                $items['hasButtonAdd']    = $this->_checkAccess($token_userID, $this->moduleActionIDs['PrintList']);
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
                    $query = $query->orderByRaw("FIELD({$this->table}.status, 0, 1, 2, 3, -2, 4, -1)");
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
                        'leave_types.name as ltName', 
                        'leave_types.nameExt as ltNameExt', 
                        'leave_types.flow', 
                        'leave_cases.name as lcName', 
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
                    ); 
                    $query = $query->leftjoin('leave_types', "{$this->table}.leaveTypeID", '=', 'leave_types.leaveTypeID'); 
                    $query = $query->leftjoin('leave_cases', "{$this->table}.leaveCaseID", '=', 'leave_cases.leaveCaseID'); 
                    $query = $query->leftjoin('user_personal_informations as applicant', "{$this->table}.userID", '=', 'applicant.userID'); 
                    $query = $query->leftjoin('user_personal_informations as recommender', "{$this->table}.recommendedBy", '=', 'recommender.userID'); 
                    $query = $query->leftjoin('user_personal_informations as checker', "{$this->table}.checkedBy", '=', 'checker.userID'); 
                    $query = $query->leftjoin('user_personal_informations as approver', "{$this->table}.approvedBy", '=', 'approver.userID'); 
                    $query = $query->leftjoin('user_personal_informations as disapprover', "{$this->table}.disapprovedBy", '=', 'disapprover.userID'); 
                    $query = $query->where($this->tablePrimaryKey, $decrypted_id); 
                    $query = $query->first(); 
                    if ($query) {

                        $dateWorked = '';
                        if ($query->dateServiceFrom) {
                            $dateWorked = date('m/d/Y', strtotime($query->dateServiceFrom));
                            if ($query->dateServiceTo) {
                                if ($query->dateServiceFrom != $query->dateServiceTo) {
                                    $dateWorked .= $query->dateServiceTo ? " - ".date('m/d/Y', strtotime($query->dateServiceTo)) : '';
                                }
                            }
                        }

                        $date = '';
                        if ($query->dateFrom) {
                            $date = date('m/d/Y', strtotime($query->dateFrom));
                            if ($query->dateTo) {
                                if ($query->dateFrom != $query->dateTo) {
                                    $date .= $query->dateTo ? " - ".date('m/d/Y', strtotime($query->dateTo)) : '';
                                }
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
                        $destinationPath = public_path('uploads/leave_applications/');
                        $destinationPath .= md5($decrypted_id);

                        $files = [];
                        if (File::exists($destinationPath)) {
                            $allFiles = File::files($destinationPath);
                    
                            foreach ($allFiles as $file) {
                                $files[] = [
                                    'name'  => $file->getFilename(),
                                    'url'   => asset('uploads/leave_applications/' . md5($decrypted_id) . '/' . $file->getFilename()) . '?t=' . time()
                                ];
                            }
                        }

                        $approvalType   = $query->approvalType;
                        $approvalDetail = $query->approvalDetail;

                        if (in_array($query->leaveTypeID, [1,2,3])) {
                            $approvalType   = 1;
                            $approvalDetail = $query->leaveWorkingDays;
                        }
                        if (in_array($query->leaveTypeID, [15])) {
                            $approvalType   = 3;
                            $approvalDetail = $query->leaveWorkingDays;
                        }

                        $creditsVacation    = number_format($query->creditsVacationEarned, 3)." - ".number_format($query->creditsVacationLess, 3)." = ".number_format($query->creditsVacationBalance, 3);
                        $creditsSick        = number_format($query->creditsSickEarned, 3)." - ".number_format($query->creditsSickLess, 3)." = ".number_format($query->creditsSickBalance, 3);

                        if (in_array($query->status, [1,3])) {

                            $creditsVacationEarned      = 0; 
                            $creditsVacationLess        = 0; 
                            $creditsSickEarned          = 0; 
                            $creditsSickLess            = 0; 

                            $leaveLess = $query->leaveDays;
                            if ($query->leaveHours) {
                                $query2 = DB::table('leave_credit_fractions');
                                $query2 = $query2->where('type', 1);
                                $query2 = $query2->where('variable', $query->leaveHours);
                                $query2 = $query2->first();
                                if ($query2) {
                                    $leaveLess += $query2->value;
                                }
                            }
                            if ($query->leaveMinutes) {
                                $query2 = DB::table('leave_credit_fractions');
                                $query2 = $query2->where('type', 2);
                                $query2 = $query2->where('variable', $query->leaveMinutes);
                                $query2 = $query2->first();
                                if ($query2) {
                                    $leaveLess += $query2->value;
                                }
                            }

                            if (in_array($query->leaveTypeID, [1,2])) $creditsVacationLess = $leaveLess;
                            if (in_array($query->leaveTypeID, [3])) $creditsSickLess = $leaveLess; 
                            if (in_array($query->leaveTypeID, [15])) {
                                $creditsVacationLess    = $query->creditsToMonetizeVL;
                                $creditsSickLess        = $query->creditsToMonetizeSL;
                            } 

                            // get latest earnings 
                            if ($query->userID) { 
                                $query2 = DB::table('user_leave_credits'); 
                                $query2 = $query2->where('userID', $query->userID); 
                                $query2 = $query2->first(); 
                                if ($query2) { 
                                    $creditsVacationEarned  = $query2->creditsVacation; 
                                    $creditsSickEarned      = $query2->creditsSick; 
                                } 
                            } 

                            $creditsVacationBalance = $creditsVacationEarned-$creditsVacationLess;
                            $creditsSickBalance     = $creditsSickEarned-$creditsSickLess;

                            $creditsVacation    = number_format($creditsVacationEarned, 3)." - ".number_format($creditsVacationLess, 3)." = ".number_format($creditsVacationBalance, 3);
                            $creditsSick        = number_format($creditsSickEarned, 3)." - ".number_format($creditsSickLess, 3)." = ".number_format($creditsSickBalance, 3);
                        }

                        $leaveTypeDetail = $query->lcName.($query->leaveCaseDetail ? ": $query->leaveCaseDetail" : '');
                        if ($query->leaveTypeID == 11) $leaveTypeDetail = $query->leaveCaseDetail;

                        $row = [
                            'leaveTypeID'               => $query->leaveTypeID, 
                            'leaveType'                 => $query->ltName, 
                            'leaveTypeDetail'           => $leaveTypeDetail, 
                            'dateWorked'                => $dateWorked, 
                            'date'                      => $date, 
                            'leaveWorkingDays'          => $query->leaveWorkingDays, 
                            'amount'                    => number_format($query->amount, 2, '.', ','), 
                            'commutation'               => $query->commutation ? 'Requested' : 'Not Requested', 
                            'creditsVacation'           => $creditsVacation, 
                            'creditsSick'               => $creditsSick, 
                            'dateInserted'              => ($query->dateFiled ? date('m/d/Y h:ia', strtotime($query->dateFiled)) : ''),
                            'applicant'                 => $applicant, 
                            'recommender'               => $recommender, 
                            'checker'                   => $checker, 
                            'approver'                  => $approver, 
                            'disapprover'               => $disapprover, 
                            'comment'                   => $query->disapproveRemarks, 
                            'approvalType'              => $approvalType, 
                            'approvalTypeDetail'        => $approvalDetail, 
                            'files'                     => $files, 
                            'status'                    => $query->status, 
                        ];

                        // 
                        $hasButtonRecommend = 0;
                        if ($query->recommendedBy == $token_userID) {
                            if (
                                $query->flow == 1 && 
                                $query->status == 0
                            ) { $hasButtonRecommend = 1; }
                            if (
                                $query->flow == 2 && 
                                $query->status == 1
                            ) { $hasButtonRecommend = 1; }
                        }

                        // 
                        $hasButtonCheck = 0;
                        if (
                            $query->flow == 1 && 
                            $query->status == 2
                        ) { $hasButtonCheck = 1; }
                        if (
                            $query->flow == 2 && 
                            $query->status == 0
                        ) { $hasButtonCheck = 1; }

                        // 
                        $hasButtonApprove = 0;
                        if ( $query->status == 3 ) { $hasButtonApprove = 1; }
                        
                        $hasButtonPrint = 0;
                        if ( $query->dateChecked && $query->status > 0 ) { $hasButtonPrint = 1; }
                        
                        /** final variables */
                        $items['hasButtonRecommend']    = $token_userID!=1 && $hasButtonRecommend;
                        $items['hasButtonCheck']        = $token_userID!=1 && $this->_checkAccess($token_userID, $this->moduleActionIDs['Check']) && $hasButtonCheck;
                        $items['hasButtonApprove']      = $token_userID!=1 && $this->_checkAccess($token_userID, $this->moduleActionIDs['Approve']) && $hasButtonApprove;
                        $items['hasButtonAudit']        = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
                        $items['hasButtonPrint']        = $this->_checkAccess($token_userID, $this->moduleActionIDs['Print Leave Application']) && $hasButtonPrint;
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

                $isPending = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id)->where('status', 0)->count();

                $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                if ($query) {

                    $request_data['dateRecommended'] = date('Y-m-d H:i:s');
                    $request_data['status'] = $isPending ? 2 : 3;
                    
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

    private function _recursive_getDays($dateFrom, $dateTo, $dates='')
    {

        if (!$dateFrom || !$dateTo || (strtotime($dateFrom) > strtotime($dateTo))) return $dates;
        $dayType = date('N', strtotime($dateFrom));
        $day = date('j', strtotime($dateFrom));
        if (in_array($dayType, [1,2,3,4,5])) $dates .= ($dates?",$day":$day);
        $dateFrom = date('Y-m-d', strtotime($dateFrom . ' +1 day'));
        return $this->_recursive_getDays($dateFrom, $dateTo, $dates);

    }
    
    public function check_page(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            $decrypted_id       = 0;
            $hasError           = 0; 
            $requires           = '';

            // primary key value 
            $decrypted_id = $this->_decryptID($id);
            if (!$decrypted_id) {
                $hasError = 1;
                $data = $this->response->status(400);
            }
            
            if (!$hasError) {

                $leaveDays      = 0;
                $leaveHours     = 0;
                $leaveMinutes   = 0;

                $creditsStatusAsOfMonth     = date('F');
                $creditsVacationEarned      = 0;
                $creditsVacationLess        = 0;
                $creditsSickEarned          = 0;
                $creditsSickLess            = 0;
                $datesInclusive             = '';
                $period                     = '';
                $particulars                = '';
                $particularsPeriod          = '';
                $vacationWithPay            = 0;
                $vacationWithoutPay         = 0;
                $sickWithPay                = 0;
                $sickWithoutPay             = 0;
                $remarks                    = '';

                $dateFrom       = '';
                $dateTo         = '';
                $leaveDays      = 0;
                $leaveHours     = 0;
                $leaveMinutes   = 0;
                $editableApplicationDate    = 0;

                $query = DB::table('leave_applications');
                $query = $query->select(
                    "leave_applications.*", 
                    "leave_types.code as ltCode", 
                    "leave_types.editableApplicationDate", 
                );
                $query = $query->leftjoin('leave_types', "leave_applications.leaveTypeID", '=', 'leave_types.leaveTypeID');
                $query = $query->where('leave_applications.leaveApplicationID', $decrypted_id);
                $query = $query->first();

                $userID = 0;
                $leaveTypeID = 0;
                if ($query) {
                    $userID         = $query->userID;
                    $leaveTypeID    = $query->leaveTypeID;
                    $dateFrom       = $query->dateFrom;
                    $dateTo         = $query->dateTo;
                    $leaveDays      = $query->leaveDays;
                    $leaveHours     = $query->leaveHours;
                    $leaveMinutes   = $query->leaveMinutes;
                    $editableApplicationDate = $query->editableApplicationDate;

                    // period
                    if ($query->dateFrom) {
                        
                        if ($query->dateFrom != $query->dateTo) {
                            $yearFrom   = date('y', strtotime($query->dateFrom));
                            $yearTo     = date('y', strtotime($query->dateTo));
                            $monthFrom  = date('m', strtotime($query->dateFrom));
                            $monthTo    = date('m', strtotime($query->dateTo));

                            if ($yearFrom == $yearTo) {
                                if ($monthFrom == $monthTo) {
                                    $dates = $this->_recursive_getDays(date('Y-m-d', strtotime($query->dateFrom)), date('Y-m-d', strtotime($query->dateTo)));
                                    $datesInclusive = date('M', strtotime($query->dateFrom))." $dates, ".date('Y', strtotime($query->dateFrom));
                                    $period = date('n', strtotime($query->dateFrom))."/$dates/".date('y', strtotime($query->dateFrom));
                                } else {
                                    $datesInclusive = date('M j', strtotime($query->dateFrom))."-".date('M j', strtotime($query->dateTo)).", ".date('Y', strtotime($query->dateFrom));
                                    $period = date('n/j/y', strtotime($query->dateFrom))."-".date('n/j/y', strtotime($query->dateTo));
                                }
                            } else {
                                $datesInclusive = date('M j, Y', strtotime($query->dateFrom))."-".date('M j, Y', strtotime($query->dateTo));
                                $period = date('n/j/y', strtotime($query->dateFrom))."-".date('n/j/y', strtotime($query->dateTo));
                            }
                        } else {
                            $datesInclusive = date('M j, Y', strtotime($query->dateFrom));
                            $period = date('n/j/y', strtotime($query->dateFrom));
                        }

                    }

                    // particularsPeriod
                    if ($leaveTypeID==14 && $query->dateServiceFrom) {

                        if ($query->dateServiceFrom != $query->dateServiceTo) {
                            $yearFrom2   = date('y', strtotime($query->dateServiceFrom));
                            $yearTo2     = date('y', strtotime($query->dateServiceTo));
                            $monthFrom2  = date('m', strtotime($query->dateServiceFrom));
                            $monthTo2    = date('m', strtotime($query->dateServiceTo));
    
                            if ($yearFrom2 == $yearTo2) {
                                if ($monthFrom2 == $monthTo2) {
                                    $dates = $this->_recursive_getDays(date('Y-m-d', strtotime($query->dateServiceFrom)), date('Y-m-d', strtotime($query->dateServiceTo)));
                                    $particularsPeriod = date('n', strtotime($query->dateServiceFrom))."/$dates/".date('y', strtotime($query->dateServiceFrom));
                                } else {
                                    $particularsPeriod = date('n/j/y', strtotime($query->dateServiceFrom))."-".date('n/j/y', strtotime($query->dateServiceTo));
                                }
                            } else {
                                $particularsPeriod = date('n/j/y', strtotime($query->dateServiceFrom))."-".date('n/j/y', strtotime($query->dateServiceTo));
                            }
                        } else {
                            $particularsPeriod = date('n/j/y', strtotime($query->dateServiceFrom));
                        }

                    }

                    $particulars = ($query->ltCode ? "$query->ltCode ($query->leaveDays-$query->leaveHours-$query->leaveMinutes)" : "($query->leaveDays-$query->leaveHours-$query->leaveMinutes)");

                    // cto 
                    if ($query->leaveTypeID == 14) $particulars .= ' in lieu of '.$particularsPeriod;

                    $leaveLess = $query->leaveDays;
                    if ($query->leaveHours) {
                        $query2 = DB::table('leave_credit_fractions');
                        $query2 = $query2->where('type', 1);
                        $query2 = $query2->where('variable', $query->leaveHours);
                        $query2 = $query2->first();
                        if ($query2) {
                            $leaveLess += $query2->value;
                        }
                    }
                    if ($query->leaveMinutes) {
                        $query2 = DB::table('leave_credit_fractions');
                        $query2 = $query2->where('type', 2);
                        $query2 = $query2->where('variable', $query->leaveMinutes);
                        $query2 = $query2->first();
                        if ($query2) {
                            $leaveLess += $query2->value;
                        }
                    }

                    // Vacation and Mandatory FL 
                    if (in_array($query->leaveTypeID, [1,2])) $creditsVacationLess = $leaveLess; 
                    // Sick 
                    if (in_array($query->leaveTypeID, [3])) $creditsSickLess = $leaveLess; 
                    // Monetization 
                    if (in_array($query->leaveTypeID, [15])) {
                        $creditsVacationLess    = $query->creditsToMonetizeVL; 
                        $creditsSickLess        = $query->creditsToMonetizeSL; 
                    }

                }

                // get latest earnings 
                if ($userID) {
                    $query = DB::table('user_leave_credits');
                    $query = $query->where('userID', $userID);
                    $query = $query->first();
                    if ($query) {
                        $creditsVacationEarned  = $query->creditsVacation;
                        $creditsSickEarned      = $query->creditsSick;
                    }
                }

                $csl = $creditsSickLess;

                // sick 
                $csl_sick_less = $csl;
                if ($csl > $creditsSickEarned) $csl_sick_less = $creditsSickEarned;
                $csl -= $csl_sick_less;
                $creditsSickBalance = $creditsSickEarned - $csl_sick_less;

                // vacation
                $csl_vacation_less = $csl;
                if (!in_array($leaveTypeID, [3])) $csl_vacation_less = $creditsVacationLess;
                if ($csl > $creditsVacationEarned) $csl_vacation_less = $creditsVacationEarned;
                $csl -= $csl_vacation_less;
                $creditsVacationBalance = $creditsVacationEarned - $csl_vacation_less;

                // terminal
                if (in_array($leaveTypeID, [16])) {
                    $csl = 0;
                    $csl_sick_less = $creditsSickEarned;
                    $creditsSickBalance = $creditsSickEarned - $csl_sick_less;
                    $csl_vacation_less = $creditsVacationEarned;
                    $creditsVacationBalance = $creditsVacationEarned - $csl_vacation_less;
                }

                $creditsVacationLess    = $csl_vacation_less;
                $creditsSickLess        = $csl_sick_less;

                $items['creditsStatusAsOfMonth']    = $creditsStatusAsOfMonth; 

                $items['creditsVacationEarned']     = $creditsVacationEarned; 
                $items['creditsVacationLess']       = $creditsVacationLess; 
                $items['creditsVacationBalance']    = $creditsVacationBalance; 

                $items['creditsSickEarned']         = $creditsSickEarned; 
                $items['creditsSickLess']           = $creditsSickLess; 
                $items['creditsSickBalance']        = $creditsSickBalance; 
                
                $vacationWithPay    = $csl_vacation_less;
                $sickWithPay        = $csl_sick_less;

                if ($leaveTypeID == 3) $sickWithoutPay -= $csl;
                if (in_array($leaveTypeID, [15,16])) $datesInclusive = ' ';

                $items['datesInclusive']            = $datesInclusive;
                $items['period']                    = $period;
                $items['particulars']               = $particulars;
                $items['vacationWithPay']           = number_format($vacationWithPay, 3, '.', '');
                $items['vacationWithoutPay']        = number_format($vacationWithoutPay, 3, '.', '');
                $items['sickWithPay']               = number_format($sickWithPay, 3, '.', '');
                $items['sickWithoutPay']            = number_format(abs($sickWithoutPay), 3, '.', '');
                $items['remarks']                   = $remarks;

                $items['dateFrom']                  = $dateFrom;
                $items['dateTo']                    = $dateTo;
                $items['leaveDays']                 = $leaveDays;
                $items['leaveHours']                = $leaveHours;
                $items['leaveMinutes']              = $leaveMinutes;
                $items['editableApplicationDate']   = $editableApplicationDate;

                $data['items'] = $items;

            } 

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
            $request_fields     = ['creditsStatusAsOfMonth', 'datesInclusive', 'period', 'particulars', 'vacationWithPay', 'vacationWithoutPay', 'sickWithPay', 'sickWithoutPay', 'remarks'];
    
            /** variables */
            $decrypted_id       = 0;
            $request_data       = [];
            $hasError           = 0; 
            $requires           = '';
            $required_fields    = [
                'creditsStatusAsOfMonth'    => 'As of', 
                'datesInclusive'            => 'Inclusive Dates', 
            ];
    
            /** data */
            if ($request_fields) {
                foreach ($request_fields as $field) {
                    if (in_array($field, ['vacationWithPay', 'vacationWithoutPay', 'sickWithPay', 'sickWithoutPay'])) {
                        $request_data[$field] = isset($_POST[$field]) ? $_POST[$field] : 0;
                    } else {
                        $request_data[$field] = isset($_POST[$field]) ? $_POST[$field] : '';
                    }
                }
            }

            $addToLeaveLedger = isset($_POST['addToLeaveLedger']) ? 1 : 0;
            if (!$addToLeaveLedger) {
                $request_data['period']             = '';
                $request_data['particulars']        = '';
                $request_data['vacationWithPay']    = 0;
                $request_data['vacationWithoutPay'] = 0;
                $request_data['sickWithPay']        = 0;
                $request_data['sickWithoutPay']     = 0;
                $request_data['remarks']            = '';
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
                if ($addToLeaveLedger) {
                    if (!$request_data['period']) {
                        $hasError = 1;
                        $requires .= ($requires ? ", " : "")."Period";
                    }
                    if (!$request_data['particulars']) {
                        $hasError = 1;
                        $requires .= ($requires ? ", " : "")."Particulars";
                    }
                }
                if ($hasError) $data = $this->response->status(401, $requires, 'Required field(s):');
            }
    
            // query
            if (!$hasError) {

                $isRecommended = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id)->where('status', 2)->count();
                
                $checkerUserEmploymentID = 0;
                if ($token_userID) {
                    $query = DB::table('user_employments')->where('userID', $token_userID)->where('status', 1)->first(); 
                    if ($query) $checkerUserEmploymentID = $query->userEmploymentID;
                }

                $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                if ($query) {

                    $request_data['addToLeaveLedger'] = $addToLeaveLedger;

                    $request_data['checkedBy'] = $token_userID;
                    $request_data['dateChecked'] = date('Y-m-d H:i:s');
                    $request_data['checkerUserEmploymentID'] = $checkerUserEmploymentID;
                    $request_data['status'] = $isRecommended ? 3 : 1;

                    $query->update($request_data);
                    // // update audit logs
                    // $logFields = $request_fields;
                    // $this->_auditLog($request_token['data'], $this->moduleActionIDs['Recommend'], $this->table, $this->tablePrimaryKey, $decrypted_id, $request_data, "Updated {$this->logTitle} Record", $logFields, 1);
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

                $approvalType               = 0;
                $approvalDetail             = '';
                $approverUserEmploymentID   = 0;

                $creditsVacationEarned      = 0;
                $creditsVacationLess        = 0;
                $creditsVacationBalance     = 0;
                $creditsSickEarned          = 0;
                $creditsSickLess            = 0;
                $creditsSickBalance         = 0;
                
                $addToLeaveLedger           = 0;
                $this_userID                = 0;
                $userEmploymentID           = 0;
                $period                     = ''; 
                $particulars                = ''; 
                $vacationWithPay            = 0; 
                $vacationWithoutPay         = 0; 
                $sickWithPay                = 0; 
                $sickWithoutPay             = 0; 
                $dateInserted               = ''; 
                $remarks                    = ''; 

                // 
                if ($token_userID) {
                    $query = DB::table('user_employments')->where('userID', $token_userID)->where('status', 1)->first(); 
                    if ($query) $approverUserEmploymentID = $query->userEmploymentID;
                }

                // 
                $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id)->first();
                if ($query) { 

                    // approval type
                    if (in_array($query->leaveTypeID, [1,2,3])) {
                        $approvalType   = 1;
                        $approvalDetail = $query->leaveWorkingDays;
                    }

                    // credits less
                    if (in_array($query->leaveTypeID, [1,2,3])) {
                        $leaveLess = $query->leaveDays;
                        if ($query->leaveHours) {
                            $query2 = DB::table('leave_credit_fractions');
                            $query2 = $query2->where('type', 1);
                            $query2 = $query2->where('variable', $query->leaveHours);
                            $query2 = $query2->first();
                            if ($query2) { 
                                $leaveLess += $query2->value; 
                            }
                        }
                        if ($query->leaveMinutes) {
                            $query2 = DB::table('leave_credit_fractions');
                            $query2 = $query2->where('type', 2);
                            $query2 = $query2->where('variable', $query->leaveMinutes);
                            $query2 = $query2->first();
                            if ($query2) {
                                $leaveLess += $query2->value;
                            }
                        } 
    
                        if (in_array($query->leaveTypeID, [1,2])) $creditsVacationLess = $leaveLess;
                        if (in_array($query->leaveTypeID, [3])) $creditsSickLess = $leaveLess; 
                    }

                    // get latest earnings 
                    if ($query->userID) { 
                        $query2 = DB::table('user_leave_credits'); 
                        $query2 = $query2->where('userID', $query->userID); 
                        $query2 = $query2->first(); 
                        if ($query2) { 
                            $creditsVacationEarned  = $query2->creditsVacation; 
                            $creditsSickEarned      = $query2->creditsSick; 
                        } 
                    } 

                    $creditsVacationBalance = $creditsVacationEarned-$creditsVacationLess;
                    $creditsSickBalance     = $creditsSickEarned-$creditsSickLess;
                    
                    $addToLeaveLedger   = $query->addToLeaveLedger;
                    $this_userID        = $query->userID; 
                    $userEmploymentID   = $query->userEmploymentID; 
                    $period             = $query->period; 
                    $particulars        = $query->particulars; 
                    $vacationWithPay    = $query->vacationWithPay; 
                    $vacationWithoutPay = $query->vacationWithoutPay; 
                    $sickWithPay        = $query->sickWithPay; 
                    $sickWithoutPay     = $query->sickWithoutPay; 
                    $remarks            = $query->remarks; 
                    $dateInserted       = $query->dateFiled; 

                }

                $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                if ($query) {

                    $request_data['creditsVacationEarned']  = $creditsVacationEarned;
                    $request_data['creditsVacationLess']    = $creditsVacationLess;
                    $request_data['creditsVacationBalance'] = $creditsVacationBalance;
                    $request_data['creditsSickEarned']      = $creditsSickEarned;
                    $request_data['creditsSickLess']        = $creditsSickLess;
                    $request_data['creditsSickBalance']     = $creditsSickBalance;

                    $request_data['approvedBy'] = $token_userID;
                    $request_data['dateApproved'] = date('Y-m-d H:i:s');
                    $request_data['approverUserEmploymentID'] = $approverUserEmploymentID;
                    $request_data['approvalType'] = $approvalType;
                    $request_data['approvalDetail'] = $approvalDetail;
                    $request_data['status'] = 4;

                    $query->update($request_data);
                    // // update audit logs
                    // $logFields = $request_fields;
                    // $this->_auditLog($request_token['data'], $this->moduleActionIDs['Recommend'], $this->table, $this->tablePrimaryKey, $decrypted_id, $request_data, "Updated {$this->logTitle} Record", $logFields, 1);

                    // insert leave credit record here
                    if ($addToLeaveLedger) {

                        $userLeaveCreditID              = 0;
                        $vacationEarned                 = 0;
                        $vacationUndertimeWithPay       = $vacationWithPay;
                        $vacationBalance                = $creditsVacationEarned-$vacationWithPay;
                        $vacationUndertimeWithoutPay    = $vacationWithoutPay;
                        $sickEarned                     = 0;
                        $sickUndertimeWithPay           = $sickWithPay;
                        $sickBalance                    = $creditsSickEarned-$sickWithPay;
                        $sickUndertimeWithoutPay        = $sickWithoutPay;

                        if ($this_userID) {
                            $query3 = DB::table('user_leave_credits');
                            $query3 = $query3->where('userID', $this_userID);
                            $query3 = $query3->first();
                            if ($query3) $userLeaveCreditID = $query3->userLeaveCreditID;
                        }

                        // insert new ledger detail
                        $request_data = [
                            'userLeaveCreditID'             => $userLeaveCreditID, 
                            'userEmploymentID'              => $userEmploymentID, 
                            'period'                        => $period, 
                            'particulars'                   => $particulars, 
                            'vacationEarned'                => $vacationEarned, 
                            'vacationUndertimeWithPay'      => $vacationUndertimeWithPay, 
                            'vacationBalance'               => $vacationBalance, 
                            'vacationUndertimeWithoutPay'   => $vacationUndertimeWithoutPay, 
                            'sickEarned'                    => $sickEarned, 
                            'sickUndertimeWithPay'          => $sickUndertimeWithPay, 
                            'sickBalance'                   => $sickBalance, 
                            'sickUndertimeWithoutPay'       => $sickUndertimeWithoutPay, 
                            'remarks'                       => $remarks, 
                            'dateInserted'                  => $dateInserted, 
                            'dateAccounted'                 => date('Y-m-d H:i:s'), 
                        ];
                        DB::table('user_leave_credit_details')->insertGetId($request_data);

                        // update ledger main
                        $query = DB::table('user_leave_credits')->where('userLeaveCreditID', $userLeaveCreditID);
                        if ($query) {
                            $request_data = [
                                'creditsVacation'   => $vacationBalance,
                                'creditsSick'       => $sickBalance,
                            ];
                            $query->update($request_data);
                        }

                    }

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
            $request_fields     = ['disapproveRemarks'];
    
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

                $disapproverUserEmploymentID   = 0;
                if ($token_userID) {
                    $query = DB::table('user_employments')->where('userID', $token_userID)->where('status', 1)->first(); 
                    if ($query) $disapproverUserEmploymentID = $query->userEmploymentID;
                }

                $query = DB::table($this->table)->where($this->tablePrimaryKey, $decrypted_id);
                if ($query) {

                    $request_data['disapprovedBy'] = $token_userID;
                    $request_data['dateDisapproved'] = date('Y-m-d H:i:s');
                    $request_data['disapproverUserEmploymentID'] = $disapproverUserEmploymentID;
                    $request_data['status'] = -1;

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

    public function print_leave_application_data(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];
            
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Print Leave Application'])) {

                $decrypted_id = $this->_decryptID($id);

                $office                 = "";
                $lname                  = "";
                $fname                  = "";
                $mname                  = "";
                $dateFiled              = "";
                $position               = "";
                $salary                 = "";
                $leaveTypeID            = 0;
                $leaveTypeDetail        = "";
                $leaveCaseID            = 0;
                $leaveCaseDetail        = "";
                $daysApplied            = "";
                $datesInclusive         = "";
                $commutation            = 0;
                $creditsStatusAsOfMonth = "";
                $creditsVacationEarned  = 0;
                $creditsVacationLess    = 0;
                $creditsVacationBalance = 0;
                $creditsSickEarned      = 0;
                $creditsSickLess        = 0;
                $creditsSickBalance     = 0;
                $recommeded             = 1;
                $approvalType           = 0;
                $approvalDetail         = "";
                $recommendedBy          = "";
                $dateRecommended        = "";
                $recommenderPos         = "";
                $checkedBy              = "";
                $dateChecked            = "";
                $checkerPos             = "";
                $approvedBy             = "";
                $dateApproved           = "";
                $approverPos            = "";
                $disapproveRemarks      = "";

                $appliedBySign          = "";
                $dateFiledSign          = "";
                $recommendedBySign      = "";
                $dateRecommendedSign    = "";
                $checkedBySign          = "";
                $dateCheckedSign        = "";
                $approvedBySign         = "";
                $dateApprovedSign       = "";

                $checker        = 0;
                $recommender    = 0;
                $approver       = 0;
                $disapprover    = 0;
                $status         = 0;
                
                $row    = [];

                $query = DB::table('leave_applications');
                $query = $query->select(
                    "leave_applications.*", 
                    "user_personal_informations.lname", 
                    "user_personal_informations.fname", 
                    "user_personal_informations.mname", 
                    "user_employments.salaryMonthly", 
                    "offices.name as oName", 
                    "JobPositions.name as jpName", 
                );
                $query = $query->leftjoin('user_personal_informations', "leave_applications.userID", '=', 'user_personal_informations.userID'); 
                $query = $query->leftjoin('user_employments', "leave_applications.userEmploymentID", '=', 'user_employments.userEmploymentID'); 
                $query = $query->leftjoin('JobPositions', "user_employments.jobPositionID", '=', 'JobPositions.jobPositionID'); 
                $query = $query->leftjoin('offices', "user_employments.officeID", '=', 'offices.officeID'); 
                $query = $query->leftjoin('user_personal_informations as checker', "leave_applications.checkedBy", '=', 'checker.userID'); 
                $query = $query->leftjoin('user_personal_informations as recommender', "leave_applications.recommendedBy", '=', 'recommender.userID'); 
                $query = $query->leftjoin('user_personal_informations as approver', "leave_applications.approvedBy", '=', 'approver.userID'); 
                $query = $query->leftjoin('user_personal_informations as disapprover', "leave_applications.approvedBy", '=', 'disapprover.userID'); 
                $query = $query->where("leave_applications.leaveApplicationID", $decrypted_id);
                $query = $query->first();

                if ($query) {

                    $aprvr = $query->approvedBy;
                    if ($query->status < 0 && $query->dateRecommended && $query->dateChecked) $aprvr = $query->disapprovedBy;

                    // signatures
                    $row['signatureApplicant']      = (File::exists(public_path('uploads/signatures/').md5($query->userID).'.png')) ? $this->_convertImageToBase64('uploads/signatures/'.md5($query->userID).'.png') : '';
                    $row['signatureChecker']        = (File::exists(public_path('uploads/signatures/').md5($query->checkedBy).'.png') && $query->dateChecked) ? $this->_convertImageToBase64('uploads/signatures/'.md5($query->checkedBy).'.png') : '';
                    $row['signatureRecommender']    = (File::exists(public_path('uploads/signatures/').md5($query->recommendedBy).'.png') && $query->dateRecommended) ? $this->_convertImageToBase64('uploads/signatures/'.md5($query->recommendedBy).'.png') : '';
                    $row['signatureApprover']       = (File::exists(public_path('uploads/signatures/').md5($aprvr).'.png') && $aprvr) ? $this->_convertImageToBase64('uploads/signatures/'.md5($aprvr).'.png') : '';

                    $checker        = $query->checkedBy;
                    $recommender    = $query->recommendedBy;
                    $approver       = $query->approvedBy;
                    $disapprover    = $query->disapprovedBy;
                    $status         = $query->status;

                    $office         = $query->oName;
                    $lname          = ucwords($query->lname);
                    $fname          = ucwords($query->fname);
                    $mname          = ucwords($query->mname);
                    $appliedBySign  = strtoupper($query->lname)." ".strtoupper($query->fname)." ".strtoupper($query->mname);
                    $dateFiledSign  = $query->dateFiled?date('Y.m.d H:i:s', strtotime($query->dateFiled)):'';
                    $dateFiled  = $query->dateFiled?date('m/d/Y h:iA', strtotime($query->dateFiled)):'';
                    $position   = $query->jpName;
                    $salary     = number_format($query->salaryMonthly, 2);

                    $leaveTypeID        = $query->leaveTypeID;
                    if ($query->leaveTypeID == 14) {
                        $leaveTypeDetail = "CTO";
                        if ($query->dateServiceFrom) {
                            $leaveTypeDetail .= ": ".date('n/j/y', strtotime($query->dateServiceFrom));
                            if ($query->dateServiceTo) {
                                $leaveTypeDetail .= " - ".date('n/j/y', strtotime($query->dateServiceTo));
                            }
                        }
                    }
                    $leaveCaseID        = $query->leaveCaseID;
                    $leaveCaseDetail    = $query->leaveCaseDetail;
                    $daysApplied        = $query->leaveWorkingDays;
                    $datesInclusive     = $query->datesInclusive;

                    // 
                    if (!$datesInclusive) {
                        if ($query->dateFrom) {
                        
                            $yearFrom   = date('y', strtotime($query->dateFrom));
                            $yearTo     = date('y', strtotime($query->dateTo));
                            $monthFrom  = date('m', strtotime($query->dateFrom));
                            $monthTo    = date('m', strtotime($query->dateTo));
    
                            if ($yearFrom == $yearTo) {
                                if ($monthFrom == $monthTo) {
                                    $dates = $this->_recursive_getDays(date('Y-m-d', strtotime($query->dateFrom)), date('Y-m-d', strtotime($query->dateTo)));
                                    $datesInclusive = date('M', strtotime($query->dateFrom))." $dates, ".date('Y', strtotime($query->dateFrom));
                                } else {
                                    $datesInclusive = date('M j', strtotime($query->dateFrom))."-".date('M j', strtotime($query->dateTo)).", ".date('Y', strtotime($query->dateFrom));
                                }
                            } else {
                                $datesInclusive = date('M j, Y', strtotime($query->dateFrom))."-".date('M j, Y', strtotime($query->dateTo));
                            }
    
                        }
                    }

                    $commutation = $query->commutation;

                    $creditsStatusAsOfMonth = $query->creditsStatusAsOfMonth;
                    $creditsVacationEarned  = $query->creditsVacationEarned;
                    $creditsVacationLess    = $query->creditsVacationLess;
                    $creditsVacationBalance = $query->creditsVacationBalance;
                    $creditsSickEarned      = $query->creditsSickEarned;
                    $creditsSickLess        = $query->creditsSickLess;
                    $creditsSickBalance     = $query->creditsSickBalance;

                    // 
                    if (in_array($query->status, [1,3])) {

                        $creditsVacationEarned      = 0; 
                        $creditsVacationLess        = 0; 
                        $creditsSickEarned          = 0; 
                        $creditsSickLess            = 0; 

                        $leaveLess = $query->leaveDays;
                        if ($query->leaveHours) {
                            $query2 = DB::table('leave_credit_fractions');
                            $query2 = $query2->where('type', 1);
                            $query2 = $query2->where('variable', $query->leaveHours);
                            $query2 = $query2->first();
                            if ($query2) {
                                $leaveLess += $query2->value;
                            }
                        }
                        if ($query->leaveMinutes) {
                            $query2 = DB::table('leave_credit_fractions');
                            $query2 = $query2->where('type', 2);
                            $query2 = $query2->where('variable', $query->leaveMinutes);
                            $query2 = $query2->first();
                            if ($query2) {
                                $leaveLess += $query2->value;
                            }
                        } 

                        if (in_array($query->leaveTypeID, [1,2])) $creditsVacationLess = $leaveLess;
                        if (in_array($query->leaveTypeID, [3])) $creditsSickLess = $leaveLess; 
                        if (in_array($query->leaveTypeID, [15])) {
                            $creditsVacationLess    = $query->creditsToMonetizeVL; 
                            $creditsSickLess        = $query->creditsToMonetizeSL; 
                        }

                        // get latest earnings 
                        if ($query->userID) { 
                            $query2 = DB::table('user_leave_credits'); 
                            $query2 = $query2->where('userID', $query->userID); 
                            $query2 = $query2->first(); 
                            if ($query2) { 
                                $creditsVacationEarned  = $query2->creditsVacation; 
                                $creditsSickEarned      = $query2->creditsSick; 
                            } 
                        } 

                        $creditsVacationBalance = $creditsVacationEarned-$creditsVacationLess;
                        $creditsSickBalance     = $creditsSickEarned-$creditsSickLess;
                    } 

                    if (!$query->dateRecommended && $query->status == -1) $recommeded = 0;

                    $approvalType   = $query->approvalType;
                    $approvalDetail = $query->approvalDetail;

                    if (in_array($query->leaveTypeID, [1,2,3])) {
                        $approvalType   = 1;
                        $approvalDetail = $query->leaveWorkingDays;
                    }
                    if (in_array($query->leaveTypeID, [15])) {
                        $approvalType   = 3;
                        $approvalDetail = $query->leaveWorkingDays;
                    }

                    // checker
                    if ($query->checkerUserEmploymentID) {
                        $query2 = DB::table('user_employments');
                        $query2 = $query2->select(
                            "user_employments.*", 
                            "user_personal_informations.lname", 
                            "user_personal_informations.fname", 
                            "user_personal_informations.mname", 
                            "JobPositions.name as jpName", 
                        );
                        $query2 = $query2->leftjoin('user_personal_informations', "user_employments.userID", '=', 'user_personal_informations.userID'); 
                        $query2 = $query2->leftjoin('JobPositions', "user_employments.jobPositionID", '=', 'JobPositions.jobPositionID'); 
                        $query2 = $query2->where('user_employments.userEmploymentID', $query->checkerUserEmploymentID); 
                        $query2 = $query2->first(); 
                        if ($query2) {
                            $checkedBySign  = strtoupper($query2->lname)." ".strtoupper($query2->fname)." ".strtoupper($query2->mname);
                            $checkedBy  = ucwords($query2->fname)." ".($query2->mname?ucwords($query2->mname)." ":'').ucwords($query2->lname);
                            $checkerPos = strtoupper($query2->jpName); 
                        }
                    } 
                    // recommender
                    if ($query->recommenderUserEmploymentID) {
                        $query2 = DB::table('user_employments');
                        $query2 = $query2->select(
                            "user_employments.*", 
                            "user_personal_informations.lname", 
                            "user_personal_informations.fname", 
                            "user_personal_informations.mname", 
                            "JobPositions.name as jpName", 
                        );
                        $query2 = $query2->leftjoin('user_personal_informations', "user_employments.userID", '=', 'user_personal_informations.userID'); 
                        $query2 = $query2->leftjoin('JobPositions', "user_employments.jobPositionID", '=', 'JobPositions.jobPositionID'); 
                        $query2 = $query2->where('user_employments.userEmploymentID', $query->recommenderUserEmploymentID); 
                        $query2 = $query2->first(); 
                        if ($query2) {
                            $recommendedBySign  = strtoupper($query2->lname)." ".strtoupper($query2->fname)." ".strtoupper($query2->mname);
                            $recommendedBy  = ucwords($query2->fname)." ".($query2->mname?ucwords($query2->mname)." ":'').ucwords($query2->lname);
                            $recommenderPos = strtoupper($query2->jpName); 
                        }
                    }

                    $approverUserEmploymentID = $query->approverUserEmploymentID;
                    if ($query->status < 0 && $query->dateChecked && $query->dateRecommended) $approverUserEmploymentID = $query->disapproverUserEmploymentID;

                    if ($approverUserEmploymentID) {
                        $query2 = DB::table('user_employments');
                        $query2 = $query2->select(
                            "user_employments.*", 
                            "user_personal_informations.lname", 
                            "user_personal_informations.fname", 
                            "user_personal_informations.mname", 
                            "JobPositions.name as jpName", 
                        );
                        $query2 = $query2->leftjoin('user_personal_informations', "user_employments.userID", '=', 'user_personal_informations.userID'); 
                        $query2 = $query2->leftjoin('JobPositions', "user_employments.jobPositionID", '=', 'JobPositions.jobPositionID'); 
                        $query2 = $query2->where('user_employments.userEmploymentID', $approverUserEmploymentID); 
                        $query2 = $query2->first(); 
                        if ($query2) {
                            $approvedBySign = strtoupper($query2->lname)." ".strtoupper($query2->fname)." ".strtoupper($query2->mname);
                            $approvedBy  = ucwords($query2->fname)." ".($query2->mname?ucwords($query2->mname)." ":'').ucwords($query2->lname);
                            $approverPos = strtoupper($query2->jpName); 
                        }
                    }

                    $dateRecommendedSign    = $query->dateRecommended ? date('Y.m.d H:i:s', strtotime($query->dateRecommended)):'';
                    $dateCheckedSign        = $query->dateChecked ? date('Y.m.d H:i:s', strtotime($query->dateChecked)):'';
                    $dateApprovedSign       = $query->dateApproved ? date('Y.m.d H:i:s', strtotime($query->dateApproved)):'';

                    $dateRecommended    = $query->dateRecommended ? date('m/d/y h:iA', strtotime($query->dateRecommended)):'';
                    $dateChecked        = $query->dateChecked ? date('m/d/y h:iA', strtotime($query->dateChecked)):'';
                    $dateApproved       = $query->dateApproved ? date('m/d/y h:iA', strtotime($query->dateApproved)):'';
                    $disapproveRemarks  = $query->disapproveRemarks;

                    if ($query->leaveTypeID == 14) {
                        $creditsVacationEarned  = 0;
                        $creditsVacationLess    = 0;
                        $creditsVacationBalance = 0;
                        $creditsSickEarned      = 0;
                        $creditsSickLess        = 0;
                        $creditsSickBalance     = 0;
                    }

                }


                $row['checker']     = $checker;
                $row['recommender'] = $recommender;
                $row['approver']    = $approver;
                $row['disapprover'] = $disapprover;
                $row['status']      = $status;

                $row['office']                  = $office;
                $row['lname']                   = $lname;
                $row['fname']                   = $fname;
                $row['mname']                   = $mname;
                $row['dateFiled']               = $dateFiled;
                $row['position']                = $position;
                $row['salary']                  = $salary;
                $row['leaveTypeID']             = $leaveTypeID;
                $row['leaveTypeDetail']         = $leaveTypeDetail;
                $row['leaveCaseID']             = $leaveCaseID;
                $row['leaveCaseDetail']         = $leaveCaseDetail;
                $row['daysApplied']             = $daysApplied;
                $row['datesInclusive']          = $datesInclusive;
                $row['commutation']             = $commutation;
                $row['creditsStatusAsOfMonth']  = $creditsStatusAsOfMonth;
                $row['creditsVacationEarned']   = $creditsVacationEarned ? number_format($creditsVacationEarned, 3) : '-';
                $row['creditsVacationLess']     = $creditsVacationLess ? number_format($creditsVacationLess, 3) : '0';
                $row['creditsVacationBalance']  = $creditsVacationEarned ? number_format($creditsVacationBalance, 3) : '-';
                $row['creditsSickEarned']       = $creditsSickEarned ? number_format($creditsSickEarned, 3) : '-';
                $row['creditsSickLess']         = $creditsSickLess ? number_format($creditsSickLess, 3) : '0';
                $row['creditsSickBalance']      = $creditsSickEarned ? number_format($creditsSickBalance, 3) : '-';
                $row['recommeded']              = $recommeded;
                $row['approvalType']            = $approvalType;
                $row['approvalDetail']          = $approvalDetail;
                $row['recommendedBy']           = strtoupper($recommendedBy);
                $row['dateRecommended']         = $dateRecommended;
                $row['recommenderPos']          = $recommenderPos;
                $row['checkedBy']               = strtoupper($checkedBy);
                $row['dateChecked']             = $dateChecked;
                $row['checkerPos']              = $checkerPos;
                $row['approvedBy']              = strtoupper($approvedBy);
                $row['dateApproved']            = $dateApproved;
                $row['approverPos']             = $approverPos;
                $row['disapproveRemarks']       = $disapproveRemarks;

                $row['appliedBySign']       = $appliedBySign;
                $row['dateFiledSign']       = $dateFiledSign;
                $row['recommendedBySign']   = $recommendedBySign;
                $row['dateRecommendedSign'] = $dateRecommendedSign;
                $row['checkedBySign']       = $checkedBySign;
                $row['dateCheckedSign']     = $dateCheckedSign;
                $row['approvedBySign']      = $approvedBySign;
                $row['dateApprovedSign']    = $dateApprovedSign;

                $items['hasButtonTravelOrder'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Print Leave Application']);
                $items['row'] = $row;
                $items['printID'] = md5($this->_printDocument(4, $token_userID));

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


