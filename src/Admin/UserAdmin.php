<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\UserBundle\Admin\Model\UserAdmin as BaseUserAdmin;

final class UserAdmin extends BaseUserAdmin
{
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('username')
            ->add('email')
            ->add('enabled', null, ['editable' => true])
            ->add('createdAt')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => []
                ]
            ])
        ;
    }
}
