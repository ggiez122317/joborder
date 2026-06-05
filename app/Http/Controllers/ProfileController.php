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

class ProfileController extends MasterController
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
        $this->module           = 'Profile';
        $this->controller       = 'profile';
        $this->logTitle         = 'Profile';
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
    public function view(string $id)
    {

        // initialize variables
        $this->page = 'View';
        $this->_setVariables();
        $data = $this->data;

        $userID = $this->_idConverter($id, 1);

        $isActive   = 0;
        $avatar     = asset('assets/img/dp.jpg');
        $lname      = '';
        $name       = '';
        $idNumber   = '';
        $phone      = '';
        $email      = '';
        $gender     = '';
        $age        = '';
        $office     = '';
        $position   = '';

        $query = DB::table('user_personal_informations');
        $query = $query->where('userID', $userID);
        $query = $query->first();

        if ($query) {

            $query1 = DB::table('user_employments');
            $query1 = $query1->select(
                "user_employments.*", 
                "offices.code as oCode", 
                "offices.name as oName", 
                "JobPositions.code as jpCode", 
                "JobPositions.name as jpName", 
            );
            $query1 = $query1->leftjoin('offices', "user_employments.officeID", '=', 'offices.officeID'); 
            $query1 = $query1->leftjoin('JobPositions', "user_employments.jobPositionID", '=', 'JobPositions.jobPositionID'); 
            $query1 = $query1->where('user_employments.userID', $userID);
            $query1 = $query1->where('user_employments.status', 1);
            $query1 = $query1->first();

            if ($query1) {
                $isActive   = 1;

                if ($query->picExt) $avatar = asset("uploads/users/changes/{$query->userPdsChangeRequestDetailID}{$query->picExt}")."?time=".time();

                $lname = ucfirst($query->lname);

                $name = ucfirst($query->fname);
                if ($query->mname) $name .= ' '.ucfirst($query->mname);
                if ($query->lname) $name .= ' '.ucfirst($query->lname);


                $office = $query1->oCode;
                if ($query1->oName) $office .= ' - '.ucfirst($query1->oName);

                $position = $query1->jpCode;
                if ($query1->jpName) $position .= ' - '.ucfirst($query1->jpName);


                $idNumber   = $query1->idNumber;
                $gender     = $query->gender ? 'Male' : 'Female';
                $age        = $query->birthDate ?(date('Y') - date('Y', strtotime($query->birthDate))) . " years old" : '-';
                $email      = $query->email ? $query->email : '-';
                $phone      = $query->phone ? $query->phone : '-';
            }

        }

        $data['id'] = $id;
        $data['isActive'] = $isActive;

        $data['avatar'] = $avatar;
        $data['name'] = $name;
        $data['lname'] = $lname;
        $data['idNumber'] = $idNumber;
        $data['phone'] = $phone;
        $data['email'] = $email;
        $data['gender'] = $gender;
        $data['age'] = $age;
        $data['office'] = $office;
        $data['position'] = $position;

        return view($this->view_path."/".($this->page?strtolower($this->page):'index'), $data);

    }

}


