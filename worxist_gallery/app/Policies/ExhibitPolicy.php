<?php

namespace App\Policies;

use App\Models\Exhibit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExhibitPolicy
{
    // Allow exhibit modification by the owner or an admin
    public function modify(User $user, Exhibit $exhibit): Response
    {
        if ($user->isOrganizer()) {
            return Response::allow();
        }

        return $user->id === $exhibit->user_id
            ? Response::allow()
            : Response::deny('You do not own this exhibit.');
    }

    // Separate policy for updating the status (oragnizer-only)
    public function updateStatus(User $user): Response
    {
        return $user->isOrganizer()
            ? Response::allow()
            : Response::deny('Only organizers can update the status of this exhibit.');
    }
}
