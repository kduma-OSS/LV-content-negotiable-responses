<?php


namespace KDuma\ContentNegotiableResponses\Traits;


use Illuminate\Support\Collection;
use KDuma\ContentNegotiableResponses\BaseViewResponse;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

trait DiscoversPublicProperties
{
    /**
     * source: https://thibaud.dauce.fr/posts/2017-07-26-improvements-of-the-new-Responsable-interface-in-Laravel.html
     *
     * @throws ReflectionException
     */
    protected function getPublicProperties(): Collection
    {
        return collect((new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC))
            ->reject(function (ReflectionProperty $property) {
                return $property->getDeclaringClass()->getName() == BaseViewResponse::class;
            })
            ->mapWithKeys(function (ReflectionProperty $property) {
                return [$property->getName() => $property->getValue($this)];
            });
    }
}
