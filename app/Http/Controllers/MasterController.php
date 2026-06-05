<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Encryption\DecryptException;

use App\Libraries\TokenHelper;

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

class MasterController extends Controller
{

    protected $auditActionColors = [
        'add'               => 'primary', 
        'edit'              => 'warning', 
        'delete'            => 'danger', 
        'change password'   => 'info', 
    ];
    protected $allowedImageExtensions = ['png', 'jpg', 'jpeg', 'gif'];

    protected function _convertImageToBase64($imageUrl)
    {
        $imagePath = "{$_SERVER['DOCUMENT_ROOT']}/{$imageUrl}";
        $imageContents = file_get_contents($imagePath);
        $base64Image = base64_encode($imageContents);
        $mimeType = mime_content_type($imagePath);
        $base64ImageWithType = 'data:' . $mimeType . ';base64,' . $base64Image;
        return $base64ImageWithType;
    }

    protected function _idConverter($data, $isDecrypt=0)
    {

        $magicNum1 = 123;
        $magicNum2 = 45;
        $pad = "700";

        // encrypt
        if (!$isDecrypt) {
            $data = $pad.($data*$magicNum1)+$magicNum2;
        } else {
            if (strlen($data)>3) {
                $data = substr($data, 3);
                $data = $data-$magicNum2;
                $data = $data/$magicNum1;
            } else {
                return 0;
            }
        }

        return $data;

    }

    protected function _decryptID($id)
    {
        try {
            return Crypt::decryptString($id);
        } catch (DecryptException $e) {
            return null; 
        }
    }

    protected function _getConfig($name)
    {
        $query = Configuration::where('name', $name)->first();
        if ($query) return $query['value'];
        return "";
    }

    protected function _authenticationLogRecordInsert($userID, $username, $remarks, $status)
    {
        AuthenticationLog::create([
            'userID'        => $userID, 
            'username'      => $username, 
            'ipAddress'     => request()->header('X-Forwarded-For') ?? request()->ip(), 
            'userAgent'     => request()->header('User-Agent'), 
            'remarks'       => $remarks, 
            'dateInserted'  => date('Y-m-d H:i:s'), 
            'status'        => $status, 
        ]);
    }

    // audit
    protected function _auditLog($userID, $appModuleActionID, $tableName, $primaryKey, $primaryKeyID, $dataNewTemp, $remarks, $logFields, $isInsertOrUpdate=0)
    {

        $query = AuditLog::where("tableName", $tableName);
        $query = $query->where("primaryKey", $primaryKey);
        $query = $query->where("primaryKeyID", $primaryKeyID);
        $query = $query->orderBy("auditLogID", "desc");
        $query = $query->first();
        $dataOldTemp = ($query ? json_decode($query->dataNew) : []);

        // username
        $query = User::where('userID', $userID)->first();
        $username = ($query?$query->username:'');

        // appID
        $query = AppModule::leftJoin('AppModuleActions', 'AppModules.appModuleID', '=', 'AppModuleActions.appModuleID');
        $query = $query->where('AppModuleActions.appModuleActionID', $appModuleActionID);
        $query = $query->select('AppModules.appID');
        $query = $query->first();
        $appID = ($query?$query->appID:0);

        // format data old
        $dataOld = [];
        if ($dataOldTemp) {
            foreach ($dataOldTemp as $lField => $lValue) {
                if (in_array($lField, $logFields)) $dataOld[$lField] = $lValue;
            }
        }

        // format data old
        $dataNew = [];
        if ($dataNewTemp) {
            foreach ($dataNewTemp as $lField => $lValue) {
                if (in_array($lField, $logFields)) $dataNew[$lField] = $lValue;
            }
        }

        // query
        $query = AuditLog::create([ 
            'userID'            => $userID, 
            'username'          => $username, 
            'ipAddress'         => request()->ip(), 
            'userAgent'         => request()->header('User-Agent'), 
            'appID'             => $appID, 
            'appModuleActionID' => $appModuleActionID, 
            'tableName'         => $tableName, 
            'primaryKey'        => $primaryKey, 
            'primaryKeyID'      => $primaryKeyID, 
            'dateInserted'      => date('Y-m-d H:i:s'), 
            'dataOld'           => json_encode($dataOld), 
            'dataNew'           => json_encode($dataNew), 
            'remarks'           => $remarks, 
        ]); 

        // audit log details insert
        if ($isInsertOrUpdate) {
            $this->_auditLogDetail($query->auditLogID, $dataOld, $dataNew);
        }

    }
    private function _auditLogDetail($auditLogID, $dataOld, $dataNew)
    {

        $hasChanges = 0;
        foreach ($dataNew as $lField => $lValue) {
            if (!$dataOld || ($dataOld[$lField] != $dataNew[$lField])) {
                
                $hasChanges = 1;

                AuditLogDetail::create([ 
                    'auditLogID'    => $auditLogID, 
                    'field'         => $lField, 
                    'valueOld'      => $dataOld?$dataOld[$lField]:'', 
                    'valueNew'      => $dataNew?$dataNew[$lField]:'', 
                ]); 
            }
        }

        if (!$hasChanges) AuditLog::where('auditLogID', $auditLogID)->delete();

    }
    protected function _auditLogGet($decrypted_id, $table, $tablePrimaryKey, $auditFieldValues)
    {

        $items = [];

        $AuditLogs         = [];
        $AuditLogDetails  = [];

        // audit logs 
        $query = AuditLog::leftJoin('users', 'AuditLogs.userID', '=', 'users.userID');
        $query = $query->leftJoin('AppModuleActions', 'AuditLogs.appModuleActionID', '=', 'AppModuleActions.appModuleActionID');
        $query = $query->leftJoin('AppModules', 'AppModuleActions.appModuleID', '=', 'AppModules.appModuleID');
        $query = $query->leftJoin('apps', 'AppModules.appID', '=', 'apps.appID');
        $query = $query->leftJoin('AppActions', 'AppModuleActions.appActionID', '=', 'AppActions.appActionID');
        $query = $query->where('AuditLogs.primaryKeyID', $decrypted_id);
        $query = $query->where('AuditLogs.tableName', $table);
        $query = $query->where('AuditLogs.primaryKey', $tablePrimaryKey);
        $query = $query->orderBy('AuditLogs.auditLogID', 'desc');
        $query = $query->select(
            'AuditLogs.dateInserted', 
            'AuditLogs.ipAddress', 
            'AuditLogs.userAgent', 
            'AuditLogs.username', 
            'apps.name as aName', 
            'AppModules.name as amName', 
            'AppActions.name as acName', 
            'AuditLogs.remarks', 
        );
        $query = $query->get();

        if ($query) {
            foreach ($query as $q) {
                $AuditLogs[] = [
                    'date'          => date('m/d/Y h:ia', strtotime($q->dateInserted)), 
                    'ipAddress'     => $q->ipAddress, 
                    'deviceInfo'    => $q->userAgent, 
                    'user'          => "{$q->username}", 
                    'module'        => "{$q->aName} - {$q->amName}", 
                    'action'        => "<span class='text-{$this->auditActionColors[strtolower($q->acName)]}'>$q->acName</span>", 
                    'remarks'       => $q->remarks, 
                ];
            }
        }

        // audit log details 
        $query = AuditLogDetail::leftJoin('AuditLogs', 'AuditLogDetails.auditLogID', '=', 'AuditLogs.auditLogID');
        $query = $query->leftJoin('AppModuleActions', 'AuditLogs.appModuleActionID', '=', 'AppModuleActions.appModuleActionID');
        $query = $query->leftJoin('AppActions', 'AppModuleActions.appActionID', '=', 'AppActions.appActionID');
        $query = $query->where('AuditLogs.primaryKeyID', $decrypted_id);
        $query = $query->where('AuditLogs.tableName', $table);
        $query = $query->where('AuditLogs.primaryKey', $tablePrimaryKey);
        $query = $query->orderBy('AuditLogDetails.auditLogDetailID', 'desc');
        $query = $query->select(
            'AuditLogs.dateInserted', 
            'AuditLogs.ipAddress', 
            'AuditLogs.username', 
            'AppActions.name as acName', 
            'AuditLogDetails.field', 
            'AuditLogDetails.valueOld', 
            'AuditLogDetails.valueNew', 
        );
        $query = $query->get();

        if ($query) {
            foreach ($query as $q) {

                $tableName  = $auditFieldValues[$q->field][1];
                $tableField = $auditFieldValues[$q->field][2];
                $valueOld = $q->valueOld;
                $valueNew = $q->valueNew;
                if ($tableName && $tableField) {
                    // get old value
                    if ($valueOld) {
                        $query = DB::table($tableName);
                        $query = $query->select( "{$tableName}.{$tableField}" );
                        $query = $query->first();
                        if ($query) $valueOld = $query->$tableField;
                    }
                    // get new value
                    if ($valueNew) {
                        $query = DB::table($tableName);
                        $query = $query->select( "{$tableName}.{$tableField}" );
                        $query = $query->where( "{$tableName}.{$q->field}", $valueNew);
                        $query = $query->first();
                        if ($query) $valueNew = $query->$tableField;
                    }
                }

                $AuditLogDetails[] = [
                    'date'      => date('m/d/Y h:ia', strtotime($q->dateInserted)), 
                    'ipAddress' => $q->ipAddress, 
                    'user'      => "{$q->username}", 
                    'action'    => "<span class='text-{$this->auditActionColors[strtolower($q->acName)]}'>$q->acName</span>", 
                    'field'     => $auditFieldValues[$q->field][0], 
                    'valueOld'  => !is_null($valueOld)?$valueOld:'', 
                    'valueNew'  => !is_null($valueNew)?$valueNew:'', 
                ];
            }
        }

        $items['AuditLogs'] = $AuditLogs;
        $items['AuditLogDetails'] = $AuditLogDetails;

        return $items;

    }

    protected function _validateToken($token, $fingerprint)
    {
        $request_token = TokenHelper::token_decode($token);

        if ($request_token['code'] == 200 && $fingerprint) {

            $query = Token::where('token', $token);
            $query = $query->where('deviceFingerprint', $fingerprint);
            $query = $query->where('status', 1);
            $query = $query->orderBy('tokenID', 'desc');
            $query = $query->first();
            
            if ($query) {
                // check if time expires
                if ($query->dateExpired <= date('Y-m-d H:i:s')) {
                    $query->dateDeactivated = date('Y-m-d H:i:s');
                    $query->timeUsed = $query->timeDuration;
                    $query->status = 0;
                    $query->save();
                    
                    // insert auth log
                    $this->_authenticationLogRecordInsert($query->userID, $query->username, "Token Expired", 0);

                    $request_token['code']      = 401;
                    $request_token['message']   = 'Invalid token';
                } 
            } else {
                $request_token['code']      = 401;
                $request_token['message']   = 'Invalid token';
            }
        } else {
            $request_token['code']      = 401;
            $request_token['message']   = 'Invalid token';
        }

        return $request_token;
    }

    protected function _checkAccess($userID, $appModuleActionID)
    {
        
        $query = UserAccess::where('userID', $userID);
        $query = $query->where('appModuleActionID', $appModuleActionID);
        $query = $query->where('status', 1);
        return $query->count();
        
    }

    protected function _printDocument($printedDocumentTypeID, $insertedBy)
    {
        
        $tableName = 'printed_documents';
        $tablePrimaryKey = 'printedDocumentID';

        $printedDocumentID = DB::table($tableName)->insertGetId([
            'md5ID' => '', 
            'printedDocumentTypeID' => $printedDocumentTypeID, 
            'insertedBy' => $insertedBy, 
            'dateInserted' => date('Y-m-d H:i:s'), 
        ]);
        DB::table($tableName)->where($tablePrimaryKey, $printedDocumentID)->update([
            'md5ID' => md5($printedDocumentID)
        ]);
        return $printedDocumentID;
        
    }

}


