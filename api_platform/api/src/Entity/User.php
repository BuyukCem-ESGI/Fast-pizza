<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Controller\CreateUser;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @UniqueEntity("email",message="L'email existe déjà",groups={"write_user_post",
 *     "write_user_put"})
 * @method string getUserIdentifier()
 */
#[ApiResource(
    #attributes: ["security" => "is_granted('R0LE_CUSTOMER')"],
    normalizationContext: ['groups' => ['read_users_get', 'read_user_delivery']],
    denormalizationContext: ['groups' => ['write_user_post','write_user_put']],
    collectionOperations: [
        'get' => ["security" => "is_granted('ROLE_ADMIN')"],
        'post' => ['validation_groups' => ['write_user_post']]
    ],
    itemOperations: [
        'delete'=> ["security" => "is_granted('ROLE_USER') and object.getId() == user.getId()"],
        'get'=>  ["security" => "is_granted('ROLE_USER') and object.getId() == user.getId()"],
        //'put' => ["security" => "is_granted('ROLE_USER') and object.getId() == user.getId()"],
        'patch' => ["security" => "is_granted('ROLE_USER') and object.getId() == user.getId()"],
        'create_user' => [
            'method' => 'POST',
            'path'=> '/auth/register',
            'controller'=> CreateUser::class,
            'read'=> false,
            "write"=> false,
            'validation_groups' => ['write_user_post'],
            'openapi_context' => [
                'summary' => 'Create a new user',
                'description' => 'Create a new user',
                'consumes' => ['application/json'],
                'produces' => ['application/json'],
                'responses' => [
                    '201' => [
                        'description' => 'User created',
                        ]
                    ],
                'requestBody' => [
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'email' => [
                                        'type' => 'string',
                                        'example' => 'test@test.com',
                                        'format' => 'email',
                                        'description' => 'Email of the user',
                                        'required' => true,
                                        ],
                                    'password' => [
                                        'type' => 'string',
                                        'example' => 'test',
                                        'description' => 'Password of the user',
                                        'required' => true,
                                        ],
                                    'firstname' => [
                                        'type' => 'string',
                                        'example' => 'test',
                                        'description' => 'Firstname of the user',
                                        'required' => true,
                                        ],
                                    'lastname' => [
                                        'type' => 'string',
                                        'example' => 'test',
                                        'description' => 'Lastname of the user',
                                        'required' => true,
                                        ],
                                    'phoneNumber'=> [
                                        'type' => 'string',
                                        'example' => '0612345678',
                                        'description' => 'Phone number of the user',
                                        'required' => true,
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
        ]
    ]
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="L'email ne peut pas être vide",groups={"write_user_post","write_user_put"})
     * @Assert\NotNull(message="L'email ne peut pas être null",groups={"write_user_post","write_user_put"})
     * @Assert\Regex(pattern="/[a-z0-9._-]+@[a-z0-9._-]+\.[a-z]{2,4}/", match=true,
     *     message="L'email n'est pas valide", groups={"write_user_post","write_user_put"})
     * @ORM\Column(type="string", length=180, unique=true)
     */
    #[Groups(['read_users_get', 'write_user_post', 'write_user_put'])]
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    #[Groups(['read_users_get', 'write_user_post', 'write_user_put'])]
    public $roles = [];

    /**
     * @Assert\NotBlank(message="Le mot de passe ne peut pas être vide",groups={"write_user_post","write_user_put"})
     * @Assert\NotNull(message="Le mot de passe ne peut pas être null",groups={"write_user_post","write_user_put"})
     * @Assert\NotCompromisedPassword(groups={"write_user_post","write_user_put"})
     * @SecurityAssert\UserPassword(message = "Mauvaise valeur pour votre mot de passe actuel",
     * groups={"write_user_put"})
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    #[Groups(['read_users_get', 'write_user_post', 'write_user_put'])]
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Cart::class, mappedBy="owner")
     */
    private $carts;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="owner")
     */
    private $orders;

    /**
     * @Assert\NotBlank(message="Le prénom ne peut pas être vide",groups={"write_user_post","write_user_put"})
     * @Assert\NotNull(message="Le prénom ne peut pas être null",groups={"write_user_post","write_user_put"})
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read_users_get', 'write_user_post', 'write_user_put', 'read_user_delivery'])]
    private $firstname;

    /**
     * @Assert\NotBlank(message="Le nom ne peut pas être vide",groups={"write_user_post","write_user_put"})
     * @Assert\NotNull(message="Le nom ne peut pas être null",groups={"write_user_post","write_user_put"})
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read_users_get', 'write_user_post', 'write_user_put', 'read_user_delivery'])]
    private $lastname;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $last_activity;

    /**
     * @ORM\Column(type="boolean",nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @Assert\NotBlank(message="Le numéro de téléphone ne peut pas être vide",
     *     groups={"write_user_post","write_user_put"})
     * @Assert\NotNull(message="Le numéro de téléphone ne peut pas être null"
     * ,groups={"write_user_post","write_user_put"})
     * @ORM\Column(type="string",nullable=true)
     */
    #[Groups(['read_users_get', 'write_user_post', 'write_user_put'])]
    private $phoneNumber;

    public function __construct()
    {
        $this->carts = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->menus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $cart->setOwner($this);
        }

        return $this;
    }

    public function removeCart(Cart $cart): self
    {
        if ($this->carts->removeElement($cart)) {
            // set the owning side to null (unless already changed)
            if ($cart->getOwner() === $this) {
                $cart->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Order[]
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders[] = $order;
            $order->setOwner($this);
        }

        return $this;
    }

    public function removeOrder(Order $order): self
    {
        if ($this->orders->removeElement($order)) {
            // set the owning side to null (unless already changed)
            if ($order->getOwner() === $this) {
                $order->setOwner(null);
            }
        }

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getLastActivity(): ?\DateTimeInterface
    {
        return $this->last_activity;
    }

    public function setLastActivity(\DateTimeInterface $last_activity): self
    {
        $this->last_activity = $last_activity;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(string $activation_token): self
    {
        $this->activation_token = $activation_token;

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

    public function getPhoneNumber(): ?int
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(int $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }
}
