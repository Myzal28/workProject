<?php

namespace App\Controller;

use App\Entity\Signup;
use App\Entity\Collect;
use App\Entity\Persons;
use App\Entity\Articles;
use App\Entity\Calendar;
use App\Entity\Services;
use App\Entity\Vehicles;
use App\Repository\SignupRepository;
use App\Repository\StatusRepository;
use App\Repository\PersonsRepository;
use App\Repository\ServicesRepository;
use App\Repository\VehiclesRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostController extends AbstractController
{
    /**
     * @Route("/post", name="post")
     */
    public function personsSignup(SignupRepository $signup)
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers, $encoders);

        $signups = $signup->findBy(["status" => 1]);
        $content = $serializer->normalize($signups, null, ['attributes' => ['person' => ['id','firstname', 'lastname', 'email','date_register']]]);
        //$content = array_values($content);
        //$jsonContent = $serializer->normalize($content, 'json');

        return $this->json(["data"=>$content], 200);
    }

    
    /**
     * @Route("/post/client/article/modify/{id}", name="article_mod")
     */
    public function articleMod(Articles $article, Request $request, ObjectManager $manager)
    {
       $person = $article->getPersonCreate();
       $article->setName($request->get('name'))
               ->setDescription($request->get("description"))
               ->setContent($request->get("content"));
        $manager->persist($article);
        $manager->flush();

        return $this->redirectToRoute('share_jobs', ['id' => $person->getId()]);
    }

    /**
     * @Route("/post/client/article/new/{id}", name="article_new")
     */
    public function articleNew(Services $service, Request $request, ObjectManager $manager, ServicesRepository $servicesRep)
    {
       
       $person = $this->getUser();

       $article = new Articles;

       $article->setName($request->get('name'))
               ->setDescription($request->get("description"))
               ->setContent($request->get("content"))
               ->setPersonCreate($person)
               ->setServiceType($service)
               ->setDateCreate(new \Datetime);
        $manager->persist($article);
        $manager->flush();
        if($service->getId()== 6){
            return $this->redirectToRoute('share_jobs', ['id' => $person->getId()]);
        }
        return $this->redirectToRoute('waste_jobs', ['id' => $person->getId()]);
    }

    /**
     * @Route("/post/user/signup/{id}", name="user_signup")
     */
    public function userSignup(Persons $person, Request $request, ObjectManager $manager, StatusRepository $statusRep, ServicesRepository $servicesRep)
    {
        $status = $statusRep->findOneBy(["status"=>$request->get('status'),"statusType"=>"SUP"]);
        if($status->getId() == 1){
            
        }elseif($status->getId() == 3){
            $signup = $person->getSignup();
            $signup->setStatus($status);
            $manager->persist($singup);
            $manager->flush();
        }else{
            $signup = $person->getSignup();
            $signup->setStatus($status);
            

            $service = $servicesRep->findOneBy(["name"=>$request->get('service')]);
            $person->setService($service);

            $manager->persist($signup);
            $manager->persist($person);
            $manager->flush();
        }
       
       

        return $this->redirectToRoute('manage_signups');
    }

    /**
     * @Route("/post/user/event/new/{id}", name="event_new")
     */
    public function eventNew(Persons $person, Request $request, ObjectManager $manager)
    {
       $event = new Calendar;
       
       $dayWeek = date("D",strtotime($request->get('date'))).$request->get('date_range');

       $event->setName($request->get('name'))
               ->setDateStart(new \Datetime($request->get('date')))
               ->setDateEnd(new \Datetime($request->get('date')))
               ->setYear(date("Y",strtotime($request->get('date'))))
               ->setWeek(date("W",strtotime($request->get('date'))))
               ->setservice($person->getService())
               ->addPerson($person)
               ->setDayWeek($dayWeek);

        $manager->persist($event);
        $manager->flush();

        return $this->redirectToRoute('manage_pla_driver');
    }

    /**
     * @Route("/post/vehicule/add/user/{id}", name="vehicule_user")
     */
    public function vehiculeUser(Vehicles $vehicule, Request $request, ObjectManager $manager, PersonsRepository $personsRep, StatusRepository $statusRep)
    {
        if($request->get('user') !== "none"){
            $vehicule->setPerson($personsRep->findOneBy(["id"=>$request->get('user')]))
                ->setStatus($statusRep->findOneBy(["id"=>$request->get('status')]));
        }else{
            $vehicule->setPerson(null)
                ->setStatus($statusRep->findOneBy(["id"=>$request->get('status')]));
        }
       

        $manager->persist($vehicule);
        $manager->flush();

        return $this->redirectToRoute('manage_vehicules');
    }

    /**
     * @Route("/post/collect/mod/{collect}/{person}", name="collect_mod")
     */
    public function collectMod(Collect $collect, Persons $person, Request $request, ObjectManager $manager, PersonsRepository $personsRep, StatusRepository $statusRep, VehiclesRepository $vehiculesRep)
    {

        $vehicules = $personsRep->find($request->get("user"))->getVehicles();
        $serializer = new Serializer(array(new ObjectNormalizer()));
        $vehicules = $serializer->normalize($vehicules, null, array('attributes' => array('id')));

        foreach($vehicules as $vehicule ){
            $pvehicule = $vehiculesRep->find($vehicule["id"]);
        }

        $collect->setPersonCheck($person)
               ->setVehicle($pvehicule)
               ->setStatus($statusRep->find($request->get("status")))
               ->setCommentary($request->get("comment"))
               ->setDateCollect(new \Datetime($request->get('date')));
       

        $manager->persist($collect);
        $manager->flush();

        return $this->redirectToRoute('manage_no_check');
    }
}
