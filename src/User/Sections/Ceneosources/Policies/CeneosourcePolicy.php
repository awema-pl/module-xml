<?php
namespace AwemaPL\Xml\User\Sections\Ceneosources\Policies;

use AwemaPL\Xml\User\Sections\Ceneosources\Models\Ceneosource;
use Illuminate\Foundation\Auth\User;

class CeneosourcePolicy
{

    /**
     * Determine if the given post can be updated by the user.
     *
     * @param  User $user
     * @param  Ceneosource $source
     * @return bool
     */
    public function isOwner(User $user, Ceneosource $source)
    {
        return $user->id === $source->user_id;
    }


}
