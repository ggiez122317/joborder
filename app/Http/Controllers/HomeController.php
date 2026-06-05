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

class HomeController extends MasterController
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
        $this->module           = 'Home';
        $this->controller       = 'home';
        $this->logTitle         = '';
        $this->table            = '';
        $this->tablePrimaryKey  = '';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'     => 2, 
            'PrintList' => 0, 
            'Insert'    => 0, 
            'View'      => 0, 
            'Audit'     => 0, 
            'Update'    => 0, 
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

    public function get_cards(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $token_userID = $request_token['data'];

            $leave  = DB::table('leave_applications')->where('userID', $token_userID)->count();
            $travel = DB::table('travel_orders')->where('userID', $token_userID)->count();

            $query  = DB::table('user_leave_credits')->where('userID', $token_userID)->first();

            $creditsVacation    = 0;
            $creditsSick        = 0;
            if ($query) {
                $creditsVacation    = $query->creditsVacation;
                $creditsSick        = $query->creditsSick;
            }
            
            $items['leave']             = number_format($leave, 0);
            $items['travel']            = number_format($travel, 0);
            $items['creditsVacation']   = number_format($creditsVacation, 3);
            $items['creditsSick']       = number_format($creditsSick, 3);

            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 

    public function get_calendar_events(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $token_userID = $request_token['data'];

            $events = [];

            // $events = [
            //     [
            //         "title" => "L: Vacation: Boracay",
            //         "start" => "2025-04-14 08:00",
            //         "end" => "2025-04-16 17:00",
            //         "extendedProps" => [ "calendar" => "Leave" ] 
            //     ], 
            //     [
            //         "title" => "T: Surigao City",
            //         "start" => "2025-04-25 14:13",
            //         "end" => "2025-04-26 14:13",
            //         "extendedProps" => [ "calendar" => "Travel" ] 
            //     ], 
            //     [
            //         "title" => "T: Cebu City",
            //         "start" => "2025-04-18 16:00",
            //         "end" => "2025-04-19 16:00",
            //         "extendedProps" => [ "calendar" => "Travel" ] 
            //     ], 
            //     [
            //         "title" => "AM In",
            //         "start" => "2025-04-18 08:00:00",
            //         "end" => "2025-04-18 09:00:00",
            //         "extendedProps" => [ "calendar" => "MissedLog" ] 
            //     ], 
            //     [
            //         "title" => "AM Out",
            //         "start" => "2025-04-18 12:00:00",
            //         "end" => "2025-04-18 13:00:00",
            //         "extendedProps" => [ "calendar" => "MissedLog" ] 
            //     ], 
            //     [
            //         "title" => "PM In",
            //         "start" => "2025-04-18 13:00:00",
            //         "end" => "2025-04-18 14:00:00",
            //         "extendedProps" => [ "calendar" => "MissedLog" ] 
            //     ], 
            //     [
            //         "title" => "PM Out",
            //         "start" => "2025-04-18 17:00:00",
            //         "end" => "2025-04-18 18:00:00",
            //         "extendedProps" => [ "calendar" => "MissedLog" ] 
            //     ], 
            //     // 
            //     [
            //         "title" => "08:00am (AM In)",
            //         "start" => "2025-04-17 08:00:00",
            //         "end" => "2025-04-17 09:00:00",
            //         "extendedProps" => [ "calendar" => "Attendance" ] 
            //     ], 
            //     [
            //         "title" => "12:00am (AM Out)",
            //         "start" => "2025-04-17 12:00:00",
            //         "end" => "2025-04-17 13:00:00",
            //         "extendedProps" => [ "calendar" => "Attendance" ] 
            //     ], 
            //     [
            //         "title" => "01:00pm (PM In)",
            //         "start" => "2025-04-17 13:00:00",
            //         "end" => "2025-04-17 14:00:00",
            //         "extendedProps" => [ "calendar" => "Attendance" ] 
            //     ], 
            //     [
            //         "title" => "05:00pm (PM Out)",
            //         "start" => "2025-04-17 17:00:00",
            //         "end" => "2025-04-17 18:00:00",
            //         "extendedProps" => [ "calendar" => "Attendance" ] 
            //     ], 

            //     [
            //         "title" => "L: Headache",
            //         "start" => "2025-04-30 16:00",
            //         "end" => "2025-04-30 16:00",
            //         "extendedProps" => [ "calendar" => "Leave" ] 
            //     ], 
            // ];
            
            $items['events'] = $events;

            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 

    public function get_leave_applications(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $token_userID = $request_token['data'];

            $leave_applications = [
                ['Disapproved', 'Pending', 'Checked', 'Recommended', 'Ready', 'Approved'], 
                [0, 0, 0, 0, 0, 0], 
            ];

            $leave_applications[1][0] = DB::table('leave_applications')->where('userID', $token_userID)->where('status', -1)->count();
            $leave_applications[1][1] = DB::table('leave_applications')->where('userID', $token_userID)->where('status', 0)->count();
            $leave_applications[1][2] = DB::table('leave_applications')->where('userID', $token_userID)->where('status', 1)->count();
            $leave_applications[1][3] = DB::table('leave_applications')->where('userID', $token_userID)->where('status', 2)->count();
            $leave_applications[1][4] = DB::table('leave_applications')->where('userID', $token_userID)->where('status', 3)->count();
            $leave_applications[1][5] = DB::table('leave_applications')->where('userID', $token_userID)->where('status', 4)->count();
            
            $items['leave_applications'] = $leave_applications;

            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    } 

    public function get_travel_orders(Request $request)
    {
        
        $data = $this->response->status(200);

        /** variables */
        $items = []; 

        // validate token
        $request_token = $this->_validateToken(JWTAuth::parseToken()->getToken(), $request->header('Device-Identifier'));
        if ($request_token['code'] == 200) {
            
            $token_userID = $request_token['data'];

            $travel_orders = [
                ['Disapproved', 'Pending', 'Recommended', 'Checked', 'Approved'], 
                [0, 0, 0, 0, 0], 
            ];

            $travel_orders[1][0] = DB::table('travel_orders')->where('userID', $token_userID)->where('status', -1)->count();
            $travel_orders[1][1] = DB::table('travel_orders')->where('userID', $token_userID)->where('status', 0)->count();
            $travel_orders[1][2] = DB::table('travel_orders')->where('userID', $token_userID)->where('status', 1)->count();
            $travel_orders[1][3] = DB::table('travel_orders')->where('userID', $token_userID)->where('status', 2)->count();
            $travel_orders[1][4] = DB::table('travel_orders')->where('userID', $token_userID)->where('status', 3)->count();
            
            $items['travel_orders'] = $travel_orders;

            $data['items'] = $items;

        } else {
            $data = $this->response->status($request_token['code'], $request_token['message']);
        }


        return response()->json($data);

    }

}


