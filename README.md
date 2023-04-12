# QQ Auth Login by Hehongyuanlove

![License](https://img.shields.io/badge/license-MIT-blue.svg) [![Latest Stable Version](https://img.shields.io/packagist/v/hehongyuanlove/flarum-auth-qq.svg)](https://packagist.org/packages/hehongyuanlove/flarum-auth-qq)

A [Flarum](http://flarum.org) extension. Allow users to log in with QQ
### 重要说明
- 本拓展未对QQ用户名进行规范(允许汉字)
- 本拓展会自动设置邮箱账号 同时密码也为邮箱账号 且自动激活邮箱

### 关于注册时邮箱用户名部分
> 有其他需求 自行修改vendor/hehongyuanlove/flarum-auth-qq/src/QQAuthController.php
```
    # 随机邮箱
    $random_email = "xxxx." . str::upper(str::random(20)) . "@xxxxx.cn";
    # 随机用户名
    $username     = $this->UserNameMatch($userinforesult["nickname"]) . str::upper(str::random(4));
    
    $registration
        ->provide("username", $username)        # 设置用户名
        ->provide("nickname", $username)        # 设置昵称
        ->provide("email", $random_email)       # 设置邮箱
        ->provide("is_email_confirmed", 1)      # 设置邮箱激活
        ->provide("password", $random_email)    # 设置密码
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
