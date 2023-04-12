<?php

namespace Hehongyuanlove\AuthQQ;

use Flarum\Http\UrlGenerator;
use Flarum\Settings\SettingsRepositoryInterface;
use Flarum\User\LoginProvider;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class QQLinkController implements RequestHandlerInterface
{

    /**
     * @var LoginProvider
     */
    protected $loginProvider;
    /**
     * @var SettingsRepositoryInterface
     */
    protected $settings;
    /**
     * @var UrlGenerator
     */
    protected $url;
    /**
     * @param LoginProvider $loginProvider
     * @param SettingsRepositoryInterface $settings
     * @param UrlGenerator $url
     */
    public function __construct(LoginProvider $loginProvider, SettingsRepositoryInterface $settings, UrlGenerator $url)
    {
        $this->loginProvider = $loginProvider;
        $this->settings      = $settings;
        $this->url           = $url;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor               = $request->getAttribute('actor');
        $actorLoginProviders = $actor->loginProviders()->where('provider', 'QQ')->first();

        if ($actorLoginProviders) {
            return $this->makeLinkResponse();
        }

        $redirectUri = $this->url->to('api')->route('auth.qq');

        $provider = new QQ([
            'clientId'     => $this->settings->get('hehongyuanlove-auth-qq.client_id'),
            'clientSecret' => $this->settings->get('hehongyuanlove-auth-qq.client_secret'),
            'redirectUri'  => $redirectUri,
        ]);

        $session     = $request->getAttribute('session');
        $authUrl = $provider->getAuthorizationUrl();
        $session->put('oauth2state', $provider->getState());
        return new RedirectResponse($authUrl . '&display=popup');
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
