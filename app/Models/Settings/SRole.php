<?php

namespace App\Models\Settings;

use App\Models\User;
use App\Traits\CreatedUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SRole extends Model
{
    use HasFactory, SoftDeletes, CreatedUpdatedByTrait;
    protected $table = 's_role';
    protected $fillable = [
        'id',
        'role',
        'name',
        /**Default timestamp */
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    public function menuAccess()
    {
        return $this->hasMany(SMenuAccess::class, 'role_id', 'id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }

    public function userMenu()
    {
        return $this->hasMany(SMenuAccess::class, 'role_id', 'role_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->hasOne(User::class, 'updated_by', 'id');
    }
}
