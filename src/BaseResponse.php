<?php

namespace KDuma\ContentNegotiableResponses;

use Illuminate\Http\Response;
use KDuma\ContentNegotiableResponses\Interfaces\MsgPackResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\JsonResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\HtmlResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\TextResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\YamlResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\XmlResponseInterface;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

abstract class BaseResponse implements Responsable
{
    /**
     * @var null|int HTTP Response Code
     */
    protected $responseCode = null;
    
    /**
     * @inheritDoc
     * @throws \ReflectionException
     */
    public function toResponse($request)
    {
        $available_types = $this->getTypesMap()
            ->filter(function ($interface, $content_type) {
                return is_subclass_of($this, $interface);
            })
            ->partition(function ($interface, $content_type) {
                return $content_type == $this->getDefaultType() || $interface == $this->getDefaultType();
            })
            ->reduce(function (?Collection $preferred, Collection $other) {
                if($preferred == null)
                    return $other;
                return $preferred->merge($other);
            });

        $response_content_type = $request->prefers($available_types->keys()->toArray()) ?? $available_types->keys()->first();
        
        abort_if($response_content_type === null, 500);
        
        $interface = (new \ReflectionClass($available_types->get($response_content_type)))->getShortName();
        $handler = 'to'.Str::before($interface, 'Interface');
        
        abort_unless(method_exists($this, $handler) && is_callable([$this, $handler]), 500);

        /** @var Response $response */
        $response = $this->{$handler}($request);
        
        if($this->responseCode)
            $response->setStatusCode($this->responseCode);
        
        return $response;
    }

    /**
     * @return Collection
     */
    protected function getTypesMap(): Collection
    {
        return collect([
            'text/plain' => TextResponseInterface::class,

            'text/html' => HtmlResponseInterface::class,
            'application/xhtml+xml' => HtmlResponseInterface::class,
            
            'text/json' => JsonResponseInterface::class,
            'application/json' => JsonResponseInterface::class,
            
            'text/yaml' => YamlResponseInterface::class,
            'application/yaml' => YamlResponseInterface::class,

            'text/xml' => XmlResponseInterface::class,
            'application/xml' => XmlResponseInterface::class,

            'application/x-msgpack' => MsgPackResponseInterface::class,
            'application/msgpack' => MsgPackResponseInterface::class,
        ]);
    }

    /**
     * @return string
     */
    abstract protected function getDefaultType(): string;
}