<?php
namespace Hehongyuanlove\AuthQQ;

use Flarum\Forum\Auth\Registration;
use Flarum\Http\Rememberer;
use Flarum\User\LoginProvider;
use Flarum\User\RegistrationToken;
use Flarum\User\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Laminas\Diactoros\Response\HtmlResponse;
use Psr\Http\Message\ResponseInterface;
use Illuminate\Contracts\Bus\Dispatcher;
use Flarum\User\Command\RegisterUser;

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

    /**
     * @param Rememberer $rememberer
     */
    public function __construct(Rememberer $rememberer, Dispatcher $bus)
    {
        $this->rememberer = $rememberer;
        $this->bus = $bus;
    }
    
    public function make(string $provider, string $identifier,User $actor, callable $configureRegistration): ResponseInterface
    {

        var_dump("QQResponseFactory");
        if ($user = LoginProvider::logIn($provider, $identifier)) {
            return $this->makeLoggedInResponse($user);
        }

        $configureRegistration($registration = new Registration);
      

        $provided = $registration->getProvided();

        if (! empty($provided['email']) && $user = User::where(Arr::only($provided, 'email'))->first()) {
            $user->loginProviders()->create(compact('provider', 'identifier'));

            return $this->makeLoggedInResponse($user);
        }

        
        $token = RegistrationToken::generate($provider, $identifier, $provided, $registration->getPayload());
        $token->save();
        
        $user = $this->bus->dispatch(
            new RegisterUser($actor,
                ["attributes"=>
                    [
                        "email" =>$provided["email"], 
                        "token" => $token->token, 
                        "username" => $provided["username"]
                    ]
                ]
            )
        );
        
        return $this->makeLoggedInResponse($user);
    }

    private function makeResponse(array $payload): HtmlResponse
    {
        $content = sprintf(
           '<script>window.location.href = "/"; window.app.authenticationComplete(%s);</script>',
            json_encode($payload)
        );
        
        return new HtmlResponse($content);
    }

    private function makeLoggedInResponse(User $user)
    {
        $response = $this->makeResponse(['loggedIn' => true]);

        return $this->rememberer->rememberUser($response, $user->id);
    }
}
