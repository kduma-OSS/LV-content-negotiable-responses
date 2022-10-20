<?php


namespace KDuma\ContentNegotiableResponses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;

class ArrayResponse extends BaseArrayResponse
{
    public function __construct(protected array|Arrayable $data)
    {}

    protected function getData(): array|Arrayable
    {
        return $this->data;
    }
}
