<?php
namespace Hehongyuanlove\AuthQQ;

use Exception;
use Flarum\Forum\Auth\Registration;
// use Flarum\Forum\Auth\ResponseFactory;
// use ResponseFactory;
use Flarum\Http\UrlGenerator;
use Laminas\Diactoros\Response\HtmlResponse;
use Flarum\Settings\SettingsRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

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
    public function __construct(QQResponseFactory $response, SettingsRepositoryInterface $settings, UrlGenerator $url){
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
             
        $redirectUri ="https:".$this->url->to('api')->route('auth.qq');
        $provider   = new QQ([
            'clientId'          => $this->settings->get('hehongyuanlove-auth-qq.client_id'),
            'clientSecret'      => $this->settings->get('hehongyuanlove-auth-qq.client_secret'),
            'redirectUri'       => $redirectUri,
        ]);
        
       
      
        $session        = $request->getAttribute('session');
        $queryParams    = $request->getQueryParams();
        $code           = Arr::get($queryParams, 'code');

        if (!$code) {
            $authUrl    = $provider->getAuthorizationUrl();
            $session->put('oauth2state', $provider->getState());
            return new RedirectResponse($authUrl);
        }
        $state          = Arr::get($queryParams, 'state');
        
       // var_dump($state,$session->get('oauth2state'));
        
        if (!$state || $state !== $session->get('oauth2state')) {
            // $session->remove('oauth2state');
            // throw new Exception('Invalid state');
             $session->put('oauth2state', $state);
        }
        
        $token          = $provider->getAccessToken('authorization_code', [
            "code"  => $code,
        ]);
      
        $user           = $provider->fetchOpenid($token);
     
        $userinfo = $provider->fetchUesrInfo($token,$user['openid']);
 
		$userinforesult = array_merge_recursive($user, $userinfo);
		
		$actor = $request->getAttribute('actor');
		
		$loginResultRes = $this->response->make(
            'QQ', $userinforesult["openid"],$actor,
            function (Registration $registration) use ($userinforesult) {
                $registration
                    // ->suggestEmail(str::upper(str::random(20)) . "@qq.com")
                    //->suggestUsername($userinforesult["nickname"].str::upper(str::random(4)))
                    ->provide("username",$userinforesult["nickname"].str::upper(str::random(4)))
                    ->provide("email","himi3d.".str::upper(str::random(20)) . "@qq.com")
                    ->provideAvatar($userinforesult['figureurl_qq_2'])
                    ->setPayload($userinforesult);
            }
        );
        
        return $loginResultRes;
        

        
        // return $loginResultRes;
        // 这里省去判断前面是否登录
        return new HtmlResponse('<script>11</script>');
        // return new HtmlResponse('<script>window.location.href = "/"; window.app.authenticationComplete({"loggedIn":true});</script>');

    }
    
}

