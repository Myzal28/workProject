<?php 
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface; //

/**
 * @Route("/advert")
 */
class AdvertController extends AbstractController
{
	/**
	 * @Route(
	 * "/{page}", 
	 * name="oc_advert_index", 
	 * requirements={
	 * 	"page" = "\d+"
	 * },
	 * defaults={
	 * 	"page" = 1
	 * }
	 * )
	 */
	public function index($page)
	{
		if ($page < 1) {
			throw $this->createNotFoundException('Page '.$page.' inexistante');
		}
		return $this->render('Advert/index.html.twig');
	}

	/**
	 * @Route("/view/{id}", name="oc_advert_view", requirements={
	 * 	"id" = "\d+",
	 * })
	 */
	public function view($id = 1)
	{
		return $this->render(
			'Advert/view.html.twig',
			['id' => $id]
		);
	}

	/**
	 * @Route("/add",name="oc_advert_add")
	 */
	public function add(Request $request)
	{
		if ($request->isMethod('POST')) 
		{
			// Ici on gèrera le formulaire
			$this->addFlash('info','Annonce bien enregistrée');
		}
		return $this->render('Advert/add.html.twig');
	}

	/**
	 * @Route(
	 * 	"/edit/{id}",
	 * 	name="oc_advert_edit",
	 * 	requirements={
	 *    "id" = "\d+"
	 * 	}
	 * )
	 */
	public function edit($id,Request $request)
	{
		if($request->isMethod('POST'))
		{
			$this->addFlash('notice','Annonce bien modifiée');

			return $this->redirectToRoute('oc_advert_view',['id' => 5]);
		}
		return $this->render('Advert/edit.html.twig');
	}

	/**
	 * @Route(
	 * 	"/delete/{id}",
	 * 	name="oc_advert_delete",
	 * 	requirements={
	 *    "id" = "\d+"
	 * 	})
	 */
	public function delete($id)
	{
		return $this->render('Advert/delete.html.twig');
	}
}