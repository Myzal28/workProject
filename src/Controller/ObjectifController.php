<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Objectif;
use App\Form\ObjectifType;
use App\Form\ObjectifUpdateType;

/**
 * @Route("/objectifs")
 * Class ObjectifController
 * @package App\Controller
 */
class ObjectifController extends AbstractController
{
    /**
     * @Route("", name="objectif_liste")
     * @return Response
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Objectif::class);

        $objectifs = $repository->findAll();

        return $this->render('objectif/index.html.twig', ['objectifs' => $objectifs]);
    }

    /**
     * @Route("/new",name="objectif_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function new(Request $request)
    {
        $objectifs = new Objectif;

        $form = $this->createForm(ObjectifType::class, $objectifs);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($objectifs);

            $em->flush();

            $this->addFlash('info', 'Objectif bien enregistré');

            return $this->redirectToRoute('objectif_liste');

        }
        return $this->render('objectif/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/edit", name="objectif_edit")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function edit($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Objectif::class);

        $objectif = $repository->find($id);
        if (!$objectif) {
            $this->addFlash('erreur', 'Objectif non trouvé');
            return $this->redirectToRoute('objectif_liste');
        }
        $form = $this->createForm(ObjectifUpdateType::class, $objectif);
        $deleteForm = $this->createDeleteForm($objectif);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('info', "L'objectif a bien été modifié");

            return $this->redirectToRoute('objectif_liste');

        }
        return $this->render('objectif/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'delete_form' => $deleteForm->createView(),
            'objectif' => $objectif
        ]);
    }

    /**
     * @param Objectif $objectif
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Objectif $objectif)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl(
                'objectif_delete',
                ['id' => $objectif->getId()]
            ))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("/{id}/delete",methods={"DELETE"},name="objectif_delete")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Objectif::class);

        $objectif = $repository->find($id);
        $idVeto = $objectif->getVeterinaire()->getId();
        if (!$objectif) {
            $this->addFlash('erreur', 'Objectif non trouvé');
        } else {
            $this->addFlash('alerte', "Suppression de l'objectif n°" . $objectif->getId() . " effectuée");
            $em->remove($objectif);
            $em->flush();
        }

        return $this->redirectToRoute('objectif_show',['id' => $idVeto]);
    }
}
