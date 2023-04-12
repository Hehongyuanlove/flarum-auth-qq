<?php

namespace Hehongyuanlove\AuthQQ;

use Exception;
use Flarum\Forum\Auth\Registration;
// use Flarum\Forum\Auth\ResponseFactory;
// use ResponseFactory;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\RedirectResponse;

// 临时日志
// use Monolog\Logger;
// use Psr\Log\LoggerInterface;
// use Monolog\Handler\StreamHandler;

class QQAuthController implements RequestHandlerInterface
{
    /**
     * @var QQResponseFactory
     */
    protected $response;

    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;

    /**
     * @var UrlGenerator
     */
    protected $url;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param QQResponseFactory $response
     * @param SettingsRepositoryInterface $settings
     * @param UrlGenerator $url
     */
    public function __construct(QQResponseFactory $response, SettingsRepositoryInterface $settings, UrlGenerator $url)
    {
        $this->response = $response;
        $this->settings = $settings;
        $this->url      = $url;
        // $this->logger = new Logger('qqAuth');
        // $this->logger->pushHandler(new StreamHandler("../storage/qqauth.log", Logger::INFO));
    }

    /**
     * @param Request $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function handle(Request $request): ResponseInterface
    {

        // 增加判断 假如已经登陆了 跳转到 /
        $actor = $request->getAttribute('actor');

        $redirectUri = $this->url->to('api')->route('auth.qq');
        $provider    = new QQ([
            'clientId'     => $this->settings->get('hehongyuanlove-auth-qq.client_id'),
            'clientSecret' => $this->settings->get('hehongyuanlove-auth-qq.client_secret'),
            'redirectUri'  => $redirectUri,
        ]);

        $session     = $request->getAttribute('session');
        $queryParams = $request->getQueryParams();
        $code        = Arr::get($queryParams, 'code');

        // 写日志 检查 code 情况
        if (!$code) {
            $authUrl = $provider->getAuthorizationUrl();
            $session->put('oauth2state', $provider->getState());
            // $this->logger->log(Logger::WARNING, '[无code]:'.$request->getUri());
            return new RedirectResponse($authUrl);
        }
        $state = Arr::get($queryParams, 'state');
        // $this->logger->log(Logger::WARNING, '[有code]:'.$request->getUri());

        if (!$state || $state !== $session->get('oauth2state')) {
            // $session->remove('oauth2state');
            // throw new Exception('Invalid state');
            $session->put('oauth2state', $state);
        }

        $token = $provider->getAccessToken('authorization_code', [
            "code" => $code,
        ]);

        $user = $provider->fetchOpenid($token);

        // 新增分支 用户已经登录的情况下 走绑定流程
        if ($actor->isGuest() == false) {
            // 微信绑定的账号已经存在 直接返回
            $actorLoginProviders = $actor->loginProviders()->where('provider', 'QQ')->first();
            if ($actorLoginProviders) {
                return $this->makeLinkResponse();
            }

            // 微信正在被其他号绑定 直接返回
            $actorLoginProviders = $actor->loginProviders()->where('identifier', $user['openid'])->first();
            if ($actorLoginProviders) {
                return $this->makeLinkResponse();
            }

            $actor->loginProviders()->create([
                'provider'   => 'QQ',
                'identifier' => $user['openid'],
            ]);
            return $this->makeLinkResponse();
        }

        $userinfo = $provider->fetchUesrInfo($token, $user['openid']);

        $userinforesult = array_merge_recursive($user, $userinfo);

        // $this->logger->log(Logger::WARNING, '[记录]:'.json_encode($userinforesult));
        $loginResultRes = $this->response->make(
            'QQ',
            $userinforesult["openid"],
            $request->getAttribute('actor'),
            function (Registration $registration) use ($userinforesult) {
                $random_email = "xxxx." . str::upper(str::random(20)) . "@xxxxx.cn";
                $username     = $this->UserNameMatch($userinforesult["nickname"]) . str::upper(str::random(4));
                $registration
                    ->provide("username", $username)
                    ->provide("nickname", $username)
                    ->provide("email", $random_email)
                    ->provide("is_email_confirmed", 1)
                    ->provide("password", $random_email)
                    ->provideAvatar($userinforesult['figureurl_qq_2'])
                    ->setPayload($userinforesult);
            }
        );

        return $loginResultRes;
    }

    public function UserNameMatch($str)
    {
        preg_match_all('/[\x{4e00}-\x{9fa5}a-zA-Z0-9]/u', $str, $result);
        return implode('', $result[0]);
    }

    private function makeLinkResponse(): HtmlResponse
    {
        $content = sprintf(
            '<script>
                if(window.opener){
                    window.opener.location.href = "%s"
                    window.close();
                }else{
                    window.location.href = "%s"
                }
            ;
            </script>',
            $this->url->to('forum')->path("settings"),
            $this->url->to('forum')->path("settings")
        );

        return new HtmlResponse($content);

    }
}
