<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class VeterinaireController
 * @package App\Controller
 * @Route("/veterinaire")
 */
class VeterinaireController extends AbstractController
{
    /**
     * @Route("", name="veterinaire_liste")
     */
    public function index()
    {
        return new Response('<h1>INDEX Action</h1>');
    }
    /**
     * @Route("/{id}/show", name="veterinaire_show")
     */
    public function show($id)
    {
        return new Response('<h1>SHOW Action</h1>');
    }

    /**
     * @Route("/new",name="veterinaire_new")
     */
    public function new()
    {
        return new Response('<h1>NEW Action</h1>');
    }

    /**
     * @Route("/{id}/edit", name="veterinaire_edit")
     */
    public function edit($id)
    {
        return new Response('<h1>EDIT Action</h1>');
    }

    /**
     * @Route("/{id}/delete", name="veterinaire_delete")
     */
    public function delete($id)
    {
        return new Response('<h1>DELETE Action</h1>');
    }

}
