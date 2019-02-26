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
		$listAdverts = array(
		  array(
		    'title'   => 'Recherche développpeur Symfony',
		    'id'      => 1,
		    'author'  => 'Alexandre',
		    'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
		    'date'    => new \Datetime()),
		  array(
		    'title'   => 'Mission de webmaster',
		    'id'      => 2,
		    'author'  => 'Hugo',
		    'content' => 'Nous recherchons un webmaster capable de maintenir notre site internet. Blabla…',
		    'date'    => new \Datetime()),
		  array(
		    'title'   => 'Offre de stage webdesigner',
		    'id'      => 3,
		    'author'  => 'Mathieu',
		    'content' => 'Nous proposons un poste pour webdesigner. Blabla…',
		    'date'    => new \Datetime())
		);
		return $this->render(
			'Advert/index.html.twig', 
			['listAdverts' => $listAdverts]
		);
	}

	/**
	 * @Route("/view/{id}", name="oc_advert_view", requirements={
	 * 	"id" = "\d+",
	 * })
	 */
	public function view($id = 1)
	{
		$advert = array(
		  'title'   => 'Recherche développpeur Symfony2',
		  'id'      => $id,
		  'author'  => 'Alexandre',
		  'content' => 'Nous recherchons un développeur Symfony2 débutant sur Lyon. Blabla…',
		  'date'    => new \Datetime()
		);
		return $this->render(
			'Advert/view.html.twig',
			['id' => $id,'advert' => $advert]
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
		$advert = array(
		  'title'   => 'Recherche développpeur Symfony',
		  'id'      => $id,
		  'author'  => 'Alexandre',
		  'content' => 'Nous recherchons un développeur Symfony débutant sur Lyon. Blabla…',
		  'date'    => new \Datetime()
		);
		return $this->render(
			'Advert/edit.html.twig',
			['advert' => $advert]
		);
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

	public function menu()
	{
		// On fixe en dur une liste ici, bien entendu par la suite
		// on la récupérera depuis la BDD !
		$listAdverts = array(
		  array('id' => 2, 'title' => 'Recherche développeur Symfony'),
		  array('id' => 5, 'title' => 'Mission de webmaster'),
		  array('id' => 9, 'title' => 'Offre de stage webdesigner')
		);

		return $this->render('menu.html.twig', array(
		  // Tout l'intérêt est ici : le contrôleur passe
		  // les variables nécessaires au template !
		  'listAdverts' => $listAdverts
		));
	}	
}