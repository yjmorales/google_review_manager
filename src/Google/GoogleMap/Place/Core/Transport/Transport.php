<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Core\Transport;

use Exception;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Arr;

/**
 * This class is the responsible to handle the communication logic between this system and Google Map Api.
 */
class Transport
{
    /**
     * Guzzle Client used to communicate with  Google Map Api endpoints.
     *
     * @var GuzzleClient|null
     */
    protected ?GuzzleClient $_guzzleClient = null;

    /**
     * Responsible to perform the endpoints calls.
     *
     * @throws Exception
     */
    public function get(string $baseUrl, string $endPoint): Response
    {
        $dataResults = $this->viaCurl($baseUrl, $endPoint);
        $status      = Arr::get($dataResults, 'status');
        if (!in_array($status, ResponseStatus::STATUS)) {
            throw new Exception("The status $status is not a valid Google Map Place API status");
        }

        ($response = new Response())->setData($dataResults);
        $response->setStatus($status);

        return $response;
    }

    /**
     * Helper function to initialize an instance of Guzzle.
     *
     * @param string $baseUrl
     *
     * @return GuzzleClient
     */
    private function _getGuzzleClient(string $baseUrl): GuzzleClient
    {
        if (!$this->_guzzleClient) {
            return $this->_guzzleClient = new GuzzleClient(['base_uri' => $baseUrl]);
        }

        return $this->_guzzleClient;
    }

    /**
     * Helper function to perform the call using Guzzle.
     *
     * @param string $endPoint
     *
     * @throws GuzzleException
     */
    private function viaGuzzle(string $endPoint): void
    {
        try {
            $arguments = [
                'headers' => [],
                'verify'  => false,
                'json'    => [],
            ];
            $response  = $this->_guzzleClient->request('GET', $endPoint, $arguments);
            // todo:
        } catch (Exception $e) {
            throw new Exception('Failed to connect to the API.', 0, $e);
        }
    }

    /**
     * Helper function to perform the call using curl.
     *
     * @param string $baseUrl
     * @param string $endPoint
     */
    private function viaCurl(string $baseUrl, string $endPoint)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $url = "$baseUrl$endPoint";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}