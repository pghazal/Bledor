<?php

namespace PG\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('products', CollectionType::class, array(
                'entry_type' => ProductCommandType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'delete_empty' => true,
                'prototype' => true,
                'label' => false,
                'required' => false,
            ))
            ->add('submit', SubmitType::class);;
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
