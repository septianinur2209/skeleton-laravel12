<?php

namespace App\Models\Settings;

use App\Models\User;
use App\Traits\CreatedUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SUserRole extends Model
{
    use HasFactory, CreatedUpdatedByTrait;
    protected $table = 's_user_role';
    protected $fillable = [
        'id',
        'user_id',
        'role_id',
        /**Default timestamp */
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(SRole::class, 'role_id', 'id');
    }
}
