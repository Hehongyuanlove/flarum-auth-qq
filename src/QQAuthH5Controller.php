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
use Illuminate\Support\Str;
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

    $param = Arr::get($request->getQueryParams(), 'param', null);
    if (!$param) {
      return new JsonResponse(['code' => 001, 'msg' => 'param un set']);
    }
    $userinforesult = json_decode($param, true);
    
    if (!isset($userinforesult['openid'])) {
      return new JsonResponse(['code' => 002, 'msg' => 'openid un set']);
    }

    return $this->response->make(
      'QQH5',
      $userinforesult["openid"],
      function (Registration $registration) use ($userinforesult) {
        $registration
          ->suggestEmail("")
          ->provideAvatar($userinforesult['figureurl_qq_2'])
          ->suggestUsername($this->phpUnescape($userinforesult['nickname']).str::upper(str::random(4)))
          ->setPayload($userinforesult);
      }
    );
  }

  public function phpUnescape($escstr)
  {
    preg_match_all("/%u[0-9A-Za-z]{4}|%.{2}|[0-9a-zA-Z.+-_]+/", $escstr, $matches);
    $ar = &$matches[0];
    $c = "";
    foreach ($ar as $val) {
      if (substr($val, 0, 1) != "%") {
        $c .= $val;
      } elseif (substr($val, 1, 1) != "u") {
        $x = hexdec(substr($val, 1, 2));
        $c .= chr($x);
      } else {
        $val = intval(substr($val, 2), 16);
        if ($val < 0x7F) // 0000-007F   
        {
          $c .= chr($val);
        } elseif ($val < 0x800) // 0080-0800   
        {
          $c .= chr(0xC0 | ($val / 64));
          $c .= chr(0x80 | ($val % 64));
        } else // 0800-FFFF   
        {
          $c .= chr(0xE0 | (($val / 64) / 64));
          $c .= chr(0x80 | (($val / 64) % 64));
          $c .= chr(0x80 | ($val % 64));
        }
      }
    }

    return $c;
  }
}
