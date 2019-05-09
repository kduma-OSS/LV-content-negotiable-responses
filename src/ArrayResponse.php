<?php


namespace KDuma\ContentNegotiableResponses;

use Illuminate\Contracts\Support\Arrayable;

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
     * @return array
     */
    protected function getDataArray()
    {
        return $this->data instanceof Arrayable 
            ? $this->data->toArray() 
            : $this->data;
    }
}