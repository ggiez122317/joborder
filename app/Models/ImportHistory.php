<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Traits\Auditable;

class ImportHistory extends Model
{
    use Auditable;

    protected $fillable = [
        'original_filename',
        'stored_path',
        'file_type',
        'status',
        'total_rows',
        'success_rows',
        'failed_rows',
        'notes',
        'error_report_path',
        'employee_id',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
