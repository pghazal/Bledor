<?php

namespace PG\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PG\PlatformBundle\Entity\Product;
use PG\PlatformBundle\Entity\Image;

class LoadProducts implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $products = array(
            array('name' => "pain", 'descriprion' => "800g de blabla"),
            array('name' => "baguette", 'descriprion' => "300g de blabla"),
            array('name' => "petit pain", 'descriprion' => "100g de blabla"),
            array('name' => "pain au chocolat", 'descriprion' => "100g de blabla et du chocolat")
        );

        foreach ($products as $prod) {
            $product = new Product();
            $product->setName($prod['name']);
            $product->setDescription($prod['descriprion']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
