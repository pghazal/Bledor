<?php

namespace PG\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCommandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('product', EntityType::class, array(
                'class' => Product::class,
                'query_builder' => function ($repository) {
                    return $repository->createQueryBuilder('p')->orderBy('p.name', 'ASC');
                },
                //'label' => false
            ))*/
            ->add('quantity', IntegerType::class, array(
                'label' => false
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PG\PlatformBundle\Entity\ProductCommand'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pg_platformbundle_productcommand';
    }
}
