<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'usuarios')]
class Usuario implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'id_usuario')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', name: 'email', unique: true, length: 150)]
    private ?string $email = null;

    #[ORM\Column(type: 'string', name: 'password', length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: 'string', name: 'rol', length: 20)]
    private ?string $rol = null;

    #[ORM\Column(type: 'datetime', name: 'fecha_registro')]
    private ?\DateTimeInterface $fecha_registro = null;

    #[ORM\OneToOne(mappedBy: 'usuario', targetEntity: Candidato::class, cascade: ['persist', 'remove'])]
    private ?Candidato $candidato = null;

    #[ORM\OneToOne(mappedBy: 'usuario', targetEntity: Anunciante::class, cascade: ['persist', 'remove'])]
    private ?Anunciante $anunciante = null;

    public function __construct()
    {
        $this->fecha_registro = new \DateTime();
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

    public function getPassword(): string
    {
        return (string) $this->password;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRol(): ?string
    {
        return $this->rol;
    }
    public function setRol(string $rol): self
    {
        $this->rol = $rol;
        return $this;
    }

    public function getFechaRegistro(): ?\DateTimeInterface
    {
        return $this->fecha_registro;
    }
    public function setFechaRegistro(\DateTimeInterface $fecha_registro): self
    {
        $this->fecha_registro = $fecha_registro;
        return $this;
    }

    public function getCandidato(): ?Candidato
    {
        return $this->candidato;
    }
    public function setCandidato(?Candidato $candidato): self
    {
        $this->candidato = $candidato;
        return $this;
    }

    public function getAnunciante(): ?Anunciante
    {
        return $this->anunciante;
    }
    public function setAnunciante(?Anunciante $anunciante): self
    {
        $this->anunciante = $anunciante;
        return $this;
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_USER'];
        $roles[] = match ($this->rol) {
            'anunciante' => 'ROLE_ANUNCIANTE',
            'candidato' => 'ROLE_CANDIDATO',
            default => 'ROLE_USER',
        };
        return array_unique($roles);
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}