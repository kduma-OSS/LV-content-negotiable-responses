<?php


namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface YamlResponseInterface
{
    /**
     * @param  Request  $request
     * @return Response
     */
    public function toYamlResponse($request);
}