<?php

namespace App\Controller;

use App\Form\SelectionActiviteType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/activite")
 * Class ActiviteController
 * @package App\Controller
 */
class ActiviteController extends AbstractController
{
    /**
     * @Route("/veterinaire", methods={"GET","POST"}, name="activite")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function search(Request $request)
    {
        $activite = NULL;

        $form = $this->createForm(SelectionActiviteType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $data = $form->getData();
            $activite = $data['activiteSelect'];
        }
        return $this->render('activite/search.html.twig', [
            'form' => $form->createView(),
            'activite' => $activite
        ]);
    }
}
