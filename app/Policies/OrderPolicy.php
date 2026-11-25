<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

/**
 * Only the Five Stitches team (admin + sellers) can see and manage orders.
 */
class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isStaff();
    }

    public function view(User $user, Order $order): bool
    {
        return $user->isStaff();
    }

    public function update(User $user, Order $order): bool
    {
        return $user->isStaff();
    }
}
