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

return [
    (new Extend\Frontend('forum'))
        ->js(__DIR__ . '/js/dist/forum.js')
        ->css(__DIR__ . '/resources/less/forum.less')
        ->route('/auth/qq', 'auth.qq.infobf', Content\HHYModel::class),
    (new Extend\Frontend('admin'))
        ->js(__DIR__ . '/js/dist/admin.js')
        ->css(__DIR__ . '/resources/less/admin.less'),

    // new DefaultSettings(),
    (new Extend\Routes('api'))
    // 获取用户信息
        ->get('/auth/qq/{code}/{state}', 'auth.qq.info', QQAuthInfoController::class)
    // 跳转页面
        ->get('/auth/qqlogin', 'auth.qq', QQAuthController::class)
    ,

    (new Extend\Routes('api'))
        ->get('/authh5/qq', 'authh5.qq', QQAuthH5Controller::class),
    new Extend\Locales(__DIR__ . '/resources/locale'),
];
