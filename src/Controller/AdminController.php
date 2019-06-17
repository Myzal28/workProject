<?php

namespace App\Controller;

use App\Repository\SignupRepository;
use App\Repository\StatusRepository;
use App\Repository\CollectRepository;
use App\Repository\PersonsRepository;
use App\Repository\CalendarRepository;
use App\Repository\ServicesRepository;
use App\Repository\VehiclesRepository;
use App\Repository\WarehousesRepository;
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

    /**
     * @Route("/admin/manage/collects/all/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="manage_collects",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function manageCollects(CollectRepository $collectRep){

        $collects = $collectRep->findall();

        return $this->render('admin/manageCollects.html.twig',[
            "collects" => $collects
        ]);
    }

    /**
     * @Route("/admin/manage/collects/no_check/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="manage_no_check",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function manageNoCheck(CollectRepository $collectRep, StatusRepository $statusRep, VehiclesRepository $vehiculesRep){

        $collects = $collectRep->findBy(["status" => 4]);
        $status = $statusRep->findBy(["statusType" => "CLL"]);
        $vehicules = $vehiculesRep->findAll();
        $datetime = new \Datetime;

        return $this->render('admin/manageNocheck.html.twig',[
            "collects" => $collects,
            "status" => $status,
            "vehicules" => $vehicules,
            "datetime" => $datetime
        ]);
    }

    /**
     * @Route("/admin/manage/vehicules/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="manage_vehicules",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function manageVehicules(VehiclesRepository $vehiculesRep, StatusRepository $statusRep){

        $vehicules = $vehiculesRep->findAll();
        $status = $statusRep->findBy(["statusType" => "VHE"]);

        return $this->render('admin/manageVehicules.html.twig',[
            "vehicules" => $vehicules,
            "status" => $status
        ]);
    }

    /**
     * @Route("/admin/manage/warehouses/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="manage_warehouses",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function manageWarehouses(WarehousesRepository $warehousesRep){

        $warehouses = $warehousesRep->findAll();
        

        return $this->render('admin/manageWarehouses.html.twig',[
            "warehouses" => $warehouses
        ]);
    }

    /**
     * @Route("/admin/manage/driver/planning/week/{num}/{_locale}",
     *     defaults={"num"= "present","_locale"="fr"},
     *     name="manage_pla_driver",
     * requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function managePlaDriver($num, PersonsRepository $personsRep, CalendarRepository $calendarsRep){

        $persons = $personsRep->findBy(["service"=>1]);

        if($num == "present"){
            $week = date("W");
            $day = date('w');
            $monday = strtotime('-'.$day.' days') + 86400;

            $date = [
                0=>date("Y",$monday),
                1=>date("m",$monday),
                2=>date("d",$monday)
            ];

        }else{            
            $date = explode("_",$num);
            $monday = strtotime($date[0]."-".$date[1]."-".$date[2]);
            $week = date("W", $monday);
        }

        $daysWeek = [
                'Mon' => date('d/m', $monday),
                'Tue' => date('d/m', $monday + 86400),
                'Wed' => date('d/m', $monday + 86400 * 2),
                'Thu' => date('d/m', $monday + 86400 * 3),
                'Fri' => date('d/m', $monday + 86400 * 4),
                'Sat' => date('d/m', $monday + 86400 * 5),
                'Sun' => date('d/m', $monday + 86400 * 6)
            ];
        
        $events = $calendarsRep->findBy(["week"=>$week, "service"=>1]);
        
        $weeks =[
            "past"=>date("Y_m_d", $monday - 86400*7),
            "present"=>$week,
            "next"=>date("Y_m_d", $monday + 86400*7)
        ];
        $times = [
            1=>"9h00 - 9h30",
            2=>"9h30 - 10h00",
            3=>"10h00 - 10h30",
            4=>"10h30 - 11h00",
            5=>"11h00 - 11h30",
            6=>"11h30 - 12h00",
            7=>"12h00 - 12h30",
            8=>"12h30 - 13h00",
            9=>"13h00 - 13h30",
            10=>"13h30 - 14h00",
            11=>"14h00 - 14h30",
            12=>"14h30 - 15h00",
            13=>"15h00 - 15h30",
            14=>"15h30 - 16h00",
            15=>"16h00 - 16h30",
            16=>"16h30 - 17h00"
        ];

        return $this->render('admin/managePlaDriver.html.twig',[
            "persons" => $persons,
            "times" => $times,
            "events" =>$events,
            "weeks" => $weeks,
            "daysWeek" => $daysWeek
        ]);
    }

    /**
     * @Route("/admin/manage/Harrange/planning/week/{num}/{_locale}",
    *     defaults={"num"= "present","_locale"="fr"},
     *     name="manage_pla_harrange",
     * requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function managePlaHarrange($num, PersonsRepository $personsRep, CalendarRepository $calendarsRep){

        $persons = $personsRep->findBy(["service"=>2]);
        
        if($num == "present"){
            $week = date("W");
            $day = date('w');
            $monday = strtotime('-'.$day.' days') + 86400;

            $date = [
                0=>date("Y",$monday),
                1=>date("m",$monday),
                2=>date("d",$monday)
            ];

        }else{            
            $date = explode("_",$num);
            $monday = strtotime($date[0]."-".$date[1]."-".$date[2]);
            $week = date("W", $monday);
        }

        $daysWeek = [
                'Mon' => date('d/m', $monday),
                'Tue' => date('d/m', $monday + 86400),
                'Wed' => date('d/m', $monday + 86400 * 2),
                'Thu' => date('d/m', $monday + 86400 * 3),
                'Fri' => date('d/m', $monday + 86400 * 4),
                'Sat' => date('d/m', $monday + 86400 * 5),
                'Sun' => date('d/m', $monday + 86400 * 6)
            ];
        
        $events = $calendarsRep->findBy(["week"=>$week, "service"=>1]);
        
        $weeks =[
            "past"=>date("Y_m_d", $monday - 86400*7),
            "present"=>$week,
            "next"=>date("Y_m_d", $monday + 86400*7)
        ];
        $times = [
            1=>"9h00 - 9h30",
            2=>"9h30 - 10h00",
            3=>"10h00 - 10h30",
            4=>"10h30 - 11h00",
            5=>"11h00 - 11h30",
            6=>"11h30 - 12h00",
            7=>"12h00 - 12h30",
            8=>"12h30 - 13h00",
            9=>"13h00 - 13h30",
            10=>"13h30 - 14h00",
            11=>"14h00 - 14h30",
            12=>"14h30 - 15h00",
            13=>"15h00 - 15h30",
            14=>"15h30 - 16h00",
            15=>"16h00 - 16h30",
            16=>"16h30 - 17h00"
        ];

        return $this->render('admin/managePlaHarrange.html.twig',[
            "persons" => $persons,
            "times" => $times,
            "events" =>$events,
            "weeks" => $weeks,
            "daysWeek" => $daysWeek
        ]);
    }

    /**
     * @Route("/admin/manage/waste/planning/week/{num}/{_locale}",
     *     defaults={"num"= "present","_locale"="fr"},
     *     name="manage_pla_waste",
     * requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function managePlaWaste($num, PersonsRepository $personsRep, CalendarRepository $calendarsRep){

        $persons = $personsRep->findBy(["service"=>3]);
        
        if($num == "present"){
            $week = date("W");
            $day = date('w');
            $monday = strtotime('-'.$day.' days') + 86400;

            $date = [
                0=>date("Y",$monday),
                1=>date("m",$monday),
                2=>date("d",$monday)
            ];

        }else{            
            $date = explode("_",$num);
            $monday = strtotime($date[0]."-".$date[1]."-".$date[2]);
            $week = date("W", $monday);
        }

        $daysWeek = [
                'Mon' => date('d/m', $monday),
                'Tue' => date('d/m', $monday + 86400),
                'Wed' => date('d/m', $monday + 86400 * 2),
                'Thu' => date('d/m', $monday + 86400 * 3),
                'Fri' => date('d/m', $monday + 86400 * 4),
                'Sat' => date('d/m', $monday + 86400 * 5),
                'Sun' => date('d/m', $monday + 86400 * 6)
            ];
        
        $events = $calendarsRep->findBy(["week"=>$week, "service"=>1]);
        
        $weeks =[
            "past"=>date("Y_m_d", $monday - 86400*7),
            "present"=>$week,
            "next"=>date("Y_m_d", $monday + 86400*7)
        ];
        $times = [
            1=>"9h00 - 9h30",
            2=>"9h30 - 10h00",
            3=>"10h00 - 10h30",
            4=>"10h30 - 11h00",
            5=>"11h00 - 11h30",
            6=>"11h30 - 12h00",
            7=>"12h00 - 12h30",
            8=>"12h30 - 13h00",
            9=>"13h00 - 13h30",
            10=>"13h30 - 14h00",
            11=>"14h00 - 14h30",
            12=>"14h30 - 15h00",
            13=>"15h00 - 15h30",
            14=>"15h30 - 16h00",
            15=>"16h00 - 16h30",
            16=>"16h30 - 17h00"
        ];

        return $this->render('admin/managePlaWaste.html.twig',[
            "persons" => $persons,
            "times" => $times,
            "events" =>$events,
            "weeks" => $weeks,
            "daysWeek" => $daysWeek
        ]);
    }

    /**
     * @Route("/admin/manage/course/planning/week/{num}/{_locale}",
     *     defaults={"num"= "present","_locale"="fr"},
     *     name="manage_pla_course",
     * requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function managePlaCourse($num, PersonsRepository $personsRep, CalendarRepository $calendarsRep){

        $persons = $personsRep->findBy(["service"=>4]);
        
        if($num == "present"){
            $week = date("W");
            $day = date('w');
            $monday = strtotime('-'.$day.' days') + 86400;

            $date = [
                0=>date("Y",$monday),
                1=>date("m",$monday),
                2=>date("d",$monday)
            ];

        }else{            
            $date = explode("_",$num);
            $monday = strtotime($date[0]."-".$date[1]."-".$date[2]);
            $week = date("W", $monday);
        }

        $daysWeek = [
                'Mon' => date('d/m', $monday),
                'Tue' => date('d/m', $monday + 86400),
                'Wed' => date('d/m', $monday + 86400 * 2),
                'Thu' => date('d/m', $monday + 86400 * 3),
                'Fri' => date('d/m', $monday + 86400 * 4),
                'Sat' => date('d/m', $monday + 86400 * 5),
                'Sun' => date('d/m', $monday + 86400 * 6)
            ];
        
        $events = $calendarsRep->findBy(["week"=>$week, "service"=>1]);
        
        $weeks =[
            "past"=>date("Y_m_d", $monday - 86400*7),
            "present"=>$week,
            "next"=>date("Y_m_d", $monday + 86400*7)
        ];
        $times = [
            1=>"9h00 - 9h30",
            2=>"9h30 - 10h00",
            3=>"10h00 - 10h30",
            4=>"10h30 - 11h00",
            5=>"11h00 - 11h30",
            6=>"11h30 - 12h00",
            7=>"12h00 - 12h30",
            8=>"12h30 - 13h00",
            9=>"13h00 - 13h30",
            10=>"13h30 - 14h00",
            11=>"14h00 - 14h30",
            12=>"14h30 - 15h00",
            13=>"15h00 - 15h30",
            14=>"15h30 - 16h00",
            15=>"16h00 - 16h30",
            16=>"16h30 - 17h00"
        ];

        return $this->render('admin/managePlaCourse.html.twig',[
            "persons" => $persons,
            "times" => $times,
            "events" =>$events,
            "weeks" => $weeks,
            "daysWeek" => $daysWeek
        ]);
    }

    /**
     * @Route("/admin/manage/guard/planning/week/{num}/{_locale}",
     *     defaults={"num"= "present","_locale"="fr"},
     *     name="manage_pla_guard",
     * requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function managePlaGuard($num, PersonsRepository $personsRep, CalendarRepository $calendarsRep){

        $persons = $personsRep->findBy(["service"=>5]);
        
        if($num == "present"){
            $week = date("W");
            $day = date('w');
            $monday = strtotime('-'.$day.' days') + 86400;

            $date = [
                0=>date("Y",$monday),
                1=>date("m",$monday),
                2=>date("d",$monday)
            ];

        }else{            
            $date = explode("_",$num);
            $monday = strtotime($date[0]."-".$date[1]."-".$date[2]);
            $week = date("W", $monday);
        }

        $daysWeek = [
                'Mon' => date('d/m', $monday),
                'Tue' => date('d/m', $monday + 86400),
                'Wed' => date('d/m', $monday + 86400 * 2),
                'Thu' => date('d/m', $monday + 86400 * 3),
                'Fri' => date('d/m', $monday + 86400 * 4),
                'Sat' => date('d/m', $monday + 86400 * 5),
                'Sun' => date('d/m', $monday + 86400 * 6)
            ];
        
        $events = $calendarsRep->findBy(["week"=>$week, "service"=>1]);
        
        $weeks =[
            "past"=>date("Y_m_d", $monday - 86400*7),
            "present"=>$week,
            "next"=>date("Y_m_d", $monday + 86400*7)
        ];
        $times = [
            1=>"9h00 - 9h30",
            2=>"9h30 - 10h00",
            3=>"10h00 - 10h30",
            4=>"10h30 - 11h00",
            5=>"11h00 - 11h30",
            6=>"11h30 - 12h00",
            7=>"12h00 - 12h30",
            8=>"12h30 - 13h00",
            9=>"13h00 - 13h30",
            10=>"13h30 - 14h00",
            11=>"14h00 - 14h30",
            12=>"14h30 - 15h00",
            13=>"15h00 - 15h30",
            14=>"15h30 - 16h00",
            15=>"16h00 - 16h30",
            16=>"16h30 - 17h00"
        ];

        return $this->render('admin/managePlaGuard.html.twig',[
            "persons" => $persons,
            "times" => $times,
            "events" =>$events,
            "weeks" => $weeks,
            "daysWeek" => $daysWeek
        ]);
    }
}
