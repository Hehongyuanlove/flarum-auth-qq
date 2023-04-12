<?php
namespace Hehongyuanlove\AuthQQ;

use Flarum\Forum\Auth\Registration;
use Flarum\Http\RememberAccessToken;
use Flarum\Http\Rememberer;
use Flarum\User\Command\RegisterUser;
use Flarum\User\LoginProvider;
use Flarum\User\RegistrationToken;
use Flarum\User\User;
use Illuminate\Contracts\Bus\Dispatcher;
use Illuminate\Support\Arr;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Flarum\Http\UrlGenerator;

class QQResponseFactory
{
    /**
     * @var Rememberer
     */
    protected $rememberer;

    /**
     * @var Dispatcher
     */
    protected $bus;

    protected $url;

    /**
     * @param Rememberer $rememberer
     */
    public function __construct(Rememberer $rememberer, Dispatcher $bus, UrlGenerator $url)
    {
        $this->rememberer = $rememberer;
        $this->bus        = $bus;
        $this->url        = $url;
    }

    public function make(string $provider, string $identifier, User $actor, callable $configureRegistration): ResponseInterface
    {

        if ($user = LoginProvider::logIn($provider, $identifier)) {
            return $this->makeLoggedInResponse($user);
        }

        $configureRegistration($registration = new Registration);
        $provided = $registration->getProvided();
        
        $token = RegistrationToken::generate($provider, $identifier, $provided, $registration->getPayload());
        $token->save();

        $user = $this->bus->dispatch(
            new RegisterUser($actor,
                ["attributes" =>
                    [
                        "email"    => $provided["email"],
                        "token"    => $token->token,
                        "username" => $provided["username"],
                    ],
                ]
            )
        );

        return $this->makeLoggedInResponse($user);
    }

    private function makeResponse(array $payload): HtmlResponse
    {
        // $content = sprintf(
        //     '<script>window.close(); window.opener.app.authenticationComplete(%s);</script>',
        //     json_encode($payload)
        // );

        // 兼容手机
        $content = sprintf(
            '<script>
                if(window.opener){
                    window.opener.app.authenticationComplete(%s)
                    window.close();
                }else{
                    window.location.href = "%s"
                }
            ;
            </script>',
            json_encode($payload),
            $this->url->to('forum')->base()
        );

        return new HtmlResponse($content);
    }

    private function makeLoggedInResponse(User $user)
    {
        $response = $this->makeResponse(['loggedIn' => true]);
        $token    = RememberAccessToken::generate($user->id);
        return $this->rememberer->remember($response, $token);
    }
}
