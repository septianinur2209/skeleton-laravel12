<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UniqueWithCaseSensitive implements Rule
{
    protected $tableName;
    protected $columnName;
    protected $id;
    protected $customAttributeName;

    /**
     * Create a new rule instance.
     * @param string $tableName
     * @param string $columnName
     * @param int $id
     * @param string $customAttributeName
     * @return void
     */
    public function __construct($tableName, $columnName, $id = null, $customAttributeName = null)
    {
        $this->tableName = $tableName;
        $this->columnName = $columnName;
        $this->id = $id;
        $this->customAttributeName = $customAttributeName;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $columnName = $this->columnName;
        $query = DB::table($this->tableName)->whereRaw("lower(trim($columnName)) = ?", [strtolower(trim($value))]);
        
        if (Schema::hasColumn($this->tableName, 'deleted_at')) {
            $query->whereNull('deleted_at');
        }

        if ($this->id) {
            $query->where('id', '!=', $this->id);
        }

        return !$query->first();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if ($this->customAttributeName !== null) {
            return "The $this->customAttributeName already exists.";
        }
        return 'The :attribute already exists.';
    }
}
