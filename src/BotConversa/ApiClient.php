<?php

namespace BotConversa;

class ApiClient
{
    private $baseUrl;
    private $apiKey;
    private $timeout;

    public function __construct()
    {
        $this->baseUrl = $_ENV['BASE_URL'] ?? '';
        $this->apiKey = $_ENV['API_KEY'] ?? '';
        $this->timeout = $_ENV['API_TIMEOUT'] ?? 10;
    }

    public function callApi(string $endpoint, string $method, array $body = []): ?array
    {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');
        
        $headers = [
            "api-key: {$this->apiKey}",
            "Content-Type: application/json"
        ];

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $this->timeout,
            CURLOPT_FOLLOWLOCATION => false,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH']) && !empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        try {
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                error_log(
                    "ApiClient::callApi - Error in cURL request: " . curl_error($ch) . "\n",
                    3,
                    "logs/" . "ApiBotConversa_" . date("d-m-Y") . ".log"
                );

                throw new \Exception("Error in cURL request: " . curl_error($ch));
            }

            $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($http_status !== 200) {
                error_log(
                    "ApiClient::callApi - Error in response: HTTP Code " . $http_status . "\n",
                    3,
                    "logs/" . "ApiBotConversa_" . date("d-m-Y") . ".log"
                );

                throw new \Exception("Error in response: HTTP Code " . $http_status);
            }

            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                error_log(
                    "ApiClient::callApi - Error decoding JSON: " . json_last_error_msg() . "\n",
                    3,
                    "../logs/" . "ApiBotConversa_" . date("d-m-Y") . ".log"
                );

                throw new \Exception("Error decoding JSON: " . json_last_error_msg());
            }
        } catch (\Exception $e) {
            error_log(
                $e->getMessage() . "\n",
                3,
                "logs/" . "ApiBotConversa_" . date("d-m-Y") . ".log"
            );

            $data = null;
        } finally {
            curl_close($ch);
        }

        return $data;

        var_dump($data);
    }

    public function getSubscriberByPhone(string $phone): ?array
    {
        $endpoint = "webhook/subscriber/get_by_phone/{$phone}/";

        return $this->callApi($endpoint, 'GET');
    }

    public function getSubscribers(int $page){
         $endpoint = "webhook/subscribers/";

         if($page > 0){
            $endpoint .= "?page=". $page . "/";
         }

         return $this->callApi($endpoint, 'GET');
    }

    public function createSubscriber(string $phone, string $first_name, string $last_name): ?array
    {
        $endpoint = "webhook/subscriber/";

        $body = [
            'phone' => $phone,
            'first_name' => $first_name,
            'last_name' => $last_name
        ];

        return $this->callApi($endpoint, 'POST', $body);
    }

    public function deleteSubscriber(string $id): ?array
    {
        $endpoint = "webhook/subscriber/{$id}/delete/";

        return $this->callApi($endpoint, 'DELETE');
    }

    public function sendMessage(string $id, string $type, string $message): ?array
    {
        $endpoint = "webhook/subscriber/{$id}/send_message/";

        $body = [
            'type' => $type,
            'value' => $message,
        ];

        return $this->callApi($endpoint, 'POST', $body);
    }
}