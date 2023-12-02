<?php
namespace HamCQ\UserCSS;
use Laminas\Diactoros\Response;
use Psr\Http\Message\StreamInterface;
use Laminas\Diactoros\Exception;
use Laminas\Diactoros\Response\InjectContentTypeTrait;
use Laminas\Diactoros\Stream;

class CssResonse extends Response
{

    use InjectContentTypeTrait;

    public function __construct($html, int $status = 200, array $headers = [])
    {
        parent::__construct(
            $this->createBody($html),
            $status,
            $this->injectContentType('text/css', $headers)
        );
    }

    private function createBody($html): StreamInterface
    {
        if ($html instanceof StreamInterface) {
            return $html;
        }

        if($html==""){
            $body = new Stream('php://temp', 'wb+');
            $body->write("");
            $body->rewind();
            return $body;
        }

        if (! is_string($html)) {
            throw new Exception\InvalidArgumentException(sprintf(
                'Invalid content (%s) provided to %s',
                is_object($html) ? $html::class : gettype($html),
                self::class
            ));
        }

        $body = new Stream('php://temp', 'wb+');
        $body->write($html);
        $body->rewind();
        return $body;
    }
}