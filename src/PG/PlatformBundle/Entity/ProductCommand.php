<?php

namespace PG\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ProductCommand
 *
 * @ORM\Table(name="product_command")
 * @ORM\Entity(repositoryClass="PG\PlatformBundle\Repository\ProductCommandRepository")
 */
class ProductCommand
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="quantity", type="integer")
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\Type("integer")
     * @Assert\NotBlank()
     */
    private $quantity;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="PG\PlatformBundle\Entity\Product")
     */
    private $product;

    /**
     * @var Command
     *
     * @ORM\ManyToOne(targetEntity="PG\PlatformBundle\Entity\Command", inversedBy="products")
     */
    protected $command;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set product
     *
     * @param \PG\PlatformBundle\Entity\Product $product
     *
     * @return ProductCommand
     */
    public function setProduct(Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \PG\PlatformBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set command
     *
     * @param \PG\PlatformBundle\Entity\Command $command
     *
     * @return ProductCommand
     */
    public function setCommand(Command $command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * Get command
     *
     * @return \PG\PlatformBundle\Entity\Command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Set quantity
     *
     * @param $quantity
     *
     * @return ProductCommand
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    function __toString()
    {
        return $this->product->__toString() . ' ' . $this->quantity;
    }
}
