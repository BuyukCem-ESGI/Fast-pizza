<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\deleteMenuController;
use App\Controller\PostMenuController;
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
    normalizationContext: ['groups' => ['read_menus_get', 'read_users_get', 'read_products_get']],
    denormalizationContext: ['groups' => ['write_menu_post', 'write_menu_patch']],
    collectionOperations: [
        'get',
        'post' => [
            'method' => 'POST',
            'path' => '/menus',
            'controller' => PostMenuController::class,
            'security' => 'is_granted("ROLE_EDITEUR")',
            'read' => false,
            'write' => false,
            'openapi_context' => [
                "summary" => "Création d'un meunu",
                "description" => "Création d'un menu",
                "consumes" => ["application/json"],
                "produces" => ["application/json"],
                "responses" => [
                    "201" => [
                        "description" => "Menu créé",
                    ]
                ],
                "requestBody" => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'name' => [
                                        'type' => 'string',
                                        'example' => 'Menu 1',
                                        'description' => 'Le nom du menu',
                                        'maxLength' => 255,
                                        'minLength' => 1,
                                    ],
                                    'description' => [
                                        'type' => 'string',
                                        'example' => 'Menu 1',
                                        'description' => 'La description du menu',
                                        'maxLength' => 255,
                                        'minLength' => 1,
                                    ],
                                    'price' => [
                                        'type' => 'number',
                                        'example' => '10',
                                        'description' => 'Le prix du menu',
                                        'maxLength' => 255,
                                        'minLength' => 1,
                                    ],
                                    'products' => [
                                        'type' => 'array',
                                        'description' => 'Les produits du menu',
                                        'maxLength' => 255,
                                        'minLength' => 1,
                                        'items' => [
                                            'type' => 'integer',
                                            'format' => 'int64',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ]
        ]
    ],
    itemOperations: [
        'delete' => [
            'method' => 'DELETE',
            'path' => '/menu/{id}',
            'controller' => DeleteMenuController::class,
            'security' => 'is_granted("ROLE_EDITEUR")',
            'read' => false,
            'write' => false,
            'openapi_context' => [
                "summary" => "Suppression d'un menu",
                "description" => "Suppression d'un menu",
                "consumes" => ["application/json"],
                "produces" => ["application/json"],
                "responses" => [
                    "204" => [
                        "description" => "Menu supprimé",
                    ]
                ],
            ]
        ],
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $menuMicroserviceId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write_menu_post', 'write_menu_patch', 'read_menus_get'])]
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write_menu_post', 'write_menu_patch', 'read_menus_get'])]
    private $description;

    /**
     * @ORM\Column(type="float")
     */
    #[Groups(['write_menu_post', 'write_menu_patch', 'read_menus_get'])]
    private $price;

    /**
     * @ORM\ManyToMany(targetEntity=Cart::class, inversedBy="menus")
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
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="product")
     */
    #[Groups(['write_menu_patch', 'read_menus_get', 'read_products_get'])]
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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

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

    /**
     * @return mixed
     */
    public function getMenuMicroserviceId()
    {
        return $this->menuMicroserviceId;
    }

    /**
     * @param mixed $menuMicroserviceId
     */
    public function setMenuMicroserviceId($menuMicroserviceId): void
    {
        $this->menuMicroserviceId = $menuMicroserviceId;
    }
}
