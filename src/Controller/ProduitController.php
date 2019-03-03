<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;

/**
 * @Route("/produit")
 * Class ProduitController
 * @package App\Controller
 */
class ProduitController extends AbstractController
{
    /**
     * @Route("", name="produit_liste")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Produit::class);
        $produits = $repository->findAll();
        return $this->render('produit/index.html.twig', [
            'produits'=> $produits
        ]);
    }
}
