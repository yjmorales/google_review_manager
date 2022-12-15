<?php
/**
 * @author Yenier Jimenez <yjmorales86@gmail.com>
 */

namespace App\Controller\Business;

use App\Core\Controller\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * todo: remove
 * @Route("/test")
 */
class TestController extends BaseController
{
    /**
     * Main route.
     *
     * @Route("/", name="test")
     */
    public function test(): Response
    {
        return $this->render('/test/test.html.twig');
    }
}