<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

use App\Libraries\Response;
use App\Libraries\PasswordHelper;

class ProfileSetupController extends MasterController
{

    protected $response;
    protected $data;
    protected $module;
    protected $controller;
    protected $page;
    protected $view_path;

    public function __construct()
    {
        $this->response         = new Response();
        $this->module       = 'Profile Setup';
        $this->controller   = 'profile-setup';
        $this->page         = '';
        $this->view_path    = 'modules/'.strtolower(str_replace(" ", "_", $this->module));  
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

    // ******************** APIs ********************
    public function put_page(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $row        = [];
            $hasError   = 0;
    
            /** query */
            if (!$hasError) {
                $query = DB::table('user_starting_informations');
                $query = $query->select(
                    'user_starting_informations.*', 
                    'offices.code as oCode', 
                    'offices.name as oName', 
                    'JobPositions.code as jpCode', 
                    'JobPositions.name as jpName', 
                    'user_employment_types.name as uetName', 
                );
                $query = $query->leftJoin('offices', 'user_starting_informations.officeID', '=', 'offices.officeID');
                $query = $query->leftJoin('JobPositions', 'user_starting_informations.jobPositionID', '=', 'JobPositions.jobPositionID');
                $query = $query->leftJoin('user_employment_types', 'user_starting_informations.userEmploymentTypeID', '=', 'user_employment_types.userEmploymentTypeID');
                $query = $query->where('user_starting_informations.userID', $token_userID);
                $query = $query->first();
                if ($query) {
                    $row = [
                        'idNumber' => $query->idNumber, 
                        'lname' => ucwords($query->lname), 
                        'fname' => ucwords($query->fname), 
                        'mname' => ucwords($query->mname), 
                        'uetName' => $query->uetName, 
                        'salaryMonthly' => number_format($query->salaryMonthly, 2), 
                        'salaryYearly' => number_format($query->salaryYearly, 2), 
                        'office' => "{$query->oName}", 
                        'position' => "{$query->jpName}", 
                    ];

                    /** final variables */
                    $items['row'] = $row;

                    $data['items'] = $items;

                } else {
                    $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                }
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 

    public function put(Request $request)
    {

        $data = $this->response->status(200);

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            $isPending = DB::table('users')->where('userID', $token_userID)->where('status', 0)->count();

            if ($isPending) {

                /** fields */
                $request_fields0 = ['passwordCon'];
                $request_fields1 = ['username', 'password'];
                $request_fields2 = ['bankAccountName', 'bankAccountNumber'];
                $request_fields3 = [
                    'fname', 
                    'mname', 
                    'lname', 
                    'gender', 
                    'civilStatus', 
                    'citizenship', 
                    'birthDate', 
                    'birthPlace', 
                    'phone', 
                    'email', 
                    'gsis', 
                    'pagibig', 
                    'philhealth', 
                    'sss', 
                    'tin', 
                    'bloodType', 
                    'permProvinceID', 
                    'permCityID', 
                    'permBarangayID', 
                    'permStreet', 
                ];
        
                /** variables */
                $request_data       = [];
                $request_data0      = [];
                $request_data1      = [];
                $request_data2      = [];
                $request_data3      = [];
                $request_data4      = [];
                $hasError           = 0; 
                $requires           = '';
                $required_fields    = [
                    'username'      => 'Username', 
                    'password'      => 'New Password', 
                    'passwordCon'   => 'Confirm New Password', 
                    'fname'         => 'First Name', 
                    'lname'         => 'Last Name', 
                    'birthDate'     => 'Birthday', 
                    'birthPlace'    => 'Birth Place', 
                ]; 
        
                /** data */
                if ($request_fields0) {
                    foreach ($request_fields0 as $field) {
                        $request_data[$field] = $request->input($field)?$request->input($field):'';
                        $request_data0[$field] = $request->input($field)?$request->input($field):'';
                    } 
                } 
                if ($request_fields1) {
                    foreach ($request_fields1 as $field) {
                        $request_data[$field] = $request->input($field)?$request->input($field):'';
                        $request_data1[$field] = $request->input($field)?$request->input($field):'';
                    } 
                } 
                if ($request_fields2) {
                    foreach ($request_fields2 as $field) {
                        $request_data[$field] = $request->input($field)?$request->input($field):'';
                        $request_data2[$field] = $request->input($field)?$request->input($field):'';
                    } 
                } 
                if ($request_fields3) {
                    foreach ($request_fields3 as $field) {
                        $value = $request->input($field)?$request->input($field):'';
                        if (in_array($field, ['bloodType', 'permProvinceID', 'permCityID', 'permBarangayID', 'gender'])) $value = $request->input($field)?$request->input($field):0;
                        $request_data[$field] = $value;
                        $request_data3[$field] = $value;
                    }
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
        
                // duplicate username
                if (!$hasError) {
                    $hasDuplicate = DB::table('users');
                    $hasDuplicate = $hasDuplicate->whereNot('userID', $token_userID);
                    $hasDuplicate = $hasDuplicate->where('username', $request_data['username']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Username already exist.', 'Invalid!');
                    }
                } 
        
                // duplicate name
                if (!$hasError) {
                    $hasDuplicate = DB::table('user_personal_informations');
                    $hasDuplicate = $hasDuplicate->whereNot('userID', $token_userID);
                    $hasDuplicate = $hasDuplicate->where('fname', $request_data['fname']);
                    $hasDuplicate = $hasDuplicate->where('lname', $request_data['lname']);
                    $hasDuplicate = $hasDuplicate->count();
                    if ($hasDuplicate) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'First Name and Last Name already exist.', 'Invalid!');
                    }
                } 
    
                // password does not match 
                if (!$hasError) {
                    if ($request_data['password'] != $request_data['passwordCon']) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'New password does not match.', 'Invalid!');
                    }
                } 
    
                // password requirements
                if (!$hasError) {
                    if (!PasswordHelper::_isValidPassword($request_data['password'])) {
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
    
                    // USERS
                    $table = 'users';
                    $tablePrimaryKey = 'userID';

                    $query = DB::table($table)->where($tablePrimaryKey, $token_userID)->first();

                    $dateInserted = null;
                    $dateDeactivated = null;
                    $userTypeID = 0;
                    if ($query) {
                        $dateInserted       = $query->dateInserted;
                        $dateDeactivated    = $query->dateDeactivated;
                        $userTypeID         = $query->userTypeID;
                    }
    
                    $query = DB::table($table)->where($tablePrimaryKey, $token_userID); 
                    if ($query) { 
     
                        $request_data1['password'] = bcrypt($request_data1['password']); 
                        $request_data1['dateActivated'] = date('Y-m-d H:i:s'); 
                        $request_data1['status'] = 1; 
                        $request_data1['dateInserted'] = $dateInserted; 
                        $request_data1['dateDeactivated'] = $dateDeactivated; 
                        $request_data1['userTypeID'] = $userTypeID; 
    
                        $request_fields1[] = 'dateActivated'; 
                        $request_fields1[] = 'status'; 
                        $request_fields1[] = 'dateInserted'; 
                        $request_fields1[] = 'dateDeactivated'; 
                        $request_fields1[] = 'userTypeID'; 
    
                        // update record
                        $query->update($request_data1);
                        
                        // update audit logs
                        $logFields = $request_fields1;
                        $this->_auditLog($request_token['data'], 70, $table, $tablePrimaryKey, $token_userID, $request_data1, "Updated User Record", $logFields, 1);
                        
                        // EMPLOYMENT
                        $table = 'user_employments';
                        $tablePrimaryKey = 'userEmploymentID';
    
                        $query = DB::table('user_starting_informations');
                        $query = $query->where('userID', $token_userID);
                        $query = $query->first();
    
                        $dateAppointed = null;
                        if ($query) {
                            // 
                            $request_data2['userID']                            = $token_userID;
                            $request_data2['userEmploymentTypeID']              = $query->userEmploymentTypeID;
                            $request_data2['idNumber']                          = $query->idNumber;
                            $request_data2['officeID']                          = $query->officeID;
                            $request_data2['jobPositionID']                     = $query->jobPositionID;
                            $request_data2['salaryMonthly']                     = $query->salaryMonthly;
                            $request_data2['salaryYearly']                      = $query->salaryYearly;
                            $request_data2['dateAppointed']                     = $dateAppointed = $query->dateAppointed;
                            $request_data2['cause']                             = 0;
                            $request_data2['remarks']                           = '';
                            $request_data2['status']                            = 1;
    
                            // 
                            $request_fields2[] = 'userID';
                            $request_fields2[] = 'userEmploymentTypeID';
                            $request_fields2[] = 'idNumber';
                            $request_fields2[] = 'officeID';
                            $request_fields2[] = 'jobPositionID';
                            $request_fields2[] = 'salaryMonthly';
                            $request_fields2[] = 'salaryYearly';
                            $request_fields2[] = 'cause';
                            $request_fields2[] = 'remarks';
                            $request_fields2[] = 'status';
                        }
                        
                        $userEmploymentID = DB::table($table)->insertGetId($request_data2);
                        if ($userEmploymentID) {
                            // insert audit logs
                            $logFields = $request_fields2;
                            $this->_auditLog($request_token['data'], 70, $table, $tablePrimaryKey, $userEmploymentID, $request_data2, "Inserted Employement Record", $logFields, 1);
                        }
    
                        // PERSONAL INFORMATION 
                        $table = 'user_personal_informations';
                        $tablePrimaryKey = 'userPersonalInformationID';

                        $request_data3['userID'] = $token_userID;
                        $request_data3['otp'] = '';
                        $request_data3['picExt'] = '';
                        $request_data3['userPdsChangeRequestDetailID'] = 0;
                        $request_fields3[] = 'userID';
                        $request_fields3[] = 'otp';
                        $request_fields3[] = 'picExt';
                        
                        $userPersonalInformationID = DB::table($table)->insertGetId($request_data3);
                        if ($userPersonalInformationID) {
                            // insert audit logs
                            $logFields = $request_fields2;
                            $this->_auditLog($request_token['data'], 70, $table, $tablePrimaryKey, $userPersonalInformationID, $request_fields3, "Inserted Employee Personal Information Record", $logFields, 1);
                        }

                        // LEAVE CREDITS 
                        $table = 'user_leave_credits';
                        $tablePrimaryKey = 'userLeaveCreditID';

                        $request_data4 = [
                            'userID'            => $token_userID, 
                            'creditsVacation'   => 0, 
                            'creditsSick'       => 0, 
                        ];
                        $request_fields4 = ['userID', 'creditsVacation', 'creditsSick'];
                        
                        $userLeaveCreditID = DB::table($table)->insertGetId($request_data4);
                        if ($userLeaveCreditID) {
                            

                            // // insert audit logs
                            // $logFields = $request_fields2;
                            // $this->_auditLog($request_token['data'], 70, $table, $tablePrimaryKey, $userLeaveCreditID, $request_fields4, "Inserted Employee Personal Information Record", $logFields, 1);

                            $table = 'user_leave_credit_details';
                            $tablePrimaryKey = 'userLeaveCreditDetailID';

                            $period         = '';
                            $vacationEarned = 0;
                            $sickEarned     = 0;

                            $dateDayStarted = date('j', strtotime($dateAppointed))+0;

                            if (!in_array($dateDayStarted, [0, 31])) {

                                // period
                                $periodMonth    = date('m');
                                $periodLastDay  = date('t');
                                $periodYear     = date('y'); 
                                
                                $period = "$periodMonth/$dateDayStarted/$periodYear"; 
                                if ($dateDayStarted != $periodLastDay) { 
                                    $period = "$periodMonth/$dateDayStarted-$periodLastDay/$periodYear"; 
                                } 
                                
                                $periodMaxDay   = date('t', strtotime($dateAppointed))+0;
                                $dayToGet       = $periodMaxDay-$dateDayStarted;

                                // earned credits
                                $query = DB::table('leave_credit_earnings')->where('days', $dayToGet)->first();
                                if ($query) {
                                    $vacationEarned = $query->vacation; 
                                    $sickEarned     = $query->sick; 
                                }

                            }

                            $request_data5 = [
                                'userLeaveCreditID'             => $userLeaveCreditID, 
                                'userEmploymentID'              => $userEmploymentID, 
                                'period'                        => $period, 
                                'particulars'                   => 'Earned Leave', 
                                'vacationEarned'                => $vacationEarned, 
                                'vacationUndertimeWithPay'      => 0, 
                                'vacationBalance'               => $vacationEarned, 
                                'vacationUndertimeWithoutPay'   => 0, 
                                'sickEarned'                    => $sickEarned, 
                                'sickUndertimeWithPay'          => 0, 
                                'sickBalance'                   => $sickEarned, 
                                'sickUndertimeWithoutPay'       => 0, 
                                'remarks'                       => '', 
                                'dateInserted'                  => date('Y-m-d H:i:s'), 
                                'dateAccounted'                 => date('Y-m-d'), 
                            ];
                            $request_fields5 = [
                                'userLeaveCreditID', 
                                'userEmploymentID', 
                                'period', 
                                'particulars', 
                                'vacationEarned', 
                                'vacationUndertimeWithPay', 
                                'vacationBalance', 
                                'vacationUndertimeWithoutPay', 
                                'sickEarned', 
                                'sickUndertimeWithPay', 
                                'sickBalance', 
                                'sickUndertimeWithoutPay', 
                                'remarks', 
                                'dateInserted', 
                            ];
                            $userLeaveCreditDetailID = DB::table($table)->insertGetId($request_data5);

                            $query = DB::table('user_leave_credits')->where('userLeaveCreditID', $userLeaveCreditID)->first();
                            if ($query) {

                                $newVacationEarned  = $vacationEarned + $query->creditsVacation; 
                                $newSickEarned      = $sickEarned + $query->creditsSick; 

                                DB::table('user_leave_credits') 
                                    ->where('userLeaveCreditID', $userLeaveCreditID) 
                                    ->update([ 
                                        'creditsVacation'   => $newVacationEarned, 
                                        'creditsSick'       => $newSickEarned, 
                                    ]); 
                            }

                        }

                        // payroll deductions
                        $table = 'user_payroll_deductions';
                        $tablePrimaryKey = 'userPayrollDeductionID';

                        $request_data4 = [];
                        $request_data4['userID'] = $token_userID;
                        $request_data4['amount'] = 0;
                        $request_fields4[] = 'userID';
                        $request_fields4[] = 'amount';

                        $userPayrollDeductionID = DB::table($table)->insertGetId($request_data4);
                        if ($userPayrollDeductionID) {

                            // details
                            $payroll_deduction_types = DB::table('payroll_deduction_types')->get();
                            if ($payroll_deduction_types) {
                                foreach ($payroll_deduction_types as $pdt) {
                                    DB::table('user_payroll_deduction_details')->insert([
                                        'userPayrollDeductionID' => $userPayrollDeductionID, 
                                        'payrollDeductionTypeID' => $pdt->payrollDeductionTypeID, 
                                        'amount' => 0, 
                                    ]);
                                }
                            }

                            // // insert audit logs
                            // $logFields = $request_fields2;
                            // $this->_auditLog($request_token['data'], 70, $table, $tablePrimaryKey, $userPayrollDeductionID, $request_fields4, "Inserted Employee Personal Information Record", $logFields, 1);
                        }

                    } else {
                        $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                    }
                } 
        
            } else {
                $data = $this->response->status(409, 'Unknown record.', 'Invalid!');
            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    // others
    public function get_provinces(Request $request)
    {

        $data = $this->response->status(200);
        $items = [];
        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $items['provinces'] = DB::table('provinces')->orderBy('name', 'asc')->get();
            $data['items'] = $items;
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }
        return response()->json($data);

    } 
    public function get_cities(Request $request, string $id)
    {

        $data = $this->response->status(200);
        $items = [];
        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $items['cities'] = DB::table('cities')->where('provinceID', $id)->orderBy('name', 'asc')->get();
            $data['items'] = $items;
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }
        return response()->json($data);

    } 
    public function get_barangays(Request $request, string $id)
    {

        $data = $this->response->status(200);
        $items = [];
        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $items['barangays'] = DB::table('barangays')->where('cityID', $id)->orderBy('name', 'asc')->get();
            $data['items'] = $items;
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }
        return response()->json($data);

    } 

}

