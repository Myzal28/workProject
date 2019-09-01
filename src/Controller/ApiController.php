<?php

namespace App\Controller;

use App\Entity\Foods;
use App\Entity\Collect;
use App\Entity\Persons;
use App\Repository\FoodsRepository;
use App\Repository\StatusRepository;
use App\Repository\CollectRepository;
use App\Repository\PersonsRepository;
use App\Repository\CalendarRepository;
use App\Service\Curl;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/users/collect/add", name="add_articles")
     * @param PersonsRepository $personsRep
     * @param Request $request
     * @return Response
     */
    public function articlesAdd(PersonsRepository $personsRep, Request $request)
    {   
   
        $data = json_decode($request->getContent(), true);
        
        //$response->setStatusCode(200);
        /*if($person->getEmail() == null){
            $response->setStatusCode(200);
        }
        */
        return $this->render("tests/json.html.twig",[
            "table" => $data
        ]);
    }

    /**
     * @Route("api/user/connect", name="user_connect")
     * @param Request $request
     * @param PersonsRepository $personsRep
     * @param UserPasswordEncoderInterface $encoder
     * @return Response
     */
    public function apiConexion(Request $request, PersonsRepository $personsRep, UserPasswordEncoderInterface $encoder)
    {

        $response = new Response();

        $user = $personsRep->findOneBy(["email"=>$request->get("_username")]);

        if($user){
            $plainPassword = $request->get('_password');

            if($encoder->isPasswordValid($user, $plainPassword)){
                $response->setContent('true');
                $response->setStatusCode(200);
                return $response;
            }
        }
        $response->setContent('false');
        $response->setStatusCode(403);
        return $response;
    }

    /**
     * @Route("/api/client/collect/create", name="create_collect")
     * @param Request $request
     * @param PersonsRepository $personsRep
     * @param ObjectManager $manager
     * @param FoodsRepository $foodsRep
     * @param StatusRepository $statusRep
     * @return Response
     * @throws \Exception
     */
    public function collectCreate(Request $request, PersonsRepository $personsRep, ObjectManager $manager, FoodsRepository $foodsRep, StatusRepository $statusRep,\Swift_Mailer $mailer)
    {
        $response = new Response();

        // On récupère le json qui nous a été envoyé
        $data = json_decode($request->getContent(), true);

        $user = $personsRep->findOneBy(["email"=> $data["email"]]);
        if($user){
            $totalWeight = 0;
            foreach($data["articles"] as $article){
                $curl = new Curl();
                $foodFromApi = $curl->getFood($article['code']);
                $totalWeight = $foodFromApi['product_quantity'];

                $food = $foodsRep->findOneBy(["code"=>$article["code"]]);

                if(!($food)){
                
                    $food = new Foods();

                    $food->setName($article["product_name"])
                        ->setIngredients($article["ingredients_text"])
                        ->setCode($article["code"])
                        ->setQuantity($article["quantity"])
                        ->setBrands($article["brands"])
                        ->setImageUrl($article["image_url"])
                        ->setNumber(0);

                    $manager->persist($food);
                    $manager->flush();
                }
            }

            $collect = new Collect();
            $status = $statusRep->findOneBy(["id"=>4]);

            $collect->setPersonCreate($user)
                    ->setStatus($status)
                    ->setCommentary("Collecte en attente de confirmation.")
                    ->setDateRegister($date = new \Datetime)
                    ->setTotalWeight($totalWeight);
            $manager->persist($collect);
            $manager->flush();
            
            $fs = new Filesystem();
            try {
                $fs->dumpFile($this->getParameter('kernel.project_dir').'/src/collects/'.$collect->getId().'.json', $request->getContent());
            }catch(IOException $e) {
                $response->setContent($e);
                return $response;
            }

            // On va générer le pdf qui sera envoyé à l'utilisateur
            $pdfOptions = new Options();
            $pdfOptions->set('defaultFond','arial');
            $pdf = new Dompdf();

            $html = $this->renderView('pdf/collect.html.twig',[
                'name' => 'Maxime',
                'products' => $data['articles']
            ]);

            $pdf->loadHtml($html);
            $pdf->setPaper('A4','portrait');
            $pdf->render();

            $path = $this->getParameter('kernel.project_dir') . "/emailPdfs/pdfCollect.pdf";
            file_put_contents($path ,$pdf->output());


            $message = (new \Swift_Message('Votre ramassage du ' . date('d/m/Y')))
                ->setFrom('ffw.pmv@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'emails/addCollect.html.twig',
                        ['name' => $user->getFirstname()]
                    ),
                    'text/html'
                )
                ->attach(\Swift_Attachment::fromPath($path))
            ;
            $mailer->send($message);

            $response->setContent('true');
            $response->setStatusCode(200);
            return $response;
        }
        
        $response->setContent('false');
        $response->setStatusCode(403);
        return $response;
    }

    /**
     * @Route("/calendar/load", name="calendar_load")
     */
    public function calendarLoad(CalendarRepository $calendarRep){

        $response = new Response();

        $events = $calendarRep->findAll();
        $tab = array();
        $serializer = new Serializer(array(new ObjectNormalizer()));
        $eventsTab = $serializer->normalize($events, null, array('attributes' => array('id', 'dateStart', 'dateEnd', 'name')));
        
        foreach($eventsTab as $event){

            $eventObj = $calendarRep->find($event["id"]);
            $tab[]= array(
                "id" => $event["id"],
                "start" => $eventObj->getDateStart()->format('Y-m-d H:i:s'),
                "end" => $eventObj->getDateEnd()->format('Y-m-d H:i:s'),
                "title" => $event["name"]
            );
        }

        $response->setContent(json_encode($tab));
        $response->setStatusCode(200);
        return $response;
    }
    
    /**
     * @Route("/test")
     */
    use DataTablesTrait;
    public function showAction(Request $request)
    {
        

        $table = $this->createDataTable()
            ->add('firstName', TextColumn::class)
            ->add('lastName', TextColumn::class)
            ->createAdapter(ArrayAdapter::class, [
                ['firstName' => 'Donald', 'lastName' => 'Trump'],
                ['firstName' => 'Barack', 'lastName' => 'Obama'],
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }
        return $this->render('tests/list.html.twig', ['datatable' => $table]);
    }

    

    /**
     * @Route("/test/google", name="api")
     */
    public function apiGoogle()
    {
        return $this->render('tests/googleapi.html.twig');
    }

    /**
     * @Route("/test/calendar", name="calendar")
     */
    public function apiCalendar()
    {
        return $this->render('tests/calendar.html.twig');
    }


}
