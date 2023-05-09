<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    public function phpinfo(): Response
    {
        ob_start();
        phpinfo();
        $phpinfo = ob_get_contents();
        ob_end_clean();

        return new Response($phpinfo);
    }
}
