<?php

namespace App\Tests\Helpers;

use App\Entity\Organization;
use App\Factory\OrganizationFactory;

class OrganizationHelper
{
    public static function create(): Organization
    {
        return OrganizationFactory::createOne()->_real();
    }
}