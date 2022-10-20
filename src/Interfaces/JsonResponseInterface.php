<?php


namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface JsonResponseInterface
{
    public function toJsonResponse(Request $request): Response;
}
