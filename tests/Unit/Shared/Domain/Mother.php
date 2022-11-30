<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Tests\Unit\Shared\Domain;

use Faker\Factory;
use Faker\Generator;

abstract class Mother
{
    protected Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }
}