<?php


namespace KDuma\ContentNegotiableResponses;


use DOMException;
use KDuma\ContentNegotiableResponses\Interfaces\MsgPackResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\JsonResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\TextResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\YamlResponseInterface;
use KDuma\ContentNegotiableResponses\Traits\DiscoversPublicProperties;
use KDuma\ContentNegotiableResponses\Interfaces\XmlResponseInterface;
use KDuma\ContentNegotiableResponses\Helpers\ResourceResponseHelper;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Collection;
use ReflectionException;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use MessagePack\MessagePack;
use JsonSerializable;

abstract class BaseArrayResponse extends BaseResponse
    implements JsonResponseInterface, XmlResponseInterface, MsgPackResponseInterface, YamlResponseInterface
{
    use DiscoversPublicProperties;

    /**
     * @throws ReflectionException
     */
    protected function getData(): array|Arrayable
    {
        return $this->getPublicProperties();
    }

    /**
     * @throws ReflectionException
     */
    protected function getDataArray(Request $request): array
    {
        $data = $this->getData();

        if ($data instanceof JsonResource) {
            $helper = new ResourceResponseHelper($data);
            $data = $helper->getData($request);

            if(!$this->responseCode)
                $this->responseCode = $helper->getStatusCode();
        }
        elseif ($data instanceof Arrayable) {
            $data = $data->toArray($request);
        }
        elseif ($data instanceof JsonSerializable) {
            $data = $data->jsonSerialize();
        }

        return $data;
    }

    protected function getDefaultType(): string
    {
        return JsonResponseInterface::class;
    }

    public function toTextResponse(Request $request): Response
    {
        $content = print_r($this->getDataArray($request), true);

        return \response($content)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    public function toJsonResponse(Request $request): Response
    {
        $content = json_encode($this->getDataArray($request), JSON_PRETTY_PRINT);

        return \response($content)->header('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * @throws DOMException|ReflectionException
     */
    public function toXmlResponse(Request $request): Response
    {
        $converter = new ArrayToXml($this->getDataArray($request));
        $dom = $converter->toDom();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $content = $dom->saveXML();

        return \response($content)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function toYamlResponse(Request $request): Response
    {
        $content = $yaml = Yaml::dump($this->getDataArray($request), 2, 4);

        return \response($content)->header('Content-Type', 'application/yaml; charset=UTF-8');
    }

    public function toMsgPackResponse(Request $request): Response
    {
        $content = MessagePack::pack($this->getDataArray($request));

        return \response($content)->header('Content-Type', 'application/msgpack');
    }
}
