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

class DocumentCheckerController extends MasterController
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
        $this->module           = 'Document Checker';
        $this->controller       = 'document-checker';
        $this->logTitle         = 'Document Checker';
        $this->table            = '';
        $this->tablePrimaryKey  = '';
        $this->page             = '';
        $this->view_path        = 'modules/'.strtolower(str_replace(" ", "_", $this->module)); 

        // 1=Index, 2=PrintList, 3=Add, 4=View, 5=AuditLogs, 6=Edit, 7=Delete
        $this->moduleActionIDs  = [
            'Index'     => 0, 
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
    public function view(string $md5ID)
    {

        // initialize variables
        $this->page = 'View';
        $this->_setVariables();
        $data = $this->data;

        $isActive   = 0;
        $message    = 'VERIFIED DOCUMENT';

        $query = DB::table('printed_documents');
        $query = $query->select(
            "printed_document_types.name", 
        );
        $query = $query->leftjoin('printed_document_types', "printed_documents.printedDocumentTypeID", '=', 'printed_document_types.printedDocumentTypeID');
        $query = $query->where('printed_documents.md5ID', $md5ID);
        $query = $query->first();

        if ($query) {
            $isActive   = 1;
            $message    = "<b>Valid $query->name Document</b>";
        }

        $data['isActive'] = $isActive;
        $data['message'] = $message;

        return view($this->view_path."/".($this->page?strtolower($this->page):'index'), $data);

    }

}


