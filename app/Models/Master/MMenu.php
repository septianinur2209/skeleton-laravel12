<?php

namespace App\Models\Master;

use App\Models\User;
use App\Traits\CreatedUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MMenu extends Model
{
    use HasFactory, CreatedUpdatedByTrait;

    protected $table = 'm_menus';
    protected $fillable = [
        'menu',
        'description',
        /**Default timestamp */
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
    ];

    public function createdBy()
    {
        return $this->hasOne(User::class, 'created_by', 'id');
    }

    public function updatedBy()
    {
        return $this->hasOne(User::class, 'updated_by', 'id');
    }
}
