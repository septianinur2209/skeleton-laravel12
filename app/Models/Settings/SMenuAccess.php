<?php

namespace App\Models\Settings;

use App\Models\Master\MMenu;
use App\Models\User;
use App\Traits\CreatedUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMenuAccess extends Model
{
    use HasFactory, CreatedUpdatedByTrait;

    protected $table = 's_menu_access';
    protected $fillable = [
        'role_id',
        'menu_id',
        'show',
        'create',
        'edit',
        'delete',
        /**Default timestamp */
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    public function role(){
        return $this->belongsTo(SRole::class,'role_id','id');
    }

    public function menu(){
        return $this->belongsTo(MMenu::class,'menu_id','id');
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
