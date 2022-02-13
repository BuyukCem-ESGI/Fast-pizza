<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use App\Controller\OrderController;
use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
#[ApiResource(
    normalizationContext: ['groups' => ['Read-order-delivery', 'read-addresses-get','read_user_delivery']],
    collectionOperations: [
        'get' =>[
            'security' => 'is_granted("ROLE_ADMIN")'
        ],
        'post' => [
            "security" => "is_granted('ROLE_CUSTOMER')",
            "method" => "POST",
            "path" => "/orders",
            "controller" => OrderController::class,
            "read" => false,
            "write" => false
        ]
    ],
    itemOperations: [
        'delete' => ['security' => "is_granted('ROLE_ADMIN')"],
        'get'=>  ['is_granted("ROLE_ADMIN") OR (is_granted("ROLE_CUSTOMER") and object.getUser() == user.getId())
                            OR (is_granted("ROLE_LIVREUR") and object.getDeliveryman().getId() == user.getId()'],
        'patch' => ["security" => "is_granted('ROLE_ADMIN')"]
    ],
    subresourceOperations: [
       'getDeliveryOrder' => [
           'method' => 'GET',
           'security' => 'is_granted("ROLE_LIVREUR")',
           'groups' => ['Read-order-delivery']
       ],
   ]
),ApiFilter(SearchFilter::class, properties: ['deliverStatus'=>'exact'])]
class Order extends \Doctrine\Common\Collections\ArrayCollection
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    #[Groups(['Read-order-delivery'])]
    private $owner;

    /**
     * @ORM\Column(type="float")
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['Read-order-delivery'])]
    private $status;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['Read-order-delivery'])]
    private $deliveryDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     */
    #[Groups(['Read-order-delivery'])]
    private $deliverStatus;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class, inversedBy="orders")
     *
     */
    #[Groups(['Read-order-delivery'])]
    private $address;

    /**
     * @return mixed
     */
    public function getDeliverStatus()
    {
        return $this->deliverStatus;
    }

    /**
     * @param mixed $deliverStatus
     */
    public function setDeliverStatus($deliverStatus): void
    {
        $this->deliverStatus = $deliverStatus;
    }

    /**
     * @return mixed
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * @param mixed $adress
     */
    public function setAdress($adress): void
    {
        $this->adress = $adress;
    }

    /**
     * @ORM\Column(type="string")
     */
    private $paymentId;

    /**
     * @return mixed
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * @param mixed $paymentId
     */
    public function setPaymentId($paymentId): void
    {
        $this->paymentId = $paymentId;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getDeliveryDate(): ?\DateTimeInterface
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(?\DateTimeInterface $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getDeliver(): ?User
    {
        return $this->delivery;
    }

    public function setDeliverId(?User $deliver): self
    {
        $this->delivery = $deliver;
        return $this;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $adress): self
    {
        $this->address = $adress;
        return $this;
    }

}
