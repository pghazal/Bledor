<?php

namespace PG\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class PGUserBundle extends Bundle
{
	public function getParent()
  	{
    	return 'FOSUserBundle';
  	}
}
