<?php

namespace App\Services;

use App\Libraries\HandleApi;

class ApiService
{
    /**
     * Make a GET request to the API and return decoded data.
     */
    public function get(string $endpoint): array
    {
        $url = config('services.api.base_url').$endpoint;
        $curlOutput = HandleApi::getCURLOutput($url, 'GET', []);
        $decodedData = json_decode($curlOutput);

        return isset($decodedData->data) ? $decodedData->data : [];
    }

    /**
     * Make a PUT request to the API and return decoded response.
     *
     * @return object|null
     */
    public function put(string $endpoint, array $data)
    {
        $url = config('services.api.base_url').$endpoint;
        $fieldData = json_encode($data);
        $curlOutput = HandleApi::getCURLOutput($url, 'PUT', $fieldData);

        return json_decode($curlOutput);
    }

    /**
     * Make a POST request to the API and return decoded response.
     *
     * @return object|null
     */
    public function post(string $endpoint, array $data)
    {
        $url = config('services.api.base_url').$endpoint;
        $fieldData = json_encode($data);
        $curlOutput = HandleApi::getCURLOutput($url, 'POST', $fieldData);

        return json_decode($curlOutput);
    }

    /**
     * Make a DELETE request to the API and return decoded response.
     *
     * @return object|null
     */
    public function delete(string $endpoint)
    {
        $url = config('services.api.base_url').$endpoint;
        $curlOutput = HandleApi::getCURLOutput($url, 'DELETE', []);

        return json_decode($curlOutput);
    }
}
