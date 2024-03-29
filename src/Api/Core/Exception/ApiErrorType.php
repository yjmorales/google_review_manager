<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Core\Exception;

use Common\Utils\Enum\TEnum;
use Symfony\Component\HttpFoundation\Response;

/**
 * Defines the possible error types
 *
 * @method static PAYLOAD_VALIDATION_ERROR
 * @method static UNEXPECTED_FAILURE
 * @method static ENTITY_NOT_FOUND_ERROR
 */
class ApiErrorType
{
    use TEnum;

    /**
     * Identifies the error.
     *
     * @var string
     */
    private string $_type;

    /**
     * Describes the error.
     *
     * @var string
     */
    private string $_message;

    /**
     * Http code generated by the error.
     *
     * @var string
     */
    private string $_httpCode;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->_type;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->_message;
    }

    /**
     * @return string
     */
    public function getHttpCode(): string
    {
        return $this->_httpCode;
    }

    protected function _populate(array $definition): void
    {
        $this->_type     = $definition['type'];
        $this->_message  = $definition['message'];
        $this->_httpCode = $definition['httpCode'];
    }

    protected static function _initializeDefinitions(): array
    {
        return [
            'PAYLOAD_VALIDATION_ERROR' => [
                'type'     => 'PayloadValidationError',
                'message'  => 'The request payload is well formed however the values passed does not match the parameter criteria.',
                'httpCode' => Response::HTTP_BAD_REQUEST,
            ],
            'UNEXPECTED_FAILURE'       => [
                'type'     => 'UnexpectedFailure',
                'message'  => 'An unexpected error has occurred.',
                'httpCode' => Response::HTTP_INTERNAL_SERVER_ERROR,
            ],
            'ENTITY_NOT_FOUND_ERROR'   => [
                'type'     => 'EntityNotFoundError',
                'message'  => 'The requested entity has not been found',
                'httpCode' => Response::HTTP_NOT_FOUND,
            ],
        ];
    }
}