<?php


namespace KDuma\ContentNegotiableResponses;


use KDuma\ContentNegotiableResponses\Interfaces\MsgPackResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\JsonResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\TextResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\YamlResponseInterface;
use KDuma\ContentNegotiableResponses\Interfaces\XmlResponseInterface;
use Illuminate\Support\Collection;
use KDuma\ContentNegotiableResponses\Traits\DiscoversPublicProperties;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\Yaml\Yaml;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use MessagePack\MessagePack;

abstract class BaseArrayResponse extends BaseResponse
    implements JsonResponseInterface, TextResponseInterface, XmlResponseInterface, MsgPackResponseInterface, YamlResponseInterface
{
    use DiscoversPublicProperties;

    /**
     * @param Request $request
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getDataArray($request) {
        return $this->getPublicProperties()
            ->toArray($request);
    }

    /**
     * @return string
     */
    protected function getDefaultType(): string
    {
        return JsonResponseInterface::class;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function toTextResponse($request)
    {
        $content = print_r($this->getDataArray($request), true);

        return \response($content)->header('Content-Type', 'text/plain; charset=UTF-8');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function toJsonResponse($request)
    {
        $content = json_encode($this->getDataArray($request), JSON_PRETTY_PRINT);

        return \response($content)->header('Content-Type', 'application/json; charset=UTF-8');
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws \DOMException
     */
    public function toXmlResponse($request)
    {
        $converter = new ArrayToXml($this->getDataArray($request));
        $dom = $converter->toDom();
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $content = $dom->saveXML();

        return \response($content)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function toYamlResponse($request)
    {
        $content = $yaml = Yaml::dump($this->getDataArray($request), 2, 4);

        return \response($content)->header('Content-Type', 'application/yaml; charset=UTF-8');
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function toMsgPackResponse($request)
    {
        $content = MessagePack::pack($this->getDataArray($request));

        return \response($content)->header('Content-Type', 'application/msgpack');
    }
}
