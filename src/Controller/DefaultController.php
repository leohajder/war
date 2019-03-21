<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
    /**
     * Displays app index
     */
    public function index(Request $request)
    {
        /** @var int $army1 */
        $army1 = (int) $request->query->getInt('army1');
        /** @var int $army2 */
        $army2 = (int) $request->query->getInt('army2');

        return $this->render('default/index.html.twig', [
            'army1' => $army1,
            'army2' => $army2,
        ]);
    }
}