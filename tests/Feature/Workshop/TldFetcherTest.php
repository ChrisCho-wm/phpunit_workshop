<?php

namespace Tests\Feature\Workshop;

use App\Libraries\TldFetcher;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Exception;
use Mockery;

class TldFetcherTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @dataProvider tldProvider
     *
     * @return void
     */
    public function testFetchTldSuccess($tld, $json, $expect)
    {
        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('get')->andReturn(new Response(200, [], $json));
        $TldFetcher = new TldFetcher($mockClient);

        $this->assertEquals($expect, $TldFetcher->countNonEnTld($tld));
    }

    public function testFetchNoFound()
    {
        $json = '{
            "message": "Failed to find any statistics for zone:abc"
        }';

        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('get')->andReturn(new Response(404, [], $json));
        $TldFetcher = new TldFetcher($mockClient);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage("Tld Not Found");
       
        $TldFetcher->countNonEnTld('tld');
    }

    public function tldProvider()
    {
        return [
            ['tw', file_get_contents(dirname(__FILE__).'/tw.json'), 3],
            ['cn', file_get_contents(dirname(__FILE__).'/cn.json'), 3],
            ['us', file_get_contents(dirname(__FILE__).'/us.json'), 0],
        ];
    }
}
