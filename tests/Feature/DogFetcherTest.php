<?php

namespace Tests\Feature;

use App\DogImage;
use App\Libraries\DogFetcher;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Exception;
use Mockery;

class DogFetcherTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFetchSuccess()
    {
        $json = '{
            "message": "https://images.dog.ceo/breeds/akita/Akina_Inu_in_Riga_1.jpg",
            "status": "success"
          }';

        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('get')->andReturn(new Response(200, [], $json));
        $dogFetcher = new DogFetcher($mockClient);

        $dogFetcher->saveDogImage('akita');

        $this->assertDatabaseHas('dog_images', [
            'breed_name' => 'akita',
            'image_url' => 'https://images.dog.ceo/breeds/akita/Akina_Inu_in_Riga_1.jpg',
        ]);
    }

    public function testFetchNoFound()
    {
        $json = '{
            "status": "error",
            "message": "Breed not found (master breed does not exist)",
            "code": 404
        }';

        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('get')->andReturn(new Response(404, [], $json));
        $dogFetcher = new DogFetcher($mockClient);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage("Couldn't get dog image");
       

        $dogFetcher->saveDogImage('affenpinscher');
    }
}
