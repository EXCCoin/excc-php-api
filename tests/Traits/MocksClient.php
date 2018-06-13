<?php
/**
 * excc-php-api (c) 2018 EXCCoin
 * Created by: widelec
 * Date: 12.06.2018
 */

namespace EXCCoin\Tests\Traits;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;

trait MocksClient
{
    /**
     * @var Client|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $httpMock;

    public function setUpClientMocking($client)
    {
        $this->httpMock = $this->getMockBuilder(Client::class)
            ->setMethods(['request'])
            ->getMock();
        $this->client = $client;
        $this->client->setGuzzle($this->httpMock);
    }

    /**
     * @param string $fileName
     *
     * @return Response
     */
    protected function getFixtureJson($fileName)
    {
        $namespace = explode('\\', strtolower(get_class($this->client)));
        $dirName = end($namespace).'client';
        return new Response(200, [], file_get_contents(__DIR__ . '/../fixtures/'.$dirName.'/'.$fileName));
    }
}