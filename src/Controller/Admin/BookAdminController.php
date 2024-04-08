<?php

namespace App\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class BookAdminController extends CRUDController
{
    public function showAction(Request $request): Response
    {
        $id = $request->get($this->admin->getIdParameter());

        $response = parent::showAction($request);

    }
}