<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Veterinaire;

/**
 * @Route("/tableaux")
 * Class TableauxBordController
 * @package App\Controller
 */
class TableauxBordController extends AbstractController
{
    /**
     * @Route("", name="tbord_nbSuivis")
     */
    public function nbSuivisActions()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository(Veterinaire::class);

        $suivisParVeto = $repo->nombreDeSuivisParVeterinaire();

        return $this->render('tableaux_bord/nbSuivis.html.twig', [
            'suivisParVeto' => $suivisParVeto,
        ]);
    }
}
