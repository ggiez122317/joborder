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

class MyTravelRequestController extends MasterController
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
        $this->module           = 'My Travel Requests';
        $this->controller       = 'my-travel-requests';
        $this->logTitle         = 'My Travel Request';
        $this->table            = 'travel_orders';
        $this->tablePrimaryKey  = 'travelOrderID';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'     => 4, 
            'PrintList' => 0, 
            'Insert'    => 122, 
            'View'      => 123, 
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
                'code'          => 'likeboth', 
                'dateFrom'      => 'where', 
                'dateTo'        => 'where', 
                'destination'   => 'likeboth', 
                'purpose'       => 'likeboth', 
                'status'        => 'where', 
            ];
    
            /** sort tables */
            $sort_tables = [
                'code'          => $this->table, 
                'dateFrom'      => $this->table, 
                'dateTo'        => $this->table, 
                'destination'   => $this->table, 
                'status'        => $this->table, 
            ];

            // query count
            $query = DB::table($this->table);
            $query = $query->leftjoin('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
            $query = $query->leftjoin('cities', "{$this->table}.cityID", '=', 'cities.cityID');
            if ($conditions) {
                foreach ($conditions as $cField => $cType) {
                    $value = isset($_GET[$cField])?$_GET[$cField]:'';
                    if ($cType == 'likeboth') $query = $query->where($cField, 'like', "%{$value}%");
                }
                $query = $query->where("{$this->table}.userID", $token_userID); 
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
                "cities.name as cName", 
            );
            $query = $query->leftjoin('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
            $query = $query->leftjoin('cities', "{$this->table}.cityID", '=', 'cities.cityID');
            if ($conditions) {
                foreach ($conditions as $cField => $cType) {
                    $value = isset($_GET[$cField])?$_GET[$cField]:'';
                    if ($cType == 'likeboth') $query = $query->where($cField, 'like', "%{$value}%"); 
                    // filters 
                    $filters[$cField] = $value;
                }
                $query = $query->where("{$this->table}.userID", $token_userID); 
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

                    $records[] = [
                        'travelOrderID' => Crypt::encryptString("{$tr->travelOrderID}"), 
                        'dateInserted'  => $tr->dateInserted?date('m/d/Y h:ia', strtotime($tr->dateInserted)):'', 
                        'code'          => $tr->code, 
                        'dateFrom'      => $tr->dateFrom?date('m/d/Y', strtotime($tr->dateFrom)):'', 
                        'dateTo'        => $tr->dateTo?date('m/d/Y', strtotime($tr->dateTo)):'', 
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

            $items['statuses'] = [
                ['status'=>'1', 'name'=>'Approved'], 
                ['status'=>'0', 'name'=>'Pending'], 
                ['status'=>'-1', 'name'=>'Disapproved'], 
            ];

            $data['items'] = $items;

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

                $recommenders = [];

                // get current position 
                // get immediate superior 
                $query = DB::table('JobPositions');
                $query = $query->select("JobPositions.head_positionID");
                $query = $query->leftjoin('user_employments', "JobPositions.jobPositionID", '=', 'user_employments.jobPositionID');
                $query = $query->where("user_employments.userID", $token_userID);
                $query = $query->where("user_employments.status", 1);
                $query = $query->first();

                $head_positionID = 0;
                if ($query) $head_positionID = $query->head_positionID;

                // get superior name
                if ($head_positionID) {
                    $query = DB::table('user_employments');
                    $query = $query->select(
                        "user_employments.userID", 
                        "user_personal_informations.lname", 
                        "user_personal_informations.fname", 
                        "user_personal_informations.mname", 
                    );
                    $query = $query->leftjoin('user_personal_informations', "user_employments.userID", '=', 'user_personal_informations.userID');
                    $query = $query->where("user_employments.jobPositionID", $head_positionID);
                    $query = $query->where("user_employments.status", 1);
                    $query = $query->orderBy("user_personal_informations.lname");
                    $query = $query->orderBy("user_personal_informations.fname");
                    $query = $query->get();

                    if ($query) {
                        foreach ($query as $q) {
                            $recommenders[] = [
                                'userID' => $q->userID, 
                                'name' => "$q->lname, $q->fname $q->mname", 
                            ];
                        }
                    }

                }


                $items['hasButtonAdd'] = $this->_checkAccess($token_userID, $this->moduleActionIDs['Insert']);
                $items['recommenders'] = $recommenders;
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
                $request_fields     = ['dateFrom', 'dateTo', 'recommendedBy', 'travelWorkingDays', 'provinceID', 'cityID', 'destination', 'purpose', 'appropriation', 'remarks'];
        
                /** variables */
                $validExtensions    = ['png', 'jpg', 'jpeg', 'gif', 'xlsx', 'xls', 'pdf', 'ppt', 'pptx', 'doc', 'docx'];
                $id                 = '';
                $request_data       = [];
                $hasError           = 0;
                $requires           = '';
                $required_fields    = [
                    'travelWorkingDays' => 'Travel Days (Working Days)', 
                    'dateFrom'          => 'Date From', 
                    'dateTo'            => 'Date To', 
                    'purpose'           => 'Purpose', 
                ];

                $destinationPath = public_path('uploads/travel_orders/');
                if (!file_exists($destinationPath)) mkdir($destinationPath, 0755, true);

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
                if (!($request_data['provinceID'] && $request_data['cityID'])) {
                    $hasError = 1;
                    $requires .= ($requires ? ", " : "")."Destination";
                }
                if ($hasError) $data = $this->response->status(401, $requires, 'Required field(s):');

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
        
                /** query */
                if (!$hasError) {

                    $userEmploymentID               = 0; 
                    $recommendedBy                  = $request_data['recommendedBy']?$request_data['recommendedBy']:0;
                    $recommenderUserEmploymentID    = 0; 
                    $checkerUserEmploymentID        = 0; 
                    $approverUserEmploymentID       = 0; 
                    $disapproverUserEmploymentID    = 0; 

                    if (is_numeric($token_userID)) {
                        $query = DB::table('user_employments')->where('userID', $token_userID)->where('status', 1)->first();
                        if ($query) $userEmploymentID = $query->userEmploymentID;
                    }

                    if ($recommendedBy) {
                        $query = DB::table('user_employments')->where('userID', $recommendedBy)->where('status', 1)->first();
                        if ($query) $recommenderUserEmploymentID = $query->userEmploymentID;
                    }

                    $request_fields[] = 'userID';
                    $request_fields[] = 'userEmploymentID';
                    $request_fields[] = 'code';
                    $request_fields[] = 'dateInserted';
                    $request_fields[] = 'recommenderUserEmploymentID';
                    $request_fields[] = 'checkedBy';
                    $request_fields[] = 'checkerUserEmploymentID';
                    $request_fields[] = 'approvedBy';
                    $request_fields[] = 'approverUserEmploymentID';
                    $request_fields[] = 'disapprovedBy';
                    $request_fields[] = 'disapproverUserEmploymentID';
                    $request_fields[] = 'comment';
                    $request_fields[] = 'status';

                    $request_data['userID']                         = $token_userID;
                    $request_data['dateInserted']                   = date('Y-m-d H:i:s');
                    $request_data['recommendedBy']                  = $recommendedBy;
                    $request_data['recommenderUserEmploymentID']    = $recommenderUserEmploymentID;
                    $request_data['checkedBy']                      = 0;
                    $request_data['checkerUserEmploymentID']        = $checkerUserEmploymentID;
                    $request_data['approvedBy']                     = 0;
                    $request_data['approverUserEmploymentID']       = $approverUserEmploymentID;
                    $request_data['disapprovedBy']                  = 0;
                    $request_data['disapproverUserEmploymentID']    = $disapproverUserEmploymentID;
                    $request_data['comment']                        = '';
                    $request_data['status']                         = 0;

                    // code
                    $year2 = date('y'); 
                    $year4 = date('Y'); 
                    $query = DB::table('travel_orders'); 
                    $query = $query->where('code', 'like', "%$year2"); 
                    $query = $query->where('dateInserted', 'like', "%$year4%"); 
                    $query = $query->orderBy('travelOrderID', 'desc'); 
                    $query = $query->first(); 

                    $code = "MO #00001-{$year2}"; 
                    if ($query) {
                        $num = substr($query->code, 4, 5); 
                        $num += 1; 
                        $num = str_pad($num, 5, "0", STR_PAD_LEFT);
                        $code = "MO #{$num}-{$year2}"; 
                    }
                    $request_data['code'] = $code; 

                    // userEmploymentID
                    $query = DB::table('user_employments');
                    $query = $query->where('userID', $token_userID);
                    $query = $query->where('status', 1);
                    $query = $query->first();

                    $userEmploymentID = 0; 
                    if ($query) $userEmploymentID = $query->userEmploymentID; 
                    $request_data['userEmploymentID'] = $userEmploymentID; 

                    $pkID = DB::table($this->table)->insertGetId($request_data);
                    if ($pkID) {
                        // return encrypted id
                        $id = Crypt::encryptString("{$pkID}");
                        // insert audit logs
                        $logFields = $request_fields;
                        $this->_auditLog($request_token['data'], $this->moduleActionIDs['Insert'], $this->table, $this->tablePrimaryKey, $pkID, $request_data, "Inserted {$this->logTitle} Record", $logFields, 1);


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
                    $query = $query->leftjoin('user_personal_informations as recommender', "{$this->table}.recommendedBy", '=', 'recommender.userID'); 
                    $query = $query->leftjoin('user_personal_informations as checker', "{$this->table}.checkedBy", '=', 'checker.userID'); 
                    $query = $query->leftjoin('user_personal_informations as approver', "{$this->table}.approvedBy", '=', 'approver.userID'); 
                    $query = $query->leftjoin('user_personal_informations as disapprover', "{$this->table}.disapprovedBy", '=', 'disapprover.userID'); 
                    $query = $query->leftjoin('provinces', "{$this->table}.provinceID", '=', 'provinces.provinceID');
                    $query = $query->leftjoin('cities', "{$this->table}.cityID", '=', 'cities.cityID');
                    $query = $query->where("{$this->table}.{$this->tablePrimaryKey}", $decrypted_id);
                    $query = $query->select(
                        "{$this->table}.*", 
                        "recommender.fname as rFname", 
                        "recommender.mname as rMname", 
                        "recommender.lname as rLname", 
                        "checker.fname as cFname", 
                        "checker.mname as cMname", 
                        "checker.lname as cLname", 
                        "approver.fname as aFname", 
                        "approver.mname as aMname", 
                        "approver.lname as aLname", 
                        "disapprover.fname as dFname", 
                        "disapprover.mname as dMname", 
                        "disapprover.lname as dLname", 
                        "provinces.name as pName", 
                        "cities.name as cName", 
                    );
                    $query = $query->first();
                    if ($query) {

                        $dateFrom   = $query->dateFrom?date('m/d/Y', strtotime($query->dateFrom)):''; 
                        $dateTo     = $query->dateTo?date('m/d/Y', strtotime($query->dateTo)):''; 

                        $date = $dateFrom;
                        if ($dateTo) {
                            if ($dateFrom != $dateTo) $date .= " - {$dateTo}";
                        }

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

                        $destination = $query->destination?$query->destination:'';
                        if ($query->cName) $destination = $destination ? "$destination, $query->cName" : $query->cName;
                        if ($query->pName) $destination = $destination ? "$destination, $query->pName" : $query->pName;

                        $row = [
                            'code'          => $query->code, 
                            'date'          => $date, 
                            'destination'   => $destination, 
                            'purpose'       => $query->purpose?$query->purpose:'', 
                            'appropriation' => $query->appropriation?$query->appropriation:'', 
                            'remarks'       => $query->remarks?$query->remarks:'', 
                            'dateInserted'  => $query->dateInserted?date('m/d/Y h:ia', strtotime($query->dateInserted)):'', 
                            'recommender'   => "{$query->rFname} {$query->rMname} {$query->rLname}", 
                            'checker'       => "{$query->cFname} {$query->cMname} {$query->cLname}", 
                            'approver'      => "{$query->aFname} {$query->aMname} {$query->aLname}", 
                            'disapprover'   => "{$query->dFname} {$query->dMname} {$query->dLname}", 
                            'comment'       => $query->comment, 
                            'status'        => $query->status, 
                            'files'         => $files, 
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


