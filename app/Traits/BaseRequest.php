<?php

namespace App\Traits;

use GuzzleHttp\Client;

trait  BaseRequest {

    /**
     * @param  string  $url
     * @param  array  $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getRequest(string $url, array $data = [], array $header = []): array    {
        $client = new Client();
        try {
            $response = $client->request('GET', $url,
                [
                    'headers' => $header,
                    'query'   => $data
                ]
            );
            $result =  json_decode($response->getBody()->getContents(), true);
            return [
                'status'    => @$result['success'],
                'data'      => @$result['data'],
                'code' => $response->getStatusCode()
            ];

        } catch (\Exception $exception) {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @param  string  $url
     * @param  array  $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function postRequest(string $url, array $data = [],array $header = [])
    {
        $client = new Client();
        try {
            $response = $client->request(
                'POST',
                $url,
                [
                    'headers' => $header,
                    'body'    => json_encode($data)
                ]
            );

            return [
                'status'    => true,
                'data'      => json_decode($response->getBody()->getContents(), true),
                'code' => $response->getStatusCode()
            ];
        } catch (\Exception $exception) {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    /**
     * @param  string  $url
     * @param  array  $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function putRequest(string $url, array $data = [], array $header = []): array
    {
        $client = new Client();
        try {
            $response = $client->request(
                'PUT',
                $url,
                [
                    'headers' => $header,
                    'body'    => json_encode($data)
                ]
            );

            return [
                'status'    => true,
                'data'      => json_decode($response->getBody()->getContents(), true),
                'code' => $response->getStatusCode()
            ];

        } catch (\Exception $exception) {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }


    /**
     * @param  string  $url
     * @param  array  $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function deleteRequest(string $url, array $data = [], array $header = []): array
    {
        $client = new Client();
        try {
            $response = $client->request(
                'DELETE',
                $url,
                [
                    'headers' => $header,
                    'body'    => json_encode($data)
                ]
            );
            return [
                'status'    => true,
                'data'      => json_decode($response->getBody()->getContents(), true),
                'code' => $response->getStatusCode()
            ];
        } catch (\Exception $exception) {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }




}
