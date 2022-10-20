<?php

namespace KDuma\ContentNegotiableResponses\Helpers;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class ResourceResponseHelper
{
    public function __construct(public JsonResource $resource)
    {}

    public function getData(Request $request): array
    {
        return $this->wrap(
            $this->resource->resolve($request),
            $this->resource->with($request),
            $this->resource->additional
        );
    }

    /**
     * Wrap the given data if necessary.
     */
    protected function wrap(array|Arrayable $data, array|Arrayable $with = [], array|Arrayable $additional = []): array
    {
        if ($data instanceof Collection) {
            $data = $data->all();
        }

        if ($this->haveDefaultWrapperAndDataIsUnwrapped($data)) {
            $data = [$this->wrapper() => $data];
        } elseif ($this->haveAdditionalInformationAndDataIsUnwrapped($data, $with, $additional)) {
            $data = [($this->wrapper() ?? 'data') => $data];
        }

        return array_merge_recursive($data, $with, $additional);
    }

    /**
     * Determine if we have a default wrapper and the given data is unwrapped.
     */
    protected function haveDefaultWrapperAndDataIsUnwrapped(array|Arrayable $data): bool
    {
        return $this->wrapper() && ! array_key_exists($this->wrapper(), $data);
    }

    /**
     * Determine if "with" data has been added and our data is unwrapped.
     */
    protected function haveAdditionalInformationAndDataIsUnwrapped(array|Arrayable $data, array|Arrayable $with, array|Arrayable $additional): bool
    {
        return (! empty($with) || ! empty($additional)) && (! $this->wrapper() || ! array_key_exists($this->wrapper(), $data));
    }

    /**
     * Get the default data wrapper for the resource.
     */
    protected function wrapper(): string
    {
        return get_class($this->resource)::$wrap;
    }

    /**
     * Calculate the appropriate status code for the response.
     */
    public function getStatusCode(): int
    {
        return $this->resource->resource instanceof Model &&
        $this->resource->resource->wasRecentlyCreated ? 201 : 200;
    }
}
