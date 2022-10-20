<?php


namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface MsgPackResponseInterface
{
    public function toMsgPackResponse(Request $request): Response;
}
