<?php

namespace PG\PlatformBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OperationProduct
 *
 * @ORM\Table(name="operation_product")
 * @ORM\Entity(repositoryClass="PG\PlatformBundle\Repository\OperationProductRepository")
 */
class OperationProduct
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
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="PG\PlatformBundle\Entity\Operation")
     * @ORM\JoinColumn(nullable=false)
     */ 
    private $operation;

    /**
     * @ORM\ManyToOne(targetEntity="PG\PlatformBundle\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;


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
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return OperationProduct
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set operation
     *
     * @param \stdClass $operation
     *
     * @return OperationProduct
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return \stdClass
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set product
     *
     * @param \stdClass $product
     *
     * @return OperationProduct
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \stdClass
     */
    public function getProduct()
    {
        return $this->product;
    }
}
