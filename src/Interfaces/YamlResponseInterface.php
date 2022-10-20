<?php


namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface YamlResponseInterface
{
    public function toYamlResponse(Request $request): Response;
}
