<?php

namespace App\Controller;


use App\Entity\Persons;
use Psr\Log\LoggerInterface;
use App\Repository\SignupRepository;
use App\Repository\CollectRepository;
use App\Repository\ServicesRepository;
use Symfony\Component\HttpFoundation\Request;
use Omines\DataTablesBundle\Column\TextColumn;
use Symfony\Component\Routing\Annotation\Route;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
use Omines\DataTablesBundle\Controller\DataTablesTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     */
    public function viewPerson(Persons $person){

        return $this->render('view/profile.html.twig', [
            'person' => $person
        ]);
    }

    /**
     * @Route("/admin/manage/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="admin_manage",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function Manage(SignupRepository $signup, CollectRepository $collect){

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
    public function ManageSignups(){

        return $this->render('admin/manageSignups.html.twig');
    }

    /**
     * @Route("/admin/manage/services/{_locale}",
     *     defaults={"_locale"="fr"},
     *     name="manage_services",
     *     requirements={
     *         "_locale"="en|fr|pt|it"
     * })
     */
    public function ManageServices(ServicesRepository $serviceRep){

        $services = $serviceRep->findAll();

        return $this->render('admin/manageServices.html.twig',[
            "services" => $services
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
}
