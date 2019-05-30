<?php

namespace App\Controller;

use App\Repository\SignupRepository;
use App\Repository\StatusRepository;
use App\Repository\CollectRepository;
use App\Repository\PersonsRepository;
use App\Repository\ServicesRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin/manage/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="admin_manage",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function manage(SignupRepository $signup, CollectRepository $collect){

        $signups = $signup->count(["status"=>1]);
        $collects = $collect->count(["status"=>1]);

        return $this->render('admin/manage.html.twig',[
            "collects" => $collects,
            "signups" => $signups
        ]);
    }

    /**
     * @Route("/admin/manage/staff/signups/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="manage_signups",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function manageSignups(SignupRepository $signupRep, StatusRepository $statusRep, ServicesRepository $serviceRep){
        $signups = $signupRep->findBy(["status"=>1]);
        $status = $statusRep->findBy(["statusType"=>'SUP']);
        $services = $serviceRep->findAll();

        return $this->render('admin/manageSignups.html.twig', [
            "signups" => $signups,
            "status" => $status,
            "services" => $services
        ]);
    }

    /**
     * @Route("/admin/manage/services/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="manage_services",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function manageServices(ServicesRepository $serviceRep){

        $services = $serviceRep->findAll();

        return $this->render('admin/manageServices.html.twig',[
            "services" => $services
        ]);
    }

    /**
     * @Route("/admin/manage/staff/all/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="manage_staff",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function manageStaff(PersonsRepository $personRep){

        $persons = $personRep->findAll();

        return $this->render('admin/manageAllStaff.html.twig',[
            "persons" => $persons
        ]);
    }

    /**
     * @Route("/admin/manage/staff/no_services/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="manage_no_services",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function manageNoServices(PersonsRepository $personRep, ServicesRepository $serviceRep){

        $persons = $personRep->findBy(["service"=> null ]);
        $services = $serviceRep->findAll();

        return $this->render('admin/manageNoServices.html.twig',[
            "persons" => $persons,
            "services" => $services
        ]);
    }
}
