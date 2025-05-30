<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'description',
        'user_id',
        'read_status',
        'target_user_id',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id')->withTrashed();
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->read_status = false;
        });
    }
}
