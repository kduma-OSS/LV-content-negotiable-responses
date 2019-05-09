<?php


namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface JsonResponseInterface
{
    /**
     * @param  Request  $request
     * @return Response
     */
    public function toJsonResponse($request);
}