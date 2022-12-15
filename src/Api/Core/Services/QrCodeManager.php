<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Api\Core\Services;

use App\Api\Core\Services\QrCodeManager\Exception\QrCodeImgNotFountException;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Exception;

/**
 * Class responsible to manage a Google review qr code.
 * This class uses the "chillerlan/php-qrcode" component to generate the qr code.
 *
 * @link: https://github.com/chillerlan/php-qrcode
 */
class QrCodeManager
{
    /**
     * Holds the qr code file extention.
     */
    private const IMG_EXT = QRCode::OUTPUT_IMAGE_PNG;

    /**
     * Initial configuration based on https://github.com/chillerlan/php-qrcode
     */
    private const DEFAULT_CONFIG = [
        'eccLevel'   => QRCode::ECC_H,
        'outputType' => self::IMG_EXT,
        'version'    => 8,
    ];

    /**
     * Configuration used to initialize the qr code generator.
     *
     * @var QROptions
     */
    private QROptions $_options;

    /**
     * Local instance to generate the qr codes.
     *
     * @var QRCode
     */
    private QRCode $_qrCode;

    /**
     * @param array|null $config Defines additional configurations to generate qr codes. @link:
     *                           https://github.com/chillerlan/php-qrcode
     */
    public function __construct(array $config = null)
    {
        $qrCodeConfig = self::DEFAULT_CONFIG;
        if ($config) {
            $qrCodeConfig = array_merge($qrCodeConfig, $config);
        }
        $this->_options = new QROptions($qrCodeConfig);
        $this->_qrCode  = new QRCode($this->_options);
    }

    /**
     * Generates a qr code image. It returns the base64 image value.
     *
     * @param $data
     *
     * @return string
     */
    public function generate($data): string
    {
        return $this->_qrCode->render($data);
    }

    /**
     * Based on a given filename this function gets its base64 value.
     *
     * @param string $fileName Represents the image file name to obtain its base64 value.
     *
     * @return string
     * @throws Exception
     */
    public static function getQrCodeBase64(string $fileName): string
    {
        if (!file_exists($fileName)) {
            throw new QrCodeImgNotFountException('QR Code filename does not exists');
        }
        $base64 = base64_encode(file_get_contents($fileName));

        return 'data:' . self::IMG_EXT . ";base64,$base64";
    }

    /**
     * @return string
     */
    public function getImgExt(): string
    {
        return self::IMG_EXT;
    }
}