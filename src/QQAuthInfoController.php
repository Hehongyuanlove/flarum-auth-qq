<?php

namespace Hehongyuanlove\AuthQQ;

use Exception;
use Flarum\Forum\Auth\Registration;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laminas\Diactoros\Response\RedirectResponse;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

// 临时日志

/** api 获取用户信息  显示进度*/

class QQAuthInfoController implements RequestHandlerInterface
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
        $this->logger   = new Logger('qqAuth');
        $this->logger->pushHandler(new StreamHandler("../storage/qqauthcode.log", Logger::INFO));
    }

    /**
     * @param Request $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function handle(Request $request): ResponseInterface
    {

        $actor = $request->getAttribute('actor');

        // 用户已经登录
        if (!$actor->isGuest()) {
            return new RedirectResponse($this->url->to('forum')->base());
        }

        $redirectUri = $this->url->to('forum')->route('auth.qq.infobf');
        $provider    = new QQ([
            'clientId'     => $this->settings->get('hehongyuanlove-auth-qq.client_id'),
            'clientSecret' => $this->settings->get('hehongyuanlove-auth-qq.client_secret'),
            'redirectUri'  => $redirectUri,
        ]);

        $session     = $request->getAttribute('session');
        $queryParams = $request->getQueryParams();
        $code        = Arr::get($queryParams, 'code');
        $state       = Arr::get($queryParams, 'state');
        $this->logger->log(Logger::WARNING, '[有code]:' . $request->getUri() . '[state]=>' . $state . "[session]=>" . $session->get('oauth2state'));

        // code 校验
        if (!$code) {
            throw new Exception('Invalid code');
        }

        // dd($code,$state,$session->get('oauth2state'));

        // // state 校验
        // if (!$state || $state !== $session->get('oauth2state')) {
        //     $session->remove('oauth2state');
        //     throw new Exception('Invalid state');
        // }

        // 获取用户数据
        $token = $provider->getAccessToken('authorization_code', [
            "code" => $code,
        ]);
        $user           = $provider->fetchOpenid($token);
        $userinfo       = $provider->fetchUesrInfo($token, $user['openid']);
        $userinforesult = array_merge_recursive($user, $userinfo);

        $this->logger->log(Logger::WARNING, '[记录]:' . json_encode($userinforesult));
        $loginResultRes = $this->response->make(
            'QQ',
            $userinforesult["openid"],
            function (Registration $registration) use ($userinforesult) {
                $registration
                // ->suggestEmail(str::upper(str::random(20)) . "@qq.com")
                //->suggestUsername($userinforesult["nickname"].str::upper(str::random(4)))
                // ->provide("username", $this->UserNameMatch($userinforesult["nickname"]) . str::upper(str::random(4)))
                // ->provide("email", "himi3d." . str::upper(str::random(20)) . "@qq.com")
                // ->provide("password", $pwd)
                ->provideAvatar($userinforesult['figureurl_qq_2'])
                ->setPayload($userinforesult);
            }
        );

        // dd($loginResultRes);
        return $loginResultRes;
    }

    public function UserNameMatch($str)
    {
        preg_match_all('/[\x{4e00}-\x{9fa5}a-zA-Z0-9]/u', $str, $result);
        return implode('', $result[0]);
    }
}
