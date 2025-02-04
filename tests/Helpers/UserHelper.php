<?php

namespace App\Tests\Helpers;

use App\Entity\Organization;
use App\Entity\User;
use App\Factory\UserFactory;

class UserHelper
{
    public static function create(?Organization $organization = null): User
    {
       $user = UserFactory::createOne();

       if ($organization) {
           $user->addOrganization($organization);

           $user->_save();
       }

       return $user->_real();
    }
}