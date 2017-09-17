<?php

namespace PG\PlatformBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use PG\PlatformBundle\Entity\ProductCommand;
use Symfony\Component\Validator\Constraints as Assert;
use PG\UserBundle\Entity\User;

/**
 * Command
 *
 * @ORM\Table(name="command")
 * @ORM\Entity(repositoryClass="PG\PlatformBundle\Repository\CommandRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Command
{
    public function __construct()
    {
        $this->date  = new \DateTime('now');
        $this->products = new ArrayCollection();
    }


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="PG\UserBundle\Entity\User", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="PG\PlatformBundle\Entity\ProductCommand", mappedBy="command", cascade={"persist"})
     */
    protected $products;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Command
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Command
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PreUpdate
     */
    public function updatedAtDate()
    {
        $this->setUpdatedAt(new \Datetime());
    }

    /**
     * Set client
     *
     * @param User $client
     *
     * @return Command
     */
    public function setClient(User $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \PG\UserBundle\Entity\User
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @param ArrayCollection $products
     */
    public function setProducts($products)
    {
        $this->products = $products;
    }

    /**
     * Add product
     *
     * @param ProductCommand $productCommand
     *
     * @return Command
     */
    public function addProduct(ProductCommand $productCommand)
    {
        //$this->products[] = $product;
        $this->products->add($productCommand);
        return $this;
    }

    /**
     * Remove product
     *
     * @param ProductCommand $productCommand
     */
    public function removeProduct(ProductCommand $productCommand)
    {
        $this->products->removeElement($productCommand);
    }
}
