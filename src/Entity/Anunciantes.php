<?php

namespace App\Entity;

use App\Repository\AnuncianteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'anunciantes')]
class Anunciante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_anunciante', type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'anunciante', targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'id_usuario', referencedColumnName: 'id_usuario', nullable: false, onDelete: 'CASCADE')]
    private ?Usuario $usuario = null;

    #[ORM\Column(name: 'nombre_anunciante', type: 'string', length: 150)]
    private string $nombre_anunciante;

    #[ORM\Column(type: 'string', length: 20)]
    private string $tipo;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descripcion = null;

    #[ORM\Column(name: 'sitio_web', type: 'string', length: 150, nullable: true)]
    private ?string $sitio_web = null;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $ciudad = null;

    #[ORM\OneToMany(mappedBy: 'anunciante', targetEntity: Oferta::class, orphanRemoval: true)]
    private Collection $ofertas;

    #[ORM\OneToMany(mappedBy: 'anunciante', targetEntity: Conversacion::class, orphanRemoval: true)]
    private Collection $conversaciones;

    public function __construct()
    {
        $this->ofertas = new ArrayCollection();
        $this->conversaciones = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(Usuario $usuario): self
    {
        $this->usuario = $usuario;
        return $this;
    }

    public function getNombreAnunciante(): string
    {
        return $this->nombre_anunciante;
    }

    public function setNombreAnunciante(string $nombre_anunciante): self
    {
        $this->nombre_anunciante = $nombre_anunciante;
        return $this;
    }

    public function getTipo(): string
    {
        return $this->tipo;
    }

    public function setTipo(string $tipo): self
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
        return $this->sitio_web;
    }

    public function setSitioWeb(?string $sitio_web): self
    {
        $this->sitio_web = $sitio_web;
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

    public function getOfertas(): Collection
    {
        return $this->ofertas;
    }

    public function getConversaciones(): Collection
    {
        return $this->conversaciones;
    }
}