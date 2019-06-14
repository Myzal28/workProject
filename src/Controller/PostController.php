<?php

namespace App\Controller;

use App\Entity\Signup;
use App\Entity\Persons;
use App\Entity\Articles;
use App\Repository\SignupRepository;
use App\Repository\PersonsRepository;
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
     * @Route("/post/client/article/modify/{id}", name="post")
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
}
