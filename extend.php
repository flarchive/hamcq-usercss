<?php

/*
 * This file is part of hamcq/usercss.
 *
 * Copyright (c) 2023 emin.lin.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace HamCQ\UserCSS;

use Flarum\Extend;
use HamCQ\UserCSS\MyCssController;
use Flarum\User\Event\Saving;
use Flarum\Api\Serializer\UserSerializer;


return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__.'/js/dist/forum.js')
        ->css(__DIR__.'/less/forum.less'),
    (new Extend\Frontend('admin'))
        ->js(__DIR__.'/js/dist/admin.js')
        ->css(__DIR__.'/less/admin.less'),
    new Extend\Locales(__DIR__.'/locale'),

    (new Extend\Event())
        ->listen(Saving::class, SaveCssMiddleware::class),

    (new Extend\Routes('api'))
        ->get('/my_style/{id}', 'self_style.my', MyCssController::class),

    (new Extend\ApiSerializer(UserSerializer::class))
        ->attributes(AddUserSerializer::class),

    (new Extend\User())
        ->registerPreference('myStyle')
        ->registerPreference('myStyleEnable', 'boolVal', false)
];
