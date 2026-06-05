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

class MyProfileController extends MasterController
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
        $this->module           = 'My Profile';
        $this->controller       = 'my-profile';
        $this->logTitle         = 'My Profile';
        $this->table            = 'users';
        $this->tablePrimaryKey  = 'userID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'     => 1, 
            'PrintList' => 0, 
            'Insert'    => 0, 
            'View'      => 0, 
            'Audit'     => 0, 
            'Update'    => 25, 
            'Delete'    => 0, 
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

    // PRINT 
    public function print_pds(Request $request, string $id)
    {

        // initialize variables
        $this->page = 'Print PDS';
        $this->_setVariables();
        $data = $this->data;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function print_leave_ledger_card(Request $request, string $id)
    {

        // initialize variables
        $this->page = 'Print Leave Ledger Card';
        $this->_setVariables();
        $data = $this->data;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function print_leave_application(Request $request, string $id)
    {

        // initialize variables
        $this->page = 'Print Leave Application';
        $this->_setVariables();
        $data = $this->data;

        $data['headerImage1'] = $this->_convertImageToBase64('assets/img/logos/lgu.png');

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function print_travel_order(Request $request, string $id)
    {

        // initialize variables
        $this->page = 'Print Travel Order';
        $this->_setVariables();
        $data = $this->data;

        $data['headerImage1'] = $this->_convertImageToBase64('assets/img/logos/lgu.png');

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function print_travel_report(Request $request, string $id)
    {

        // initialize variables
        $this->page = 'Print Travel Report';
        $this->_setVariables();
        $data = $this->data;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function print_dtr(Request $request, string $id)
    {

        // initialize variables
        $this->page = 'Print DTR';
        $this->_setVariables();
        $data = $this->data;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function print_payslip(Request $request, string $id)
    {

        // initialize variables
        $this->page = 'Print Payslip';
        $this->_setVariables();
        $data = $this->data;

        $data['headerImage1'] = $this->_convertImageToBase64('assets/img/logos/lgu.png');

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function print_general_payroll(Request $request, string $id)
    {

        // initialize variables
        $this->page = 'Print General Payroll';
        $this->_setVariables();
        $data = $this->data;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }

    // Edit
    public function edit_personal_information(string $id)
    {

        // initialize variables
        $this->page = 'Edit Personal Information';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function edit_family_background(string $id)
    {

        // initialize variables
        $this->page = 'Edit Family Background';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function edit_educational_background(string $id)
    {

        // initialize variables
        $this->page = 'Edit Educational Background';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function edit_civil_service_eligibilities(string $id)
    {

        // initialize variables
        $this->page = 'Edit Civil Service Eligibilities';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function edit_work_experiences(string $id)
    {

        // initialize variables
        $this->page = 'Edit Work Experiences';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function edit_training_programs(string $id)
    {

        // initialize variables
        $this->page = 'Edit Training Programs';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }

    // Changes
    public function changes_personal_information(string $id)
    {

        // initialize variables
        $this->page = 'Change Requests Personal Information';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function changes_family_background(string $id)
    {

        // initialize variables
        $this->page = 'Change Requests Family Background';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function changes_educational_background(string $id)
    {

        // initialize variables
        $this->page = 'Change Requests Educational Background';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function changes_civil_service_eligibilities(string $id)
    {

        // initialize variables
        $this->page = 'Change Requests Civil Service Eligibilities';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function changes_work_experiences(string $id)
    {

        // initialize variables
        $this->page = 'Change Requests Work Experiences';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }
    public function changes_training_programs(string $id)
    {

        // initialize variables
        $this->page = 'Change Requests Training Programs';
        $this->_setVariables();
        $data = $this->data;

        $data['id'] = $id;

        return view($this->view_path."/".($this->page?strtolower(str_replace(' ', '_', $this->page)):'index'), $data);

    }

    // ******************** APIs ********************
    public function signature_post(Request $request)
    {

        $data = $this->response->status(200);
        
        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];
            $base64Image = $request->input('signature');

            if ($base64Image) {
                // create path if not exist
                // $destinationPath = base_path('public_html/uploads/signatures/');
                $destinationPath = base_path('public/uploads/signatures/');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true); 
                // signature file name
                $filename = md5($token_userID).".png"; 
                // decode base64 image
                $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
                $imageData = base64_decode($imageData);
                // upload
                file_put_contents($destinationPath.$filename, $imageData);
            } else {
                $data = $this->response->status(409, 'Please draw your new signature.', 'Invalid!');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 
    public function signature_get(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            $signaturePath = public_path('uploads/signatures/').md5($token_userID).'.png';
            $items['signature'] = (File::exists($signaturePath)) ? asset('uploads/signatures/' . md5($token_userID).'.png')."?t=".time() : '';
            $data['items'] = $items;
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);

    } 
    public function signature_upload(Request $request)
    {

        $data = $this->response->status(200);
        
        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];
            $base64Image = $request->input('signature');

            if ($base64Image) {
                // create path if not exist
                // $destinationPath = base_path('public_html/uploads/signatures/');
                $destinationPath = base_path('public/uploads/signatures/');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true); 
                // signature file name
                $filename = md5($token_userID).".png"; 
                // decode base64 image
                $imageData = substr($base64Image, strpos($base64Image, ',') + 1);
                $imageData = base64_decode($imageData);
                // upload
                file_put_contents($destinationPath.$filename, $imageData);
            } else {
                $data = $this->response->status(409, 'Please draw your new signature.', 'Invalid!');
            }
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    }   

    // VIEW
    public function get_personal_information(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Index'])) {
                $row        = []; 
                $hasError   = 0;
        
        
                /** query */
                if (!$hasError) {

                    $query = DB::table('user_personal_informations');
                    $query = $query->leftjoin('provinces', "user_personal_informations.permProvinceID", '=', 'provinces.provinceID'); 
                    $query = $query->leftjoin('cities', "user_personal_informations.permCityID", '=', 'cities.cityID'); 
                    $query = $query->leftjoin('barangays', "user_personal_informations.permBarangayID", '=', 'barangays.barangayID'); 
                    $query = $query->where("user_personal_informations.userID", $token_userID);
                    $query = $query->select(
                        "user_personal_informations.*", 
                        "provinces.name as pName", 
                        "cities.name as cName", 
                        "barangays.name as bName", 
                    );
                    $query = $query->first();
                    if ($query) {

                        $avatar = asset('assets/img/dp.jpg');
                        if ($query->picExt) $avatar = asset("uploads/users/changes/{$query->userPdsChangeRequestDetailID}{$query->picExt}")."?time=".time();

                        $address = $query->permStreet;
                        if ($query->bName) $address .= $address ? ", {$query->bName}" : $query->bName;
                        if ($query->cName) $address .= $address ? ", {$query->cName}" : $query->cName;
                        if ($query->pName) $address .= $address ? ", {$query->pName}" : $query->pName;

                        $civilStatuses  = ['', 'Single', 'Married', 'Divorced', 'Widowed'];
                        $bloodTypes     = ['', 'O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];

                        $row = [
                            'avatar'            => $avatar, 
                            'fname'             => $query->fname, 
                            'mname'             => $query->mname, 
                            'lname'             => $query->lname, 
                            'gender'            => $query->gender ? 'Male' : 'Female', 
                            'birthDate'         => $query->birthDate ? date('m/d/Y', strtotime($query->birthDate)) : '', 
                            'birthPlace'        => $query->birthPlace, 
                            'citizenship'       => $query->citizenship, 
                            'civilStatus'       => $civilStatuses[$query->civilStatus], 
                            'gsis'              => $query->gsis, 
                            'pagibig'           => $query->pagibig, 
                            'philhealth'        => $query->philhealth, 
                            'sss'               => $query->sss, 
                            'tin'               => $query->tin, 
                            'bloodType'         => $bloodTypes[$query->bloodType], 
                            'phone'             => $query->phone, 
                            'email'             => $query->email, 
                            'address'           => $address, 
                        ];

                        /** final variables */
                        $items['hasButtonAudit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
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
    public function get_family_background(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Index'])) {
                $row        = []; 
                $hasError   = 0;
        
                /** query */
                if (!$hasError) {
                    
                    $userID = $token_userID;
                    if ($userID) {

                        $row = [
                            'spouseName'        => '', 
                            'spouseOccupation'  => '', 
                            'spouseBizName'     => '', 
                            'spouseBizAddress'  => '', 
                            'spouseTelNo'       => '', 
                            'father'            => '', 
                            'mother'            => '', 
                        ];
                        
                        $query = DB::table('user_families');
                        $query = $query->select( "user_families.*" );
                        $query = $query->where("user_families.userID", $userID);
                        $query = $query->first();
                        if ($query) {
                            $row['spouseName']          = "{$query->spouseFname} {$query->spouseMname} {$query->spouseLname}";
                            $row['spouseOccupation']    = $query->spouseOccupation;
                            $row['spouseBizName']       = $query->spouseBizName;
                            $row['spouseBizAddress']    = $query->spouseBizAddress;
                            $row['spouseTelNo']         = $query->spouseTelNo;
                            $row['father']              = "{$query->fatherFname} {$query->fatherMname} {$query->fatherLname}";
                            $row['mother']              = "{$query->motherFname} {$query->motherMname} {$query->motherLname}";
                        } 

                        $query = DB::table('user_childrens');
                        $query = $query->select( "user_childrens.*" );
                        $query = $query->where("user_childrens.userID", $userID);
                        $query = $query->orderBy("user_childrens.birthDate", 'asc');
                        $query = $query->get();

                        $childrens = [];
                        if ($query) {
                            foreach ($query as $q) {
                                $childrens[] = [
                                    'name'      => $q->name, 
                                    'birthDate' => $q->birthDate ? date('m/d/Y', strtotime($q->birthDate)) : '', 
                                ];
                            }
                        }
                        $row['childrens'] = $childrens;

                        /** final variables */
                        $items['hasButtonAudit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
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
    public function get_educational_background(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Index'])) {
                
                $educations = [];
                $row        = []; 
                $hasError   = 0;

                $educationLevels = [
                    '', 
                    'ELEMENTARY',  
                    'SECONDARY',  
                    'VOCATIONAL/ TRADE COURSE',  
                    'COLLEGE',  
                    'GRADUATE STUDIES',  
                ];

                /** query */
                if (!$hasError) {
                    
                    $userID = $token_userID;
                    if ($userID) {

                        $educations = [];

                        for ($i=1; $i<=5; $i++) {
                            $query = DB::table('user_educations');
                            $query = $query->where('userID', $userID);
                            $query = $query->where('type', $i);
                            $query = $query->first();

                            $level              = $educationLevels[$i];
                            $schoolName         = "";
                            $degree             = "";
                            $dateAttendedFrom   = "";
                            $dateAttendedTo     = "";
                            $highestLevelEarned = "";
                            $yearGraduated      = "";
                            $scholarship        = "";

                            if ($query) {
                                $schoolName         = $query->schoolName;
                                $degree             = $query->degree;
                                $dateAttendedFrom   = $query->dateAttendedFrom ? date('m/d/Y', strtotime($query->dateAttendedFrom)) : '';
                                $dateAttendedTo     = $query->dateAttendedTo ? date('m/d/Y', strtotime($query->dateAttendedTo)) : '';
                                $highestLevelEarned = $query->highestLevelEarned;
                                $yearGraduated      = $query->yearGraduated;
                                $scholarship        = $query->scholarship;
                            }

                            $educations[] = [
                                'level'                 => $level, 
                                'schoolName'            => $schoolName, 
                                'degree'                => $degree, 
                                'dateAttendedFrom'      => $dateAttendedFrom, 
                                'dateAttendedTo'        => $dateAttendedTo, 
                                'highestLevelEarned'    => $highestLevelEarned, 
                                'yearGraduated'         => $yearGraduated, 
                                'scholarship'           => $scholarship, 
                            ];

                        }
                        $row['educations'] = $educations;

                        /** final variables */
                        $items['hasButtonAudit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
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
    public function get_civil_service_eligibilities(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Index'])) {
                $row        = []; 
                $hasError   = 0;
        
                /** query */
                if (!$hasError) {
                    
                    $userID = $token_userID;
                    if ($userID) {

                        
                        $query = DB::table('user_civil_services');
                        $query = $query->where('userID', $userID);
                        $query = $query->orderBy('userCivilServiceID', 'asc');
                        $query = $query->get();
                        
                        $civilServices = [];
                        if ($query) {
                            foreach ($query as $q) {
                                $civilServices[] = [
                                    'name'                  => $q->name, 
                                    'rating'                => $q->rating,  
                                    'dateExamination'       => $q->dateExamination ? date('m/d/Y', strtotime($q->dateExamination)) : '', 
                                    'placeExamination'      => $q->placeExamination, 
                                    'licenseNumber'         => $q->licenseNumber, 
                                    'licenseDateValidity'   => $q->licenseDateValidity, 
                                ];
                            }
                        }
                        $row['civilServices'] = $civilServices;

                        /** final variables */
                        $items['hasButtonAudit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
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
    public function get_work_experiences(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Index'])) {
                $row        = []; 
                $hasError   = 0;
        
                /** query */
                if (!$hasError) {
                    
                    $userID = $token_userID;
                    if ($userID) {

                        
                        $query = DB::table('user_works');
                        $query = $query->where('userID', $userID);
                        $query = $query->orderBy('dateFrom', 'asc');
                        $query = $query->get();
                        
                        $workExperiences = [];
                        if ($query) {
                            foreach ($query as $q) {
                                $workExperiences[] = [
                                    'dateFrom'          => $q->dateFrom ? date('m/d/Y', strtotime($q->dateFrom)) : '', 
                                    'dateTo'            => $q->dateTo ? date('m/d/Y', strtotime($q->dateTo)) : '', 
                                    'position'          => $q->position,  
                                    'company'           => $q->company, 
                                    'salary'            => $q->salary ? number_format($q->salary, 2) : '', 
                                    'salaryGrade'       => $q->salaryGrade, 
                                    'appointmentStatus' => $q->appointmentStatus, 
                                    'isGovt'            => $q->isGovt ? 'Yes' : 'No', 
                                ];
                            }
                        }
                        $row['workExperiences'] = $workExperiences;

                        /** final variables */
                        $items['hasButtonAudit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
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
    public function get_training_programs(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Index'])) {
                $row        = []; 
                $hasError   = 0;
        
                /** query */
                if (!$hasError) {
                    
                    $userID = $token_userID;
                    if ($userID) {

                        
                        $query = DB::table('user_trainings');
                        $query = $query->where('userID', $userID);
                        $query = $query->orderBy('dateFrom', 'asc');
                        $query = $query->get();
                        
                        $trainingPrograms = [];
                        if ($query) {
                            foreach ($query as $q) {
                                $trainingPrograms[] = [
                                    'trainingName'  => $q->trainingName,  
                                    'dateFrom'      => $q->dateFrom ? date('m/d/Y', strtotime($q->dateFrom)) : '', 
                                    'dateTo'        => $q->dateTo ? date('m/d/Y', strtotime($q->dateTo)) : '', 
                                    'hours'         => $q->hours, 
                                    'ldType'        => $q->ldType, 
                                    'sponsor'       => $q->sponsor, 
                                ];
                            }
                        }
                        $row['trainingPrograms'] = $trainingPrograms;

                        /** final variables */
                        $items['hasButtonAudit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
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
    public function get_201_files(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $userID = $request_token['data'];

            // check access
            if ($this->_checkAccess($userID, $this->moduleActionIDs['Index'])) {

                $row        = []; 
                $hasError   = 0;
        
                /** query */
                if (!$hasError) {
                    
                    
                    $query = DB::table('user_201_files');
                    $query = $query->select(
                        "user_201_files.*", 
                        "user_201_file_types.name as u2ftName", 
                    );
                    $query = $query->leftjoin('user_201_file_types', "user_201_files.user201FileTypeID", '=', 'user_201_file_types.user201FileTypeID'); 
                    $query = $query->where('user_201_files.userID', $userID);
                    $query = $query->orderBy('user_201_files.date', 'asc');
                    $query = $query->get();
                    
                    $user_201_files = [];
                    if ($query) {
                        foreach ($query as $q) {

                            $temp_arr = [
                                'user201FileID' => Crypt::encryptString("{$q->user201FileID}"), 
                                'type'          => $q->u2ftName,  
                                'date'          => $q->date ? date('M d/y', strtotime($q->date)) : '',  
                            ];

                            $folderPath = public_path("uploads/users/".md5($userID)."/".md5($q->user201FileID)."/");

                            $fileNames = [];
                            if (File::exists($folderPath)) {
                                $files = File::files($folderPath); 
                                foreach ($files as $file) {
                                    $fileNames[] = [
                                        'name' => $file->getFilename(), 
                                        'link' => asset("uploads/users/".md5($userID)."/".md5($q->user201FileID)."/".$file->getFilename()),
                                    ]; 
                                }
                            }
                            $temp_arr['fileNames'] = $fileNames;

                            $user_201_files[] = $temp_arr;

                        }
                    }
                    $row['user_201_files'] = $user_201_files;

                    /** final variables */
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
    public function get_employments(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            $token_userID = $request_token['data'];
            // check access
            if ($this->_checkAccess($token_userID, $this->moduleActionIDs['Index'])) {
                $row        = []; 
                $hasError   = 0;
        
                /** query */
                if (!$hasError) {
                    
                    $userID = $token_userID;

                    if ($userID) {

                        
                        $query = DB::table('user_employments');
                        $query = $query->select(
                            "user_employments.*", 
                            "user_employment_types.name as uetName", 
                            "offices.code as oCode", 
                            "offices.name as oName", 
                            "JobPositions.code as jpCode", 
                            "JobPositions.name as jpName", 
                        );
                        $query = $query->leftjoin('user_employment_types', "user_employments.userEmploymentTypeID", '=', 'user_employment_types.userEmploymentTypeID'); 
                        $query = $query->leftjoin('offices', "user_employments.officeID", '=', 'offices.officeID'); 
                        $query = $query->leftjoin('JobPositions', "user_employments.jobPositionID", '=', 'JobPositions.jobPositionID'); 
                        $query = $query->where('user_employments.userID', $userID);
                        $query = $query->orderBy('user_employments.dateAppointed', 'asc');
                        $query = $query->get();
                        
                        $employments = [];
                        if ($query) {
                            foreach ($query as $q) {
                                $employments[] = [
                                    'dateAppointed' => $q->dateAppointed ? date('m/d/Y', strtotime($q->dateAppointed)) : '',  
                                    'idNumber'      => $q->idNumber,  
                                    'office'        => "{$q->oName}",  
                                    'position'      => "{$q->jpName}",  
                                    // 'office'        => "{$q->oCode} - {$q->oName}",  
                                    // 'position'      => "{$q->jpCode} - {$q->jpName}",  
                                    'type'          => $q->uetName, 
                                    'bankName'      => $q->bankAccountName, 
                                    'bankNumber'    => $q->bankAccountNumber, 
                                    'salaryBasic'   => number_format($q->salaryMonthly, 2), 
                                    'status'        => $q->status, 
                                ];
                            }
                        }
                        $row['employments'] = $employments;

                        /** final variables */
                        $items['hasButtonAudit'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Audit']);
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

    // EDIT
    public function put_page_personal_information(Request $request, string $id)
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
                $query = DB::table('user_personal_informations')->where('userID', $token_userID)->first();
                if ($query) {

                    $avatar = asset('assets/img/dp.jpg');
                    if ($query->picExt) $avatar = asset("uploads/users/changes/{$query->userPdsChangeRequestDetailID}{$query->picExt}")."?time=".time();

                    $row = [
                        'avatar'            => $avatar, 
                        'fname'             => $query->fname, 
                        'mname'             => $query->mname, 
                        'lname'             => $query->lname, 
                        'gender'            => $query->gender, 
                        'birthDate'         => $query->birthDate, 
                        'birthPlace'        => $query->birthPlace, 
                        'citizenship'       => $query->citizenship, 
                        'civilStatus'       => $query->civilStatus, 
                        'gsis'              => $query->gsis, 
                        'pagibig'           => $query->pagibig, 
                        'philhealth'        => $query->philhealth, 
                        'sss'               => $query->sss, 
                        'tin'               => $query->tin, 
                        'bloodType'         => $query->bloodType, 
                        'phone'             => $query->phone, 
                        'email'             => $query->email, 
                        'permProvinceID'    => $query->permProvinceID, 
                        'permCityID'        => $query->permCityID, 
                        'permBarangayID'    => $query->permBarangayID, 
                        'permStreet'        => $query->permStreet, 
                    ];

                    /** final variables */
                    $items['row'] = $row;
                    $items['blood_types'] = [
                        ['bloodType'=>1,'name'=>'O+'], 
                        ['bloodType'=>2,'name'=>'O-'], 
                        ['bloodType'=>3,'name'=>'A+'], 
                        ['bloodType'=>4,'name'=>'A-'], 
                        ['bloodType'=>5,'name'=>'B+'], 
                        ['bloodType'=>6,'name'=>'B-'], 
                        ['bloodType'=>7,'name'=>'AB+'], 
                        ['bloodType'=>8,'name'=>'AB-'], 
                    ];
                    $items['genders'] = [
                        ['gender'=>1,'name'=>'Male'], 
                        ['gender'=>0,'name'=>'Female'], 
                    ];
                    $items['civilStatuses'] = [
                        ['civilStatus'=>1,'name'=>'Single'], 
                        ['civilStatus'=>2,'name'=>'Married'], 
                        ['civilStatus'=>3,'name'=>'Separated'], 
                        ['civilStatus'=>4,'name'=>'Widowed'], 
                    ];

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
    public function put_personal_information(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $token_userID = $request_token['data'];

            /** fields */
            $request_fields = [
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
            $table              = 'user_personal_informations';
            $tablePrimaryKey    = 'userPersonalInformationID';
            $tablePrimaryKeyID  = 0;
            $request_data       = [];
            $request_data       = [];
            $hasError           = 0; 
            $requires           = '';
            $required_fields    = [
                'fname'         => 'First Name', 
                'lname'         => 'Last Name', 
                'birthDate'     => 'Birthday', 
                'birthPlace'    => 'Birth Place', 
            ];
    
            /** data */
            if ($request_fields) {
                foreach ($request_fields as $field) {
                    $request_data[$field] = $request->input($field);
                }
            }
    
            /** check errors */
    
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

            // check if has record
            if (!$hasError) {
                $query = DB::table($table)->where('userID', $token_userID)->first();
                if ($query) {
                    $tablePrimaryKeyID = $query->$tablePrimaryKey;
                } else {
                    $hasError = 1;
                    $data = $this->response->status(409, 'Unknown record.', 'Invalid!');
                }
            }

            // check image file extension 
            $picExt = '';
            if (!$hasError) {
                if ($request->hasFile('croppedImage')) {
                    $file = $request->file('croppedImage');
                    $picExt = $file->getClientOriginalExtension();
                    if (!in_array(strtolower($picExt), $this->allowedImageExtensions)) {
                        $hasError = 1;
                        $data = $this->response->status(409, 'Invalid file format.', 'Opps!');
                    }
                } 
            }

            // duplicate name
            if (!$hasError) {
                $hasDuplicate = DB::table($table);
                $hasDuplicate = $hasDuplicate->whereNot($tablePrimaryKey, $tablePrimaryKeyID);
                $hasDuplicate = $hasDuplicate->where('fname', $request_data['fname']);
                $hasDuplicate = $hasDuplicate->where('lname', $request_data['lname']);
                $hasDuplicate = $hasDuplicate->count();
                if ($hasDuplicate) {
                    $hasError = 1;
                    $data = $this->response->status(409, 'First Name and Last Name already exist.', 'Invalid!');
                }
            }
    
            // query
            if (!$hasError) {

                // field => 'table', 'pkName', 'pkID', 'label'
                $fields = [
                    'fname'             => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'First Name'], 
                    'mname'             => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Middle Name'], 
                    'lname'             => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Last Name'], 
                    'gender'            => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Gender'], 
                    'birthDate'         => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Birthday'], 
                    'birthPlace'        => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Birth Place'], 
                    'citizenship'       => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Citizenship'], 
                    'civilStatus'       => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Civil Status'], 
                    'gsis'              => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'GSIS'], 
                    'pagibig'           => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Pag-IBIG'], 
                    'philhealth'        => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'PhilHealth'], 
                    'sss'               => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'SSS'], 
                    'tin'               => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'TIN'], 
                    'bloodType'         => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Blood Type'], 
                    'phone'             => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Phone'], 
                    'email'             => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Email'], 
                    'permProvinceID'    => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Province'], 
                    'permCityID'        => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Municipality'], 
                    'permBarangayID'    => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Barangay'], 
                    'permStreet'        => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Street'], 
                    'picExt'            => ['user_personal_informations', 'userPersonalInformationID', $tablePrimaryKeyID, 'Display Picture'], 
                ];

                $query = DB::table($table)->where($tablePrimaryKey, $tablePrimaryKeyID)->first();
                if ($query) {

                    // Create change request directory if not exists
                    $destinationPath = public_path('uploads/users/changes/');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true); 
                    }

                    // update all change requests to cancelled
                    $query_side = DB::table('user_pds_change_requests');
                    $query_side = $query_side->where('userID', $token_userID);
                    $query_side = $query_side->where('type', 1); // personal_information
                    $query_side = $query_side->where('status', 0);
                    if ($query_side) {
                        $query_side->update([
                            'dateCancelled' => date('Y-m-d H:i:s'), 
                            'remarks'       => "Record adjusted.", 
                            'status'        => -2
                        ]);
                    }

                    // header
                    $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                        'userID'        => $token_userID, 
                        'dateInserted'  => date('Y-m-d H:i:s'), 
                        'dateCancelled' => null, 
                        'approvedBy'    => 0, 
                        'dateApproved'  => null, 
                        'deniedBy'      => 0, 
                        'dateDenied'    => null, 
                        'remarks'       => '', 
                        'type'          => 1, 
                        'status'        => 0, 
                    ]);
                    
                    if ($userPdsChangeRequestID) {
                        
                        // details
                        $hasChanges = 0;
                        foreach ($query as $field => $value) {
                            if (in_array($field, $request_fields)) {
                                if ($value != $request_data[$field]) {
                                    $hasChanges = 1;
                                    DB::table('user_pds_change_request_details')->insert([
                                        'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                        'tableName'     => $fields[$field][0], 
                                        'primaryKey'    => $fields[$field][1], 
                                        'primaryKeyID'  => $fields[$field][2], 
                                        'label'         => $fields[$field][3], 
                                        'field'         => $field, 
                                        'valueOld'      => $value, 
                                        'valueNew'      => $request_data[$field], 
                                    ]);
                                }
                            }
                        }
                        // upload file if exists
                        if ($picExt) {
                            if ($request->hasFile('croppedImage')) {
                                $hasChanges = 1;
                                $file = $request->file('croppedImage');

                                $field = 'picExt';
                                $userPdsChangeRequestDetailID = DB::table('user_pds_change_request_details')->insertGetId([
                                    'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                    'tableName'     => $fields[$field][0], 
                                    'primaryKey'    => $fields[$field][1], 
                                    'primaryKeyID'  => $fields[$field][2], 
                                    'label'         => $fields[$field][3], 
                                    'field'         => $field, 
                                    'valueOld'      => $query->$field ? "{$query->userPdsChangeRequestDetailID}{$query->$field}" : '', 
                                    'valueNew'      => "", 
                                ]);
                                
                                $newFileName = "{$userPdsChangeRequestDetailID}.{$picExt}";
                                $fullPath = $destinationPath . '/' . $newFileName;

                                // Delete existing file
                                if (file_exists($fullPath)) {
                                    unlink($fullPath); 
                                }

                                // upload file
                                $file->move($destinationPath, $newFileName);

                                // update record 
                                $query_side2 = DB::table('user_pds_change_request_details');
                                $query_side2 = $query_side2->where('userPdsChangeRequestDetailID', $userPdsChangeRequestDetailID);
                                if ($query_side2) {
                                    $query_side2->update([ 'valueNew' => $newFileName ]);
                                }

                            }
                        }
    
                        // delete header if no changes
                        if (!$hasChanges) {
                            DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                        }

                    }

                } else {
                    $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                }
            } 
    
            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    public function put_page_family_background(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];
            
            /** variables */
            $childrens  = [];
            $row        = [];
            $hasError   = 0;
    
            /** check errors */
    
            /** query */
            if (!$hasError) {

                // row
                $row = [
                    'spouseFname'       => "", 
                    'spouseMname'       => "", 
                    'spouseLname'       => "", 
                    'spouseOccupation'  => "", 
                    'spouseBizName'     => "", 
                    'spouseBizAddress'  => "", 
                    'spouseTelNo'       => "", 
                    'fatherFname'       => "", 
                    'fatherMname'       => "", 
                    'fatherLname'       => "", 
                    'motherFname'       => "", 
                    'motherMname'       => "", 
                    'motherLname'       => "", 
                ];
                $query = DB::table('user_families')->where('userID', $token_userID)->first();
                if ($query) {
                    $row = [
                        'spouseFname'       => $query->spouseFname, 
                        'spouseMname'       => $query->spouseMname, 
                        'spouseLname'       => $query->spouseLname, 
                        'spouseOccupation'  => $query->spouseOccupation, 
                        'spouseBizName'     => $query->spouseBizName, 
                        'spouseBizAddress'  => $query->spouseBizAddress, 
                        'spouseTelNo'       => $query->spouseTelNo, 
                        'fatherFname'       => $query->fatherFname, 
                        'fatherMname'       => $query->fatherMname, 
                        'fatherLname'       => $query->fatherLname, 
                        'motherFname'       => $query->motherFname, 
                        'motherMname'       => $query->motherMname, 
                        'motherLname'       => $query->motherLname, 
                    ];
                }

                // 
                $query = DB::table('user_childrens')->where('userID', $token_userID)->orderBy('userChildrenID', 'asc')->get();
                if ($query) {
                    foreach ($query as $q) {
                        $childrens[] = [
                            'userChildrenID'    => $q->userChildrenID, 
                            'name'              => $q->name, 
                            'birthDate'         => $q->birthDate, 
                        ];
                    }
                }

                /** final variables */
                $items['row'] = $row;
                $items['childrens'] = $childrens;

                $data['items'] = $items;

            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 
    public function put_family_background(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** fields */
            $request_fields = [
                'spouseFname', 
                'spouseMname', 
                'spouseLname', 
                'spouseOccupation', 
                'spouseBizName', 
                'spouseBizAddress', 
                'spouseTelNo', 
                'fatherFname', 
                'fatherMname', 
                'fatherLname', 
                'motherFname', 
                'motherMname', 
                'motherLname', 
            ];
    
            /** variables */
            $table              = 'user_families';
            $tablePrimaryKey    = 'userFamilyID';
            $tablePrimaryKeyID  = 0;
            $request_data       = [];
            $request_data       = [];
            $hasError           = 0; 
            $requires           = '';
            $required_fields    = [];
    
            /** data */
            if ($request_fields) {
                foreach ($request_fields as $field) {
                    $request_data[$field] = $request->input($field);
                }
            }
    
            /** check errors */
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

            // check if has record
            if (!$hasError) {
                $query = DB::table($table)->where('userID', $token_userID)->first();
                if ($query) {
                    $tablePrimaryKeyID = $query->$tablePrimaryKey;
                } else {
                    $hasError = 1;
                    $data = $this->response->status(409, 'Unknown record.', 'Invalid!');
                }
            }

            // query
            if (!$hasError) {

                $request_fields = [
                    'spouseFname', 
                    'spouseMname', 
                    'spouseLname', 
                    'spouseOccupation', 
                    'spouseBizName', 
                    'spouseBizAddress', 
                    'spouseTelNo', 
                    'fatherFname', 
                    'fatherMname', 
                    'fatherLname', 
                    'motherFname', 
                    'motherMname', 
                    'motherLname', 
                ];

                // field => 'table', 'pkName', 'pkID', 'label'
                $fields = [
                    'spouseFname'       => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Spouse First Name'], 
                    'spouseMname'       => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Spouse Middle Name'], 
                    'spouseLname'       => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Spouse Last Name'], 
                    'spouseOccupation'  => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Spouse Occupation'], 
                    'spouseBizName'     => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Spouse Bisuness Name'], 
                    'spouseBizAddress'  => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Spouse Bisuness Address'], 
                    'spouseTelNo'       => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Spouse Telephone Number'], 
                    'fatherFname'       => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Father First Name'], 
                    'fatherMname'       => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Father Middle Name'], 
                    'fatherLname'       => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Father Last Name'], 
                    'motherFname'       => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Mother First Name'], 
                    'motherMname'       => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Mother Middle Name'], 
                    'motherLname'       => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Mother Last Name'], 
                ];

                $query = DB::table($table)->where($tablePrimaryKey, $tablePrimaryKeyID)->first();
                if ($query) {

                    // update all change requests to cancelled
                    $query_side = DB::table('user_pds_change_requests');
                    $query_side = $query_side->where('userID', $token_userID);
                    $query_side = $query_side->where('type', 2); // family_background
                    $query_side = $query_side->where('status', 0);
                    if ($query_side) {
                        $query_side->update([
                            'dateCancelled' => date('Y-m-d H:i:s'), 
                            'remarks'       => "Record adjusted.", 
                            'status'        => -2
                        ]);
                    }

                    // header
                    $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                        'userID'        => $token_userID, 
                        'dateInserted'  => date('Y-m-d H:i:s'), 
                        'dateCancelled' => null, 
                        'approvedBy'    => 0, 
                        'dateApproved'  => null, 
                        'deniedBy'      => 0, 
                        'dateDenied'    => null, 
                        'remarks'       => '', 
                        'type'          => 2, // family_background
                        'status'        => 0, 
                    ]);
                    
                    if ($userPdsChangeRequestID) {
                        
                        // details
                        $hasChanges = 0;
                        foreach ($query as $field => $value) {
                            if (in_array($field, $request_fields)) {
                                if ($value != $request_data[$field]) {
                                    $hasChanges = 1;
                                    DB::table('user_pds_change_request_details')->insert([
                                        'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                        'tableName'     => $fields[$field][0], 
                                        'primaryKey'    => $fields[$field][1], 
                                        'primaryKeyID'  => $fields[$field][2], 
                                        'label'         => $fields[$field][3], 
                                        'field'         => $field, 
                                        'valueOld'      => $value, 
                                        'valueNew'      => $request_data[$field], 
                                    ]);
                                }
                            }
                        }
    
                        // delete header if no changes
                        if (!$hasChanges) {
                            DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                        }

                    }

                    /** ******************* Childrens ******************* */

                    // get all existing childrenID under user
                    $query = DB::table('user_childrens')->where('userID', $token_userID)->get();
                    $userChildrenIDs = [];
                    if ($query) {
                        foreach ($query as $q) {
                            if (!in_array($q->userChildrenID, $userChildrenIDs)) $userChildrenIDs[] = $q->userChildrenID;
                        }
                    }

                    $childrenIDs        = $request->input('childrenIDs');
                    $childrenNames      = $request->input('childrenNames');
                    $childrenBirthdays  = $request->input('childrenBirthdays');

                    $request_fields = ['userID', 'name', 'birthDate'];

                    if ($childrenIDs) {

                        $table              = 'user_childrens';
                        $tablePrimaryKey    = 'userChildrenID';
                        $tablePrimaryKeyID  = 0;


                        $fields = [
                            'userID'    => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Employee'], 
                            'name'      => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Child Name'], 
                            'birthDate' => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Child Birthday'], 
                        ];

                        foreach ($childrenIDs as $count => $childrenID) {

                            $userID     = $token_userID;
                            $name       = $childrenNames[$count] ? $childrenNames[$count] : '';
                            $birthDate  = $childrenBirthdays[$count] ? $childrenBirthdays[$count] : null;

                            if (trim($name)) {
                                if ($childrenID) {
                                    if (in_array($childrenID, $userChildrenIDs)) $userChildrenIDs = array_diff($userChildrenIDs, [$childrenID]); 

                                    $query = DB::table('user_childrens');
                                    $query = $query->where('userChildrenID', $childrenID);
                                    $query = $query->first();

                                    if ($query) {

                                        // header
                                        $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                                            'userID'        => $token_userID, 
                                            'dateInserted'  => date('Y-m-d H:i:s'), 
                                            'dateCancelled' => null, 
                                            'approvedBy'    => 0, 
                                            'dateApproved'  => null, 
                                            'deniedBy'      => 0, 
                                            'dateDenied'    => null, 
                                            'remarks'       => '', 
                                            'type'          => 2, // family_background
                                            'action'        => 0, 
                                            'status'        => 0, 
                                        ]);

                                        if ($userPdsChangeRequestID) {
                        
                                            // details
                                            $hasChanges = 0;
                                            foreach ($query as $field => $value) {
                                                if (in_array($field, $request_fields)) {
                                                    if ($value != $$field) {
                                                        $hasChanges = 1;
                                                        DB::table('user_pds_change_request_details')->insert([
                                                            'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                                            'tableName'     => $fields[$field][0], 
                                                            'primaryKey'    => $fields[$field][1], 
                                                            'primaryKeyID'  => $childrenID, 
                                                            'label'         => $fields[$field][3], 
                                                            'field'         => $field, 
                                                            'valueOld'      => $value, 
                                                            'valueNew'      => $$field, 
                                                        ]);
                                                    }
                                                }
                                            }

                                            // delete header if no changes
                                            if (!$hasChanges) {
                                                DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                                            }
                    
                                        }

                                    }

                                } else {

                                    // header
                                    $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                                        'userID'        => $token_userID, 
                                        'dateInserted'  => date('Y-m-d H:i:s'), 
                                        'dateCancelled' => null, 
                                        'approvedBy'    => 0, 
                                        'dateApproved'  => null, 
                                        'deniedBy'      => 0, 
                                        'dateDenied'    => null, 
                                        'remarks'       => '', 
                                        'type'          => 2, // family_background
                                        'action'        => 1, 
                                        'status'        => 0, 
                                    ]);

                                    if ($userPdsChangeRequestID) {
                                        
                                        // details
                                        $hasChanges = 1;
                                        foreach ($request_fields as $field) {
                                            DB::table('user_pds_change_request_details')->insert([
                                                'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                                'tableName'     => $fields[$field][0], 
                                                'primaryKey'    => $fields[$field][1], 
                                                'primaryKeyID'  => 0, 
                                                'label'         => $fields[$field][3], 
                                                'field'         => $field, 
                                                'valueOld'      => '', 
                                                'valueNew'      => $$field, 
                                            ]);
                                        }

                                        // delete header if no changes
                                        if (!$hasChanges) {
                                            DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                                        }
                
                                    }

                                }
                            }
                        }
                    }

                    // to delete
                    if ($userChildrenIDs) {
                        foreach ($userChildrenIDs as $childrenID) {

                            $query = DB::table('user_childrens');
                            $query = $query->where('userChildrenID', $childrenID);
                            $query = $query->first();

                            if ($query) {

                                // header
                                $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                                    'userID'        => $token_userID, 
                                    'dateInserted'  => date('Y-m-d H:i:s'), 
                                    'dateCancelled' => null, 
                                    'approvedBy'    => 0, 
                                    'dateApproved'  => null, 
                                    'deniedBy'      => 0, 
                                    'dateDenied'    => null, 
                                    'remarks'       => '', 
                                    'type'          => 2, // family_background
                                    'action'        => -1, 
                                    'status'        => 0, 
                                ]);

                                if ($userPdsChangeRequestID) {
                
                                    // details
                                    $hasChanges = 0;
                                    foreach ($query as $field => $value) {
                                        if (in_array($field, $request_fields)) {
                                            $hasChanges = 1;
                                            DB::table('user_pds_change_request_details')->insert([
                                                'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                                'tableName'     => $fields[$field][0], 
                                                'primaryKey'    => $fields[$field][1], 
                                                'primaryKeyID'  => $childrenID, 
                                                'label'         => $fields[$field][3], 
                                                'field'         => $field, 
                                                'valueOld'      => $value, 
                                                'valueNew'      => '', 
                                            ]);
                                        }
                                    }

                                    // delete header if no changes
                                    if (!$hasChanges) {
                                        DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                                    }
            
                                }

                            }

                        }
                    }

                } else {
                    $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
                }
            } 
    
            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    public function put_page_educational_background(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];
            
            /** variables */
            $educations = [];
            $hasError   = 0;
    
            /** check errors */
    
            /** query */
            if (!$hasError) {


                $educationLevels = [
                    '', 
                    'ELEMENTARY',  
                    'SECONDARY',  
                    'VOCATIONAL/ TRADE COURSE',  
                    'COLLEGE',  
                    'GRADUATE STUDIES',  
                ];

                // 
                for ($i=1; $i<=5; $i++) {
                    $query = DB::table('user_educations')->where('userID', $token_userID)->where('type', $i)->first();
                    if ($query) {
                        $educations[] = [
                            'userEducationID'       => $query->userEducationID, 
                            'level'                 => $educationLevels[$i], 
                            'schoolName'            => $query->schoolName, 
                            'degree'                => $query->degree, 
                            'dateAttendedFrom'      => $query->dateAttendedFrom, 
                            'dateAttendedTo'        => $query->dateAttendedTo, 
                            'highestLevelEarned'    => $query->highestLevelEarned, 
                            'yearGraduated'         => $query->yearGraduated, 
                            'scholarship'           => $query->scholarship, 
                            'type'                  => $query->type, 
                        ];
                    } else {
                        $educations[] = [
                            'userEducationID'       => 0, 
                            'level'                 => $educationLevels[$i], 
                            'schoolName'            => '', 
                            'degree'                => '', 
                            'dateAttendedFrom'      => null, 
                            'dateAttendedTo'        => null, 
                            'highestLevelEarned'    => '', 
                            'yearGraduated'         => '', 
                            'scholarship'           => '', 
                            'type'                  => $i, 
                        ];
                    }
                }


                /** final variables */
                $items['educations'] = $educations;

                $data['items'] = $items;

            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 
    public function put_educational_background(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $userID = $request_token['data'];

            $table = 'user_educations';
            $tablePrimaryKey = 'userEducationID';

            // update all change requests to cancelled
            $query_side = DB::table('user_pds_change_requests');
            $query_side = $query_side->where('userID', $userID);
            $query_side = $query_side->where('type', 3); // Educational Background
            $query_side = $query_side->where('status', 0);
            if ($query_side) {
                $query_side->update([
                    'dateCancelled' => date('Y-m-d H:i:s'), 
                    'remarks'       => "Record adjusted.", 
                    'status'        => -2
                ]);
            }
    
            // // get all existing childrenID under user
            // $query = DB::table($table)->where('userID', $userID)->get();
            // $userEducationIDs = [];
            // if ($query) {
            //     foreach ($query as $q) {
            //         if (!in_array($q->userEducationID, $userEducationIDs)) $userEducationIDs[] = $q->userEducationID;
            //     }
            // }

            $educationIDs           = $request->input('userEducationIDs');
            $schoolNames            = $request->input('schoolNames');
            $degrees                = $request->input('degrees');
            $dateAttendedFroms      = $request->input('dateAttendedFroms');
            $dateAttendedTos        = $request->input('dateAttendedTos');
            $highestLevelEarneds    = $request->input('highestLevelEarneds');
            $yearGraduateds         = $request->input('yearGraduateds');
            $scholarships           = $request->input('scholarships');
            $types                  = $request->input('types');

            $request_fields = [
                'userID', 
                'type', 
                'schoolName', 
                'degree', 
                'dateAttendedFrom', 
                'dateAttendedTo', 
                'highestLevelEarned', 
                'yearGraduated', 
                'scholarship', 
            ];

            if ($educationIDs) {

                $tablePrimaryKeyID  = 0;

                $fields = [
                    'userID'                => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Employee'], 
                    'type'                  => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Level'], 
                    'schoolName'            => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Name of School'], 
                    'degree'                => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Basic Education/ Degree/ Course'], 
                    'dateAttendedFrom'      => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Attendance Period From'], 
                    'dateAttendedTo'        => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Attendance Period To'], 
                    'highestLevelEarned'    => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Highest Level/ Unit Earned'], 
                    'yearGraduated'         => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Year Graduated'], 
                    'scholarship'           => [$table, $tablePrimaryKey, $tablePrimaryKeyID, 'Scholarship/ Academic Honor Received'], 
                ];

                foreach ($educationIDs as $count => $educationID) {

                    $schoolName         = $schoolNames[$count] ? $schoolNames[$count] : '';
                    $degree             = $degrees[$count] ? $degrees[$count] : '';
                    $dateAttendedFrom   = $dateAttendedFroms[$count] ? $dateAttendedFroms[$count] : null;
                    $dateAttendedTo     = $dateAttendedTos[$count] ? $dateAttendedTos[$count] : null;
                    $highestLevelEarned = $highestLevelEarneds[$count] ? $highestLevelEarneds[$count] : '';
                    $yearGraduated      = $yearGraduateds[$count] ? $yearGraduateds[$count] : '';
                    $scholarship        = $scholarships[$count] ? $scholarships[$count] : '';
                    $type               = $types[$count] ? $types[$count] : 0;

                    // if (trim($name)) {
                        if ($educationID) {

                            // if (in_array($educationID, $userEducationIDs)) $userEducationIDs = array_diff($userEducationIDs, [$educationID]); 

                            $query = DB::table($table);
                            $query = $query->where($tablePrimaryKey, $educationID);
                            $query = $query->first();

                            if ($query) {

                                // header
                                $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                                    'userID'        => $userID, 
                                    'dateInserted'  => date('Y-m-d H:i:s'), 
                                    'dateCancelled' => null, 
                                    'approvedBy'    => 0, 
                                    'dateApproved'  => null, 
                                    'deniedBy'      => 0, 
                                    'dateDenied'    => null, 
                                    'remarks'       => '', 
                                    'type'          => 3, // Educational Background
                                    'action'        => 0, 
                                    'status'        => 0, 
                                ]);

                                if ($userPdsChangeRequestID) {
                
                                    // details
                                    $hasChanges = 0;
                                    foreach ($query as $field => $value) {
                                        if (in_array($field, $request_fields)) {
                                            if ($value != $$field) {
                                                $hasChanges = 1;
                                                DB::table('user_pds_change_request_details')->insert([
                                                    'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                                    'tableName'     => $fields[$field][0], 
                                                    'primaryKey'    => $fields[$field][1], 
                                                    'primaryKeyID'  => $educationID, 
                                                    'label'         => $fields[$field][3], 
                                                    'field'         => $field, 
                                                    'valueOld'      => $value, 
                                                    'valueNew'      => $$field, 
                                                ]);
                                            }
                                        }
                                    }

                                    // delete header if no changes
                                    if (!$hasChanges) {
                                        DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                                    }
            
                                }

                            }

                        } else {

                            // header
                            $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                                'userID'        => $userID, 
                                'dateInserted'  => date('Y-m-d H:i:s'), 
                                'dateCancelled' => null, 
                                'approvedBy'    => 0, 
                                'dateApproved'  => null, 
                                'deniedBy'      => 0, 
                                'dateDenied'    => null, 
                                'remarks'       => '', 
                                'type'          => 3, // Educational Background
                                'action'        => 1, 
                                'status'        => 0, 
                            ]);

                            if ($userPdsChangeRequestID) {
                                
                                // details
                                $hasChanges = 1;
                                foreach ($request_fields as $field) {
                                    DB::table('user_pds_change_request_details')->insert([
                                        'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                        'tableName'     => $fields[$field][0], 
                                        'primaryKey'    => $fields[$field][1], 
                                        'primaryKeyID'  => 0, 
                                        'label'         => $fields[$field][3], 
                                        'field'         => $field, 
                                        'valueOld'      => '', 
                                        'valueNew'      => $$field, 
                                    ]);
                                }

                                // delete header if no changes
                                if (!$hasChanges) {
                                    DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                                }
        
                            }

                        }
                    // }

                }
            }

            // // to delete
            // if ($userEducationIDs) {
            //     foreach ($userEducationIDs as $educationID) {

            //         $query = DB::table($table);
            //         $query = $query->where($tablePrimaryKey, $educationID);
            //         $query = $query->first();

            //         if ($query) {

            //             // header
            //             $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
            //                 'userID'        => $userID, 
            //                 'dateInserted'  => date('Y-m-d H:i:s'), 
            //                 'dateCancelled' => null, 
            //                 'approvedBy'    => 0, 
            //                 'dateApproved'  => null, 
            //                 'deniedBy'      => 0, 
            //                 'dateDenied'    => null, 
            //                 'remarks'       => '', 
            //                 'type'          => 3, // Educational Background
            //                 'action'        => -1, 
            //                 'status'        => 0, 
            //             ]);

            //             if ($userPdsChangeRequestID) {
        
            //                 // details
            //                 $hasChanges = 0;
            //                 foreach ($query as $field => $value) {
            //                     if (in_array($field, $request_fields)) {
            //                         $hasChanges = 1;
            //                         DB::table('user_pds_change_request_details')->insert([
            //                             'userPdsChangeRequestID' => $userPdsChangeRequestID, 
            //                             'tableName'     => $fields[$field][0], 
            //                             'primaryKey'    => $fields[$field][1], 
            //                             'primaryKeyID'  => $educationID, 
            //                             'label'         => $fields[$field][3], 
            //                             'field'         => $field, 
            //                             'valueOld'      => $value, 
            //                             'valueNew'      => '', 
            //                         ]);
            //                     }
            //                 }

            //                 // delete header if no changes
            //                 if (!$hasChanges) {
            //                     DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
            //                 }
    
            //             }

            //         }

            //     }
            // }

            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    public function put_page_civil_service_eligibilities(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $token_userID = $request_token['data'];
            
            /** variables */
            $civil_services = [];
            $hasError = 0;
    
            /** check errors */
    
            /** query */
            if (!$hasError) {

                // 
                $query = DB::table('user_civil_services')->where('userID', $token_userID)->orderBy('userCivilServiceID', 'asc')->get();
                if ($query) {
                    foreach ($query as $q) {
                        $civil_services[] = [
                            'userCivilServiceID'    => $q->userCivilServiceID, 
                            'name'                  => $q->name, 
                            'rating'                => $q->rating, 
                            'dateExamination'       => $q->dateExamination, 
                            'placeExamination'      => $q->placeExamination, 
                            'licenseNumber'         => $q->licenseNumber, 
                            'licenseDateValidity'   => $q->licenseDateValidity, 
                        ];
                    }
                }

                /** final variables */
                $items['civil_services'] = $civil_services;

                $data['items'] = $items;

            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 
    public function put_civil_service_eligibilities(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $userID = $request_token['data'];

            $table = 'user_civil_services';
            $tablePrimaryKey = 'userCivilServiceID';

            // update all change requests to cancelled
            $query_side = DB::table('user_pds_change_requests');
            $query_side = $query_side->where('userID', $userID);
            $query_side = $query_side->where('type', 4); // Civil Service Eligibilities
            $query_side = $query_side->where('status', 0);
            if ($query_side) {
                $query_side->update([
                    'dateCancelled' => date('Y-m-d H:i:s'), 
                    'remarks'       => "Record adjusted.", 
                    'status'        => -2
                ]);
            }
    
            // get all existing childrenID under user
            $query = DB::table($table)->where('userID', $userID)->get();
            $userCivilServiceIDs = [];
            if ($query) {
                foreach ($query as $q) {
                    if (!in_array($q->userCivilServiceID, $userCivilServiceIDs)) $userCivilServiceIDs[] = $q->userCivilServiceID;
                }
            }


            $civilServiceIDs        = $request->input('civilServiceIDs');
            $names                  = $request->input('names');
            $ratings                = $request->input('ratings');
            $dateExaminations       = $request->input('dateExaminations');
            $placeExaminations      = $request->input('placeExaminations');
            $licenseNumbers         = $request->input('licenseNumbers');
            $licenseDateValiditys   = $request->input('licenseDateValiditys');

            $request_fields = [
                'userID', 
                'name', 
                'rating', 
                'dateExamination', 
                'placeExamination', 
                'licenseNumber', 
                'licenseDateValidity', 
            ];

            if ($civilServiceIDs) {

                $fields = [
                    'userID'                => [$table, $tablePrimaryKey, 0, 'Employee'], 
                    'name'                  => [$table, $tablePrimaryKey, 0, 'Eligibility Name'], 
                    'rating'                => [$table, $tablePrimaryKey, 0, 'Rating'], 
                    'dateExamination'       => [$table, $tablePrimaryKey, 0, 'Date of Examination/ Conferment'], 
                    'placeExamination'      => [$table, $tablePrimaryKey, 0, 'Place of Examination/ Conferment'], 
                    'licenseNumber'         => [$table, $tablePrimaryKey, 0, 'License Number'], 
                    'licenseDateValidity'   => [$table, $tablePrimaryKey, 0, 'License Date of Validity'], 
                ];

                foreach ($civilServiceIDs as $count => $civilServiceID) {

                    $name                   = $names[$count] ? $names[$count] : '';
                    $rating                 = $ratings[$count] ? $ratings[$count] : '';
                    $dateExamination        = $dateExaminations[$count] ? $dateExaminations[$count] : null;
                    $placeExamination       = $placeExaminations[$count] ? $placeExaminations[$count] : '';
                    $licenseNumber          = $licenseNumbers[$count] ? $licenseNumbers[$count] : '';
                    $licenseDateValidity    = $licenseDateValiditys[$count] ? $licenseDateValiditys[$count] : null;

                    if (trim($name)) {
                        if ($civilServiceID) {

                            if (in_array($civilServiceID, $userCivilServiceIDs)) $userCivilServiceIDs = array_diff($userCivilServiceIDs, [$civilServiceID]); 

                            $query = DB::table($table);
                            $query = $query->where($tablePrimaryKey, $civilServiceID);
                            $query = $query->first();

                            if ($query) {

                                // header
                                $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                                    'userID'        => $userID, 
                                    'dateInserted'  => date('Y-m-d H:i:s'), 
                                    'dateCancelled' => null, 
                                    'approvedBy'    => 0, 
                                    'dateApproved'  => null, 
                                    'deniedBy'      => 0, 
                                    'dateDenied'    => null, 
                                    'remarks'       => '', 
                                    'type'          => 4, // Civil Service Eligibilities
                                    'action'        => 0, 
                                    'status'        => 0, 
                                ]);

                                if ($userPdsChangeRequestID) {
                
                                    // details
                                    $hasChanges = 0;
                                    foreach ($query as $field => $value) {
                                        if (in_array($field, $request_fields)) {
                                            if ($value != $$field) {
                                                $hasChanges = 1;
                                                DB::table('user_pds_change_request_details')->insert([
                                                    'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                                    'tableName'     => $fields[$field][0], 
                                                    'primaryKey'    => $fields[$field][1], 
                                                    'primaryKeyID'  => $civilServiceID, 
                                                    'label'         => $fields[$field][3], 
                                                    'field'         => $field, 
                                                    'valueOld'      => $value, 
                                                    'valueNew'      => $$field, 
                                                ]);
                                            }
                                        }
                                    }

                                    // delete header if no changes
                                    if (!$hasChanges) {
                                        DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                                    }
            
                                }

                            }

                        } else {

                            // header
                            $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                                'userID'        => $userID, 
                                'dateInserted'  => date('Y-m-d H:i:s'), 
                                'dateCancelled' => null, 
                                'approvedBy'    => 0, 
                                'dateApproved'  => null, 
                                'deniedBy'      => 0, 
                                'dateDenied'    => null, 
                                'remarks'       => '', 
                                'type'          => 4, // Civil Service Eligibilities
                                'action'        => 1, 
                                'status'        => 0, 
                            ]);

                            if ($userPdsChangeRequestID) {
                                
                                // details
                                $hasChanges = 1;
                                foreach ($request_fields as $field) {
                                    DB::table('user_pds_change_request_details')->insert([
                                        'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                        'tableName'     => $fields[$field][0], 
                                        'primaryKey'    => $fields[$field][1], 
                                        'primaryKeyID'  => 0, 
                                        'label'         => $fields[$field][3], 
                                        'field'         => $field, 
                                        'valueOld'      => '', 
                                        'valueNew'      => $$field, 
                                    ]);
                                }

                                // delete header if no changes
                                if (!$hasChanges) {
                                    DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                                }
        
                            }

                        }
                    }

                }
            }

            // to delete
            if ($userCivilServiceIDs) {
                foreach ($userCivilServiceIDs as $civilServiceID) {

                    $query = DB::table($table);
                    $query = $query->where($tablePrimaryKey, $civilServiceID);
                    $query = $query->first();

                    if ($query) {

                        // header
                        $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                            'userID'        => $userID, 
                            'dateInserted'  => date('Y-m-d H:i:s'), 
                            'dateCancelled' => null, 
                            'approvedBy'    => 0, 
                            'dateApproved'  => null, 
                            'deniedBy'      => 0, 
                            'dateDenied'    => null, 
                            'remarks'       => '', 
                            'type'          => 4, // Civil Service Eligibilities
                            'action'        => -1, 
                            'status'        => 0, 
                        ]);

                        if ($userPdsChangeRequestID) {
        
                            // details
                            $hasChanges = 0;
                            foreach ($query as $field => $value) {
                                if (in_array($field, $request_fields)) {
                                    $hasChanges = 1;
                                    DB::table('user_pds_change_request_details')->insert([
                                        'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                        'tableName'     => $fields[$field][0], 
                                        'primaryKey'    => $fields[$field][1], 
                                        'primaryKeyID'  => $civilServiceID, 
                                        'label'         => $fields[$field][3], 
                                        'field'         => $field, 
                                        'valueOld'      => $value, 
                                        'valueNew'      => '', 
                                    ]);
                                }
                            }

                            // delete header if no changes
                            if (!$hasChanges) {
                                DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                            }
    
                        }

                    }

                }
            }

            $data['items'] = $items;
            
        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    public function put_page_work_experiences(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $token_userID = $request_token['data'];
            
            /** variables */
            $work_experiences = [];
            $hasError = 0;
    
            /** check errors */
    
            /** query */
            if (!$hasError) {


                // 
                $query = DB::table('user_works')->where('userID', $token_userID)->orderBy('dateFrom', 'asc')->get();
                if ($query) {
                    foreach ($query as $q) {
                        $work_experiences[] = [
                            'userWorkID'        => $q->userWorkID, 
                            'dateFrom'          => $q->dateFrom, 
                            'dateTo'            => $q->dateTo, 
                            'position'          => $q->position, 
                            'company'           => $q->company, 
                            'salary'            => $q->salary, 
                            'salaryGrade'       => $q->salaryGrade, 
                            'appointmentStatus' => $q->appointmentStatus, 
                            'isGovt'            => $q->isGovt, 
                        ];
                    }
                }

                /** final variables */
                $items['work_experiences'] = $work_experiences;

                $data['items'] = $items;

            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 
    public function put_work_experiences(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $userID = $request_token['data'];

            $table = 'user_works';
            $tablePrimaryKey = 'userWorkID';

            // update all change requests to cancelled
            $query_side = DB::table('user_pds_change_requests');
            $query_side = $query_side->where('userID', $userID);
            $query_side = $query_side->where('type', 5); // Work Experiences
            $query_side = $query_side->where('status', 0);
            if ($query_side) {
                $query_side->update([
                    'dateCancelled' => date('Y-m-d H:i:s'), 
                    'remarks'       => "Record adjusted.", 
                    'status'        => -2
                ]);
            }
    
            // get all existing childrenID under user
            $query = DB::table($table)->where('userID', $userID)->get();
            $userWorkIDs = [];
            if ($query) {
                foreach ($query as $q) {
                    if (!in_array($q->userWorkID, $userWorkIDs)) $userWorkIDs[] = $q->userWorkID;
                }
            }


            $workIDs            = $request->input('workIDs');
            $dateFroms          = $request->input('dateFroms');
            $dateTos            = $request->input('dateTos');
            $positions          = $request->input('positions');
            $companys           = $request->input('companys');
            $salarys            = $request->input('salarys');
            $salaryGrades       = $request->input('salaryGrades');
            $appointmentStatuss = $request->input('appointmentStatuss');
            $isGovts            = $request->input('isGovts');

            $request_fields = [
                'userID', 
                'dateFrom', 
                'dateTo', 
                'position', 
                'company', 
                'salary', 
                'salaryGrade', 
                'appointmentStatus', 
                'isGovt', 
            ];

            if ($workIDs) {

                $fields = [
                    'userID'            => [$table, $tablePrimaryKey, 0, 'Employee'], 
                    'dateFrom'          => [$table, $tablePrimaryKey, 0, 'From Inclusive Date'], 
                    'dateTo'            => [$table, $tablePrimaryKey, 0, 'To Inclusive Date'], 
                    'position'          => [$table, $tablePrimaryKey, 0, 'Position Title'], 
                    'company'           => [$table, $tablePrimaryKey, 0, 'Department/ Agency/ Office/ Company'], 
                    'salary'            => [$table, $tablePrimaryKey, 0, 'Monthly Salary'], 
                    'salaryGrade'       => [$table, $tablePrimaryKey, 0, 'Salary/ Job/ Pay Grade & Step'], 
                    'appointmentStatus' => [$table, $tablePrimaryKey, 0, 'Status of Appointment'], 
                    'isGovt'            => [$table, $tablePrimaryKey, 0, 'Government Service'], 
                ];

                foreach ($workIDs as $count => $workID) {

                    $dateFrom           = $dateFroms[$count] ? $dateFroms[$count] : null;
                    $dateTo             = $dateTos[$count] ? $dateTos[$count] : null;
                    $position           = $positions[$count] ? $positions[$count] : '';
                    $company            = $companys[$count] ? $companys[$count] : '';
                    $salary             = $salarys[$count] ? $salarys[$count] : 0;
                    $salaryGrade        = $salaryGrades[$count] ? $salaryGrades[$count] : '';
                    $appointmentStatus  = $appointmentStatuss[$count] ? $appointmentStatuss[$count] : '';
                    $isGovt             = $isGovts[$count] ? $isGovts[$count] : 0;

                    if (trim($position)) {
                        if ($workID) {

                            if (in_array($workID, $userWorkIDs)) $userWorkIDs = array_diff($userWorkIDs, [$workID]); 

                            $query = DB::table($table);
                            $query = $query->where($tablePrimaryKey, $workID);
                            $query = $query->first();

                            if ($query) {

                                // header
                                $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                                    'userID'        => $userID, 
                                    'dateInserted'  => date('Y-m-d H:i:s'), 
                                    'dateCancelled' => null, 
                                    'approvedBy'    => 0, 
                                    'dateApproved'  => null, 
                                    'deniedBy'      => 0, 
                                    'dateDenied'    => null, 
                                    'remarks'       => '', 
                                    'type'          => 5, // Work Experiences
                                    'action'        => 0, 
                                    'status'        => 0, 
                                ]);

                                if ($userPdsChangeRequestID) {
                
                                    // details
                                    $hasChanges = 0;
                                    foreach ($query as $field => $value) {
                                        if (in_array($field, $request_fields)) {
                                            if ($value != $$field) {
                                                $hasChanges = 1;
                                                DB::table('user_pds_change_request_details')->insert([
                                                    'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                                    'tableName'     => $fields[$field][0], 
                                                    'primaryKey'    => $fields[$field][1], 
                                                    'primaryKeyID'  => $workID, 
                                                    'label'         => $fields[$field][3], 
                                                    'field'         => $field, 
                                                    'valueOld'      => $value, 
                                                    'valueNew'      => $$field, 
                                                ]);
                                            }
                                        }
                                    }

                                    // delete header if no changes
                                    if (!$hasChanges) {
                                        DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                                    }
            
                                }

                            }

                        } else {

                            // header
                            $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                                'userID'        => $userID, 
                                'dateInserted'  => date('Y-m-d H:i:s'), 
                                'dateCancelled' => null, 
                                'approvedBy'    => 0, 
                                'dateApproved'  => null, 
                                'deniedBy'      => 0, 
                                'dateDenied'    => null, 
                                'remarks'       => '', 
                                'type'          => 5, // Work Experiences
                                'action'        => 1, 
                                'status'        => 0, 
                            ]);

                            if ($userPdsChangeRequestID) {
                                
                                // details
                                $hasChanges = 1;
                                foreach ($request_fields as $field) {
                                    DB::table('user_pds_change_request_details')->insert([
                                        'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                        'tableName'     => $fields[$field][0], 
                                        'primaryKey'    => $fields[$field][1], 
                                        'primaryKeyID'  => 0, 
                                        'label'         => $fields[$field][3], 
                                        'field'         => $field, 
                                        'valueOld'      => '', 
                                        'valueNew'      => $$field, 
                                    ]);
                                }

                                // delete header if no changes
                                if (!$hasChanges) {
                                    DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                                }
        
                            }

                        }
                    }

                }
            }

            // to delete
            if ($userWorkIDs) {
                foreach ($userWorkIDs as $workID) {

                    $query = DB::table($table);
                    $query = $query->where($tablePrimaryKey, $workID);
                    $query = $query->first();

                    if ($query) {

                        // header
                        $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                            'userID'        => $userID, 
                            'dateInserted'  => date('Y-m-d H:i:s'), 
                            'dateCancelled' => null, 
                            'approvedBy'    => 0, 
                            'dateApproved'  => null, 
                            'deniedBy'      => 0, 
                            'dateDenied'    => null, 
                            'remarks'       => '', 
                            'type'          => 5, // Work Experiences
                            'action'        => -1, 
                            'status'        => 0, 
                        ]);

                        if ($userPdsChangeRequestID) {
        
                            // details
                            $hasChanges = 0;
                            foreach ($query as $field => $value) {
                                if (in_array($field, $request_fields)) {
                                    $hasChanges = 1;
                                    DB::table('user_pds_change_request_details')->insert([
                                        'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                        'tableName'     => $fields[$field][0], 
                                        'primaryKey'    => $fields[$field][1], 
                                        'primaryKeyID'  => $workID, 
                                        'label'         => $fields[$field][3], 
                                        'field'         => $field, 
                                        'valueOld'      => $value, 
                                        'valueNew'      => '', 
                                    ]);
                                }
                            }

                            // delete header if no changes
                            if (!$hasChanges) {
                                DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                            }
    
                        }

                    }

                }
            }

            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    public function put_page_training_programs(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $token_userID = $request_token['data'];
            
            /** variables */
            $training_programs = [];
            $hasError = 0;
    
            /** check errors */
            /** query */
            if (!$hasError) {

                // 
                $query = DB::table('user_trainings')->where('userID', $token_userID)->orderBy('dateFrom', 'asc')->get();
                if ($query) {
                    foreach ($query as $q) {
                        $training_programs[] = [
                            'userTrainingID'    => $q->userTrainingID, 
                            'trainingName'      => $q->trainingName, 
                            'dateFrom'          => $q->dateFrom, 
                            'dateTo'            => $q->dateTo, 
                            'hours'             => $q->hours, 
                            'ldType'            => $q->ldType, 
                            'sponsor'           => $q->sponsor, 
                        ];
                    }
                }

                /** final variables */
                $items['training_programs'] = $training_programs;

                $data['items'] = $items;

            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 
    public function put_training_programs(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $userID = $request_token['data'];

            $table = 'user_trainings';
            $tablePrimaryKey = 'userTrainingID';

            // update all change requests to cancelled
            $query_side = DB::table('user_pds_change_requests');
            $query_side = $query_side->where('userID', $userID);
            $query_side = $query_side->where('type', 6); // Training Programs
            $query_side = $query_side->where('status', 0);
            if ($query_side) {
                $query_side->update([
                    'dateCancelled' => date('Y-m-d H:i:s'), 
                    'remarks'       => "Record adjusted.", 
                    'status'        => -2
                ]);
            }
    
            // get all existing childrenID under user
            $query = DB::table($table)->where('userID', $userID)->get();
            $userTrainingIDs = [];
            if ($query) {
                foreach ($query as $q) {
                    if (!in_array($q->userTrainingID, $userTrainingIDs)) $userTrainingIDs[] = $q->userTrainingID;
                }
            }

            $trainingIDs    = $request->input('userTrainingIDs');
            $trainingNames  = $request->input('trainingNames');
            $dateFroms      = $request->input('dateFroms');
            $dateTos        = $request->input('dateTos');
            $hourss         = $request->input('hourss');
            $ldTypes        = $request->input('ldTypes');
            $sponsors       = $request->input('sponsors');

            $request_fields = [
                'userID', 
                'trainingName', 
                'dateFrom', 
                'dateTo', 
                'hours', 
                'ldType', 
                'sponsor', 
            ];

            if ($trainingIDs) {

                $fields = [
                    'userID'            => [$table, $tablePrimaryKey, 0, 'Employee'], 
                    'trainingName'      => [$table, $tablePrimaryKey, 0, 'Title of Learning and Development Intervention'], 
                    'dateFrom'          => [$table, $tablePrimaryKey, 0, 'Inclusive Date of Attendance From'], 
                    'dateTo'            => [$table, $tablePrimaryKey, 0, 'Inclusive Date of Attendance To'], 
                    'hours'             => [$table, $tablePrimaryKey, 0, 'number of Hours'], 
                    'ldType'            => [$table, $tablePrimaryKey, 0, 'Type of LD'], 
                    'sponsor'           => [$table, $tablePrimaryKey, 0, 'Conducted/ Sponsored By'], 
                ];

                foreach ($trainingIDs as $count => $trainingID) {

                    $trainingName   = $trainingNames[$count] ? $trainingNames[$count] : '';
                    $dateFrom       = $dateFroms[$count] ? $dateFroms[$count] : null;
                    $dateTo         = $dateTos[$count] ? $dateTos[$count] : null;
                    $hours          = $hourss[$count] ? $hourss[$count] : '';
                    $ldType         = $ldTypes[$count] ? $ldTypes[$count] : '';
                    $sponsor        = $sponsors[$count] ? $sponsors[$count] : '';

                    if (trim($trainingName)) {
                        if ($trainingID) {

                            if (in_array($trainingID, $userTrainingIDs)) $userTrainingIDs = array_diff($userTrainingIDs, [$trainingID]); 

                            $query = DB::table($table);
                            $query = $query->where($tablePrimaryKey, $trainingID);
                            $query = $query->first();

                            if ($query) {

                                // header
                                $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                                    'userID'        => $userID, 
                                    'dateInserted'  => date('Y-m-d H:i:s'), 
                                    'dateCancelled' => null, 
                                    'approvedBy'    => 0, 
                                    'dateApproved'  => null, 
                                    'deniedBy'      => 0, 
                                    'dateDenied'    => null, 
                                    'remarks'       => '', 
                                    'type'          => 6, // Training Programs
                                    'action'        => 0, 
                                    'status'        => 0, 
                                ]);

                                if ($userPdsChangeRequestID) {
                
                                    // details
                                    $hasChanges = 0;
                                    foreach ($query as $field => $value) {
                                        if (in_array($field, $request_fields)) {
                                            if ($value != $$field) {
                                                $hasChanges = 1;
                                                DB::table('user_pds_change_request_details')->insert([
                                                    'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                                    'tableName'     => $fields[$field][0], 
                                                    'primaryKey'    => $fields[$field][1], 
                                                    'primaryKeyID'  => $trainingID, 
                                                    'label'         => $fields[$field][3], 
                                                    'field'         => $field, 
                                                    'valueOld'      => $value, 
                                                    'valueNew'      => $$field, 
                                                ]);
                                            }
                                        }
                                    }

                                    // delete header if no changes
                                    if (!$hasChanges) {
                                        DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                                    }
            
                                }

                            }

                        } else {

                            // header
                            $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                                'userID'        => $userID, 
                                'dateInserted'  => date('Y-m-d H:i:s'), 
                                'dateCancelled' => null, 
                                'approvedBy'    => 0, 
                                'dateApproved'  => null, 
                                'deniedBy'      => 0, 
                                'dateDenied'    => null, 
                                'remarks'       => '', 
                                'type'          => 6, // Training Programs
                                'action'        => 1, 
                                'status'        => 0, 
                            ]);

                            if ($userPdsChangeRequestID) {
                                
                                // details
                                $hasChanges = 1;
                                foreach ($request_fields as $field) {
                                    DB::table('user_pds_change_request_details')->insert([
                                        'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                        'tableName'     => $fields[$field][0], 
                                        'primaryKey'    => $fields[$field][1], 
                                        'primaryKeyID'  => 0, 
                                        'label'         => $fields[$field][3], 
                                        'field'         => $field, 
                                        'valueOld'      => '', 
                                        'valueNew'      => $$field, 
                                    ]);
                                }

                                // delete header if no changes
                                if (!$hasChanges) {
                                    DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                                }
        
                            }

                        }
                    }

                }
            }

            // to delete
            if ($userTrainingIDs) {
                foreach ($userTrainingIDs as $trainingID) {

                    $query = DB::table($table);
                    $query = $query->where($tablePrimaryKey, $trainingID);
                    $query = $query->first();

                    if ($query) {

                        // header
                        $userPdsChangeRequestID = DB::table('user_pds_change_requests')->insertGetId([
                            'userID'        => $userID, 
                            'dateInserted'  => date('Y-m-d H:i:s'), 
                            'dateCancelled' => null, 
                            'approvedBy'    => 0, 
                            'dateApproved'  => null, 
                            'deniedBy'      => 0, 
                            'dateDenied'    => null, 
                            'remarks'       => '', 
                            'type'          => 6, // Training Programs
                            'action'        => -1, 
                            'status'        => 0, 
                        ]);

                        if ($userPdsChangeRequestID) {
        
                            // details
                            $hasChanges = 0;
                            foreach ($query as $field => $value) {
                                if (in_array($field, $request_fields)) {
                                    $hasChanges = 1;
                                    DB::table('user_pds_change_request_details')->insert([
                                        'userPdsChangeRequestID' => $userPdsChangeRequestID, 
                                        'tableName'     => $fields[$field][0], 
                                        'primaryKey'    => $fields[$field][1], 
                                        'primaryKeyID'  => $trainingID, 
                                        'label'         => $fields[$field][3], 
                                        'field'         => $field, 
                                        'valueOld'      => $value, 
                                        'valueNew'      => '', 
                                    ]);
                                }
                            }

                            // delete header if no changes
                            if (!$hasChanges) {
                                DB::table('user_pds_change_requests')->where('userPdsChangeRequestID', $userPdsChangeRequestID)->delete();
                            }
    
                        }

                    }

                }
            }

            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);
    } 

    // CHANGES 
    public function get_changes_personal_informations(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $records        = [];
            $hasError   = 0;

            /** query */
            if (!$hasError) {
                $query = DB::table('user_pds_change_requests');
                $query = $query->where('userID', $token_userID);
                $query = $query->where('type', 1); // personal_information
                $query = $query->orderBy('dateInserted', 'desc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {
                        $records[] = [
                            'userPdsChangeRequestID' => $q->userPdsChangeRequestID, 
                            'dateInserted'  => $q->dateInserted ? date('m/d/Y h:i a', strtotime($q->dateInserted)) : '', 
                            'dateCancelled' => $q->dateCancelled ? date('m/d/Y h:i a', strtotime($q->dateCancelled)) : '', 
                            'dateApproved'  => $q->dateApproved ? date('m/d/Y h:i a', strtotime($q->dateApproved)) : '', 
                            'dateDenied'    => $q->dateDenied ? date('m/d/Y h:i a', strtotime($q->dateDenied)) : '', 
                            'remarks'       => $q->remarks, 
                            'status'        => $q->status, 
                        ];
                    }

                    /** final variables */
                    $items['records'] = $records;

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
    public function get_changes_personal_information(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $isPending  = 0;
            $records    = [];

            $genders        = ['Female', 'Male'];
            $civilStatuses  = ['', 'Single', 'Married', 'Separated', 'Widowed'];
            $bloodTypes     = ['', 'O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];

            /** query */
            $query = DB::table('user_pds_change_requests');
            $query = $query->where('userPdsChangeRequestID', $id);
            $query = $query->first();
            if ($query) {

                $query = DB::table('user_pds_change_request_details');
                $query = $query->where('userPdsChangeRequestID', $id);
                $query = $query->orderBy('userPdsChangeRequestDetailID', 'asc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {

                        $valueOld = $q->valueOld?$q->valueOld:'';
                        $valueNew = $q->valueNew?$q->valueNew:'';

                        if ($q->field == 'gender') {
                            if (!in_array($q->valueOld, ['', null])) $valueOld = $genders[$q->valueOld];
                            if (!in_array($q->valueNew, ['', null])) $valueNew = $genders[$q->valueNew];
                        }

                        if ($q->field == 'civilStatus') {
                            if (!in_array($q->valueOld, ['', null])) $valueOld = $civilStatuses[$q->valueOld];
                            if (!in_array($q->valueNew, ['', null])) $valueNew = $civilStatuses[$q->valueNew];
                        }

                        if ($q->field == 'bloodType') {
                            if (!in_array($q->valueOld, ['', null])) $valueOld = $bloodTypes[$q->valueOld];
                            if (!in_array($q->valueNew, ['', null])) $valueNew = $bloodTypes[$q->valueNew];
                        }

                        if ($q->field == 'permBarangayID') {
                            if (!in_array($q->valueOld, ['', null])) {
                                $query_side = DB::table('barangays')->where('barangayID', $q->valueOld)->first();
                                if ($query_side) $valueOld = $query_side->name;
                            }
                            if (!in_array($q->valueNew, ['', null])) {
                                $query_side = DB::table('barangays')->where('barangayID', $q->valueNew)->first();
                                if ($query_side) $valueNew = $query_side->name;
                            }
                        }

                        if ($q->field == 'permCityID') {
                            if (!in_array($q->valueOld, ['', null])) {
                                $query_side = DB::table('cities')->where('cityID', $q->valueOld)->first();
                                if ($query_side) $valueOld = $query_side->name;
                            }
                            if (!in_array($q->valueNew, ['', null])) {
                                $query_side = DB::table('cities')->where('cityID', $q->valueNew)->first();
                                if ($query_side) $valueNew = $query_side->name;
                            }
                        }

                        if ($q->field == 'permProvinceID') {
                            if (!in_array($q->valueOld, ['', null])) {
                                $query_side = DB::table('provinces')->where('provinceID', $q->valueOld)->first();
                                if ($query_side) $valueOld = $query_side->name;
                            }
                            if (!in_array($q->valueNew, ['', null])) {
                                $query_side = DB::table('provinces')->where('provinceID', $q->valueNew)->first();
                                if ($query_side) $valueNew = $query_side->name;
                            }
                        }

                        $records[] = [
                            'field'     => $q->field, 
                            'label'     => $q->label, 
                            'valueOld'  => $valueOld, 
                            'valueNew'  => $valueNew, 
                        ];
                    }

                    /** final variables */
                    $items['records'] = $records;

                    $data['items'] = $items;

                }

            } else {
                $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 
    
    public function get_changes_family_backgrounds(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $records        = [];
            $hasError   = 0;

            /** query */
            if (!$hasError) {
                $query = DB::table('user_pds_change_requests');
                $query = $query->where('userID', $token_userID);
                $query = $query->where('type', 2); // family_background
                $query = $query->orderBy('dateInserted', 'desc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {
                        $records[] = [
                            'userPdsChangeRequestID' => $q->userPdsChangeRequestID, 
                            'dateInserted'  => $q->dateInserted ? date('m/d/Y h:i a', strtotime($q->dateInserted)) : '', 
                            'dateCancelled' => $q->dateCancelled ? date('m/d/Y h:i a', strtotime($q->dateCancelled)) : '', 
                            'dateApproved'  => $q->dateApproved ? date('m/d/Y h:i a', strtotime($q->dateApproved)) : '', 
                            'dateDenied'    => $q->dateDenied ? date('m/d/Y h:i a', strtotime($q->dateDenied)) : '', 
                            'remarks'       => $q->remarks, 
                            'status'        => $q->status, 
                        ];
                    }

                    /** final variables */
                    $items['records'] = $records;

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
    public function get_changes_family_background(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $action     = 0;
            $isPending  = 0;
            $records    = [];

            /** query */
            $query = DB::table('user_pds_change_requests');
            $query = $query->where('userPdsChangeRequestID', $id);
            $query = $query->first();
            if ($query) {

                $action = $query->action;

                $query = DB::table('user_pds_change_request_details');
                $query = $query->where('userPdsChangeRequestID', $id);
                $query = $query->orderBy('userPdsChangeRequestDetailID', 'asc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {

                        $valueOld = $q->valueOld?$q->valueOld:'';
                        $valueNew = $q->valueNew?$q->valueNew:'';

                        if ($q->field == 'userID') {
                            if (!in_array($q->valueOld, ['', null])) {
                                $query_side = DB::table('user_personal_informations')->where('userID', $q->valueOld)->first();
                                if ($query_side) $valueOld = "{$query_side->fname} {$query_side->lname}";
                            }
                            if (!in_array($q->valueNew, ['', null])) {
                                $query_side = DB::table('user_personal_informations')->where('userID', $q->valueNew)->first();
                                if ($query_side) $valueNew = "{$query_side->fname} {$query_side->lname}";
                            }
                        }

                        $records[] = [
                            'field'     => $q->field, 
                            'label'     => $q->label, 
                            'valueOld'  => $valueOld, 
                            'valueNew'  => $valueNew, 
                        ];
                    }

                    /** final variables */
                    $items['action'] = $action;
                    $items['records'] = $records;

                    $data['items'] = $items;

                }

            } else {
                $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 
    
    public function get_changes_educational_backgrounds(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $records        = [];
            $hasError   = 0;

            /** query */
            if (!$hasError) {
                $query = DB::table('user_pds_change_requests');
                $query = $query->where('userID', $token_userID);
                $query = $query->where('type', 3); // educational_background
                $query = $query->orderBy('dateInserted', 'desc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {
                        $records[] = [
                            'userPdsChangeRequestID' => $q->userPdsChangeRequestID, 
                            'dateInserted'  => $q->dateInserted ? date('m/d/Y h:i a', strtotime($q->dateInserted)) : '', 
                            'dateCancelled' => $q->dateCancelled ? date('m/d/Y h:i a', strtotime($q->dateCancelled)) : '', 
                            'dateApproved'  => $q->dateApproved ? date('m/d/Y h:i a', strtotime($q->dateApproved)) : '', 
                            'dateDenied'    => $q->dateDenied ? date('m/d/Y h:i a', strtotime($q->dateDenied)) : '', 
                            'remarks'       => $q->remarks, 
                            'status'        => $q->status, 
                        ];
                    }

                    /** final variables */
                    $items['records'] = $records;

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
    public function get_changes_educational_background(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $action  = 0;
            $isPending  = 0;
            $records    = [];

            $levels = ['', 'Elementary', 'Secondary', 'Vocational/ Trade Course', 'College', 'Graduate Studies'];

            /** query */
            $query = DB::table('user_pds_change_requests');
            $query = $query->where('userPdsChangeRequestID', $id);
            $query = $query->first();
            if ($query) {

                $action = $query->action;

                $query = DB::table('user_pds_change_request_details');
                $query = $query->where('userPdsChangeRequestID', $id);
                $query = $query->orderBy('userPdsChangeRequestDetailID', 'asc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {

                        $valueOld = $q->valueOld?$q->valueOld:'';
                        $valueNew = $q->valueNew?$q->valueNew:'';

                        if ($q->field == 'userID') {
                            if (!in_array($q->valueOld, ['', null])) {
                                $query_side = DB::table('user_personal_informations')->where('userID', $q->valueOld)->first();
                                if ($query_side) $valueOld = "{$query_side->fname} {$query_side->lname}";
                            }
                            if (!in_array($q->valueNew, ['', null])) {
                                $query_side = DB::table('user_personal_informations')->where('userID', $q->valueNew)->first();
                                if ($query_side) $valueNew = "{$query_side->fname} {$query_side->lname}";
                            }
                        }
    
                        if ($q->field == 'type') {
                            if (!in_array($q->valueOld, ['', null])) $valueOld = $levels[$q->valueOld];
                            if (!in_array($q->valueNew, ['', null])) $valueNew = $levels[$q->valueNew];
                        }

                        $records[] = [
                            'field'     => $q->field, 
                            'label'     => $q->label, 
                            'valueOld'  => $valueOld, 
                            'valueNew'  => $valueNew, 
                        ];
                    }

                    /** final variables */
                    $items['action'] = $action;
                    $items['records'] = $records;

                    $data['items'] = $items;

                }

            } else {
                $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 
    
    public function get_changes_civil_service_eligibilities(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $records        = [];
            $hasError   = 0;

            /** query */
            if (!$hasError) {
                $query = DB::table('user_pds_change_requests');
                $query = $query->where('userID', $token_userID);
                $query = $query->where('type', 4); // civil_service_eligibility
                $query = $query->orderBy('dateInserted', 'desc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {
                        $records[] = [
                            'userPdsChangeRequestID' => $q->userPdsChangeRequestID, 
                            'dateInserted'  => $q->dateInserted ? date('m/d/Y h:i a', strtotime($q->dateInserted)) : '', 
                            'dateCancelled' => $q->dateCancelled ? date('m/d/Y h:i a', strtotime($q->dateCancelled)) : '', 
                            'dateApproved'  => $q->dateApproved ? date('m/d/Y h:i a', strtotime($q->dateApproved)) : '', 
                            'dateDenied'    => $q->dateDenied ? date('m/d/Y h:i a', strtotime($q->dateDenied)) : '', 
                            'remarks'       => $q->remarks, 
                            'status'        => $q->status, 
                        ];
                    }

                    /** final variables */
                    $items['records'] = $records;

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
    public function get_changes_civil_service_eligibility(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $action  = 0;
            $isPending  = 0;
            $records    = [];

            $genders        = ['Female', 'Male'];
            $civilStatuses  = ['', 'Single', 'Married', 'Separated', 'Widowed'];
            $bloodTypes     = ['', 'O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];

            /** query */
            $query = DB::table('user_pds_change_requests');
            $query = $query->where('userPdsChangeRequestID', $id);
            $query = $query->first();
            if ($query) {

                $action = $query->action;

                $query = DB::table('user_pds_change_request_details');
                $query = $query->where('userPdsChangeRequestID', $id);
                $query = $query->orderBy('userPdsChangeRequestDetailID', 'asc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {

                        $valueOld = $q->valueOld?$q->valueOld:'';
                        $valueNew = $q->valueNew?$q->valueNew:'';

                        if ($q->field == 'userID') {
                            if (!in_array($q->valueOld, ['', null])) {
                                $query_side = DB::table('user_personal_informations')->where('userID', $q->valueOld)->first();
                                if ($query_side) $valueOld = "{$query_side->fname} {$query_side->lname}";
                            }
                            if (!in_array($q->valueNew, ['', null])) {
                                $query_side = DB::table('user_personal_informations')->where('userID', $q->valueNew)->first();
                                if ($query_side) $valueNew = "{$query_side->fname} {$query_side->lname}";
                            }
                        }

                        $records[] = [
                            'field'     => $q->field, 
                            'label'     => $q->label, 
                            'valueOld'  => $valueOld, 
                            'valueNew'  => $valueNew, 
                        ];
                    }

                    /** final variables */
                    $items['action'] = $action;
                    $items['records'] = $records;

                    $data['items'] = $items;

                }

            } else {
                $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 
    
    public function get_changes_work_experiences(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $records        = [];
            $hasError   = 0;

            /** query */
            if (!$hasError) {
                $query = DB::table('user_pds_change_requests');
                $query = $query->where('userID', $token_userID);
                $query = $query->where('type', 5); // work_experience
                $query = $query->orderBy('dateInserted', 'desc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {
                        $records[] = [
                            'userPdsChangeRequestID' => $q->userPdsChangeRequestID, 
                            'dateInserted'  => $q->dateInserted ? date('m/d/Y h:i a', strtotime($q->dateInserted)) : '', 
                            'dateCancelled' => $q->dateCancelled ? date('m/d/Y h:i a', strtotime($q->dateCancelled)) : '', 
                            'dateApproved'  => $q->dateApproved ? date('m/d/Y h:i a', strtotime($q->dateApproved)) : '', 
                            'dateDenied'    => $q->dateDenied ? date('m/d/Y h:i a', strtotime($q->dateDenied)) : '', 
                            'remarks'       => $q->remarks, 
                            'status'        => $q->status, 
                        ];
                    }

                    /** final variables */
                    $items['records'] = $records;

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
    public function get_changes_work_experience(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $action  = 0;
            $isPending  = 0;
            $records    = [];

            $genders        = ['Female', 'Male'];
            $civilStatuses  = ['', 'Single', 'Married', 'Separated', 'Widowed'];
            $bloodTypes     = ['', 'O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];

            /** query */
            $query = DB::table('user_pds_change_requests');
            $query = $query->where('userPdsChangeRequestID', $id);
            $query = $query->first();
            if ($query) {

                $action = $query->action;

                $query = DB::table('user_pds_change_request_details');
                $query = $query->where('userPdsChangeRequestID', $id);
                $query = $query->orderBy('userPdsChangeRequestDetailID', 'asc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {

                        $valueOld = $q->valueOld?$q->valueOld:'';
                        $valueNew = $q->valueNew?$q->valueNew:'';

                        if ($q->field == 'userID') {
                            if (!in_array($q->valueOld, ['', null])) {
                                $query_side = DB::table('user_personal_informations')->where('userID', $q->valueOld)->first();
                                if ($query_side) $valueOld = "{$query_side->fname} {$query_side->lname}";
                            }
                            if (!in_array($q->valueNew, ['', null])) {
                                $query_side = DB::table('user_personal_informations')->where('userID', $q->valueNew)->first();
                                if ($query_side) $valueNew = "{$query_side->fname} {$query_side->lname}";
                            }
                        }

                        $records[] = [
                            'field'     => $q->field, 
                            'label'     => $q->label, 
                            'valueOld'  => $valueOld, 
                            'valueNew'  => $valueNew, 
                        ];
                    }

                    /** final variables */
                    $items['action'] = $action;
                    $items['records'] = $records;

                    $data['items'] = $items;

                }

            } else {
                $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 
    
    public function get_changes_training_programs(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $records        = [];
            $hasError   = 0;

            /** query */
            if (!$hasError) {
                $query = DB::table('user_pds_change_requests');
                $query = $query->where('userID', $token_userID);
                $query = $query->where('type', 6); // training_program
                $query = $query->orderBy('dateInserted', 'desc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {
                        $records[] = [
                            'userPdsChangeRequestID' => $q->userPdsChangeRequestID, 
                            'dateInserted'  => $q->dateInserted ? date('m/d/Y h:i a', strtotime($q->dateInserted)) : '', 
                            'dateCancelled' => $q->dateCancelled ? date('m/d/Y h:i a', strtotime($q->dateCancelled)) : '', 
                            'dateApproved'  => $q->dateApproved ? date('m/d/Y h:i a', strtotime($q->dateApproved)) : '', 
                            'dateDenied'    => $q->dateDenied ? date('m/d/Y h:i a', strtotime($q->dateDenied)) : '', 
                            'remarks'       => $q->remarks, 
                            'status'        => $q->status, 
                        ];
                    }

                    /** final variables */
                    $items['records'] = $records;

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
    public function get_changes_training_program(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $token_userID = $request_token['data'];

            /** variables */
            $action  = 0;
            $isPending  = 0;
            $records    = [];

            $genders        = ['Female', 'Male'];
            $civilStatuses  = ['', 'Single', 'Married', 'Separated', 'Widowed'];
            $bloodTypes     = ['', 'O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];

            /** query */
            $query = DB::table('user_pds_change_requests');
            $query = $query->where('userPdsChangeRequestID', $id);
            $query = $query->first();
            if ($query) {

                $action = $query->action;

                $query = DB::table('user_pds_change_request_details');
                $query = $query->where('userPdsChangeRequestID', $id);
                $query = $query->orderBy('userPdsChangeRequestDetailID', 'asc');
                $query = $query->get();
                if ($query) {

                    foreach ($query as $q) {

                        $valueOld = $q->valueOld?$q->valueOld:'';
                        $valueNew = $q->valueNew?$q->valueNew:'';

                        if ($q->field == 'userID') {
                            if (!in_array($q->valueOld, ['', null])) {
                                $query_side = DB::table('user_personal_informations')->where('userID', $q->valueOld)->first();
                                if ($query_side) $valueOld = "{$query_side->fname} {$query_side->lname}";
                            }
                            if (!in_array($q->valueNew, ['', null])) {
                                $query_side = DB::table('user_personal_informations')->where('userID', $q->valueNew)->first();
                                if ($query_side) $valueNew = "{$query_side->fname} {$query_side->lname}";
                            }
                        }

                        $records[] = [
                            'field'     => $q->field, 
                            'label'     => $q->label, 
                            'valueOld'  => $valueOld, 
                            'valueNew'  => $valueNew, 
                        ];
                    }

                    /** final variables */
                    $items['action'] = $action;
                    $items['records'] = $records;

                    $data['items'] = $items;

                }

            } else {
                $data = $this->response->status(404, 'Unknown record.', 'Invalid!');
            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 

    // print
    public function print_pds_data(Request $request, string $id)
    {

        $data = $this->response->status(200);

        $items = [];

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $userID = $request_token['data'];
            
            $decrypted_id = $this->_decryptID($id);

            $personal_information           = [];
            $family_background              = [];
            $educational_background         = [];
            $civil_service_eligibilities    = [];
            $work_experiences               = [];
            $training_programs              = [];

            $query = DB::table('user_personal_informations');
            $query = $query->leftjoin('provinces', "user_personal_informations.permProvinceID", '=', 'provinces.provinceID'); 
            $query = $query->leftjoin('cities', "user_personal_informations.permCityID", '=', 'cities.cityID'); 
            $query = $query->leftjoin('barangays', "user_personal_informations.permBarangayID", '=', 'barangays.barangayID'); 
            $query = $query->where("user_personal_informations.userID", $userID);
            $query = $query->select(
                "user_personal_informations.*", 
                "provinces.name as pName", 
                "cities.name as cName", 
                "barangays.name as bName", 
            );
            $query = $query->first();

            if ($query) {

                // personal information
                $avatar = asset('assets/img/dp.jpg');
                if ($query->picExt) $avatar = asset("uploads/users/changes/{$query->userPdsChangeRequestDetailID}{$query->picExt}");

                $civilStatuses  = ['', 'Single', 'Married', 'Divorced', 'Widowed'];
                $bloodTypes     = ['', 'O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-'];

                $personal_information = [
                    'avatar'            => $avatar, 
                    'fname'             => $query->fname, 
                    'mname'             => $query->mname, 
                    'lname'             => $query->lname, 
                    'gender'            => $query->gender ? 'Male' : 'Female', 
                    'birthDate'         => $query->birthDate ? date('m/d/Y', strtotime($query->birthDate)) : '', 
                    'birthPlace'        => $query->birthPlace, 
                    'citizenship'       => $query->citizenship, 
                    'civilStatus'       => $civilStatuses[$query->civilStatus], 
                    'gsis'              => $query->gsis, 
                    'pagibig'           => $query->pagibig, 
                    'philhealth'        => $query->philhealth, 
                    'sss'               => $query->sss, 
                    'tin'               => $query->tin, 
                    'bloodType'         => $bloodTypes[$query->bloodType], 
                    'phone'             => $query->phone, 
                    'email'             => $query->email, 
                    'permProvince'      => $query->pName, 
                    'permCity'          => $query->cName, 
                    'permBarangay'      => $query->bName, 
                    'permStreet'        => $query->permStreet, 
                ];

                // family background 
                $family_background = [
                    'spouseFname'       => '', 
                    'spouseMname'       => '', 
                    'spouseLname'       => '', 
                    'spouseOccupation'  => '', 
                    'spouseBizName'     => '', 
                    'spouseBizAddress'  => '', 
                    'spouseTelNo'       => '', 
                    'fatherFname'       => '', 
                    'fatherMname'       => '', 
                    'fatherLname'       => '', 
                    'motherFname'       => '', 
                    'motherMname'       => '', 
                    'motherLname'       => '', 
                    'child1Name'        => '', 
                    'child1BirthDate'   => '', 
                    'child2Name'        => '', 
                    'child2BirthDate'   => '', 
                    'child3Name'        => '', 
                    'child3BirthDate'   => '', 
                    'child4Name'        => '', 
                    'child4BirthDate'   => '', 
                    'child5Name'        => '', 
                    'child5BirthDate'   => '', 
                    'child6Name'        => '', 
                    'child6BirthDate'   => '', 
                    'child7Name'        => '', 
                    'child7BirthDate'   => '', 
                    'child8Name'        => '', 
                    'child8BirthDate'   => '', 
                    'child9Name'        => '', 
                    'child9BirthDate'   => '', 
                    'child10Name'       => '', 
                    'child10BirthDate'  => '', 
                    'child11Name'       => '', 
                    'child11BirthDate'  => '', 
                    'child12Name'       => '', 
                    'child12BirthDate'  => '', 
                ];
                
                // 
                $query = DB::table('user_families');
                $query = $query->select( "user_families.*" );
                $query = $query->where("user_families.userID", $userID);
                $query = $query->first();
                if ($query) {
                    $family_background['spouseFname']    = $query->spouseFname;
                    $family_background['spouseMname']    = $query->spouseMname;
                    $family_background['spouseLname']    = $query->spouseLname;
                    $family_background['spouseOccupation']    = $query->spouseOccupation;
                    $family_background['spouseBizName']       = $query->spouseBizName;
                    $family_background['spouseBizAddress']    = $query->spouseBizAddress;
                    $family_background['spouseTelNo']         = $query->spouseTelNo;
                    $family_background['fatherFname']    = $query->fatherFname;
                    $family_background['fatherMname']    = $query->fatherMname;
                    $family_background['fatherLname']    = $query->fatherLname;
                    $family_background['motherFname']    = $query->motherFname;
                    $family_background['motherMname']    = $query->motherMname;
                    $family_background['motherLname']    = $query->motherLname;
                } 

                // 
                $query = DB::table('user_childrens');
                $query = $query->select( "user_childrens.*" );
                $query = $query->where("user_childrens.userID", $userID);
                $query = $query->orderBy("user_childrens.birthDate", 'asc');
                $query = $query->get();

                if ($query) {
                    $count = 0;
                    foreach ($query as $q) {
                        $count++;
                        if ($count <= 12) {
                            if (array_key_exists("child{$count}Name", $family_background)) $family_background["child{$count}Name"] = $q->name;
                            if (array_key_exists("child{$count}BirthDate", $family_background)) $family_background["child{$count}BirthDate"] = $q->birthDate ? date('m/d/Y', strtotime($q->birthDate)) : '';
                        }
                    }
                }

                // educational background 
                $educationLevels = [
                    '', 
                    'ELEMENTARY',  
                    'SECONDARY',  
                    'VOCATIONAL/ TRADE COURSE',  
                    'COLLEGE',  
                    'GRADUATE STUDIES',  
                ];
                for ($i=1; $i<=5; $i++) {
                    $query = DB::table('user_educations');
                    $query = $query->where('userID', $userID);
                    $query = $query->where('type', $i);
                    $query = $query->first();

                    $level              = $educationLevels[$i];
                    $schoolName         = "";
                    $degree             = "";
                    $dateAttendedFrom   = "";
                    $dateAttendedTo     = "";
                    $highestLevelEarned = "";
                    $yearGraduated      = "";
                    $scholarship        = "";

                    if ($query) {
                        $schoolName         = $query->schoolName;
                        $degree             = $query->degree;
                        $dateAttendedFrom   = $query->dateAttendedFrom ? date('m/d/Y', strtotime($query->dateAttendedFrom)) : '';
                        $dateAttendedTo     = $query->dateAttendedTo ? date('m/d/Y', strtotime($query->dateAttendedTo)) : '';
                        $highestLevelEarned = $query->highestLevelEarned;
                        $yearGraduated      = $query->yearGraduated;
                        $scholarship        = $query->scholarship;
                    }

                    $educational_background[] = [
                        'level'                 => $level, 
                        'schoolName'            => $schoolName, 
                        'degree'                => $degree, 
                        'dateAttendedFrom'      => $dateAttendedFrom, 
                        'dateAttendedTo'        => $dateAttendedTo, 
                        'highestLevelEarned'    => $highestLevelEarned, 
                        'yearGraduated'         => $yearGraduated, 
                        'scholarship'           => $scholarship, 
                    ];

                }

                // civil service eligibilities 
                $maxCount = 12;

                $query = DB::table('user_civil_services');
                $query = $query->where('userID', $userID);
                $query = $query->orderBy('userCivilServiceID', 'asc');
                $query = $query->limit($maxCount);
                $query = $query->get();
                
                if ($query) {
                    foreach ($query as $q) {
                        $civil_service_eligibilities[] = [
                            'name'                  => $q->name, 
                            'rating'                => $q->rating,  
                            'dateExamination'       => $q->dateExamination ? date('m/d/Y', strtotime($q->dateExamination)) : '', 
                            'placeExamination'      => $q->placeExamination, 
                            'licenseNumber'         => $q->licenseNumber, 
                            'licenseDateValidity'   => $q->licenseDateValidity ? date('m/d/Y', strtotime($q->licenseDateValidity)) : '', 
                        ];
                    }
                }

                $remainingCount = $maxCount - count($civil_service_eligibilities);
                if ($remainingCount) {
                    for ($i=0; $i<$remainingCount; $i++) {
                        $civil_service_eligibilities[] = [
                            'name'                  => '', 
                            'rating'                => '',  
                            'dateExamination'       => '', 
                            'placeExamination'      => '', 
                            'licenseNumber'         => '', 
                            'licenseDateValidity'   => '', 
                        ];
                    }
                }

                // work experiences  
                $maxCount = 30;

                $query = DB::table('user_works');
                $query = $query->where('userID', $userID);
                $query = $query->orderBy('dateFrom', 'asc');
                $query = $query->limit($maxCount);
                $query = $query->get();
                
                if ($query) {
                    foreach ($query as $q) {
                        $work_experiences[] = [
                            'dateFrom'          => $q->dateFrom ? date('m/d/Y', strtotime($q->dateFrom)) : '', 
                            'dateTo'            => $q->dateTo ? date('m/d/Y', strtotime($q->dateTo)) : '', 
                            'position'          => $q->position,  
                            'company'           => $q->company, 
                            'salary'            => $q->salary ? number_format($q->salary, 2) : '', 
                            'salaryGrade'       => $q->salaryGrade, 
                            'appointmentStatus' => $q->appointmentStatus, 
                            'isGovt'            => $q->isGovt ? 'Yes' : 'No', 
                        ];
                    }
                }

                $remainingCount = $maxCount - count($work_experiences);
                if ($remainingCount) {
                    for ($i=0; $i<$remainingCount; $i++) {
                        $work_experiences[] = [
                            'dateFrom'          => '', 
                            'dateTo'            => '', 
                            'position'          => '',  
                            'company'           => '', 
                            'salary'            => '', 
                            'salaryGrade'       => '', 
                            'appointmentStatus' => '', 
                            'isGovt'            => '', 
                        ];
                    }
                }

                // training programs 
                $maxCount = 20;

                $query = DB::table('user_trainings');
                $query = $query->where('userID', $userID);
                $query = $query->orderBy('dateFrom', 'asc');
                $query = $query->limit($maxCount);
                $query = $query->get();
                
                if ($query) {
                    foreach ($query as $q) {
                        $training_programs[] = [
                            'trainingName'  => $q->trainingName,  
                            'dateFrom'      => $q->dateFrom ? date('m/d/Y', strtotime($q->dateFrom)) : '', 
                            'dateTo'        => $q->dateTo ? date('m/d/Y', strtotime($q->dateTo)) : '', 
                            'hours'         => $q->hours, 
                            'ldType'        => $q->ldType, 
                            'sponsor'       => $q->sponsor, 
                        ];
                    }
                }

                $remainingCount = $maxCount - count($training_programs);
                if ($remainingCount) {
                    for ($i=0; $i<$remainingCount; $i++) {
                        $training_programs[] = [
                            'trainingName'  => '',  
                            'dateFrom'      => '', 
                            'dateTo'        => '', 
                            'hours'         => '', 
                            'ldType'        => '', 
                            'sponsor'       => '', 
                        ];
                    }
                }

            }

            $items['personal_information']          = $personal_information;
            $items['family_background']             = $family_background;
            $items['educational_background']        = $educational_background;
            $items['civil_service_eligibilities']   = $civil_service_eligibilities;
            $items['work_experiences']              = $work_experiences;
            $items['training_programs']             = $training_programs;

            $data['items'] = $items;

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


