<?php

namespace DemoApp\UtilisateurBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DemoAppUtilisateurBundle extends Bundle
{
	public function getParent()
    {
        return 'FOSUserBundle';
    }
}
