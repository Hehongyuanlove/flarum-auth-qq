<?php
namespace Hehongyuanlove\AuthQQ\Content;

use Flarum\Frontend\Document;
use Flarum\Http\RequestUtil;
use Psr\Http\Message\ServerRequestInterface as Request;

class HHYModel
{
    public function __invoke(Document $document, Request $request)
    {
        // RequestUtil::getActor($request)->assertRegistered();
    }
}