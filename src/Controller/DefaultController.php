<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * Displays app index
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }
}