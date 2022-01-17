<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 * * @UniqueEntity("name",message="Le nom du menu existe déjà",groups={"write_menu_post",
 *     "write_menu_patch"})
 */
#[ApiResource(
    normalizationContext: ['groups' => ['read_menus_get','read_users_get','read_products_get']],
    denormalizationContext: ['groups' => ['write_menu_post','write_menu_patch']],
    collectionOperations: [
        'get',
        'post' => ['validation_groups' => ['write_menu_post'],
            [ "security_post_denormalize" => "is_granted('MENU_CREATE', object)" ]]
    ],
    itemOperations: [
        'delete',
        'get',
        'patch' => ['validation_groups' => ['write_menu_patch']]
    ]
)]
class Menu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write_menu_post','write_menu_patch','read_menus_get'])]
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write_menu_post','write_menu_patch','read_menus_get'])]
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    #[Groups(['write_menu_post','write_menu_patch','read_menus_get'])]
    private $priceTTC;

    /**
     * @ORM\ManyToOne(targetEntity=Cart::class, inversedBy="menus")
     */
    private $cart;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="menus")
     * @ORM\JoinColumn(nullable=true)
     */
    #[Groups(['write_menu_post','write_menu_patch','read_menus_get','read_users_get'])]
    private $owner;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="menus")
     */
    #[Groups(['write_menu_post','write_menu_patch','read_menus_get','read_products_get'])]
    #[ApiSubresource(maxDepth: 1)]
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPriceTTC(): ?float
    {
        return $this->priceTTC;
    }

    public function setPriceTTC(float $priceTTC): self
    {
        $this->priceTTC = $priceTTC;

        return $this;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): self
    {
        $this->cart = $cart;

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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        $this->products->removeElement($product);

        return $this;
    }
}
