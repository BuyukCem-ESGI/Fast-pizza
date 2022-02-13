<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\AddressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AddressRepository::class)
 *
 */
#[ApiResource(
    normalizationContext: ['groups' => ['read-addresses-get']],
    collectionOperations: [
        'get' => ["security" => "is_granted('ROLE_ADMIN')"],
        'post' => ['security' => "is_granted('ROLE_USER')"],
    ],
    itemOperations: [
        'delete'=> ["security" => "is_granted('ROLE_USER') and object.getId() == user.getId()"],

        'get'=>  ["security" => "(is_granted('ROLE_USER') and object.getId() == user.getId()) OR
                                    (is_granted('ROLE_LIVREUR') and object.getId() == delivery.getId())"],

        'put' => ["security" => "is_granted('ROLE_USER') and object.getId() == user.getId()"]
    ]
)]
class Address
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    #[Groups(['read-addresses-get'])]
    private $streetNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read-addresses-get'])]
    private $street;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['read-addresses-get'])]
    private $department;

    /**
     * @ORM\Column(type="string")
     */
    #[Groups(['read-addresses-get'])]
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read-addresses-get'])]
    private $city;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="address")
     */
    #[ApiSubresource(maxDepth: 1)]
    private $orders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStreetNumber(): ?int
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(int $streetNumber): self
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getZipCode(): ?int
    {
        return $this->zipCode;
    }

    public function setZipCode(int $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

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

    /**
     * @return ArrayCollection
     */
    public function getOrders(): ArrayCollection
    {
        return $this->orders;
    }

    /**
     * @param ArrayCollection $orders
     */
    public function setOrders(ArrayCollection $orders): void
    {
        $this->orders = $orders;
    }
}
