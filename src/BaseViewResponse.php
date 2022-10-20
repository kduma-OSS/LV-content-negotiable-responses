<?php


namespace KDuma\ContentNegotiableResponses;


use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Illuminate\Contracts\Support\Htmlable;
use KDuma\ContentNegotiableResponses\Interfaces\HtmlResponseInterface;
use ReflectionException;

abstract class BaseViewResponse extends BaseArrayResponse implements HtmlResponseInterface
{
    abstract protected function generateView(Request $request): View;

    public function toHtmlResponse(Request $request): Response|Htmlable
    {
        return $this->generateView($request);
    }

    protected function getDefaultType(): string
    {
        return HtmlResponseInterface::class;
    }

    /**
     * source: https://thibaud.dauce.fr/posts/2017-07-26-improvements-of-the-new-Responsable-interface-in-Laravel.html
     *
     * @throws ReflectionException
     */
    public function view(string $view, array|Collection $overrides = []): View
    {
        return $this->getPublicProperties()
            ->merge($overrides)
            ->pipe(function($data) use ($view) {
                return view($view, $data);
            });
    }
}
