<?php


namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface XmlResponseInterface
{
    /**
     * @param  Request  $request
     * @return Response
     */
    public function toXmlResponse($request);
}