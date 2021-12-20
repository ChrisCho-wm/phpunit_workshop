<?php
namespace App\Libraries;

use App\DogImage;
use Exception;
use GuzzleHttp\Client as HttpClient;

class DogFetcher
{
    protected $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function saveDogImage($breedName)
    {
        $image = $this->getDogImage($breedName);

        $dogImage = new DogImage;
        $dogImage->image_url = $image['message'];
        $dogImage->breed_name = $breedName;
        $dogImage->save();
    }

    protected function getDogImage($breedName)
    {
        $url = "https://dog.ceo/api/breed/$breedName/images/random";

        $response = $this->httpClient->get($url, ['http_errors' => false]);

        if ($response->getStatusCode() == 200) {
            return json_decode($response->getBody(), true);    
        } else {
            throw new Exception("Couldn't get dog image", 404);
        }
    }
}