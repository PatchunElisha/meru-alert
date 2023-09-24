<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    public function setFirstIdAttribute(int $value): void
    {
        $this->attributes['first_id'] = empty($value) ? null : $value;
    }

    public function setSecondIdAttribute(int $value): void
    {
        $this->attributes['second_id'] = empty($value) ? null : $value;
    }
}
