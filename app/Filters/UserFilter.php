<?php

namespace App\Filters;

use DateTime;
use Slim\Http\Request;
use Illuminate\Database\Eloquent\Builder;

class UserFilter extends Filter
{
    protected function setup()
    {
        if ($this->value('status') === null) {
            $this->query->where('is_active', true);
        }
    }

    protected function applyName(string $value)
    {
        $this->query->where('name', 'like', "%{$value}%");
    }

    protected function applyEmail(string $value)
    {
        $this->query->where('email', 'like', "{$value}%");
    }

    protected function applyStatus(string $value)
    {
        if ($value === 'inactive') {
            $this->query->where('is_active', false);
        } elseif ($value === 'active') {
            $this->query->where('is_active', true);
        }
    }
}