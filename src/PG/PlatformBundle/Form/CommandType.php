<?php

namespace PG\PlatformBundle\Form;

use PG\UserBundle\Form\UserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /* $builder
             ->add('name', TextType::class)
             ->add('products', CollectionType::class, array(
                 'type' => new ProductCommandType(),
                 'allow_add' => true,
                 'allow_delete' => true,
                 'by_reference' => false))
             ->add('save', SubmitType::class);*/

        $builder
            //->add('client', $this->user)
            ->add('products', CollectionType::class, array(
                'entry_type' => ProductCommandType::class,
                'allow_add' => true,
                'prototype' => true,
            ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PG\PlatformBundle\Entity\Command'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pg_platformbundle_command';
    }
}
