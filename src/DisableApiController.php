<?php

namespace Hehongyuanlove\AuthQQ;

use Flarum\Api\Controller\CreateUserController;
use Flarum\Settings\SettingsRepositoryInterface;
use Illuminate\Contracts\Bus\Dispatcher;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;
use InvalidArgumentException;

class DisableApiController extends CreateUserController
{

    /**
     * @var Dispatcher
     */
    protected $bus;

    /**
     * @var SettingsRepositoryInterface
     */
    public $settings;

    public function __construct(Dispatcher $bus, SettingsRepositoryInterface $settings)
    {
        parent::__construct($bus);
        $this->settings = $settings;
    }

    public function data(ServerRequestInterface $request, Document $document)
    {
        $close_email_register = $this->settings->get('hehongyuanlove-auth-qq.close_email_register');
        if ((bool) $close_email_register) {
            throw new InvalidArgumentException('CreateUserController Route disabled', 403);
        }
        
        return parent::data($request, $document);

    }
}
