<?php

namespace App\Controller;



use App\Entity\Persons;
use Psr\Log\LoggerInterface;
use App\Repository\StatusRepository;
use Symfony\Component\Finder\Finder;
use App\Repository\CollectRepository;
use App\Repository\ArticlesRepository;
use App\Repository\InventoryRepository;
use App\Repository\WarehousesRepository;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Argument\ServiceLocator;

class HomeController extends AbstractController
{

    
    /**
     * @Route("/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="home",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function index()
    {
        return $this->render('home/index.html.twig');
    }

    /**
     * @Route("/contact/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="contact",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contact(){
        return $this->render('home/contact.html.twig');
    }

    /**
     * @Route("/user/view/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="view_profile_user",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @Route("/admin/view/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="view_profile_admin",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @Route("/client/view/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="view_profile_client",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     * @Route("/internal/view/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="view_profile_internal",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function viewPerson(Persons $person){

        return $this->render('view/profile.html.twig', [
            'person' => $person
        ]);
    }

    /**
     * @Route("/email/send/{email}/{name}",name="email_send")
     */
    public function mail($name, $email, \Swift_Mailer $mailer)
    {
        $message = (new \Swift_Message('Welcome on board !'))
            ->setFrom('planitcalendar2018@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    // templates/emails/registration.html.twig
                    'emails/registration.html.twig',
                    ['name' => $name]
                ),
                'text/html'
            )
            /*
            * If you also want to include a plaintext version of the message
            ->addPart(
                $this->renderView(
                    'emails/registration.txt.twig',
                    ['name' => $name]
                ),
                'text/plain'
            )
            */
        ;

        $mailer->send($message);

        return $this->redirectToRoute('security_login');
    }

    /**
     * @Route("/client/jobs/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="client_jobs",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function clientsJobs(Persons $person, CollectRepository $collectsRep){

        $allCollects = $collectsRep->findBy(["personCreate"=>$person]);

        $serializer = new Serializer(array(new ObjectNormalizer()));
        $data = $serializer->normalize($allCollects, null, array('attributes' => 
          array('id','personCheck'=>array('email'),'personCreate'=>array('email'),'vehicle'=>array("id"),'status'=>array("id","status"),'commentary','dateRegister','dateCollect')));
        
        $i = 0;
        foreach($data as $collect){
            $finder = new Finder();
            $finder->in($this->getParameter('kernel.root_dir').'/collects');
            $finder->name($collect["id"].".json");
            
            foreach ($finder as $file) {
                $contents = $file->getContents();
                $collectA = json_decode($contents, true);
                // ...
            }
            $data[$i]["articles"]=$collectA["articles"];

            $i++;
        }
        
        return $this->render('home/clientsJobs.html.twig',[
            "collects"=>$data
        ]);
    }
    
    /**
     * @Route("/user/jobs/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="user_jobs",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function userJobs(Persons $person){
        return $this->render('home/usersJobs.html.twig');
    }

    /**
     * @Route("/internal/jobs/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="internal_jobs",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function internalJobs(Persons $person){
        return $this->render('home/internalJobs.html.twig');
    }

    /**
     * @Route("/client/share/jobs/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="share_jobs",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function shareJobs(Persons $person, ArticlesRepository $articlesRep){

        $articles = $articlesRep->findBy(["serviceType"=>6]);

        return $this->render('services/shareJobs.html.twig',[
            "articles" => $articles,
            "type" => 6
        ]);
    }

    /**
     * @Route("/client/waste/jobs/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="waste_jobs",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function wasteJobs(Persons $person, ArticlesRepository $articlesRep){

        $articles = $articlesRep->findBy(["serviceType"=>3]);

        return $this->render('services/wasteJobs.html.twig',[
            "articles" => $articles,
            "type" => 3
        ]);
    }
    
    /**
     * @Route("/client/course/jobs/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="course_jobs",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function courseJobs(Persons $person, ArticlesRepository $articlesRep){

        $articles = $articlesRep->findBy(["serviceType"=>6]);

        return $this->render('services/courseJobs.html.twig',[
            "articles" => $articles
        ]);
    }

    /**
     * @Route("/client/guard/jobs/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="guard_jobs",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function guardJobs(Persons $person, ArticlesRepository $articlesRep){

        $articles = $articlesRep->findBy(["serviceType"=>6]);

        return $this->render('services/courseJobs.html.twig',[
            "articles" => $articles
        ]);
    }

    /**
     * @Route("/user/driver/work/{id}/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="drivers_work",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function driversWork(Persons $person, CollectRepository $collectsRep){


        foreach($person->getVehicles() as $vehicule){
            $collects = $collectsRep->findBy(["vehicle"=>$vehicule]);
        }

        $serializer = new Serializer(array(new ObjectNormalizer()));
        $data = $serializer->normalize($collects, null, array('attributes' => array('id')));
        
        $i = 0;
        foreach($data as $collect){
            $finder = new Finder();
            $finder->in($this->getParameter('kernel.root_dir').'/collects');
            $finder->name($collect["id"].".json");
            
                foreach ($finder as $file) {
                    $contents = $file->getContents();
                    $collectA = json_decode($contents, true);
                    // ...
                }
            if(isset($collectA)){
                $data[$i]["articles"]=$collectA["articles"];
            }
            

            $i++;
        }
        
        return $this->render('services/driversWork.html.twig',[
            "collects" => $collects,
            "data"=> $data
        ]);
    }

    /**
     * @Route("/user/stock/work/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="stock_work",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function stockWork(WarehousesRepository $warehousesRep, CollectRepository $collectsRep, StatusRepository $statusRep){
        
        $from = new \DateTime(date("Y-m-d")." 00:00:00");
        $to = new \DateTime(date("Y-m-d")." 23:59:59");
        $status = $statusRep->findBy(["statusType"=>"AST"]);
        $statusEx = $statusRep->find(15);

        $qb = $collectsRep->createQueryBuilder("e");
        $qb->andWhere('e.dateCollect BETWEEN :from AND :to')
        ->andWhere('e.status != :statusEx')
        ->setParameter('from', $from )
        ->setParameter('statusEx', $statusEx )
        ->setParameter('to', $to);

        $collects = $qb->getQuery()->getResult();

        $warehouses = $warehousesRep->findAll();

        return $this->render('services/stockWork.html.twig',[
            "status" => $status,
            "collects" => $collects,
            "warehouses" => $warehouses
        ]);
    }

    /**
     * @Route("/admin/manage/inventory/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="manage_inventory",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function manageInventory(InventoryRepository $inventoriesRep){

        $inventories = $inventoriesRep->findAll();

        return $this->render('admin/manageInventory.html.twig',[
            "inventories" => $inventories
        ]);
    }

}
