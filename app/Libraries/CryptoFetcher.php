<?php
namespace App\Libraries;

use App\Book;
use Exception;
use GuzzleHttp\Client as HttpClient;

class CryptoFetcher
{
    protected $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function isBitCoinSave()
    {
        $global = $this->getGlobal()[0];
        $btc = $this->getBTC()[0];

        $btc_d = $global['btc_d'];
        $btc_price_usd = $btc['price_usd'];

        $holdSuggestion = '';
        switch (true) {
            case ($btc_d >= 80):
                $holdSuggestion = "very safe";
                break;
            case ($btc_d >= 40):
                $holdSuggestion = "most safe";
                break;
            case ($btc_d >= 20);
                $holdSuggestion = "not safe";
                break; 
            case ($btc_d < 20);
            default:
                $holdSuggestion = "run asap";
                break;
            
        }

        if ($btc_price_usd >= 40000) {
            $buySuggestion = 'too high, keep waiting';
        } else {
            $buySuggestion = 'low enough, go buy it';
        }

        return [
            'holdSuggestion' => $holdSuggestion,
            'buySuggestion' => $buySuggestion,
        ];
    }

    protected function getGlobal()
    {
        $url = "https://api.coinlore.net/api/global";

        $response = $this->httpClient->get($url, ['http_errors' => false]);

        return json_decode($response->getBody(), true);

        /*
        status: 200
            [
                {
                    "coins_count": 6966,
                    "active_markets": 23292,
                    "total_mcap": 2202524503899.354,
                    "total_volume": 153244755358.76865,
                    "btc_d": "40.19",
                    "eth_d": "21.25",
                    "mcap_change": "-4.91",
                    "volume_change": "2.61",
                    "avg_change_percent": "-0.17",
                    "volume_ath": 3992741953593.4854,
                    "mcap_ath": 2912593726674.3335
                }
            ]
        */

    }

    protected function getBTC()
    {
        $url = "https://api.coinlore.net/api/ticker/?id=90";

        $response = $this->httpClient->get($url, ['http_errors' => false]);

        return json_decode($response->getBody(), true);

        /*
        status: 200
            [
                {
                    "id": "90",
                    "symbol": "BTC",
                    "name": "Bitcoin",
                    "nameid": "bitcoin",
                    "rank": 1,
                    "price_usd": "46821.16",
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
            ]
        */
    }
}