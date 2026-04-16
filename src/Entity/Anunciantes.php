<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'anunciantes')]
class Anunciante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_anunciante', type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'anunciante', targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'id_usuario', referencedColumnName: 'id_usuario', nullable: false, onDelete: 'CASCADE')]
    private $usuario;

    #[ORM\Column(name: 'nombre_anunciante', type: 'string', length: 150)]
    private $nombre_anunciante;

    #[ORM\Column(type: 'string', length: 20)]
    private $tipo;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descripcion = null;


    #[ORM\Column(name: 'sitio_web', type: 'string', length: 150, nullable: true)]
    private $sitio_web;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $ciudad;

    #[ORM\OneToMany(mappedBy: 'anunciante', targetEntity: Oferta::class, orphanRemoval: true)]
    private $ofertas = [];

    #[ORM\OneToMany(mappedBy: 'anunciante', targetEntity: Conversacion::class, orphanRemoval: true)]
    private $conversaciones = [];

    public function getId()
    {
        return $this->id;
    }
    public function getUsuario()
    {
        return $this->usuario;
    }
    public function setUsuario(Usuario $usuario)
    {
        $this->usuario = $usuario;
        return $this;
    }
    public function getNombreAnunciante()
    {
        return $this->nombre_anunciante;
    }
    public function setNombreAnunciante(string $nombre_anunciante)
    {
        $this->nombre_anunciante = $nombre_anunciante;
        return $this;
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function setTipo(string $tipo)
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

    public function getSitioWeb()
    {
        return $this->sitio_web;
    }
    public function setSitioWeb(string $sitio_web)
    {
        $this->sitio_web = $sitio_web;
        return $this;
    }
    public function getCiudad()
    {
        return $this->ciudad;
    }
    public function setCiudad(string $ciudad)
    {
        $this->ciudad = $ciudad;
        return $this;
    }
    public function getOfertas()
    {
        return $this->ofertas;
    }
    public function getConversaciones()
    {
        return $this->conversaciones;
    }
}