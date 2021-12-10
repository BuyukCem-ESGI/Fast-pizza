<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProductTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ProductTypeRepository::class)
 * @UniqueEntity("name",message="Le nom du type de produit existe déjà",groups={"write_productType_post",
 *     "write_productType_patch"})
 */
#[ApiResource(
    normalizationContext: ['groups' => ['read_productTypes_get']],
    denormalizationContext: ['groups' => ['write_productType_post','write_productType_patch']],
    collectionOperations: [
        'get',
        'post' => ['validation_groups' => ['write_productType_post']]
    ],
    itemOperations: [
        'delete',
        'get',
        'patch' => ['validation_groups' => ['write_productType_patch']]
    ]
)]
class ProductType
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Le nom du type de produit ne peut pas être vide",
     *     groups={"write_category_post","write_category_put"})
     * @Assert\NotNull(message="Le nom du type de produit ne peut pas être null",
     *     groups={"write_productType_post","write_productType_patch"})
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    #[Groups(['write_productType_post','write_productType_patch','read_productTypes_get'])]
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\ManyToMany(targetEntity=Product::class, inversedBy="productTypes")
     */
    #[Groups(['write_productType_post','write_productType_patch','read_productsTypes_get'])]
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

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): self
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
