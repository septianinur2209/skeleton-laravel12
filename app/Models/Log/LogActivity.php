<?php

namespace App\Models\Log;

use App\Models\User;
use App\Traits\CreatedUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class LogActivity extends Model
{
    use HasFactory, CreatedUpdatedByTrait;

    protected $table = 'log_activities';
    protected $primaryKey = 'id';
    protected $fillable = [
        'action',
        'modul',
        'submodul',
        'user',
        'description',
        'created_at',
        'created_by',
        'updated_at',
    ];

    public function createdBy()
    {
        return $this->hasOne(User::class, 'created_by', 'id');
    }
}
