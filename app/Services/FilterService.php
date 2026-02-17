<?php

namespace App\Services;

use App\Enums\UserRole;

class FilterService
{
    public function getRoles()
    {
        return collect(UserRole::cases())->map(fn ($role) => [
            'value' => $role->value,
            'label' => $role->label(),
        ]);
    }

    public function getLoginStatus()
    {
        return collect([
            ['value' => 1, 'label' => 'Active'],
            ['value' => 0, 'label' => 'Inactive'],
        ]);
    }


}
