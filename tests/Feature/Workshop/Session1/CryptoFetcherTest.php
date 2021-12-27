<?php

namespace Tests\Feature\Workshop\Session1;

use App\Libraries\CryptoFetcher;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Exception;
use Mockery;

class CryptoFetcherTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @dataProvider tldProvider
     *
     * @return void
     */
    public function testCrypto($globalJson, $btcJson, $expect)
    {
        $mockClient = Mockery::mock(Client::class);
        $mockClient
            ->shouldReceive('get')
            ->withSomeOfArgs('https://api.coinlore.net/api/global')
            ->andReturn(new Response(200, [], $globalJson));

        $mockClient
            ->shouldReceive('get')
            ->withSomeOfArgs('https://api.coinlore.net/api/ticker/?id=90')
            ->andReturn(new Response(200, [], $btcJson));

        $cryptoFetcher = new CryptoFetcher($mockClient);

        $this->assertEquals($expect, $cryptoFetcher->isBitCoinSave());
    }

    public function tldProvider()
    {
        $globalJson = '[
            {
                "coins_count": 6966,
                "active_markets": 23292,
                "total_mcap": 2202524503899.354,
                "total_volume": 153244755358.76865,
                "btc_d": "__BTCD__",
                "eth_d": "21.25",
                "mcap_change": "-4.91",
                "volume_change": "2.61",
                "avg_change_percent": "-0.17",
                "volume_ath": 3992741953593.4854,
                "mcap_ath": 2912593726674.3335
            }
        ]';

        // bitcoin

        $btcJson = '[
            {
                "id": "90",
                "symbol": "BTC",
                "name": "Bitcoin",
                "nameid": "bitcoin",
                "rank": 1,
                "price_usd": "__PRICE_USD__",
                "percent_change_24h": "0.57",
                "percent_change_1h": "-0.12",
                "percent_change_7d": "-3.35",
                "market_cap_usd": "885215573690.00",
                "volume24": "22854547378.14",
                "volume24_native": "488124.34",
                "csupply": "18906315.00",
                "price_btc": "1.00",
                "tsupply": "18903802",
                "msupply": "21000000"
            }
        ]';

        return [
            [
                str_replace('__BTCD__', 80, $globalJson),
                str_replace('__PRICE_USD__', 40000, $btcJson),
                [
                    'holdSuggestion' => 'very safe',
                    'buySuggestion' => 'too high, keep waiting',
                ],
            ],
            [
                str_replace('__BTCD__', 40, $globalJson),
                str_replace('__PRICE_USD__', 40000, $btcJson),
                [
                    'holdSuggestion' => 'most safe',
                    'buySuggestion' => 'too high, keep waiting',
                ],
            ],
            [
                str_replace('__BTCD__', 20, $globalJson),
                str_replace('__PRICE_USD__', 40000, $btcJson),
                [
                    'holdSuggestion' => 'not safe',
                    'buySuggestion' => 'too high, keep waiting',
                ],
            ],
            [
                str_replace('__BTCD__', 19, $globalJson),
                str_replace('__PRICE_USD__', 30000, $btcJson),
                [
                    'holdSuggestion' => 'run asap',
                    'buySuggestion' => 'low enough, go buy it',
                ],
            ],
        ];
    }
}
