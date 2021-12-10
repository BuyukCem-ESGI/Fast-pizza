<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass=CategoryRepository::class)
 * @UniqueEntity("libelle",message="Le libellé existe déjà",groups={"write_category_post",
 *     "write_category_put"})
 */
#[ApiResource(
    normalizationContext: ['groups' => ['read_categorys_get']],
    denormalizationContext: ['groups' => ['write_category_post','write_category_put']],
    collectionOperations: [
        'get',
        'post' => ['validation_groups' => ['write_category_post']]
    ],
    itemOperations: [
        'delete',
        'get',
        'put' => ['validation_groups' => ['write_category_put']]
    ]
)]
class Category
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Le libellé ne peut pas être vide",groups={"write_category_post",
     *     "write_category_put"})
     * @Assert\NotNull(message="Le libellé ne peut pas être null",groups={"write_category_post",
     *     "write_category_put"})
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read_categorys_get','write_category_post','write_category_put'])]
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Product::class, mappedBy="category")
     */
    #[Groups(['read_categorys_get'])]
    private $products;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    #[Groups(['write_category_put'])]
    private $updated_at;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
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
}
