<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Core\Listener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;

/**
 * Handles exceptions
 *
 * @package App\Core\Listener
 */
class ExceptionListener
{
    /**
     * @var RouterInterface
     */
    private $_router;

    /**
     * @var string
     */
    private $_env;

    /**
     * ExceptionListener constructor.
     */
    public function __construct(RouterInterface $router, string $env)
    {
        $this->_router = $router;
        $this->_env    = $env;
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        if ('prod' !== $this->_env) {
            return;
        }

        $exception      = $event->getThrowable();
        $path           = $event->getRequest()->getPathInfo();
        $isAdmin        = strpos($path, 'admin') !== false;
        $exceptionClass = get_class($exception);

        switch ($exceptionClass) {
            case NotFoundHttpException::class:
                $response = new RedirectResponse($this->_router->generate('landing_404_error'));
                if ($isAdmin) {
                    $response = new RedirectResponse($this->_router->generate('admin_404_error'));
                }
                $event->setResponse($response);
                break;
            default:
                $response = new RedirectResponse($this->_router->generate('landing_500_error'));
                if ($isAdmin) {
                    $response = new RedirectResponse($this->_router->generate('admin_500_error'));
                }
                $event->setResponse($response);
                break;
        }
    }
}