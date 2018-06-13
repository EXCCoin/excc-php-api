<?php namespace EXCCoin\Tests\Data;

use EXCCoin\Client\Notifier as NotifierClient;
use EXCCoin\Tests\Traits\MocksClient;
use PHPUnit\Framework\TestCase;

class NotifierClientTest extends TestCase
{
    use MocksClient;

    /**
     * @var NotifierClient
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->setUpClientMocking(new NotifierClient(null));
    }

    public function test_add_to_watchlist()
    {
        $addr = 'TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75';

        $this->httpMock->expects($this->once())
            ->method('request')
            ->with('post', $this->equalTo('/add'))
            ->willReturn($this->getFixtureJson('add.json'));

        $result = $this->client->addAddressesToWatchList([$addr]);

        $this->assertSame('OK', $result[$addr]);
    }
}