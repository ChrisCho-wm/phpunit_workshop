<?php
namespace App\Libraries;

use App\Book;
use Exception;
use GuzzleHttp\Client as HttpClient;

class BookFetcher
{
    protected $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function saveBook($isbn)
    {
        $book = $this->getBook($isbn);

        if (!empty($book["ISBN:$isbn"])) {
            Book::create($book["ISBN:$isbn"]);
        } else {
            throw new Exception("Book Not Found", 404);
        }
    }

    protected function getBook($isbn)
    {
        $url = "https://openlibrary.org/api/books?bibkeys=ISBN:$isbn&format=json";

        $response = $this->httpClient->get($url, ['http_errors' => false]);

        return json_decode($response->getBody(), true);

        /*
        status: 200

            {
                "ISBN:0747581088": {
                "bib_key": "ISBN:0747581088",
                "info_url": "https://openlibrary.org/books/OL27938783M/Harry_Potter_and_the_Half_Blood_Prince",
                "preview": "noview",
                "preview_url": "https://openlibrary.org/books/OL27938783M/Harry_Potter_and_the_Half_Blood_Prince"
                }
            }

        status: 200

            {

            }

        */

    }
}