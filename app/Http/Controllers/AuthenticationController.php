<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


use App\Libraries\Response;
use App\Libraries\TokenHelper;
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

class AuthenticationController extends MasterController
{

    protected $response;
    protected $module;
    protected $controller;
    protected $table;
    protected $tablePrimaryKey;
    protected $page;
    protected $view_path;
    protected $data;

    public function __construct()
    {
        $this->response         = new Response();
        $this->module           = 'Positions';
        $this->controller       = 'positions';
        $this->table            = 'positions';
        $this->tablePrimaryKey  = 'positionID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module));  
    }

    public function create_account()
    {

        $username = 'admin';
        
        $data = $this->response->status(200);
        
        if (User::where('username', $username)->exists()) {
            $data = $this->response->status(409, "Username already exist.", "Opps!");
        }

        if ($data['status'] == 200) {
            User::create([
                'userTypeID'    => 1, 
                'username'      => $username, 
                'password'      => bcrypt($username), 
            ]);
        }

        return response()->json($data);

    }

    public function login(Request $request)
    {

        
        $data = $this->response->status(200);
        
        $items = [];

        $deviceFingerprint = $request->header('Device-Identifier');
        $token = "";

        $username   = $request->input('username');
        $password   = $request->input('password');

        // check all filled
        if ($username && $password) {
            // check if username exist
            $row = User::where('username', $request->input('username'))->first();
            if ($row) {
                // check if password is valid
                if (Hash::check($password, $row->password)) {
                    // check if status is active
                    if ($row->status >= 0) {
                        // check if has index access 
                        $count = UserAccess::leftJoin('AppModuleActions', 'UserAccesses.appModuleActionID', '=', 'AppModuleActions.appModuleActionID');
                        $count = $count->where('UserAccesses.userID', $row->userID);
                        $count = $count->where('UserAccesses.status', 1);
                        $count = $count->where('AppModuleActions.appActionID', 1);
                        $count = $count->count();
                        if ($count) {
                            $time = $this->_getConfig('Token Time Limit');
                            if (!is_numeric($time) || !$time) $time = 8 * 60 * 60; // set token limit to 8hrs
                            $request_token = TokenHelper::token_encode($row->userID, (int) $time);
                            if ($request_token['code'] == 200) {
                                $token = $request_token['data'];
                                // insert auth log
                                $this->_authenticationLogRecordInsert($row->userID, $row->username, "Login Successful!", 1);

                                if (!$deviceFingerprint) $deviceFingerprint = Str::uuid();

                                // insert token to database 
                                $dateInserted = date('Y-m-d H:i:s');
                                Token::create([
                                    'userID'            => $row->userID, 
                                    'username'          => $row->username, 
                                    'deviceFingerprint' => $deviceFingerprint, 
                                    'token'             => $token, 
                                    'dateInserted'      => $dateInserted, 
                                    'dateExpired'       => date('Y-m-d H:i:s', strtotime($dateInserted." +$time seconds")), 
                                    'timeDuration'      => $time, 
                                    'timeUsed'          => 0, 
                                    'status'            => 1, 
                                ]);

                                $items['token'] = $token;
                                $items['deviceFingerprint'] = $deviceFingerprint;
                                $data['items'] = $items;

                            } else {
                                $data = $this->response->status(400, "Error when generating token");
                                // insert auth log
                                $this->_authenticationLogRecordInsert($row->userID, $row->username, $data['message'], -1);
                            }
                        } else {
                            $data = $this->response->status(400, "Account has no access.", "Invalid!");
                            // insert auth log
                            $this->_authenticationLogRecordInsert($row->userID, $row->username, $data['message'], -1);
                        }
                    } else {
                        $data = $this->response->status(400, "Account deactivated.", "Invalid!");
                        // insert auth log
                        $this->_authenticationLogRecordInsert($row->userID, $row->username, $data['message'], -1);
                    }
                } else {
                    $data = $this->response->status(400, "Invalid password.", "Opps!");
                    // insert auth log
                    $this->_authenticationLogRecordInsert($row->userID, $row->username, $data['message'], -1);
                }
            } else {
                $data = $this->response->status(400, "Username doesn't exist.", "Invalid!");
            }
        } else {
            $data = $this->response->status(400, "Please fill all fields.", "Invalid!");
        }

        return response()->json($data);

    }

    public function verify(Request $request)
    {
        
        $data = $this->response->status(200);

        $deviceFingerprint = $request->header('Device-Identifier');
        $token = JWTAuth::parseToken()->getToken();

        // check if token in database 
        $query = Token::where('token', $token);
        $query = $query->where('status', 1);
        $query = $query->orderBy('tokenID', 'asc');
        $query = $query->first();
        if ($query) {
            // check if uuid is same
            if ($deviceFingerprint == $query->deviceFingerprint) {
                // confirm token in library
                $request_token = TokenHelper::token_decode($token, 1);
                if ($request_token['code'] != 200) {
                    // if has error deactivate token in database
                    $data = $this->response->status($request_token['code'], $request_token['message']);
                    
                    // update record
                    $query->dateDeactivated = date('Y-m-d H:i:s');
                    $query->timeUsed = $query->timeDuration;
                    $query->status = 0;
                    $query->save();

                    // insert auth log
                    $this->_authenticationLogRecordInsert($row->userID, $row->username, "Token Expired", 0);
                }
            } else {
                $data = $this->response->status(401, 'Invalid Unique ID.');
            }
        } else {
            $data = $this->response->status(401, 'Invalid token.');
        }

        return response()->json($data);

    }

    public function UserAccess(Request $request)
    {

        $data = $this->response->status(200);

        $isAdminUsername = 0;

        $items      = [];
        $accesses   = [];

        $controller     = '';
        $controllers    = [];

        $modulesIndices = [
            69  => 'profile-setup', 
            1   => 'my-profile', 
            2   => 'home', 
            3   => 'my-leave-requests', 
            4   => 'my-travel-requests', 
            126 => 'my-attendances', 
            127 => 'my-payslips', 
            149 => 'dashboard', 
            5   => 'leave-requests', 
            6   => 'travel-requests', 
            150 => 'biometric-logs', 
            129 => 'attendances', 
            130 => 'payrolls', 
            170 => 'payroll-deductions', 
            171 => 'payroll-deduction-types', 
            161 => 'tax-brackets', 
            146 => 'leave-credits', 
            7   => 'employments', 
            8   => 'employees', 
            11  => 'job-positions', 
            10  => 'offices', 
            13  => 'provinces', 
            14  => 'municipalities', 
            15  => 'barangays', 
            9   => 'users', 
            12  => 'user-types', 
            16  => 'audit-logs', 
            17  => 'authentication-logs', 
            18  => 'configurations', 
        ];
        
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $userID = $request_token['data'];

            if ($userID==1) $isAdminUsername = 1;

            // check account status
            $accountPending = DB::table('users')->where('userID', $userID)->where('status', 0)->count();

            $query = UserAccess::leftJoin('AppModuleActions', 'UserAccesses.appModuleActionID', '=', 'AppModuleActions.appModuleActionID');
            $query = $query->leftJoin('AppModules', 'AppModuleActions.appModuleID', '=', 'AppModules.appModuleID');
            $query = $query->where('AppModuleActions.appActionID', 1);
            $query = $query->where('UserAccesses.userID', $userID);
            $query = $query->where('UserAccesses.status', 1);
            $query = $query->orderBy('AppModules.rank', 'asc');
            $query = $query->select('UserAccesses.appModuleActionID');
            $query = $query->get();

            if ($query) {
                foreach ($query as $q) {
                    $accesses[] = $q->appModuleActionID;

                    $module = $modulesIndices[$q->appModuleActionID];
                    if ($module) {
                        if (!in_array($module, $controllers)) $controllers[] = $module;
                    }
                }
            }

            // remove and add controller so that it would not display first
            $amaID = 69;
            if (!$accountPending) {
                if (in_array($amaID, $accesses)) {
                    $accesses = array_values(array_diff($accesses, [$amaID]));
                    $accesses[] = $amaID;
                }
            }

            // remove and add controller so that it would not display first
            $amaID = 1;
            if (in_array($amaID, $accesses)) {
                $accesses = array_values(array_diff($accesses, [$amaID]));
                $accesses[] = $amaID;
            }

            $items['controller'] = $modulesIndices[$accesses[0]];
            $items['controllers'] = $controllers;
            $items['accesses'] = $accesses;
            $items['isAdminUsername'] = $isAdminUsername;
            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);

    }

    public function user_notifications(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];

        // check if token and fingerprint is in database
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $userID = $request_token['data'];

            $travelOrders       = 0;
            $leaveApplications  = 0;

            if ($userID != 1) {
                
                $isMayor                = 0;
                $agusanDelSurProvinceID = 3;
                $isTravelChecker        = $this->_checkAccess($userID, 132);
                $isTravelApprover       = $this->_checkAccess($userID, 133);
                $isLeaveChecker         = $this->_checkAccess($userID, 140);
                $isLeaveApprover        = $this->_checkAccess($userID, 141);
    
                // isMayor?
                $query = DB::table('JobPositions');
                $query = $query->leftjoin('user_employments', "JobPositions.jobPositionID", '=', 'user_employments.jobPositionID');
                $query = $query->where('JobPositions.isMayor', 1);
                $query = $query->where('user_employments.userID', $userID);
                $query = $query->where('user_employments.status', 1);
                $query = $query->first();
                if ($query) $isMayor = 1;
    
                // travel
                $query = DB::table('travel_orders');
                $query->where(function ($subQuery) use ($userID, $isTravelChecker, $isTravelApprover, $isMayor, $agusanDelSurProvinceID) { 
                    // recommender
                    $subQuery->orWhere(function($subQuery2) use ($userID) {
                        $subQuery2->where('recommendedBy', $userID);
                        $subQuery2->where('recommenderUserEmploymentID', '!=', 0); 
                        $subQuery2->whereNull('dateRecommended'); 
                    });
                    // checker 
                    if ($isTravelChecker) {
                        $subQuery->orWhere(function($subQuery2) use ($userID) {
                            // $subQuery2->orWhere('checkedBy', $userID);
                            $subQuery2->orWhere(function($subQuery3) {
                                $subQuery3->where('status', 1);
                                $subQuery3->whereNotNull('dateRecommended');
                            });
                        });
                    }
                    // approver
                    if ($isTravelApprover) {
                        $subQuery->orWhere(function($subQuery2) use ($userID, $isMayor, $agusanDelSurProvinceID) {
                            if ($isMayor) {
                                $subQuery2->orWhere(function($subQuery3) {
                                    $subQuery3->where('status', 2);
                                    $subQuery3->whereNotNull('dateChecked');
                                });
                            } else {
                                $subQuery2->orWhere(function($subQuery3) use ($agusanDelSurProvinceID) {
                                    $subQuery3->where('status', 2);
                                    $subQuery3->where("travel_orders.provinceID", $agusanDelSurProvinceID);
                                });
                            }
                        });
                    }
                });
                $travelOrders = $query->count();
                
                // leave
                $query = DB::table('leave_applications');
                $query = $query->leftjoin('leave_types', "leave_applications.leaveTypeID", '=', 'leave_types.leaveTypeID');
                $query->where(function ($subQuery) use ($userID, $isLeaveChecker, $isLeaveApprover, $isMayor, $agusanDelSurProvinceID) { 
                    // recommender
                    $subQuery->orWhere(function($subQuery2) use ($userID) {
                        $subQuery2->orWhere(function($subQuery3) use ($userID) {
                            $subQuery3->where('leave_types.flow', 1);
                            $subQuery3->where('leave_applications.recommendedBy', $userID);
                            $subQuery3->where('leave_applications.status', 0);
                        });
                        $subQuery2->orWhere(function($subQuery3) use ($userID) {
                            $subQuery3->where('leave_types.flow', 2);
                            $subQuery3->where('leave_applications.recommendedBy', $userID);
                            $subQuery3->where('leave_applications.status', 1); 
                        });
                    });
                    // checker 
                    if ($isLeaveChecker) {
                        $subQuery->orWhere(function($subQuery2) use ($userID) {
                            $subQuery2->orWhere(function($subQuery3) use ($userID) {
                                $subQuery3->where('leave_types.flow', 1);
                                $subQuery3->where('leave_applications.status', 2);
                            });
                            $subQuery2->orWhere(function($subQuery3) use ($userID) {
                                $subQuery3->where('leave_types.flow', 2);
                                $subQuery3->where('leave_applications.status', 0); 
                            });
                        });
                    }
                    // approver
                    if ($isLeaveApprover) {
                        $subQuery->orWhere('status', 3);
                    }
                });
                $leaveApplications = $query->count();

            }

            $items['travelOrders']      = $travelOrders;
            $items['leaveApplications'] = $leaveApplications;

            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);

    }

    public function user_details(Request $request)
    {

        $data = $this->response->status(200);

        $items      = [];
        $avatar     = asset('assets/img/dp.jpg');
        $username   = '';
        $userType   = '';

        // check if token and fingerprint is in database
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $userID = $request_token['data'];

            $query = User::leftJoin('UserTypes', 'users.userTypeID', '=', 'UserTypes.userTypeID');
            $query = $query->leftJoin('user_personal_informations', 'users.userID', '=', 'user_personal_informations.userID');
            $query = $query->where('users.userID', $userID);
            $query = $query->select(
                'users.username', 
                'UserTypes.name as utName', 
                'user_personal_informations.userPdsChangeRequestDetailID', 
                'user_personal_informations.picExt', 
            );
            $query = $query->first();
            if ($query) {

                $query2 = DB::table('user_employments');
                $query2 = $query2->select( 'JobPositions.code' );
                $query2 = $query2->leftJoin('JobPositions', 'user_employments.jobPositionID', '=', 'JobPositions.jobPositionID');
                $query2 = $query2->where('user_employments.userID', $userID);
                $query2 = $query2->first();

                if ($query2) $userType = $query2->code;

                $avatar = asset('assets/img/dp.jpg');
                if ($query->picExt) $avatar = asset("uploads/users/changes/{$query->userPdsChangeRequestDetailID}{$query->picExt}")."?time=".time();

                $username = $query->username;
                if (!$userType) $userType = $query->utName;

            }

            $items['avatar']    = $avatar;
            $items['username']  = $username;
            $items['userType']  = $userType;
            $data['items']      = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);

    }

    public function check_password(Request $request)
    {

        $data = $this->response->status(200);

        $items = [];
        
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $userID = $request_token['data'];
            
            $query = User::where('userID', $userID);
            $query = $query->first();

            $needToChange = 0;
            if ($query) {
                if (Hash::check($query->username, $query->password)) $needToChange = 1;
            }

            $items['needToChange'] = $needToChange;
            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);

    }

    public function change_password(Request $request)
    {

        $data = $this->response->status(200);
        
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {

            $userID = $request_token['data'];

            $passwordOld   = $request->input('passwordOld');
            $passwordNew   = $request->input('passwordNew');
            $passwordCon   = $request->input('passwordCon');

            if ($passwordOld && $passwordNew && $passwordCon) {
                $query = User::where('userID', $userID);
                $query = $query->where('status', 1);
                $query = $query->first();
                if ($query) {
                    // check if password is valid
                    if (Hash::check($passwordOld, $query->password)) {
                        if ($passwordNew == $passwordCon) {
                            if (PasswordHelper::_isValidPassword($passwordNew)) {
                                $query->password = bcrypt($passwordNew);
                                $query->save();
                            } else {
                                $message = <<<EOT
                                Your password should be:
                                <br />✔️ At least 8 characters long
                                <br />✔️ Have at least one number (0-9)
                                <br />✔️ Include at least one uppercase letter (A-Z)
                                <br />✔️ Include at least one lowercase letter (a-z)
                                EOT;
                                $data = $this->response->status(400, $message, "Invalid!");
                            }
                        } else {
                            $data = $this->response->status(400, "New password does not match.", "Invalid!");
                        }
                    } else {
                        $data = $this->response->status(400, "Invalid old password.", "Opps!");
                    }
                } else {
                    $data = $this->response->status(400, "Unknown account.", "Invalid!");
                }
            } else {
                $data = $this->response->status(400, "Please fill all fields.", "Invalid!");
            }

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }

        return response()->json($data);

    }

    public function logout(Request $request)
    {

        $data = $this->response->status(200);
        
        $query = Token::where('token', JWTAuth::parseToken()->getToken());
        $query = $query->where('deviceFingerprint', $request->header('Device-Identifier'));
        $query = $query->where('status', 1);
        $query = $query->orderBy('tokenID', 'desc');
        $query = $query->first();

        if ($query) {

            $dateDeactivated = date('Y-m-d H:i:s');          
            $timeUsed = abs(strtotime($dateDeactivated) - strtotime($query->dateInserted));

            if ($timeUsed > $query->timeDuration) $timeUsed = $query->timeDuration;

            $query->dateDeactivated = $dateDeactivated;
            $query->timeUsed = $timeUsed;
            $query->status = 0;
            $query->save();
                    
            // insert auth log
            $this->_authenticationLogRecordInsert($query->userID, $query->username, "Log Out Successful", 0);
        }

        return response()->json($data);

    }

    public function forgot_password(Request $request)
    {

        $data = $this->response->status(200);
        
        // $this->_authenticationLogRecordInsert($row->userID, $row->username, $data['message'], -1);
        
        return response()->json($data);

    }

    public function reset_password(Request $request)
    {

        $data = $this->response->status(200);
        
        // $this->_authenticationLogRecordInsert($row->userID, $row->username, $data['message'], -1);
        
        return response()->json($data);

    }

}


