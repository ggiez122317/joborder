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

class MyLeaveRequestController extends MasterController
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
        $this->module           = 'My Leave Requests'; 
        $this->controller       = 'my-leave-requests'; 
        $this->logTitle         = 'My Leave Request'; 
        $this->table            = 'leave_applications'; 
        $this->tablePrimaryKey  = 'leaveApplicationID'; 
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'     => 3, 
            'PrintList' => 0, 
            'Insert'    => 124, 
            'View'      => 125, 
            'Audit'     => 0, 
            'Update'    => 0, 
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
                    "leave_types.name as ltName",  
                );
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
                            'leaveApplicationID'    => Crypt::encryptString("{$tr->leaveApplicationID}"), 
                            'dateInserted'          => $tr->dateFiled ? date('m/d/y h:i A', strtotime($tr->dateFiled)) : '', 
                            'ltName'                => $tr->ltName, 
                            'leaveWorkingDays'      => $tr->leaveWorkingDays, 
                            'status'                => $tr->status, 
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

                $creditsVacation        = 0;
                $creditsSick            = 0;
                $monetizeCreditsSalary  = 0;
                $basicSalary            = 0;

                $query = DB::table('user_leave_credits');
                $query = $query->where('userID', $token_userID);
                $query = $query->first();

                if ($query) {
                    $creditsVacation    = number_format($query->creditsVacation, 3);
                    $creditsSick        = number_format($query->creditsSick, 3);
                }

                if (($creditsVacation + $creditsSick) > 0) {
                    $monetizeCreditsTotal       = $creditsVacation + $creditsSick;
                    $monetizeCreditsConvertable = $monetizeCreditsTotal/2;
                }

                $query = DB::table('user_employments');
                $query = $query->where('status', 1);
                $query = $query->where('userID', $token_userID);
                $query = $query->first();

                if ($query) $monetizeCreditsSalary = $query->salaryMonthly+0; 

                $items['creditsVacation']   = $creditsVacation;
                $items['creditsSick']       = $creditsSick;
                $items['monetizeCreditsTotal']          = $monetizeCreditsTotal;
                $items['monetizeCreditsConvertable']    = $monetizeCreditsConvertable;
                $items['monetizeCreditsSalary']         = $monetizeCreditsSalary;
                $items['monetizeConstantFactor']        = $this->_getConfig('Leave Credits Monetization Constant Factor')+0;
                $items['hasButtonAdd']      = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $items['leave_types']       = DB::table('leave_types')->select('leaveTypeID', 'name', 'nameExt', 'daysPrior')->orderBy('leaveTypeID', 'asc')->get();
                $items['credit_fractions_hour'] = DB::table('leave_credit_fractions')->where('type', 1)->where('variable', '!=', 8)->orderBy('variable', 'desc')->get();
                $items['credit_fractions_minute'] = DB::table('leave_credit_fractions')->where('type', 2)->where('variable', '!=', 60)->orderBy('variable', 'desc')->get();

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
                $request_fields     = ['leaveTypeID', 'leaveCaseID', 'leaveCaseDetail', 'leaveDays', 'leaveHours', 'leaveMinutes', 'dateFrom', 'dateTo', 'dateServiceFrom', 'dateServiceTo', 'dateCTOFrom', 'dateCTOTo', 'creditsToMonetizeVL', 'creditsToMonetizeSL', 'creditsToMonetize', 'dateSeparate', 'commutation', 'creditsConvertDays', 'creditsConvertHours', 'creditsConvertMinutes'];
        
                /** variables */
                $validExtensions    = ['png', 'jpg', 'jpeg', 'gif', 'xlsx', 'xls', 'pdf', 'ppt', 'pptx', 'doc', 'docx'];
                $id                 = '';
                $request_data       = [];
                $hasError           = 0;
                $requires           = '';
                $required_fields    = [
                    'leaveTypeID' => 'Leave Type', 
                ];

                $constantFactor = $this->_getConfig('Leave Credits Monetization Constant Factor')+0;

                $destinationPath = public_path('uploads/leave_applications/');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);
        
                /** data */
                if ($request_fields) {
                    foreach ($request_fields as $field) {
                        if (in_array($field, ['commutation', 'leaveCaseID', 'leaveDays', 'leaveHours', 'leaveMinutes'])) {
                            $request_data[$field] = isset($_POST[$field]) ? ($_POST[$field] ? $_POST[$field] : 0) : 0;
                        } else {
                            $request_data[$field] = isset($_POST[$field]) ? $_POST[$field] : '';
                        }
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
                    // vacation/SLP
                    if (in_array($request_data['leaveTypeID'], [1,6])) {
                        if (!in_array($request_data['leaveCaseID'], [1,2])) {
                            $hasError = 1;
                            $requires .= ($requires ? ", " : "")."Within the Philippines or Abroad";
                        } else {
                            if ($request_data['leaveCaseID']==2 && !$request_data['leaveCaseDetail']) {
                                $hasError = 1;
                                $requires .= ($requires ? ", " : "")."Specify Location";
                            }
                        }
                    }
                    // Sick
                    if (in_array($request_data['leaveTypeID'], [3])) {
                        if (!in_array($request_data['leaveCaseID'], [3,4])) {
                            $hasError = 1;
                            $requires .= ($requires ? ", " : "")."In Hospital or Out Patient";
                        } else {
                            if ($request_data['leaveCaseID']==4 && !$request_data['leaveCaseDetail']) {
                                $hasError = 1;
                                $requires .= ($requires ? ", " : "")."Specify Illness";
                            }
                        }
                    }
                    // Study
                    if (in_array($request_data['leaveTypeID'], [8])) {
                        if (!in_array($request_data['leaveCaseID'], [5,6])) {
                            $hasError = 1;
                            $requires .= ($requires ? ", " : "")."Completion of Master's Degree or Bar/Board Examination Review";
                        } 
                    } 
                    // Benefits for Women
                    if (in_array($request_data['leaveTypeID'], [11])) {
                        if (!$request_data['leaveCaseDetail']) {
                            $hasError = 1;
                            $requires .= ($requires ? ", " : "")."Specify Illness";
                        } 
                    }
                    // CTO
                    if (in_array($request_data['leaveTypeID'], [14])) {
                        $rqf = [
                            'dateServiceFrom'   => 'Date of Actual Work From', 
                            'dateServiceTo'     => 'Date of Actual Work To', 
                            'dateCTOFrom'       => 'CTO Date From', 
                            'dateCTOTo'         => 'CTO Date To'
                        ];
                        foreach ($rqf as $ff => $label) {
                            if (!$request_data[$ff]) {
                                $hasError = 1;
                                $requires .= ($requires ? ", " : "").$label;
                            } 
                        }
                        // values 
                        $request_data['dateFrom']   = $request_data['dateCTOFrom'];
                        $request_data['dateTo']     = $request_data['dateCTOTo'];
                        $request_data['leaveDays']      = 0;
                        $request_data['leaveHours']     = 0;
                        $request_data['leaveMinutes']   = 0;

                        if (isset($_POST['leaveCTODays'])) {
                            $request_data['leaveDays'] = isset($_POST['leaveCTODays']) && $_POST['leaveCTODays'] ? $_POST['leaveCTODays'] : 0;
                        }
                        if (isset($_POST['leaveCTOHours'])) {
                            $request_data['leaveHours'] = isset($_POST['leaveCTOHours']) && $_POST['leaveCTOHours'] ? $_POST['leaveCTOHours'] : 0;
                        }
                        if (isset($_POST['leaveCTOMinutes'])) {
                            $request_data['leaveMinutes'] = isset($_POST['leaveCTOMinutes']) && $_POST['leaveCTOMinutes'] ? $_POST['leaveCTOMinutes'] : 0;
                        }

                        if (!((int)$request_data['leaveDays'] + (int)$request_data['leaveHours'] + (int)$request_data['leaveMinutes'])) {
                            $hasError = 1;
                            $requires .= ($requires ? ", " : "")."Leave Working Day(s)/Hr(s)/Min(s)";
                        }

                    }
                    // Monetization
                    if (in_array($request_data['leaveTypeID'], [15])) {
                        $rqf = [
                            'creditsToMonetize' => 'Convert Working Day(s)/ Hr(s)/ Min(s)'
                        ];
                        foreach ($rqf as $ff => $label) {
                            if (!$request_data[$ff]) {
                                $hasError = 1;
                                $requires .= ($requires ? ", " : "").$label;
                            } 
                        }
                    } else {
                        $request_data['creditsToMonetize'] = 0;
                    }
                    // Terminal
                    if (in_array($request_data['leaveTypeID'], [16])) {
                        $rqf = [
                            'dateSeparate' => 'Separation Date'
                        ];
                        foreach ($rqf as $ff => $label) {
                            if (!$request_data[$ff]) {
                                $hasError = 1;
                                $requires .= ($requires ? ", " : "").$label;
                            } 
                        }
                    } else {
                        $request_data['dateSeparate'] = null;
                    }
                    // not Monitezation or not Terminal
                    if (!in_array($request_data['leaveTypeID'], [14,15,16])) {
                        if (!$request_data['dateFrom']) {
                            $hasError = 1;
                            $requires .= ($requires ? ", " : "")."Leave Date From";
                        }
                        if (!$request_data['dateTo']) {
                            $hasError = 1;
                            $requires .= ($requires ? ", " : "")."Leave Date To";
                        }
                        if (!((int)$request_data['leaveDays'] + (int)$request_data['leaveHours'] + (int)$request_data['leaveMinutes'])) {
                            $hasError = 1;
                            $requires .= ($requires ? ", " : "")."Leave Working Day(s)/ Hr(s)/ Min(s)";
                        }
                    }
                    // no leave CaseID
                    if (!in_array($request_data['leaveTypeID'], [1,3,6,8])) {
                        $request_data['leaveCaseID']        = 0;
                        // $request_data['leaveCaseDetail']    = '';
                    }
                }
                if ($hasError) $data = $this->response->status(401, $requires, 'Required field(s):');

                // check file type
                if (!$hasError && $request->hasFile('files')) {
                    $files = $request->file('files');
                    if ($files) {
                        foreach ($files as $file) {
                            $extension = $file->getClientOriginalExtension();
                
                            // Step 4: Validate file extension
                            if (!in_array(strtolower($extension), $validExtensions)) {
                                $hasError = 1;
                                $data = $this->response->status(409, '.png, .jpg, .jpeg, .gif, .xlsx, .xls, .pdf, .ppt, .pptx, .doc, .docx', 'Allowed file types!');
                                break;
                            }
                        }
                    }
                }
                
                // 
                $query = DB::table('user_employments');
                $query = $query->where('userID', $token_userID);
                $query = $query->where('status', 1);
                $query = $query->first();

                $userEmploymentID       = 0;
                $userEmploymentSalary   = 0;
                $jobPositionID          = 0;
                if ($query) {
                    $userEmploymentID       = $query->userEmploymentID;
                    $userEmploymentSalary   = $query->salaryMonthly;
                    $jobPositionID          = $query->jobPositionID;
                }

                if (!$hasError) {
                    if (!$userEmploymentID) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'This account has been deactivated.', 'Invalid!');
                    }
                }
                if (!$hasError) {
                    if (!$jobPositionID) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'This user has no job position.', 'Invalid!');
                    }
                }

                // recommender
                $recommendedBy                  = 0;
                $recommenderUserEmploymentID    = 0;
                if ($jobPositionID) {
                    // get immediate superior
                    $query = DB::table('user_employments');
                    $query = $query->select(
                        "user_employments.userEmploymentID", 
                        "user_employments.userID", 
                    );
                    $query = $query->leftjoin('JobPositions', "user_employments.jobPositionID", '=', 'JobPositions.head_positionID');
                    $query = $query->where('JobPositions.jobPositionID', $jobPositionID);
                    $query = $query->where('user_employments.status', 1);
                    $query = $query->first();
                    if ($query) {
                        $recommendedBy                  = $query->userID;
                        $recommenderUserEmploymentID    = $query->userEmploymentID;
                    }
                }

                if (!$hasError) {
                    if (!$recommendedBy || !$recommenderUserEmploymentID) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'This user has immediate superior.', 'Invalid!');
                    }
                }

                // ============ 
                $query = DB::table('leave_types');
                $query = $query->where('daysLimitPerYear', '>', 0);
                $query = $query->get();

                $daysLimitPerYear = 0;
                $limitedLeaveTypeID = [];
                if ($query) {
                    foreach ($query as $q) {
                        $limitedLeaveTypeID[] = $q->leaveTypeID;
                        if ($q->leaveTypeID == $request_data['leaveTypeID']) $daysLimitPerYear = $q->daysLimitPerYear;
                    }
                }

                // Yearly leave must be filed within the year
                if (!$hasError) {
                    
                    $dateYearFrom   = date('Y', strtotime($request_data['dateFrom']));
                    $dateYearTo     = date('Y', strtotime($request_data['dateTo']));

                    if (in_array($request_data['leaveTypeID'], $limitedLeaveTypeID)) {
                        if ($dateYearFrom != $dateYearTo) {
                            $hasError = 1;
                            $data = $this->response->status(409, 'Annual leave must be used within the year it is allocated.', 'Invalid!');
                        }
                        if ($dateYearFrom != date('Y')) {
                            $hasError = 1;
                            $data = $this->response->status(409, 'Annual leave must be used within the year it is allocated.', 'Invalid!');
                        }
                    }
                }

                // yearly limited leave within the year
                if (!$hasError) {
                    if ($daysLimitPerYear) {

                        $cYear = date('Y');

                        $leaveDays      = (float) $request_data['leaveDays'];
                        $leaveHours     = (float) $request_data['leaveHours'];
                        $leaveMinutes   = (float) $request_data['leaveMinutes'];
                        $usedDays       = 0;
                        $usedHours      = 0;
                        $usedMinutes    = 0;
                        $processDays    = 0;
                        $processHours   = 0;
                        $processMinutes = 0;

                        // approved
                        $query = DB::table('leave_applications');
                        $query = $query->where('leaveTypeID', $request_data['leaveTypeID']);
                        $query = $query->where('userID', $token_userID);
                        $query = $query->where('dateFrom', 'like', "%$cYear%");
                        $query = $query->where('dateTo', 'like', "%$cYear%");
                        $query = $query->where('status', 4); 
                        $query = $query->get();
                        if ($query) {
                            foreach ($query as $q) {
                                $usedDays       += $q->leaveDays;
                                $usedHours      += $q->leaveHours;
                                $usedMinutes    += $q->leaveMinutes;
                            }
                        }

                        // processing
                        $query = DB::table('leave_applications');
                        $query = $query->where('leaveTypeID', $request_data['leaveTypeID']);
                        $query = $query->where('userID', $token_userID);
                        $query = $query->where('dateFrom', 'like', "%$cYear%");
                        $query = $query->where('dateTo', 'like', "%$cYear%");
                        $query = $query->whereIn('status', [0,1,2,3]); 
                        $query = $query->get();
                        if ($query) {
                            foreach ($query as $q) {
                                $processDays       += $q->leaveDays;
                                $processHours      += $q->leaveHours;
                                $processMinutes    += $q->leaveMinutes;
                            }
                        }

                        // message
                        $used = "";
                        if ($usedDays + 0) $used .= ($used?" {$usedDays}d":"{$usedDays}d");
                        if ($usedHours + 0) $used .= ($used?" {$usedHours}h":"{$usedHours}h");
                        if ($usedMinutes + 0) $used .= ($used?" {$usedMinutes}m":"{$usedMinutes}m");
                        $processing = "";
                        if ($processDays + 0) $processing .= ($processing?" {$processDays}d":"{$processDays}d");
                        if ($processHours + 0) $processing .= ($processing?" {$processHours}h":"{$processHours}h");
                        if ($processMinutes + 0) $processing .= ($processing?" {$processMinutes}m":"{$processMinutes}m");
                        $another = "";
                        if ($leaveDays + 0) $another .= ($another?" {$leaveDays}d":"{$leaveDays}d");
                        if ($leaveHours + 0) $another .= ($another?" {$leaveHours}h":"{$leaveHours}h");
                        if ($leaveMinutes + 0) $another .= ($another?" {$leaveMinutes}m":"{$leaveMinutes}m");

                        if (!$used) $used = '-';
                        if (!$processing) $processing = '-';
                        if (!$another) $another = '-';
                        $msg = "
                            <table class='table table-bordered mb-0'>
                                <tr>
                                    <td class='text-start'>Annual Limit:</td>
                                    <td class='text-start'>{$daysLimitPerYear}d</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>Used:</td>
                                    <td class='text-start'>{$used}</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>On Process:</td>
                                    <td class='text-start'>{$processing}</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>Submitted:</td>
                                    <td class='text-start'>{$another}</td>
                                </tr>
                            </table>
                        ";

                        $usedDays       += $leaveDays;
                        $usedHours      += $leaveHours;
                        $usedMinutes    += $leaveMinutes;
                        $usedDays       += $processDays;
                        $usedHours      += $processHours;
                        $usedMinutes    += $processMinutes;

                        if ($usedMinutes > 60) $usedHours += (floor($usedMinutes / 60));
                        if ($usedHours > 8) $usedDays += (floor($usedHours / 8));

                        $hasExceeded = 0;
                        if ($usedDays == $daysLimitPerYear) {
                            if (($usedMinutes % 60) > 0) $hasExceeded = 1;
                            if (($usedHours % 8) > 0) $hasExceeded = 1;
                        }
                        if ($usedDays > $daysLimitPerYear) $hasExceeded = 1;

                        if ($hasExceeded) {
                            $hasError = 1;
                            $data = $this->response->status(409, $msg, 'Limit Exceeded!');
                        }

                    }
                }

                // check if has enough points for vl/mandatory/sl/monetize
                if (!$hasError) {

                    if (in_array($request_data['leaveTypeID'], [1,2,15])) {

                        $vlPoints           = 0;
                        $vlReq              = 0;
                        $vlForcedReq        = 0;
                        $slPoints           = 0;
                        $slReq              = 0;
                        $monetizationVL     = 0;
                        $monetizationSL     = 0;
                        $monetizationReq    = 0; 

                        $pRequested = 0;
    
                        // total
                        $query = DB::table('user_leave_credits');
                        $query = $query->where('userID', $token_userID);
                        $query = $query->first();
                        if ($query) {
                            $vlPoints += $query->creditsVacation;
                            $slPoints += $query->creditsSick;
                        }
    
                        // processing monetization
                        $query = DB::table('leave_applications');
                        $query = $query->where('userID', $token_userID);
                        $query = $query->where('leaveTypeID', 15); 
                        $query = $query->whereIn('status', [0,1,2,3]); 
                        $query = $query->get();
                        if ($query) {
                            foreach ($query as $q) {
                                $monetizationVL     += $q->creditsToMonetizeVL;
                                $monetizationSL     += $q->creditsToMonetizeSL;
                                $monetizationReq    += $q->creditsToMonetize;
                            }
                        }

                        if (!$pRequested) {
                            if (in_array($request_data['leaveTypeID'], [1,2])) {
                                $pRequested += ($request_data['leaveDays']?($request_data['leaveDays']+0):0);
                                if ($request_data['leaveHours']) {
                                    $query = DB::table('leave_credit_fractions');
                                    $query = $query->where('type', 1);
                                    $query = $query->where('variable', $request_data['leaveHours']);
                                    $query = $query->first();
                                    if ($query) $pRequested += $query->value;
                                }
                                if ($request_data['leaveMinutes']) {
                                    $query = DB::table('leave_credit_fractions');
                                    $query = $query->where('type', 2);
                                    $query = $query->where('variable', $request_data['leaveMinutes']);
                                    $query = $query->first();
                                    if ($query) $pRequested += $query->value;
                                }
                            } else {
                                $pRequested = $request_data['creditsToMonetize'];
                            }
                        }
                        // processing monetization
                        $query = DB::table('leave_applications');
                        $query = $query->where('userID', $token_userID);
                        $query = $query->where('leaveTypeID', 15); 
                        $query = $query->whereIn('status', [0,1,2,3]); 
                        $query = $query->get();
                        if ($query) {
                            foreach ($query as $q) {
                                $monetizationVL     += $q->creditsToMonetizeVL;
                                $monetizationSL     += $q->creditsToMonetizeSL;
                                $monetizationReq    += $q->creditsToMonetize;
                            }
                        }

                        // processing vl
                        $query = DB::table('leave_applications');
                        $query = $query->where('userID', $token_userID);
                        $query = $query->where('leaveTypeID', 1); 
                        $query = $query->whereIn('status', [0,1,2,3]); 
                        $query = $query->get();
                        if ($query) {
                            $vlReq = 0;
                            foreach ($query as $q) {
                                $vlReq += $q->leaveDays;
                                if ($q->leaveHours) {
                                    $query2 = DB::table('leave_credit_fractions');
                                    $query2 = $query2->where('type', 1);
                                    $query2 = $query2->where('variable', $q->leaveHours);
                                    $query2 = $query2->first();
                                    if ($query2) $vlReq += $query2->value;
                                }
                                if ($q->leaveMinutes) {
                                    $query2 = DB::table('leave_credit_fractions');
                                    $query2 = $query2->where('type', 2);
                                    $query2 = $query2->where('variable', $q->leaveMinutes);
                                    $query2 = $query2->first();
                                    if ($query2) $vlReq += $query2->value;
                                }
                            }
                        }
    
                        // processing forced
                        $query = DB::table('leave_applications');
                        $query = $query->where('userID', $token_userID);
                        $query = $query->where('leaveTypeID', 2); 
                        $query = $query->whereIn('status', [0,1,2,3]); 
                        $query = $query->get();
                        if ($query) {
                            $vlForcedReq = 0;
                            foreach ($query as $q) {
                                $vlForcedReq += $q->leaveDays;
                                if ($q->leaveHours) {
                                    $query2 = DB::table('leave_credit_fractions');
                                    $query2 = $query2->where('type', 1);
                                    $query2 = $query2->where('variable', $q->leaveHours);
                                    $query2 = $query2->first();
                                    if ($query2) $vlForcedReq += $query2->value;
                                }
                                if ($q->leaveMinutes) {
                                    $query2 = DB::table('leave_credit_fractions');
                                    $query2 = $query2->where('type', 2);
                                    $query2 = $query2->where('variable', $q->leaveMinutes);
                                    $query2 = $query2->first();
                                    if ($query2) $vlForcedReq += $query2->value;
                                }
                            }
                        }
    
                        // processing sick
                        $query = DB::table('leave_applications');
                        $query = $query->where('userID', $token_userID);
                        $query = $query->where('leaveTypeID', 3); 
                        $query = $query->whereIn('status', [0,1,2,3]); 
                        $query = $query->get();
                        if ($query) {
                            $slReq = 0;
                            foreach ($query as $q) {
                                $slReq += $q->leaveDays;
                                if ($q->leaveHours) {
                                    $query2 = DB::table('leave_credit_fractions');
                                    $query2 = $query2->where('type', 1);
                                    $query2 = $query2->where('variable', $q->leaveHours);
                                    $query2 = $query2->first();
                                    if ($query2) $slReq += $query2->value;
                                }
                                if ($q->leaveMinutes) {
                                    $query2 = DB::table('leave_credit_fractions');
                                    $query2 = $query2->where('type', 2);
                                    $query2 = $query2->where('variable', $q->leaveMinutes);
                                    $query2 = $query2->first();
                                    if ($query2) $slReq += $query2->value;
                                }
                            }
                        }

                        $pTotalLabel = 'Total';
                        if (in_array($request_data['leaveTypeID'], [15])) $pTotalLabel = 'Total: <span style="font-size: 8pt;">(SL + VL) ÷ 2</span>';
                        $pOnProcessLabel = 'On Process';
                        if (in_array($request_data['leaveTypeID'], [1,2])) $pOnProcessLabel = 'On Process <span style="font-size: 8pt;">(VL + Monetize)</span>';
                        if (in_array($request_data['leaveTypeID'], [3])) $pOnProcessLabel = 'On Process <span style="font-size: 8pt;">(SL + Monetize)</span>';
                        if (in_array($request_data['leaveTypeID'], [15])) $pOnProcessLabel = 'On Process: <span style="font-size: 8pt;">(SL + VL + Monetize)</span>';

                        $pTotal     = 0;
                        $pOnProcess = 0;
                        $pAvailable = 0;
                        $pBalance   = 0;

                        // vl/mandatory
                        if (in_array($request_data['leaveTypeID'], [1,2])) {
                            $pTotal     = $vlPoints;
                            $pOnProcess = $vlReq + $vlForcedReq + $monetizationReq;
                            $pAvailable = number_format(($pTotal - $pOnProcess), 3, '.', '');
                            $pBalance   = number_format(($pAvailable - $pRequested), 3, '.', '');
                        }

                        // sick
                        if (in_array($request_data['leaveTypeID'], [3])) {
                            $pTotal     = $slPoints;
                            $pOnProcess = $slReq + $monetizationReq;
                            $pAvailable = number_format(($pTotal - $pOnProcess), 3, '.', '');
                            $pBalance   = number_format(($pAvailable - $pRequested), 3, '.', '');
                        }

                        // monetize
                        if (in_array($request_data['leaveTypeID'], [15])) {
                            $pTotal     = ($vlPoints + $slPoints)/2;
                            $pOnProcess = $vlReq + $slReq + $monetizationReq;
                            $pAvailable = number_format(($pTotal - $pOnProcess), 3, '.', '');
                            $pBalance   = number_format(($pAvailable - $pRequested), 3, '.', '');
                        }

                        $pTotal     = number_format($pTotal, 3, '.', '');
                        $pOnProcess = number_format($pOnProcess, 3, '.', '');
                        $pAvailable = number_format($pAvailable, 3, '.', '');
                        $pRequested = number_format($pRequested, 3, '.', '');
                        $pBalance   = number_format($pBalance, 3, '.', '');

                        $msg = "
                            <br />
                            <table class='table table-bordered mb-0'>
                                <tr>
                                    <td class='text-start'><b>Credits</b></td>
                                    <td class='text-center'><b>Amount</b></td>
                                </tr>
                                <tr>
                                    <td class='text-start'>{$pTotalLabel}</td>
                                    <td class='text-end text-nowrap'>{$pTotal}</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>{$pOnProcessLabel}</td>
                                    <td class='text-end text-danger text-nowrap' style='border-bottom: 1px solid #000;'>{$pOnProcess}</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>Available</td>
                                    <td class='text-end text-nowrap'>{$pAvailable}</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>Requested</td>
                                    <td class='text-end text-danger text-nowrap' style='border-bottom: 1px solid #000;'>{$pRequested}</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>Balance</td>
                                    <td class='text-end text-nowrap'><b>{$pBalance}</b></td>
                                </tr>
                            </table>
                            <br />
                            <p class='mb-0'>Credit Balance should be greater than or equal zero.</p>
                        ";

                        if ($pBalance < 0) {
                            $hasError = 1;
                            $data = $this->response->status(409, $msg, 'Invalid!');
                        }

                    }


                }

                // terminal
                if (!$hasError) {
                    if ($request_data['leaveTypeID'] == 16) {

                        // processing
                        $query = DB::table('leave_applications');
                        $query = $query->where('userID', $token_userID);
                        $query = $query->whereIn('status', [-2,0,1,2,3]); 
                        $query = $query->get();

                        $pPending       = 0;
                        $pChecked       = 0;
                        $pRecommended   = 0;
                        $pReady         = 0;
                        $pWithdrawing   = 0;
                        $pTotal         = 0;

                        if ($query) {
                            foreach ($query as $q) {
                                if ($q->status == 0) $pPending++;
                                if ($q->status == 1) $pChecked++;
                                if ($q->status == 2) $pRecommended++;
                                if ($q->status == 3) $pReady++;
                                if ($q->status == -2) $pWithdrawing++;
                            } 
                            $pTotal = ($pPending + $pChecked + $pRecommended + $pReady + $pWithdrawing);
                        }

                        $msg = "
                            <br />
                            <table class='table table-bordered mb-0'>
                                <tr>
                                    <td class='text-center' colspan='2'>Requests</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>Pending</td>
                                    <td class='text-start'>{$pPending}</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>Checked</td>
                                    <td class='text-start'>{$pChecked}</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>Recommended</td>
                                    <td class='text-start'>{$pRecommended}</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>Ready</td>
                                    <td class='text-start'>{$pReady}</td>
                                </tr>
                                <tr>
                                    <td class='text-start'>Withdrawing</td>
                                    <td class='text-start'>{$pWithdrawing}</td>
                                </tr>
                                <tr>
                                    <td class='text-start'><b>TOTAL</b></td>
                                    <td class='text-start'><b>{$pTotal}</b></td>
                                </tr>
                            </table>
                            <br />
                            <p class='mb-0'>Please process all outstanding requests.</p>
                        ";

                        if ($query) {
                            $hasError = 1;
                            $data = $this->response->status(409, $msg, 'Invalid Request!');
                        }

                    }
                }

                /** query */
                if (!$hasError) {

                    $dateFiled = date('Y-m-d H:i:s');

                    if (in_array($request_data['leaveTypeID'], [15,16])) {

                        $request_data['leaveDays'] = 0;
                        $request_data['leaveHours'] = 0;
                        $request_data['leaveMinutes'] = 0;

                        // monetize
                        if (in_array($request_data['leaveTypeID'], [15])) {

                            $request_data['leaveDays']      = $request_data['creditsConvertDays'];
                            $request_data['leaveHours']     = $request_data['creditsConvertHours'];
                            $request_data['leaveMinutes']   = $request_data['creditsConvertMinutes'];

                            // ==== 
                            $query = DB::table('user_leave_credits');
                            $query = $query->where('userID', $token_userID);
                            $query = $query->first();
                            if ($query) {
                                $request_data['creditsToMonetizeVL']    = $query->creditsVacation - $request_data['creditsToMonetizeVL'];
                                $request_data['creditsToMonetizeSL']    = $query->creditsSick - $request_data['creditsToMonetizeSL'];
                            }

                        }

                    } else {
                        $request_data['creditsToMonetizeVL']    = 0;
                        $request_data['creditsToMonetizeSL']    = 0;
                        $request_data['creditsToMonetize']      = 0;
                    }

                    $leaveWorkingDays = ''; 
                    if ($request_data['leaveDays']) $leaveWorkingDays .= ($leaveWorkingDays?(" ".$request_data['leaveDays']."d"):($request_data['leaveDays']."d"));
                    if ($request_data['leaveHours']) $leaveWorkingDays .= ($leaveWorkingDays?(" ".$request_data['leaveHours']."h"):($request_data['leaveHours']."h"));
                    if ($request_data['leaveMinutes']) $leaveWorkingDays .= ($leaveWorkingDays?(" ".$request_data['leaveMinutes']."m"):($request_data['leaveMinutes']."m"));

                    $creditsVacationEarned  = 0; 
                    $creditsVacationLess    = 0; 
                    $creditsVacationBalance = 0; 
                    $creditsSickEarned      = 0; 
                    $creditsSickLess        = 0; 
                    $creditsSickBalance     = 0; 
                    $creditsStatusAsOfMonth = ''; 

                    $datesInclusive = ''; 

                    $addToLeaveLedger   = 0; 
                    $period             = '';  
                    $particulars        = '';  
                    $vacationWithPay    = 0; 
                    $vacationWithoutPay = 0; 
                    $sickWithPay        = 0; 
                    $sickWithoutPay     = 0; 
                    $remarks            = '';  

                    $checkedBy                      = 0; 
                    $checkerUserEmploymentID        = 0; 
                    $approvedBy                     = 0; 
                    $approverUserEmploymentID       = 0; 
                    $disapprovedBy                  = 0; 
                    $disapproverUserEmploymentID    = 0; 
                    $disapproveRemarks              = ''; 
                    $approvalType                   = 0; 
                    $approvalDetail                 = ''; 
                    $status                         = 0; 

                    $amount = 0; 
                    if (in_array($request_data['leaveTypeID'], [15])) $amount = (float) $request_data['creditsToMonetize'] * $userEmploymentSalary * $constantFactor; 

                    if ($request_data['leaveTypeID'] == 15)

                    // remove unnecessary fields
                    $request_fields = array_diff($request_fields, ['dateCTOFrom']);
                    $request_fields = array_diff($request_fields, ['dateCTOTo']);
                    unset($request_data['dateCTOFrom']);
                    unset($request_data['dateCTOTo']);
                    unset($request_data['creditsConvertDays']);
                    unset($request_data['creditsConvertHours']);
                    unset($request_data['creditsConvertMinutes']);

                    // 
                    $request_data['userID']                 = $token_userID;
                    $request_data['userEmploymentID']       = $userEmploymentID;
                    $request_data['userEmploymentSalary']   = $userEmploymentSalary;
                    $request_data['constantFactor']         = $constantFactor;
                    $request_data['dateFiled']              = $dateFiled;
                    $request_data['leaveWorkingDays']       = $leaveWorkingDays;

                    $request_data['creditsVacationEarned']  = $creditsVacationEarned;
                    $request_data['creditsVacationLess']    = $creditsVacationLess;
                    $request_data['creditsVacationBalance'] = $creditsVacationBalance;
                    $request_data['creditsSickEarned']      = $creditsSickEarned;
                    $request_data['creditsSickLess']        = $creditsSickLess;
                    $request_data['creditsSickBalance']     = $creditsSickBalance;
                    $request_data['creditsStatusAsOfMonth'] = $creditsStatusAsOfMonth;

                    $request_data['datesInclusive']   = $datesInclusive;

                    $request_data['addToLeaveLedger']   = $addToLeaveLedger;
                    $request_data['period']             = $period;
                    $request_data['particulars']        = $particulars;
                    $request_data['vacationWithPay']    = $vacationWithPay;
                    $request_data['vacationWithoutPay'] = $vacationWithoutPay;
                    $request_data['sickWithPay']        = $sickWithPay;
                    $request_data['sickWithoutPay']     = $sickWithoutPay;
                    $request_data['remarks']            = $remarks;

                    $request_data['amount'] = $amount;

                    $request_data['checkedBy']                      = $checkedBy;
                    $request_data['checkerUserEmploymentID']        = $checkerUserEmploymentID;
                    $request_data['recommendedBy']                  = $recommendedBy;
                    $request_data['recommenderUserEmploymentID']    = $recommenderUserEmploymentID;
                    $request_data['approvedBy']                     = $approvedBy;
                    $request_data['approverUserEmploymentID']       = $approverUserEmploymentID;
                    $request_data['disapprovedBy']                  = $disapprovedBy;
                    $request_data['disapproverUserEmploymentID']    = $disapproverUserEmploymentID;
                    $request_data['disapproveRemarks']              = $disapproveRemarks;
                    $request_data['approvalType']                   = $approvalType;
                    $request_data['approvalDetail']                 = $approvalDetail;
                    $request_data['status']                         = $status;

                    $pkID = DB::table($this->table)->insertGetId($request_data);
                    if ($pkID) {
                        // return encrypted id
                        $id = Crypt::encryptString("{$pkID}");
                        // // insert audit logs
                        // $logFields = $request_fields;
                        // $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], $this->table, $this->tablePrimaryKey, $pkID, $request_data, "Inserted {$this->logTitle} Record", $logFields, 1);

                        // Save file here 
                        if ($request->hasFile('files')) {
                            
                            $destinationPath .= md5($pkID);
                            if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

                            $files = $request->file('files');
                            if ($files) {
                                $count = 0;
                                foreach ($files as $file) {
                                    $count++;
                                    $extension = $file->getClientOriginalExtension();
                                    $filename = "File{$count}.{$extension}";
                                    $file->move($destinationPath, $filename);
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
                            'creditsVacationEarned'     => number_format($query->creditsVacationEarned, 3), 
                            'creditsVacationLess'       => number_format($query->creditsVacationLess, 3), 
                            'creditsVacationBalance'    => number_format($query->creditsVacationBalance, 3), 
                            'creditsSickEarned'         => number_format($query->creditsSickEarned, 3), 
                            'creditsSickLess'           => number_format($query->creditsSickLess, 3), 
                            'creditsSickBalance'        => number_format($query->creditsSickBalance, 3), 
                            'dateInserted'              => ($query->dateFiled ? date('m/d/Y h:ia', strtotime($query->dateFiled)) : ''),
                            'applicant'                 => $applicant, 
                            'recommender'               => $recommender, 
                            'checker'                   => $checker, 
                            'approver'                  => $approver, 
                            'disapprover'               => $disapprover, 
                            'comment'                   => $query->disapproveRemarks, 
                            'approvalType'              => $query->approvalType, 
                            'approvalTypeDetail'        => $query->approvalDetail, 
                            'files'                     => $files, 
                            'status'                    => $query->status, 
                        ];

                        /** final variables */
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

}


