<?php

declare(strict_types=1);

namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface MsgPackResponseInterface
{
    public function toMsgPackResponse(Request $request): Response;
}
