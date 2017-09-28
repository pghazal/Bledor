<?php

namespace PG\UserBundle\Form;

use PG\UserBundle\Service\RolesHelper;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    private $class;

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
        $this->class = $class;
        $this->rolesHelper = $rolesHelper;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, array(
                'required' => true,
                'label' => array('translation_domain' => 'FOSUserBundle')
                ))
            ->add('email', EmailType::class, array(
                'required' => true,
                'label' => array('translation_domain' => 'FOSUserBundle')
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.new_password'),
                'second_options' => array('label' => 'form.new_password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
                'required' => false,
            ))
            ->add('roles', ChoiceType::class, array(
                'required' => true,
                'label' => 'roles',
                'choices' => $this->rolesHelper->getRoles(),
                'multiple' => true
            ))
            ->add('submit', SubmitType::class)
            ->add('cancel', SubmitType::class);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_unused_registration';
    }
}
