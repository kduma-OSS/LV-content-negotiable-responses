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
    public function __construct(protected string $view, protected array|Arrayable $data)
    {}

    protected function getDataArray(Request $request): array
    {
        return $this->data instanceof Arrayable
            ? $this->data->toArray()
            : $this->data;
    }

    /**
     * @throws ReflectionException
     */
    protected function generateView(Request $request): View
    {
        return $this->view($this->view, $this->getDataArray($request));
    }
}
