<?php

namespace App\Controller;

use App\Entity\Persons;
use App\Repository\PersonsRepository;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiController extends Controller
{
    /**
     * @Route("/api/users/articles/add", name="add_articles")
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
