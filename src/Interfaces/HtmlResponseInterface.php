<?php


namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface HtmlResponseInterface
{
    public function toHtmlResponse(Request $request): Response|Htmlable;
}
