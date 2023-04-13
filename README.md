# QQ Auth Login by Hehongyuanlove

![License](https://img.shields.io/badge/license-MIT-blue.svg) [![Latest Stable Version](https://img.shields.io/packagist/v/hehongyuanlove/flarum-auth-qq.svg)](https://packagist.org/packages/hehongyuanlove/flarum-auth-qq)

A [Flarum](http://flarum.org) extension. Allow users to log in with QQ
### 重要说明
- 用户名进行规范(允许汉字)
- 随机生成用户名
- 自动设置邮箱账号 同时密码也为邮箱账号 且自动激活邮箱
- 邮箱与密码相同

### 关于注册时邮箱用户名部分
> 有其他需求 自行修改vendor/hehongyuanlove/flarum-auth-qq/src/QQAuthController.php
```
    $username = $this->RandomUserName();
    $random_email = $username. "@xxxxx.cn";
    $nickname     = $this->UserNameMatch($userinforesult["nickname"]) . str::upper(str::random(4));

    $registration
        ->provide("username", $username)
        ->provide("nickname", $nickname)
        ->provide("email", $random_email)
        ->provide("is_email_confirmed", 1)
        ->provide("password", $random_email)
        ->provideAvatar($userinforesult['figureurl_qq_2'])
        ->setPayload($userinforesult);

```
### 参考
Copy from [nomiscz/flarum-ext-auth-wechat](https://packagist.org/packages/nomiscz/flarum-ext-auth-wechat)

### QQ互联回调地址
- https://域名/api/auth/qq


### 安装

Use [Bazaar](https://discuss.flarum.org/d/5151-flagrow-bazaar-the-extension-marketplace) or install manually with composer:

```sh
composer require hehongyuanlove/flarum-auth-qq
# 有兼容提示就
composer require hehongyuanlove/flarum-auth-qq:*
```

### 更新

```sh
composer update hehongyuanlove/flarum-auth-qq
```

### Links

- [Packagist](https://packagist.org/packages/hehongyuanlove/flarum-auth-qq)
