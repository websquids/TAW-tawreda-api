<?php

namespace App\Services;

use App\Filters\UserFilter;
use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    protected UserFilter $userFilter;

    public function __construct()
    {
        $this->userFilter = new UserFilter();
    }

    public function getFilteredUsers(Request $request)
    {
        $query = $this->userFilter->apply(User::query(), $request);
        $query->role('customer', 'web');
        return $query->get();
    }
}
