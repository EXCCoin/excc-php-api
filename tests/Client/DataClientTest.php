<?php namespace EXCCoin\Tests\Data;

use EXCCoin\Client\Data as DataClient;
use EXCCoin\Data\Transaction;
use EXCCoin\Tests\Traits\MocksClient;
use PHPUnit\Framework\TestCase;

class DataClientTest extends TestCase
{
    use MocksClient;

    /**
     * @var DataClient
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->setUpClientMocking(new DataClient(null));
    }

    public function test_address_raw()
    {
        $this->httpMock->expects($this->exactly(2))
            ->method('request')
            ->with('get', $this->equalTo('/api/address/TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75/raw'))
            ->willReturn($this->getFixtureJson('api_address_TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75_raw.json'));

        $transactions = $this->client->getAddressRaw('TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75');

        $this->assertCount(1, $transactions);
        $this->assertInstanceOf(Transaction::class, $transactions[0]);
        $this->assertEquals('2cf8880d4d9fd925381046ab28ca15c77e4a4b9e935da65f607d1afd27b33119', $transactions[0]->getTxid());
        $this->assertEquals(5847, $transactions[0]->getConfirmations());
        $this->assertEquals(0.14509833, $transactions[0]->getOutAmount('TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75'));

        $transactions = $this->client->getAddressRaw('TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75', new \DateTime());
        $this->assertCount(0, $transactions);
    }

    public function test_request_exception()
    {
        $this->httpMock->expects($this->once())
            ->method('request')
            ->with('get', $this->equalTo('/api/address/TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75/raw'))
            ->willThrowException(new \Exception());

        $this->assertEquals(false, $this->client->getAddressRaw('TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75'));
    }
}
