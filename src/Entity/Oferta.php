<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'ofertas')]
class Oferta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_oferta', type: 'integer')]
    private $id;

    #[ORM\ManyToOne(inversedBy: 'ofertas', targetEntity: Anunciante::class)]
    #[ORM\JoinColumn(name: 'id_anunciante', referencedColumnName: 'id_anunciante', nullable: false, onDelete: 'CASCADE')]
    private $anunciante;

    #[ORM\Column(type: 'string', length: 150)]
    private $titulo;

    #[ORM\Column(type: 'text', nullable: true)]
    private $descripcion;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private $salario;

    #[ORM\Column(name: 'tipo_contrato', type: 'string', length: 50, nullable: true)]
    private $tipo_contrato;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $ciudad;

    #[ORM\Column(name: 'fecha_publicacion', type: 'datetime')]
    private $fecha_publicacion;

    #[ORM\OneToMany(mappedBy: 'oferta', targetEntity: Postulacion::class, orphanRemoval: true)]
    private $postulaciones = [];

    #[ORM\OneToMany(mappedBy: 'oferta', targetEntity: Conversacion::class)]
    private Collection $conversaciones;
    
    // Getters y Setters
    public function getId() { return $this->id; }
    public function getAnunciante() { return $this->anunciante; }
    public function setAnunciante(Anunciante $anunciante)
    {
        $this->anunciante = $anunciante;
        return $this;
    }
    public function getTitulo() { return $this->titulo; }
    public function setTitulo(string $titulo)
    {
        $this->titulo = $titulo;
        return $this;
    }
    public function getDescripcion() { return $this->descripcion; }
    public function setDescripcion(?string $descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }
    public function getSalario() { return $this->salario; }
    public function setSalario(?string $salario)
    {
        $this->salario = $salario;
        return $this;
    }
    public function getTipoContrato() { return $this->tipo_contrato; }
    public function setTipoContrato(?string $tipo_contrato)
    {
        $this->tipo_contrato = $tipo_contrato;
        return $this;
    }
    public function getCiudad() { return $this->ciudad; }
    public function setCiudad(?string $ciudad)
    {
        $this->ciudad = $ciudad;
        return $this;
    }
    public function getFechaPublicacion() { return $this->fecha_publicacion; }
    public function setFechaPublicacion(\DateTimeInterface $fecha_publicacion)
    {
        $this->fecha_publicacion = $fecha_publicacion;
        return $this;
    }
    public function getPostulaciones() { return $this->postulaciones; }
    public function getConversaciones() { return $this->conversaciones; }
}