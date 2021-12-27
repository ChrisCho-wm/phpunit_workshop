<?php
namespace App\Libraries;

use App\Book;
use Exception;
use GuzzleHttp\Client as HttpClient;

class TldFetcher
{
    protected $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function countNonEnTld($tld)
    {
        $tld = $this->getTld($tld);

        return collect($tld['includes'])->filter(function ($v) {
            return !preg_match("@^[a-zA-Z0-9\.\-]*$@", $v);
        })->count();
    }

    protected function getTld($tld)
    {
        $url = "https://api.domainsdb.info/v1/info/tld/$tld?limit=50";

        $response = $this->httpClient->get($url, ['http_errors' => false]);

        if ($response->getStatusCode() != 200) {
            throw new Exception('Tld Not Found', 404);
        }

        return json_decode($response->getBody(), true);

        /*
        status: 200
            tests/Feature/Workshop/tw.json
            tests/Feature/Workshop/cn.json
            tests/Feature/Workshop/us.json
        */

    }
}