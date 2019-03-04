<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\VeterinaireType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Veterinaire;
/**
 * Class VeterinaireController
 * @package App\Controller
 * @Route("/veterinaire")
 */
class VeterinaireController extends AbstractController
{
    /**
     * @Route("", name="veterinaire_liste")
     * @return Response
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Veterinaire::class);

        $lesVeterinaires = $repository->findAll();

        return $this->render('veterinaire/index.html.twig', ['lesVetos' => $lesVeterinaires]);
    }

    /**
     * @Route("/{id}/show", name="veterinaire_show")
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Veterinaire::class);
        $veto = $repository->find($id);

        return $this->render('veterinaire/show.html.twig', ['veto' => $veto]);
    }

    /**
     * @Route("/new",name="veterinaire_new")
     * @return Response
     */
    public function new(Request $request)
    {
        $veto = new Veterinaire;

        $form = $this->createForm(VeterinaireType::class, $veto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($veto);

            $em->flush();

            $this->addFlash('info', 'Vétérinaire bien enregistré');

            return $this->redirectToRoute('veterinaire_show', ['id' => $veto->getId()]);

        }
        return $this->render('veterinaire/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{id}/edit", name="veterinaire_edit")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function edit($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Veterinaire::class);

        $veto = $repository->find($id);
        if (!$veto) {
            $this->addFlash('erreur', 'Vétérinaire non trouvé');
            return $this->redirectToRoute('veterinaire_liste');
        }
        $form = $this->createForm(VeterinaireType::class, $veto);
        $deleteForm = $this->createDeleteForm($veto);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('info', 'Le vétérinaire a bien été modifié');

            return $this->redirectToRoute('veterinaire_show', ['id' => $veto->getId()]);

        }
        return $this->render('veterinaire/edit.html.twig', [
            'form' => $form->createView(),
            'id' => $id,
            'delete_form' => $deleteForm->createView()
        ]);
    }

    /**
     *
     * @Route("/{id}/delete",methods={"DELETE"},name="veterinaire_delete")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $repository = $em->getRepository(Veterinaire::class);

        $veto = $repository->find($id);
        if (!$veto) {
            $this->addFlash('erreur', 'Vétérinaire inconnu');
        } else {
            $this->addFlash('alerte', 'Suppression vétérinaire n°' . $veto->getId() . ' effectuée');
            $em->remove($veto);
            $em->flush();
        }

        return $this->redirectToRoute('veterinaire_liste');
    }

    /**
     * @param Session $session
     * @param $id
     * @return bool
     */
    public function deleteVeterinaire(Session $session, $id)
    {
        $lesVetos = $this->getLesVeterinaires($session);

        $del = false;

        for ($i = 0; $i < count($lesVetos); $i++) {
            if ($lesVetos[$i]['id'] == $id) {
                unset($lesVetos[$i]);
                break;
            }
        }

        $newVetos = array_values($lesVetos);
        $del = true;

        $session->set('lesVetos', $newVetos);
        $this->lesVeterinaires = $newVetos;

        return $del;
    }

    /**
     * @param Veterinaire $veto
     * @return \Symfony\Component\Form\FormInterface
     */
    private function createDeleteForm(Veterinaire $veto)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl(
                'veterinaire_delete',
                ['id' => $veto->getId()]
            ))
            ->setMethod('DELETE')
            ->getForm();
    }

}
