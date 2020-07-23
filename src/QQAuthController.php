<?php
namespace Hehongyuanlove\AuthQQ;

use Exception;
use Flarum\Forum\Auth\Registration;
use Flarum\Forum\Auth\ResponseFactory;
use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;

class QQAuthController implements RequestHandlerInterface {
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
     * @param ResponseFactory $response
     * @param SettingsRepositoryInterface $settings
     * @param UrlGenerator $url
     */
    public function __construct(ResponseFactory $response, SettingsRepositoryInterface $settings, UrlGenerator $url){
        $this->response = $response;
        $this->settings = $settings;
        $this->url      = $url;
    }


    /**
     * @param Request $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function handle(Request  $request): ResponseInterface {
        $redirectUri = $this->url->to('forum')->route('auth.qq');
        $provider   = new QQ([
            'clientId'          => $this->settings->get('hehgonyuanlove-auth-qq.client_id'),
            'clientSecret'      => $this->settings->get('hehgonyuanlove-auth-qq.client_secret'),
            'redirectUri'       => $redirectUri,
            'graphApiVersion'   => 'v3.0',
        ]);
        $session        = $request->getAttribute('session');
        $queryParams    = $request->getQueryParams();
        $code           = array_get($queryParams, 'code');
        if (!$code) {
            $authUrl    = $provider->getAuthorizationUrl();
            $session->put('oauth2state', $provider->getState());
            return new RedirectResponse($authUrl);
        }
        $state          = array_get($queryParams, 'state');
        if (!$state || $state !== $session->get('oauth2state')) {
            $session->remove('oauth2state');
            throw new Exception('Invalid state');
        }
        $token          = $provider->getAccessToken('authorization_code', [
            "code"  => $code,
        ]);
        $user           = $provider->fetchOpenid($token);

        return $this->response->make(
            'QQ', $user["id"],
            function (Registration $registration) use ($user) {
                $registration
                    ->suggestEmail("")
                    ->provideAvatar($user['avatar_hd'])
                    ->suggestUsername($user["name"])
                    ->setPayload($user);
            }
        );
    }
}

