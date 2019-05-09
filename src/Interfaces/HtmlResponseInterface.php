<?php


namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface HtmlResponseInterface
{
    /**
     * @param  Request  $request
     * @return Response
     */
    public function toHtmlResponse($request);
}