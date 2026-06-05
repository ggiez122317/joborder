<?php
namespace App\Traits;

use App\Services\AuditLogService;
use Illuminate\Support\Facades\App;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->logAudit('created');
        });

        static::updated(function ($model) {
            $model->logAudit('updated');
        });

        static::deleted(function ($model) {
            $model->logAudit('deleted');
        });
    }

    public function logAudit(string $action, ?string $description = null, array $context = [])
    {
        $service = App::make(AuditLogService::class);
        $modelName = class_basename($this);
        $eventType = 'crud';
        $actionName = strtolower($modelName) . '-' . $action;
        $description ??= ucfirst($action) . ' ' . $modelName . ' record';

        $service->log(
            $eventType,
            $actionName,
            $description,
            request(),
            auth()->user(),
            get_class($this),
            $this->id,
            $context
        );
    }
}
