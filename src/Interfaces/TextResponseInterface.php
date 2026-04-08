<?php

declare(strict_types=1);

namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface TextResponseInterface
{
    public function toTextResponse(Request $request): Response;
}
