<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

// Entités
use App\Entity\Advert;
use App\Entity\Image;
use App\Entity\Application;
use App\Entity\Category;
use App\Entity\AdvertSkill;
use App\Entity\Skill;
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
     * }
     * )
     */
    public function index($page = 1)
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
        // Initialisation de l'objet doctrine
        $doctrine = $this->getDoctrine()->getManager();

        // On récupère l'annonce a l'id $id
        $advert = $doctrine->getRepository('App:Advert')->find($id);

        // Si l'annonce n'est pas trouvée on lance une erreur 404
        if ($advert === NULL) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas");
        }

        // On récupère les candidatures
        $listApplications = $doctrine
            ->getRepository('App:Application')
            ->findBy(['advert' => $advert])
        ;

        // On récupère les skills nécéssaires
        $listAdvertSkills = $doctrine
            ->getRepository('App:AdvertSkill')
            ->findBy(['advert' => $advert])
        ;
        return $this->render(
            'Advert/view.html.twig',
            [
                'advert' => $advert,
                'listApplications' => $listApplications,
                'listAdvertSkills' => $listAdvertSkills,
            ]
        );
    }

    /**
     * @Route("/add",name="oc_advert_add")
     */
    public function add(Request $request)
    {
        // On crée l'objet doctrine permettant de gérer la BDD
        $doctrine = $this->getDoctrine()->getManager();

        // On crée l'annnonce de base
        $advert = new Advert;
        $advert->setTitle("Recherche CDD Caviste");
        $advert->setAuthor("Benoît");
        $advert->setContent("Nous recherchons un caviste pour 6 mois pour le magasin de Paris Espace");

        // On ajoute les skills à l'annonce
        $listSkills = $doctrine->getRepository('App:Skill')->findAll();
        foreach ($listSkills as $skill) {
            $advertSkill = new AdvertSkill();
            $advertSkill->setAdvert($advert);
            $advertSkill->setSkill($skill);
            $advertSkill->setLevel(rand(0,5));

            $doctrine->persist($advertSkill);
        }

        // On ajoute l'image à l'annonce
        $image = new Image();
        $image->setUrl('http://www.alexis-berger.com/wp-content/uploads/2017/07/Pourquoi-vous-devez-prendre-un-job-dappoint.jpg');
        $image->setAlt('Find job');

        $advert->setImage($image);

        // On ajoute des candidatures à l'annonce
        $application1 = new Application();
        $application1->setAuthor('Marine');
        $application1->setContent("J'ai toutes les qualités requises");

        $application2 = new Application();
        $application2->setAuthor('Pierre');
        $application2->setContent("Je suis très motivé");

        $application1->setAdvert($advert);
        $application2->setAdvert($advert);

        $doctrine->persist($advert);
        $doctrine->persist($application1);
        $doctrine->persist($application2);

        // On valide tous les ajouts et on éxecute les requêtes
        $doctrine->flush();

        if ($request->isMethod('POST'))
        {
            // Ici on gèrera le formulaire
            $this->addFlash('info','Annonce bien enregistrée');
            return $this->redirectToRoute('oc_advert_view',['id' => $advert->getId()]);
        }

        // On crée et renvoie la vue
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
        $doctrine = $this->getDoctrine()->getManager();

        $advert = $doctrine->getRepository('App:Advert')->find($id);

        if($advert === NULL)
        {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas");
        }

        $listCategories = $doctrine->getRepository('App:Category')->findAll();

        foreach ($listCategories as $category) {
            $advert->addCategory($category);
        }

        $doctrine->flush();

        if($request->isMethod('POST'))
        {
            $this->addFlash('notice','Annonce bien modifiée');

            return $this->redirectToRoute('oc_advert_view',['id' => 5]);
        }

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
        $doctrine = $this->getDoctrine()->getManager();

        $advert = $doctrine->getRepository('App:Advert')->find($id);
        if ($advert === NULL) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas");
        }

        foreach ($advert->getCategories() as $category) {
            $advert->removeCategory($category);
        }

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