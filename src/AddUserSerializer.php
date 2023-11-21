<?php

namespace HamCQ\UserCSS;

use Flarum\Api\Serializer\UserSerializer;
use Flarum\User\User;


class AddUserSerializer
{
    public function __invoke(UserSerializer $serializer, User $user, array $attributes)
    {
        $attributes["myStyleEnable"] = $user->getPreference("myStyleEnable",false);
        return $attributes;
    }
}