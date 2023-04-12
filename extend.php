<?php

/*
 * This file is part of hehongyuanlove/flarum-auth-qq.
 *
 * Copyright (c) 2020 YC.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Hehongyuanlove\AuthQQ;

use Flarum\Extend;
use Flarum\Api\Serializer\UserSerializer;

return [
  (new Extend\Frontend('forum'))
    ->js(__DIR__ . '/js/dist/forum.js')
    ->css(__DIR__ . '/resources/less/forum.less'),
  (new Extend\Frontend('admin'))
    ->js(__DIR__ . '/js/dist/admin.js')
    ->css(__DIR__ . '/resources/less/admin.less'),

  new Extend\Locales(__DIR__ . '/resources/locale'),

  (new Extend\Routes('api'))
    ->get('/auth/qq', 'auth.qq', QQAuthController::class),
  
  (new Extend\Routes('api'))
    ->get('/auth/qq/link', 'auth.qq.link', QQLinkController::class)
    ->post('/auth/qq/unlink', 'auth.qq.unlink', QQUnLinkController::class),


  (new Extend\ApiSerializer(UserSerializer::class))
      ->attributes(function($serializer, $user, $attributes) {

        $loginProviders = $user->loginProviders();
        $steamProvider = $loginProviders->where('provider', 'QQ')->first();

        $attributes['QQAuth'] = [
            'isLinked' => $steamProvider !== null,
            'identifier' => null, // Hidden, don't expose this information
            'providersCount' => $loginProviders->count()
        ];

        return $attributes;
    }),
];
