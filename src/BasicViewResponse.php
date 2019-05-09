<?php


namespace KDuma\ContentNegotiableResponses;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\View\View;
use ReflectionException;

class BasicViewResponse extends BaseViewResponse
{
    /**
     * @var string
     */
    protected $view;
    
    /**
     * @var array|Arrayable
     */
    protected $data;

    /**
     * ArrayResponse constructor.
     *
     * @param string           $view
     * @param array|Arrayable $data
     */
    public function __construct(string $view, $data)
    {
        $this->data = $data;
        $this->view = $view;
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

    /**
     * @param Request $request
     *
     * @return Factory|View
     * @throws ReflectionException
     */
    protected function generateView(Request $request)
    {
        return $this->view($this->view, $this->getDataArray());
    }
}