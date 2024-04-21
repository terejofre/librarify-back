<?php

namespace App\Controller\Admin;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CommentAdminController extends CRUDController
{
    public function createAction(Request $request): Response
    {
        $response =  parent::createAction($request);

        $form = $this->admin->getForm();
        if ($form->isSubmitted()) {
            $id = $request->get($this->admin->getIdParameter());
            return $this->redirectToRoute('admin_app_book_show', [
                'action' => 'show',
                'object' => $this->admin->getObject($id),
                'elements' => $this->admin->getShow(),
            ]);
        }

        return $response;
    }
}
