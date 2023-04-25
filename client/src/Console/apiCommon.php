<?php

namespace App\Console;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

trait apiCommon
{
    public function makeRequest(array $data = [], string $method = 'POST'): array|null
    {
        $client = (new HttpClient())->create();

        $response = $client->request(
            $method,
            $_ENV['API_SERVER'] . $this->url, [
                'body' => $data
            ]
        );

        return json_decode($response->getContent(), true);
    }

    public function makeRequestAndOut(OutputInterface $output, array $data = [], string $method = 'POST'): void
    {
        $resp = $this->makeRequest($data, $method);

        $output->write(json_encode($resp, JSON_PRETTY_PRINT));
        $output->write("\n");
    }
}