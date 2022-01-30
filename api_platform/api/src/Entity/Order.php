<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
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
            'security' => ['is_granted("ROLE_ADMIN")']
        ],
        'post' => ["security" => "is_granted('ROLE_CUSTOMER')"],
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
           'security' => 'is_granted("ROLE_LIVREUR") and object.getDeliveryman().getId() == user.getId()',
           'groups' => ['Read-order-delivery']
       ],
   ]
)]
class Order
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
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="orders")
     * @ORM\JoinColumn(nullable=true)
     */
    #[Groups(['Read-order-delivery'])]
    private $delivery;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=Address::class, inversedBy="Address")
     */
    private $adresse;

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

    public function getAdresse(): ?Address
    {
        return $this->adresse;
    }

    public function setAdresse(?Address $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

}
