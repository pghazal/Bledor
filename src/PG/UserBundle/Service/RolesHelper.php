<?php

namespace PG\UserBundle\Service;

class RolesHelper
{
    private $rolesHierarchy;

    public function __construct($rolesHierarchy)
    {
        $this->rolesHierarchy = $rolesHierarchy;
    }

    public function getRoles()
    {
        $roles = array();
        array_walk_recursive($this->rolesHierarchy, function($val) use (&$roles) {
            $roles[$val] = $val;
        });

        return array_unique($roles);
    }
}