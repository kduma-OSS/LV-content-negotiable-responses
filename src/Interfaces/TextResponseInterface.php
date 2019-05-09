<?php


namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface TextResponseInterface
{
    /**
     * @param  Request  $request
     * @return Response
     */
    public function toTextResponse($request);
}