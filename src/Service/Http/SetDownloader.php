<?php


namespace App\Service\Http;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class SetDownloader
{
    private HttpClientInterface $client;
    private string $data = '';

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function download(string $setName): bool
    {
        $downloadUrl = sprintf('https://mtgjson.com/api/v5/%s.json', strtoupper($setName));

        $response = $this->client->request('GET', $downloadUrl);

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        $this->data = $response->getContent();

        return true;
    }

    public function getData(): string
    {
        return $this->data;
    }
}
