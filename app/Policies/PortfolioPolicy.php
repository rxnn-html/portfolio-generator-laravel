<?php

namespace App\Policies;

use App\Models\Portfolio;
use App\Models\User;

// Only the owner may change or delete a portfolio. Anyone may VIEW it by its link.
class PortfolioPolicy
{
    public function update(User $user, Portfolio $portfolio): bool
    {
        return $portfolio->user_id !== null && $portfolio->user_id === $user->id;
    }

    public function delete(User $user, Portfolio $portfolio): bool
    {
        return $this->update($user, $portfolio);
    }
}