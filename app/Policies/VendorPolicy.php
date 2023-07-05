<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Access\HandlesAuthorization;

class VendorPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    }
    public function create(User $user)
    {
        $vendor = Vendor::where('user_id', $user->id)->get();

        return $vendor->isEmpty() ? true : false;
    }
}
