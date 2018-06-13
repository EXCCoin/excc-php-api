<?php namespace EXCCoin\Tests\Data;

use EXCCoin\Client\Chain as ChainClient;
use EXCCoin\Data\Transaction;
use EXCCoin\Tests\Traits\MocksClient;
use PHPUnit\Framework\TestCase;

class ChainClientTest extends TestCase
{
    use MocksClient;

    /**
     * @var ChainClient
     */
    protected $client;

    public function setUp()
    {
        parent::setUp();

        $this->setUpClientMocking(new ChainClient(null));
    }

    public function test_transaction()
    {
        $txId = '2f102d398ba7e3f79a1ca7c9ec6e3238876570a5b6d1785a66a77032775614ce';

        $this->httpMock->expects($this->once())
            ->method('request')
            ->with('post', $this->equalTo('/'))
            ->willReturn($this->getFixtureJson('rpc_getrawtransaction_2f102d398ba7e3f79a1ca7c9ec6e3238876570a5b6d1785a66a77032775614ce_decoded.json'));

        $transaction = $this->client->getTransaction($txId);

        $this->assertEquals(8, $transaction->getConfirmations());
        $this->assertEquals($txId, $transaction->getTxid());
        $this->assertEquals((new \DateTime())->setTimestamp('1528793552'), $transaction->getTime());
    }

    public function test_get_best_block_hash()
    {
        $this->httpMock->expects($this->once())
            ->method('request')
            ->with('post', $this->equalTo('/'))
            ->willReturn($this->getFixtureJson('rpc_getbestblockhash.json'));

        $bestBlockHash = $this->client->getBestBlockHash();

        $this->assertEquals('0000000006aa6dfd0d63e67ab736cc3bb6ef3556c030cbee1395bb07cb3d7415', $bestBlockHash);
    }

    public function test_get_block_header()
    {
        $this->httpMock->expects($this->once())
            ->method('request')
            ->with('post', $this->equalTo('/'))
            ->willReturn($this->getFixtureJson('rpc_getblockheader_00000000045d753df82df7352e98f5a20d91b9fa5d441e1cbaa57651eef8e2ef_decoded.json'));

        $blockHeader = $this->client->getBlockHeader('00000000045d753df82df7352e98f5a20d91b9fa5d441e1cbaa57651eef8e2ef');

        $this->assertSame('00000000045d753df82df7352e98f5a20d91b9fa5d441e1cbaa57651eef8e2ef', $blockHeader->getHash());
        $this->assertSame(82, $blockHeader->getConfirmations());
        $this->assertSame(6, $blockHeader->getVersion());
        $this->assertSame('0000000001978c37f1d8565254fc1e682135a79099750a358945a12f947080fc', $blockHeader->getPreviousBlockHash());
        $this->assertSame('dcec8dbff997a5f79e717193d417c74146c7b31d44786cd389daa0a182811bfa', $blockHeader->getMerkleRoot());
        $this->assertSame('41ee582db6e1e13ae8e0138253fbb02768c02fc16b7d80ce9c678348e7a3c095', $blockHeader->getStakeRoot());
        $this->assertSame(0x1c06dd0d, $blockHeader->getBits());
        $this->assertSame(39.00613433, $blockHeader->getSBits());
        $this->assertSame(323504, $blockHeader->getHeight());
        $this->assertEquals((new \DateTime())->setTimestamp(1528793552), $blockHeader->getTime());
        $this->assertSame(1051204842, $blockHeader->getNonce());
        $this->assertSame(9548.36375763, $blockHeader->getDifficulty());
        $this->assertSame('0000000003f63ab08750efbf8ed783db94ce7a26bdbd63ca0f96f4a0d54bf257', $blockHeader->getNextBlockHash());
    }

    public function test_get_block()
    {
        $this->httpMock->expects($this->once())
            ->method('request')
            ->with('post', $this->equalTo('/'))
            ->willReturn($this->getFixtureJson('rpc_getblock_00000000045d753df82df7352e98f5a20d91b9fa5d441e1cbaa57651eef8e2ef_decoded.json'));

        $block = $this->client->getBlock('00000000045d753df82df7352e98f5a20d91b9fa5d441e1cbaa57651eef8e2ef');

        $this->assertSame('00000000045d753df82df7352e98f5a20d91b9fa5d441e1cbaa57651eef8e2ef', $block->getHash());
        $this->assertSame(170, $block->getConfirmations());
        $this->assertSame(6, $block->getVersion());
        $this->assertSame('0000000001978c37f1d8565254fc1e682135a79099750a358945a12f947080fc', $block->getPreviousBlockHash());
        $this->assertSame('dcec8dbff997a5f79e717193d417c74146c7b31d44786cd389daa0a182811bfa', $block->getMerkleRoot());
        $this->assertSame('41ee582db6e1e13ae8e0138253fbb02768c02fc16b7d80ce9c678348e7a3c095', $block->getStakeRoot());
        $this->assertSame(0x1c06dd0d, $block->getBits());
        $this->assertSame(39.00613433, $block->getSBits());
        $this->assertSame(323504, $block->getHeight());
        $this->assertEquals((new \DateTime())->setTimestamp(1528793552), $block->getTime());
        $this->assertSame(1051204842, $block->getNonce());
        $this->assertSame(9548.36375763, $block->getDifficulty());
        $this->assertSame('0000000003f63ab08750efbf8ed783db94ce7a26bdbd63ca0f96f4a0d54bf257', $block->getNextBlockHash());
        $this->assertSame([
            'b0cd26a5204e32a8aefa9ceb4601869b402a0f742fd9b1a271a35a04683df569',
            '2f102d398ba7e3f79a1ca7c9ec6e3238876570a5b6d1785a66a77032775614ce',
            '3df28420a8f3af6669fa45f19559370cc0b9b2e4e7e91af9408b8fb8d2b69256',
            '43c5a69d06d5b476daed924eaf349f9ba463e6b9da72884a1ec647545a6703b5',
            'e7c98d02af6929fd4ea3ceefdcb09f4ecce5a45c75b626349926f76f782a1bba',
        ], $block->getTransactionsHashes());
        $this->assertSame([
            "5acd4afc8dc2016378a8e7ec55207abb52980753663ca125c12aa27a28aaf655",
            "f5e23d851995936392ae5643c4a49f32dbbeb0ab7a907f7ef3cd1ed0383dc9a8",
            "a562db434b15deb40fab13de568e945a1a295ed11ba909c949be407c482a8c82",
            "3b8148613eb7f8a3c9b15394424c285333976b92777b2a28b7830aeccfb7a60e",
            "6d39816a5abe266e3992d365598cdfc544d46d7ee4c5dc21eaba8f854aedaa76",
            "f5a138667057fedbcb65a5480daa9803cc96597e574c46c49511c9c3d8e79d77",
            "f6d25b7eae3d6347ea9bd803e317907adb55ba5187f918c15c4b52ec58d27117",
            "880887a95ae62b1d651b8a819e3be22c14c30833dcc0b3bb8aec04e12364561d",
            "528aea565ca5a8ee21ab460e6f2b550484a2dc3a04f00e8333cdb46e3dff34c0",
            "285b1ff8df417596d2bddac48ec878c16707de5e2007cf72d20315ed8fa0e928",
            "49c201d848917c7e3952123863c321434efe25c82410829ce72b5a2f05d7864b",
            "a98df7ae3ef2145bd3f5d052afe299279e2330b7b52b56b68624b0db9716e52c",
            "b94a4056e5023e547a378e0cac044575655802eb5a3e30cd4eb38ca18911e625"
        ], $block->getStakeTransactionsHashes());
        $this->assertSame(6622, $block->getSize());
        $this->assertSame(1, $block->getRevocations());
    }

    public function test_get_relevant_transactions()
    {
        $this->httpMock->expects($this->once())
            ->method('request')
            ->with('post', $this->equalTo('/'))
            ->willReturn($this->getFixtureJson('rpc_searchrawtransactions_TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75_decoded.json'));

        $transactions = $this->client->getRelevantTransactions('TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75');

        $this->assertCount(2, $transactions);
        $this->assertInstanceOf(Transaction::class, $transactions[0]);
        $this->assertInstanceOf(Transaction::class, $transactions[1]);
        $this->assertSame('2cf8880d4d9fd925381046ab28ca15c77e4a4b9e935da65f607d1afd27b33119', $transactions[0]->getTxid());
        $this->assertSame(74443, $transactions[0]->getConfirmations());
        $this->assertSame(70711, $transactions[1]->getConfirmations());
        $this->assertSame(0.14509833, $transactions[0]->getOutAmount('TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75'));
        $this->assertSame(false, $transactions[1]->getOutAmount('TseQDDfk4xDvz2R92cexf1WaAvUGXEfRJ75'));
    }

    public function test_create_transaction()
    {
        $this->httpMock->expects($this->once())
            ->method('request')
            ->with('post', $this->equalTo('/'))
            ->willReturn($this->getFixtureJson('rpc_createrawtransaction.json'));

        $inputs = [
            ['txid' => '2f102d398ba7e3f79a1ca7c9ec6e3238876570a5b6d1785a66a77032775614ce', 'vout' => 1]
        ];
        $outputs = [
            'TsSW886LsvH9Lhpdi6E7CmBo3vij7UkQU9K' => 0.5
        ];

        $rawTransaction = $this->client->createTransaction($inputs, $outputs);

        $this->assertSame('0100000001ce1456773270a7665a78d1b6a570658738326eecc9a71c9af7e3a78b392d102f0100000000ffffffff0180f0fa020000000000001976a91410437a67b9eef35979f5412667e700a7b5b4f78088ac000000000000000001ffffffffffffffff00000000ffffffff00',
            $rawTransaction->getHexData());
    }

    public function test_decode_raw_transaction()
    {
        $this->httpMock->expects($this->exactly(2))
            ->method('request')
            ->with('post', $this->equalTo('/'))
            ->willReturn($this->getFixtureJson('rpc_createrawtransaction.json'), $this->getFixtureJson('rpc_decoderawtransaction.json'));

        $inputs = [
            ['txid' => '2f102d398ba7e3f79a1ca7c9ec6e3238876570a5b6d1785a66a77032775614ce', 'vout' => 1]
        ];
        $outputs = [
            'TsSW886LsvH9Lhpdi6E7CmBo3vij7UkQU9K' => 0.5
        ];

        $rawTransaction = $this->client->createTransaction($inputs, $outputs);
        $transaction = $this->client->decodeRawTransaction($rawTransaction);

        $this->assertSame('5bbac481bd9a378b10305ea020efb3b29e951eef99ef653c404d0f36c5dea75e', $transaction->getTxid());
        $this->assertSame(0, $transaction->getConfirmations());
    }
}
