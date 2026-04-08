<?php

declare(strict_types=1);

namespace KDuma\ContentNegotiableResponses\Interfaces;


use Illuminate\Http\Request;
use Illuminate\Http\Response;

interface XmlResponseInterface
{
    public function toXmlResponse(Request $request): Response;
}
