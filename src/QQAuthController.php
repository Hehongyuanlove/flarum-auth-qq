<?php

namespace Hehongyuanlove\AuthQQ;

use Exception;
use Flarum\Forum\Auth\ResponseFactory;
// use ResponseFactory;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;

// 临时日志
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class QQAuthController implements RequestHandlerInterface
{
    /**
     * @var ResponseFactory
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
     * @param ResponseFactory $response
     * @param SettingsRepositoryInterface $settings
     * @param UrlGenerator $url
     */
    public function __construct(ResponseFactory $response, SettingsRepositoryInterface $settings, UrlGenerator $url)
    {
        $this->response = $response;
        $this->settings = $settings;
        $this->url      = $url;
        $this->logger   = new Logger('qqAuth');
        $this->logger->pushHandler(new StreamHandler("../storage/qqauth.log", Logger::INFO));
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

        $state_current = $provider->getState();
        $session       = $request->getAttribute('session');
        $session->put('oauth2state', $provider->getState());

        // 获取code
        $authUrl = $provider->getAuthorizationUrl(['state' => $state_current]);
        $this->logger->log(Logger::WARNING, '[无code]:' . $request->getUri() . '[state]=>' . $state_current);
        // dd($session, $provider->getState(), $state_current, $authUrl);
        return new RedirectResponse($authUrl);

    }

    public function UserNameMatch($str)
    {
        preg_match_all('/[\x{4e00}-\x{9fa5}a-zA-Z0-9]/u', $str, $result);
        return implode('', $result[0]);
    }
}
