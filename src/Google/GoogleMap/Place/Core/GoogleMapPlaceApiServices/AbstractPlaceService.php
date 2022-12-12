<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Core\GoogleMapPlaceApiServices;

use App\Google\GoogleMap\Place\Core\Transport\Response;
use App\Google\GoogleMap\Place\Core\Transport\Transport;
use Exception;
use RuntimeException;

/**
 * Holds the abstract logic to perform actions over the Google map place api.
 */
abstract class AbstractPlaceService
{
    /**
     * Holds the Google api key.
     *
     * @var string
     */
    protected string $_apiKey;

    /**
     * Responsible to communicate with the Google api endpoint.
     *
     * @var Transport
     */
    protected Transport $_transport;

    /**
     * @param Transport $transport Responsible to communicate with the Google api endpoint.
     * @param string    $apiKey    Holds the Google api key.
     */
    public function __construct(Transport $transport, string $apiKey)
    {
        $this->_apiKey    = $apiKey;
        $this->_transport = $transport;
    }

    /**
     * Executes the endpoint held by the base url with the given args.
     *
     * @param string $args Holds the query string used to query the api endpoint.
     *
     * @return IPlaceResponse
     *
     * @throws Exception
     */
    protected function _execute(string $args): IPlaceResponse
    {
        if (empty($this->baseUrl())) {
            throw new RuntimeException('The base url is not set yet. You should defined first.');
        }
        $response = $this->_transport->get($this->baseUrl(), "/json?$args");

        return $this->parseResponse($response);
    }

    /**
     * Each Google map place api service has a particular base url (endpoint). This defines the base url of the
     * endpoint.
     *
     * @return string
     */
    abstract protected function baseUrl(): string;

    /**
     * Each Google map place api service has a different response structure. This parse the response data and build the
     * respective response model.
     *
     * @param Response $dataResponse Holds the response returned by the transport once the call is made to the endpoint.
     *
     * @return IPlaceResponse
     */
    abstract protected function parseResponse(Response $dataResponse): IPlaceResponse;

}