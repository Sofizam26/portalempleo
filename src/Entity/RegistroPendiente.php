<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "registro_pendiente")]
class RegistroPendiente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_registro", type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 150, unique: true)]
    private string $email;

    #[ORM\Column(type: "string", length: 255)]
    private string $password;

    #[ORM\Column(type: "string", length: 20)]
    private string $rol;

    #[ORM\Column(type: "string", length: 64)]
    private string $token;

    #[ORM\Column(name: "fecha_creacion", type: "datetime")]
    private \DateTime $fechaCreacion;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $nombre = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $telefono = null;

    #[ORM\Column(type: "string", length: 100, nullable: true)]
    private ?string $ciudad = null;

    #[ORM\Column(name: "cv_pdf", type: "string", length: 255, nullable: true)]
    private ?string $cvPdf = null;

    #[ORM\Column(name: "nombre_anunciante", type: "string", length: 150, nullable: true)]
    private ?string $nombreAnunciante = null;

    #[ORM\Column(type: "string", length: 20, nullable: true)]
    private ?string $tipo = null;

    #[ORM\Column(type: "text", nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(name: "sitio_web", type: "string", length: 150, nullable: true)]
    private ?string $sitioWeb = null;

    public function __construct()
    {
        $this->fechaCreacion = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): string
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
        return $this->password;
    }
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRol(): string
    {
        return $this->rol;
    }
    public function setRol(string $rol): self
    {
        $this->rol = $rol;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getFechaCreacion(): \DateTime
    {
        return $this->fechaCreacion;
    }
    public function setFechaCreacion(\DateTime $fechaCreacion): self
    {
        $this->fechaCreacion = $fechaCreacion;
        return $this;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }
    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }
    public function setTelefono(?string $telefono): self
    {
        $this->telefono = $telefono;
        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }
    public function setCiudad(?string $ciudad): self
    {
        $this->ciudad = $ciudad;
        return $this;
    }

    public function getCvPdf(): ?string
    {
        return $this->cvPdf;
    }
    public function setCvPdf(?string $cvPdf): self
    {
        $this->cvPdf = $cvPdf;
        return $this;
    }

    public function getNombreAnunciante(): ?string
    {
        return $this->nombreAnunciante;
    }
    public function setNombreAnunciante(?string $nombreAnunciante): self
    {
        $this->nombreAnunciante = $nombreAnunciante;
        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }
    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;
        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }
    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getSitioWeb(): ?string
    {
        return $this->sitioWeb;
    }
    public function setSitioWeb(?string $sitioWeb): self
    {
        $this->sitioWeb = $sitioWeb;
        return $this;
    }
}
