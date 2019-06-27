<?php

namespace App\Filters\User;

use App\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Builder;

class UserFilters extends QueryFilters
{
    public function name($name)
    {
        return $this->builder->where('name', 'LIKE', "%". $name . "%");
    }

    public function email($email)
    {
        return $this->builder->where('email', 'LIKE', "%". $email . "%");
    }
}
