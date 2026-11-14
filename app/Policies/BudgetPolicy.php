<?php

namespace App\Policies;

use App\Models\Budget;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BudgetPolicy
{

    public function view(User $user, Budget $budget): bool
    {
        return false;
    }

    public function update(User $user, Budget $budget): Response
    {
        return $user->id === $budget->user_id ? Response::allow() : Response::deny('No tienes permisos para editar este presupuesto');
    }

    public function delete(User $user, Budget $budget): bool
    {
        return false;
    }
}
