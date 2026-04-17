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
    private $email;

    #[ORM\Column(type: 'string', name: 'password', length: 255)]
    private $password;
    #[ORM\Column(type: 'string', name: 'rol', length: 20)]
    private $rol;

    #[ORM\Column(type: 'datetime', name: 'fecha_registro')]
    private $fecha_registro;

    #[ORM\OneToOne(mappedBy: 'usuario', targetEntity: Candidato::class, cascade: ['persist', 'remove'])]
    private ?Candidato $candidato = null;

    #[ORM\OneToOne(mappedBy: 'usuario', targetEntity: Anunciante::class, cascade: ['persist', 'remove'])]
    private ?Anunciante $anunciante = null;

    // Getters y Setters
    public function getId()
    {
        return $this->id;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail(string $email)
    {
        $this->email = $email;
        return $this;
    }
    public function getPassword(): string
    {
        return (string) $this->password;
    }
    public function setPassword(string $password)
    {
        $this->password = $password;
        return $this;
    }
    public function getRol()
    {
        return $this->rol;
    }
    public function setRol(string $rol)
    {
        $this->rol = $rol;
        return $this;
    }
    public function getFechaRegistro()
    {
        return $this->fecha_registro;
    }
    public function setFechaRegistro(\DateTimeInterface $fecha_registro)
    {
        $this->fecha_registro = $fecha_registro;
        return $this;
    }
    public function getCandidato()
    {
        return $this->candidato;
    }
    public function setCandidato(Candidato $candidato)
    {
        $this->candidato = $candidato;
        return $this;
    }
    public function getAnunciante()
    {
        return $this->anunciante;
    }
    public function setAnunciante(Anunciante $anunciante)
    {
        $this->anunciante = $anunciante;
        return $this;
    }
    public function getRoles(): array
    {
        return match ($this->rol) {
            'anunciante' => ['ROLE_ANUNCIANTE'],
            'candidato' => ['ROLE_CANDIDATO'],
            default => ['ROLE_USER'],
        };
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }
}