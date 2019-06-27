<?php

namespace App\Filters\Customer;

use App\Filters\QueryFilters;
use Illuminate\Database\Eloquent\Builder;

class CustomerFilters extends QueryFilters
{
    public function customer_id($customer_id)
    {
        return $this->builder->where('id', $customer_id);
    }

    public function registration_card_number($registration_card_number)
    {
        return $this->builder->where('registration_card_number', 'LIKE', '%'. $registration_card_number .'%');
    }

    public function name($name)
    {
        $names = explode(" ", $name);

        return $this->builder
            ->whereIn('family_name', $names);
//            ->orWhere('first_name', $names);
    }

    public function tel($tel)
    {
        return $this->builder->where('tel', 'LIKE', '%'. $tel .'%');
    }

    public function birthday($birthday)
    {
        return $this->builder->whereDate('birthday', $birthday);
    }

    public function email($email)
    {
        return $this->builder->where('email', 'LIKE', '%'. $email .'%');
    }

    public function updated_at($updated_at)
    {
        return $this->builder->whereDate('updated_at', $updated_at);
    }

    public function name_sorting($sorting)
    {
        return $this->builder->orderBy('family_name', $sorting);
    }

    public function registration_card_number_sorting($registration_card_number)
    {
        return $this->builder->orderBy('registration_card_number', $registration_card_number);
    }

    public function birthday_sorting($birthday_sorting)
    {
        return $this->builder->orderBy('birthday', $birthday_sorting);
    }

    public function email_sorting($email_sorting)
    {
        return $this->builder->orderBy('email', $email_sorting);
    }

    public function updated_at_sorting($updated_at_sorting)
    {
        return $this->builder->orderBy('updated_at', $updated_at_sorting);
    }
}
