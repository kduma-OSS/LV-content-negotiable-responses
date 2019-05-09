<?php


namespace KDuma\ContentNegotiableResponses;


use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use KDuma\ContentNegotiableResponses\Interfaces\HtmlResponseInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

abstract class BaseViewResponse extends BaseArrayResponse implements HtmlResponseInterface
{
    
    abstract protected function generateView(Request $request);

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function toHtmlResponse($request)
    {
        return $this->generateView($request);
    }

    /**
     * @return string
     */
    protected function getDefaultType(): string
    {
        return HtmlResponseInterface::class;
    }

    /**
     * source: https://thibaud.dauce.fr/posts/2017-07-26-improvements-of-the-new-Responsable-interface-in-Laravel.html
     *
     * @param string $view
     * @param array|Collection $overrides
     *
     * @return View|Factory
     * @throws ReflectionException
     */
    public function view($view, $overrides = [])
    {
        return $this->getPublicProperties()
            ->merge($overrides)
            ->pipe(function($data) use($view) {
                return view($view, $data);
            });
    }

}