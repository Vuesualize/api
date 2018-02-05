<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ConnectController extends Controller
{
    /**
     * @Route("/connect/check", name="connect_check")
     */
    public function connectCheckAction(Request $request)
    {
        // never reached because of Authentication Guard
    }
}