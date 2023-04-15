<?php

namespace Hehongyuanlove\AuthQQ;

use Flarum\Api\Client;
use Flarum\Forum\Controller\RegisterController;
use Flarum\Http\Rememberer;
use Flarum\Http\SessionAuthenticator;
use Flarum\Settings\SettingsRepositoryInterface;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface;

class DisableController extends RegisterController implements RequestHandlerInterface
{
    /**
     * @var Client
     */
    protected $api;

    /**
     * @var SessionAuthenticator
     */
    protected $authenticator;

    /**
     * @var Rememberer
     */
    protected $rememberer;

    /**
     * @var SettingsRepositoryInterface
     */
    public $settings;

    public function __construct(SettingsRepositoryInterface $settings, Client $api, SessionAuthenticator $authenticator, Rememberer $rememberer)
    {
        parent::__construct($api, $authenticator, $rememberer);
        $this->settings = $settings;
    }

    public function handle(Request $request): ResponseInterface
    {
        $close_email_register = $this->settings->get('hehongyuanlove-auth-qq.close_email_register');
        if ((bool) $close_email_register) {
            return new JsonResponse([
                "status" => 403,
                "error"  => "RegisterController Route disabled",
            ], 403);
        }

        return parent::handle($request);
    }

}
