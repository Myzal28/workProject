<?php


namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\IndividualOffer;

use App\Service\QuickAlert;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ServicesController
 * @package App\Controller
 * @Route("/services/")
 *
 * Ce controller est dédié aux services mis à disposition des adhérents qui cotisent pour l'association
 */
class ServicesController extends AbstractController
{
    /**
     * @Route("waste/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="waste_advices",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @return Response
     */
    public function wasteAdvices(){
        return $this->render('services/wasteAdvices.html.twig');
    }

    /**
     * @Route("cooking/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="cooking_class",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @return Response
     */
    public function cookingClass(){
        return $this->render('services/cookingClass.html.twig');
    }

    /**
     * @Route("guarding/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="guarding",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function guarding(){
        return $this->render('services/guarding.html.twig');
    }

    /**
     * @Route("individual_offers/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="individual_offers",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function individualOffers(){
        $repo = $this->getDoctrine()->getRepository(IndividualOffer::class);

        // On récupère d'abord les annonces de l'utilisateur connecté
        $userOffers = $repo->findBy(['personCreate' => $this->getUser()->getId()]);

        // On récupère ensuite toutes les annonces
        $offers = $repo->findAll();

        return $this->render('services/individualOffers.html.twig',[
            'userOffers' => $userOffers,
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("api/individualOffer",
     *     defaults={"_locale"="fr"},
     *     name="individual_offers_api",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @param Request $request
     * @throws Exception
     */
    public function apiIndividualOffers(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        // Micro API REST permettant de gérer les annonces PàP
        switch ($request->getMethod()){
            // Création
            case 'POST':
                $person = $this->getUser();

                $offer = new IndividualOffer();

                $offer->setName($request->get('name'))
                    ->setPersonCreate($person)
                    ->setDescription($request->get('description'))
                    ->setDateCreate(new \Datetime)
                    ->setContact($request->get('contact'));

                $manager->persist($offer);
                $manager->flush();
                return $offer;
                break;
            // Modification
            case 'PUT':
                $repo = $this->getDoctrine()->getRepository(IndividualOffer::class);
                $offer = $repo->find($request->get('offer'));
                $manager->flush();
                return $offer;
                break;
            // Suppression
            case 'DELETE':
                $repo = $this->getDoctrine()->getRepository(IndividualOffer::class);
                $offer = $repo->find($request->get('offer'));
                $manager->remove($offer);
                $manager->flush();
                break;
            // Pas besoin de GET donc GET et le reste en default
            default:
                break;
        }

    }
}