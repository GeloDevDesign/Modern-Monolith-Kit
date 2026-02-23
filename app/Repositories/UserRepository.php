<?php

namespace App\Repositories;

use App\Models\User;
use App\Enums\UserRole;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAllAdmin()
    {
        return $this->model->where('type', '!=', UserRole::TYPE_ADMIN);
    }

    public function getAllSuperAdmin()
    {
        return $this->model->where('type', UserRole::TYPE_SUPER_ADMIN);
    }

    public function getAllRegularUser()
    {
        return $this->model->where('type', UserRole::TYPE_USER);
    }

    public function getAllRegularUserCount()
    {
        return $this->getAllRegularUser()->count();
    }
}
