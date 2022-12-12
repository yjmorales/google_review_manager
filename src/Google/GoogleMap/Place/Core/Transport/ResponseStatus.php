<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Google\GoogleMap\Place\Core\Transport;

/**
 * Holds a set of possibles status returned by the Google Map Place API Services.
 */
class ResponseStatus
{
    const OK = 'OK';
    const INVALID_REQUEST = 'INVALID_REQUEST';
    const OVER_QUERY_LIMIT = 'OVER_QUERY_LIMIT';
    const REQUEST_DENIED = 'REQUEST_DENIED';
    const UNKNOWN_ERROR = 'UNKNOWN_ERROR';
    const ZERO_RESULTS = 'ZERO_RESULTS';

    const   STATUS = [
        self::OK,
        self::INVALID_REQUEST,
        self::OVER_QUERY_LIMIT,
        self::REQUEST_DENIED,
        self::UNKNOWN_ERROR,
        self::ZERO_RESULTS,
    ];
}