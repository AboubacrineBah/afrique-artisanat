<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Les vues utilisent @vite : inutile de compiler les assets pour lancer les tests
        $this->withoutVite();
    }
}
