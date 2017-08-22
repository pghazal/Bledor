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
            array('name' => "pain", 'descriprion' => "800g de blabla", 'price' => 0.50, 'image' => new Image("https://www.academiedugout.fr/images/5418/370-274/ffffff/pain-campagne-copy.jpg?poix=50&poiy=50", "Pain")),
            array('name' => "baguette", 'descriprion' => "300g de blabla", 'price' => 0.30, 'image' => new Image("http://www.entrup-haselbach.de/sites/default/files/imagecache/product_zoom/sites/default/files/images/products/4213_baguette_schmuck.png", "Baguette")),
            array('name' => "petit pain", 'descriprion' => "100g de blabla", 'price' => 0.10, 'image' => new Image("http://d3l6n8hsebkot8.cloudfront.net/images/products/11/LN_018542_BP_11.jpg", "Petit pain")),
            array('name' => "pain au chocolat", 'descriprion' => "100g de blabla et du chocolat", 'price' => 0.08, 'image' => new Image("http://www.chococlic.com/photo/art/grande/8626869-13605559.jpg?v=1449515894", "Pain au chocolat"))
        );

        foreach ($products as $prod) {
            $product = new Product();
            $product->setName($prod['name']);
            $product->setDescription($prod['descriprion']);
            $product->setPrice($prod['price']);
            $product->setImage($prod['image']);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
