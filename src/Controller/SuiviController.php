<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Suivi;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SuiviType;
/**
 * @Route("/suivi")
 * Class SuiviController
 * @package App\Controller
 */
class SuiviController extends AbstractController
{
    /**
     * @Route("", methods={"GET"}, name="suivi_liste")
     */
    public function index()
    {
        // Récupère l'Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Récupère le repository Suivi
        $repository = $em->getRepository(Suivi::class);

        // Récupère la liste de tous les objets Suivi
        $lesSuivis = $repository->findAll();

        // On appelle la vue en lui fournissant la liste des vétérinaires  -
        return $this->render('suivi/index.html.twig',
            ['lesSuivis' => $lesSuivis]
        );
    }

    /**
     * @Route("/{id}/show", methods={"GET"}, name="suivi_show")
     */
    public function show(int $id)
    {
        // Récupère l'Entity Manager
        $em = $this->getDoctrine()->getManager();

        // Récupère le repository Suivi
        $repository = $em->getRepository(Suivi::class);

        // Récupère l'objet suivi ayant pour identifiant $id
        $suivi = $repository->find($id);

        return $this->render('suivi/show.html.twig',
            ['suivi' => $suivi]
        );
    }

    /**
     * @Route("/new", methods={"GET", "POST"}, name="suivi_new")
     */
    public function new(Request $request)
    {
        // On instancie un objet vétérinaire
        $suivi = new Suivi();

        // Création du formulaire (vierge)
        $form = $this->createForm(SuiviType::class, $suivi);

        // On hydrate l’objet $form si des données sont postées
        $form->handleRequest($request);

        // On teste si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {

            // Récupère l'Entity Manager
            $em = $this->getDoctrine()->getManager();

            // Persiste le nouvel objet Suivi (on confie sa gestion à Doctrine)
            $em->persist($suivi);

            // Met à jour la BDD
            $em->flush();

            $this->addFlash('info', 'Suivi bien enregistré');

            // Redirection vers la vue détail
            return $this->redirectToRoute('suivi_show', ['id' => $suivi->getId()]);
        }

        // Le contrôleur demande à la vue de s'afficher et lui transmet le formulaire
        return $this->render('suivi/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/{id}/edit", methods={"GET", "POST"}, name="suivi_edit")
     */
    public function edit(Request $request, int $id)
    {
        // Récupère l'entity manager
        $em = $this->getDoctrine()->getManager();

        // Récupère le repository Suivi
        $repository = $em->getRepository(Suivi::class);

        // Récupère le vétérinaire ayant pour identifiant $id
        $suivi = $repository->find($id);

        if (!$suivi) {
            $this->addFlash('erreur', 'Suivi inexistant !');
            return $this->redirectToRoute('suivi_liste');
        }

        // Création des formulaires
        $editform = $this->createForm(SuiviType::class, $suivi);
        $deleteform = $this->createDeleteForm($suivi);

        // On hydrate l’objet $editform si des données sont postées
        $editform->handleRequest($request);

        // On teste si le formulaire a été soumis et si les données sont valides
        if ($editform->isSubmitted() && $editform->isValid()) {

            $em->flush(); // Met à jour la BDD

            $this->addFlash('info', 'Suivi mis à jour');

            // Redirection vers la vue modification
            return $this->redirectToRoute('suivi_show', ['id' => $suivi->getId()]);
        }

        // Le contrôleur demande à la vue de s'afficher et lui transmet le formulaire
        return $this->render('suivi/edit.html.twig', [
            'edit_form' => $editform->createView(),
            'delete_form' => $deleteform->createView(),
            'suivi' => $suivi
        ]);
    }

    /**
     * @Route("/{id}/delete", methods={"DELETE"}, name="suivi_delete")
     */
    public function delete(int $id)
    {
        // Récupère l'entity manager
        $em = $this->getDoctrine()->getManager();

        // Récupère le suivi ayant pour identifiant $id
        $suivi = $em->getRepository(Suivi::class)->find($id);

        // Affichage d'un message flash
        if (!$suivi) {
            $this->addFlash('erreur', 'Suivi inconnu !');
        } else {
            $this->addFlash('alerte', 'Suppression Suivi n° ' . $id  . ' effectuée');

            // Supprime le suivi
            $em->remove($suivi);

            // Met à jour la BDD
            $em->flush();
        }

        // Redirection vers la vue détail
        return $this->redirectToRoute('suivi_liste');
    }


    /**
     * @param Suivi $suivi
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Suivi $suivi)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('suivi_delete', ['id' => $suivi->getId()]))
            ->setMethod('DELETE')
            ->getForm();
    }
}
