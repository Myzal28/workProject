<?php

namespace App\Controller;

use App\Entity\Foods;
use App\Entity\Collect;
use App\Entity\Persons;
use App\Repository\FoodsRepository;
use App\Repository\StatusRepository;
use App\Repository\CollectRepository;
use App\Repository\PersonsRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Symfony\Component\HttpFoundation\ParameterBag;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiController extends Controller
{
    /**
     * @Route("/api/users/collect/add", name="add_articles")
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
     */
    public function apiConexion(Request $request, PersonsRepository $personsRep, UserPasswordEncoderInterface $encoder)
    {

        $response = new Response();

        $user = $personsRep->findOneBy(["email"=>$request->get("_username")]);

        if($user){
            $plainPassword = $request->get('_password');

            if($encoder->isPasswordValid($user, $plainPassword)){
                $response->setStatusCode(200);
                return $response;
            }
        }
        $response->setStatusCode(403);

        return $response;
    }

    /**
     * @Route("/api/client/collect/create", name="create_collect")
     */
    public function collectCreate(Request $request, PersonsRepository $personsRep, ObjectManager $manager, FoodsRepository $foodsRep, StatusRepository $statusRep)
    {   
        
        $response = new Response();

        $data = json_decode($request->getContent(), true);

        $user = $personsRep->findOneBy(["email"=> $data["email"]]);
        if($user){
            foreach($data["articles"] as $article){

                $food = $foodsRep->findOneBy(["code"=>$article["code"]]);

                if(!($food)){
                
                    $food = new Foods();

                    $food->setName($article["product_name"])
                        ->setIngredients($article["ingredients_text"])
                        ->setCode($article["code"])
                        ->setQuantity($article["quantity"])
                        ->setBrands($article["brands"])
                        ->setImageUrl($article["image_url"]);

                    $manager->persist($food);
                    $manager->flush();
                }
            }

            

            $collect = new Collect();
            $status = $statusRep->findOneBy(["id"=>4]);

            $collect->setPersonCreate($user)
                    ->setStatus($status)
                    ->setCommentary("Collecte en attente de confirmation.")
                    ->setDateRegister($date = new \Datetime);

            $manager->persist($collect);
            $manager->flush();
            
            $fs = new Filesystem();
            try {
                $fs->dumpFile($this->get('kernel')->getRootDir().'/collects/'.$collect->getId().'.json', $request->getContent());
            }catch(IOException $e) {
                $response->setContent($e);
            }

            $response->setStatusCode(200);
            return $response;
        }
        
        $response->setStatusCode(403);
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
}
