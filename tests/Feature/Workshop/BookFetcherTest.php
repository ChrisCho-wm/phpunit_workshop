<?php

namespace Tests\Feature\Workshop;

use App\Libraries\BookFetcher;
use App\Libraries\DogFetcher;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Exception;
use Mockery;

class BookFetcherTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testFetchBookSuccess()
    {
        $json = '{
            "ISBN:0747581088": {
              "bib_key": "ISBN:0747581088",
              "info_url": "https://openlibrary.org/books/OL27938783M/Harry_Potter_and_the_Half_Blood_Prince",
              "preview": "noview",
              "preview_url": "https://openlibrary.org/books/OL27938783M/Harry_Potter_and_the_Half_Blood_Prince"
            }
        }';

        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('get')->andReturn(new Response(200, [], $json));
        $bookFetcher = new BookFetcher($mockClient);

        $bookFetcher->saveBook('0747581088');

        $this->assertDatabaseHas('books', [
            "bib_key" => "ISBN:0747581088",
            "info_url" => "https://openlibrary.org/books/OL27938783M/Harry_Potter_and_the_Half_Blood_Prince",
            "preview" => "noview",
            "preview_url" => "https://openlibrary.org/books/OL27938783M/Harry_Potter_and_the_Half_Blood_Prince"
        ]);
    }

    public function testFetchNoFound()
    {
        $json = '{}';

        $mockClient = Mockery::mock(Client::class);
        $mockClient->shouldReceive('get')->andReturn(new Response(200, [], $json));
        $bookFetcher = new BookFetcher($mockClient);

        $this->expectException(Exception::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage("Book Not Found");
       
        $bookFetcher->saveBook('isbn not exists');
    }
}
