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
use Illuminate\Support\Arr;
use Laminas\Diactoros\Response\JsonResponse;

class QQAuthH5Controller implements RequestHandlerInterface
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
   * @param ResponseFactory $response
   * @param SettingsRepositoryInterface $settings
   * @param UrlGenerator $url
   */
  public function __construct(ResponseFactory $response, SettingsRepositoryInterface $settings, UrlGenerator $url)
  {
    $this->response = $response;
    $this->settings = $settings;
    $this->url      = $url;
  }


  /**
   * @param Request $request
   * @return ResponseInterface
   * @throws Exception
   */
  public function handle(Request  $request): ResponseInterface
  {
  	
    $param =Arr::get($request->getQueryParams(), 'param', null);
    if(!$param){
    	return new JsonResponse(['code'=>001,'msg'=>'param un set']);
    }
    $userinforesult = json_decode($param,true);
   
    if(!isset($userinforesult['openid'])){
    	return new JsonResponse(['code'=>002,'msg'=>'openid un set']);
    }
    
    return $this->response->make(
      'QQH5',
      $userinforesult["openid"],
      function (Registration $registration) use ($userinforesult) {
        $registration
          ->suggestEmail("")
          ->provideAvatar($userinforesult['headimgurl'])
          ->suggestUsername($userinforesult["nickname"])
          ->setPayload($userinforesult);
      }
    );

    // $userinforesult = [];
    // return $this->response->make(
    //   'QQH5',
    //   $userinforesult["openid"],
    //   function (Registration $registration) use ($userinforesult) {
    //     $registration
    //       ->suggestEmail("")
    //       ->provideAvatar($userinforesult['figureurl_qq_2'])
    //       ->suggestUsername($userinforesult["nickname"])
    //       ->setPayload($userinforesult);
    //   }
    // );

  }
}
