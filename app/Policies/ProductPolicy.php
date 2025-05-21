<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProductPolicy
{

    public function modify(User $user, Product $product): Response
    {
        return $user->role === $product->user_role
            ? Response::allow()
            : Response::deny('You do not have access to this');
    }
}
