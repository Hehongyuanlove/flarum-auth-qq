<?php

namespace Hehongyuanlove\AuthQQ;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\Response\EmptyResponse;

class QQUnLinkController implements RequestHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     * @throws Exception
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $actor = $request->getAttribute('actor');
        $actorLoginProviders = $actor->loginProviders()->where('provider', 'QQ')->first();

        if (!$actorLoginProviders) {
            return new EmptyResponse(StatusCodeInterface::STATUS_BAD_REQUEST);
        }

        $actorLoginProviders->delete();

        return new EmptyResponse(StatusCodeInterface::STATUS_OK);
    }
}
