<?php


namespace KDuma\ContentNegotiableResponses;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;

class ArrayResponse extends BaseArrayResponse 
{
    /**
     * @var array|Arrayable
     */
    protected $data;

    /**
     * ArrayResponse constructor.
     *
     * @param array|Arrayable $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
    
    /**
     * @return array|Arrayable
     */
    protected function getData()
    {
        return $this->data;
    }
}