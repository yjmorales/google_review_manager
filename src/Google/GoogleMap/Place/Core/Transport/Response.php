<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Core\Transport;

/**
 * Response holding the information returned by all Google Map Place API Service api endpoints.
 *
 * @link https://developers.google.com/maps/documentation/places/web-service/overview
 */
class Response
{
    /**
     * Holds the returned data.
     */
    protected $_data;

    /**
     * Holds the response status value.
     *
     * @var string
     */
    protected string $_status;

    /**
     * Sets the returned data.
     *
     * @param mixed $data New data value.
     *
     * @return void
     */
    public function setData($data): void
    {
        $this->_data = $data;
    }

    /**
     * Sets the response status value.
     *
     * @param string $status New value
     *
     * @return void
     */
    public function setStatus(string $status): void
    {
        $this->_status = $status;
    }

    /**
     * Gets the status returned by the Google API.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->_status;
    }

    /**
     * Gets the returned data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->_data;
    }
}