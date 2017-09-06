<?php

namespace PG\UserBundle\Form;

use PG\UserBundle\Service\RolesHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    /**
     * @var RolesHelper
     */
    private $rolesHelper;

    /**
     * @param string $class The User class name
     * @param RolesHelper $rolesHelper Array or roles.
     */
    public function __construct($class, RolesHelper $rolesHelper)
    {
        $this->rolesHelper = $rolesHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('roles', ChoiceType::class, array(
            'required' => true,
            'label' => 'Rôle',
            'choices' => $this->rolesHelper->getRoles(),
            'multiple' => true
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}