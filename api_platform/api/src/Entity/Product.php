<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\DeleteProductController;
use App\Controller\PatchProductController;
use App\Controller\PostProductController;
use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 * @UniqueEntity("name",message="Le nom du produit existe déjà",groups={"write_product_post",
 *     "write_product_put"})
 */
#[ApiResource(
    collectionOperations: [
        'get',
        'post_product' => [
            'method' => 'POST',
            'path' => '/products',
            'controller' => PostProductController::class,
            'security' => "is_granted('ROLE_EDITEUR')",
            'read' => false,
            'write' => false,
            "openapi_context" => [
                "summary" => "Création d'un produit",
                "description" => "Création d'un produit",
                "consumes" => ["multipart/form-data"],
                "produces" => ["application/json"],
                "responses" => [
                    "201" => [
                        "description" => "Voici la desc de mon produit",
                    ]
                ]
            ],
            "requestBody" => [
                'content' => [
                    'multipart/form-data' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'name' => [
                                    'type' => 'string',
                                    'description' => 'Nom du produit',
                                    'example' => 'Produit 1',
                                    'format' => 'string',
                                    'maxLength' => 255,
                                    'minLength' => 1,
                                ],
                                'description' => [
                                    'type' => 'string',
                                    'description' => 'Description du produit',
                                    'example' => 'Ceci est un produit',
                                    'format' => 'string',
                                    'maxLength' => 255,
                                    'minLength' => 1,
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
    ],
    itemOperations: [
        'get',
        'patch' => [
            'method' => 'PATCH',
            'path' => '/products/{id}',
            'controller' => PatchProductController::class,
            'security' => 'is_granted("ROLE_EDITEUR")',
            'read' => false,
            'write' => false,
            "openapi_context" => [
                "summary" => "Modification d'un produit",
                "description" => "Modification d'un produit",
                "consumes" => ["application/merge-patch+json"],
                "produces" => ["application/json"],
                "responses" => [
                    "200" => [
                        "description" => "Voici la desc de mon produit",
                    ]
                ]
            ]
        ],
        'delete' => [
            'method' => 'DELETE',
            'path' => '/products/{id}',
            'controller' => DeleteProductController::class,
            'security' => 'is_granted("ROLE_EDITEUR")',
            'read' => false,
            'write' => false,
            "openapi_context" => [
                "summary" => "Suppression d'un produit",
                "description" => "Suppression d'un produit",
                "produces" => ["application/json"],
                "responses" => [
                    "204" => [
                        "description" => "Voici la desc de mon produit",
                    ]
                ]
            ]
        ]
    ],
    denormalizationContext: ['groups' => ['write_product_post', 'write_product_patch']],
    /*subresourceOperations: [
        'api_products_types_products_get_subresource' => [
            'method' => 'GET',
            'normalization_context' => [
                'groups' => ['read_products_to_productsType_get_subresource'],
            ],
        ],
    ],*/
)]
class Product
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
    private $productMicroserviceId;


    /**
     * @Assert\NotBlank(message="Le nom du produit ne peut pas être vide",
     *     groups={"write_product_post","write_product_patch"})
     * @Assert\NotNull(message="Le nom du produit ne peut pas être null",
     *     groups={"write_product_post","write_product_patch"})
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write_product_post', 'write_product_patch', 'read_products_get', 'read_categorys_get'])]
    private $name;

    /**
     * @Assert\NotBlank(message="La description du produit ne peut pas être vide",
     *     groups={"write_product_post","write_product_patch"})
     * @Assert\NotNull(message="La description du produit ne peut pas être null",
     *     groups={"write_product_post","write_product_patch"})
     * @Assert\Length(
     *      min = 10,
     *      max = 500,
     *      minMessage = "Votre description doit contenir au moins 10 caractères",
     *      maxMessage = "Votre description doit contenir au plus 500 caractères",
     *     groups={"write_product_post","write_product_patch"})
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['write_product_post', 'write_product_patch', 'read_products_get', 'read_categorys_get'])]
    private $description;

    /**
     * @Assert\NotBlank(message="Le prix du produit ne peut pas être vide",
     *     groups={"write_product_post","write_product_patch"})
     * @Assert\NotNull(message="Le prix du produit ne peut pas être null",
     *     groups={"write_product_post","write_product_patch"})
     * @Assert\PositiveOrZero(groups={"write_product_post","write_product_patch"})
     * @ORM\Column(type="array")
     */
    #[Groups(['write_product_post', 'write_product_patch', 'read_products_get', 'read_categorys_get'])]
    private $price;

    /**
     * @Assert\NotBlank(message="La référence du produit ne peut pas être vide",
     *     groups={"write_product_post","write_product_patch"})
     * @Assert\NotNull(message="La référence du produit ne peut pas être null",
     *     groups={"write_product_post","write_product_patch"})
     * @Assert\Regex(pattern="/^([A-Za-z0-9]+[\s]{0,1}[\'-]{0,1}[\s]{0,1}|[\s]{0,1}[\'.]{0,1}[\s]{0,1}[A-Za-z0-9])+$/",
     *     match=true, message="La référence n'est pas valide",
     *     groups={"write_product_post","write_product_patch"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['write_product_post', 'write_product_patch', 'read_products_get', 'read_categorys_get'])]
    private $reference;

    /**
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['write_product_post', 'write_product_patch', 'read_products_get', 'read_categorys_get'])]
    private $imageUrl;

    /**
     * @ORM\ManyToMany (targetEntity=Cart::class, mappedBy="product")
     */
    private $carts;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    #[Groups(['read_products_get'])]
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['read_products_get'])]
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=ProductType::class, mappedBy="products")
     */
    #[ApiSubresource(
        maxDepth: 1,
    )]
    private $productTypes;

    /**
     * @ORM\ManyToMany(targetEntity=Menu::class, inversedBy="menu")
     */
    private $menus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $idExt;

    /**
     * @return mixed
     */
    public function getIdExt()
    {
        return $this->idExt;
    }

    /**
     * @param mixed $idExt
     */
    public function setIdExt($idExt): void
    {
        $this->idExt = $idExt;
    }

    public function __construct()
    {
        $this->carts = new ArrayCollection();
        $this->productTypes = new ArrayCollection();
        $this->menus = new ArrayCollection();
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

    public function getPrice(): ?array
    {
        return $this->price;
    }

    public function setPrice(array $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function Reference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @param mixed $imageUrl
     */
    public function setImageUrl(string $imageUrl): void
    {
        $this->imageUrl = $imageUrl;
    }

    /**
     * @return Collection|Cart[]
     */
    public function getCarts(): Collection
    {
        return $this->carts;
    }

    public function addCart(Cart $cart): self
    {
        if (!$this->carts->contains($cart)) {
            $this->carts[] = $cart;
            $cart->setProduct($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getProduct() === $this) {
                $cart->setProduct(null);
            }
        }

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
     * @return Collection|ProductType[]
     */
    public function getProductTypes(): Collection
    {
        return $this->productTypes;
    }

    public function addProductType(ProductType $productType): self
    {
        if (!$this->productTypes->contains($productType)) {
            $this->productTypes[] = $productType;
            $productType->addProduct($this);
        }

        return $this;
    }

    public function removeProductType(ProductType $productType): self
    {
        if ($this->productTypes->removeElement($productType)) {
            $productType->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return Collection|Menu[]
     */
    public function getMenus(): Collection
    {
        return $this->menus;
    }

    public function addMenu(Menu $menu): self
    {
        if (!$this->menus->contains($menu)) {
            $this->menus[] = $menu;
            $menu->addProduct($this);
        }

        return $this;
    }

    public function removeMenu(Menu $menu): self
    {
        if ($this->menus->removeElement($menu)) {
            $menu->removeProduct($this);
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getProductMicroserviceId(): ?string
    {
        return $this->productMicroserviceId;
    }

    /**
     * @param mixed $productMicroserviceId
     */
    public function setProductMicroserviceId($productMicroserviceId): void
    {
        $this->productMicroserviceId = $productMicroserviceId;
    }
}
