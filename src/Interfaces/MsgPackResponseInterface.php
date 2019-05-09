<?php


namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface MsgPackResponseInterface
{
    /**
     * @param  Request  $request
     * @return Response
     */
    public function toMsgPackResponse($request);
}