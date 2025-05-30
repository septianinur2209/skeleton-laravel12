<?php
namespace App\Traits;

trait CreatedUpdatedByTrait{

    public static function booted()
    {
        // updating created_by and updated_by when model is created
        static::creating(function ($model) {
            $model->created_by = auth()->user()->id ?? 1;
            $model->updated_at = null;
            $model->updated_by = null;
        });

        // updating updated_by when model is updated
        static::updating(function ($model) {
            $model->updated_by = auth()->user()->id ?? 1;
        });


    }

}
