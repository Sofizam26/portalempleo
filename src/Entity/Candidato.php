<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'candidatos')]
class Candidato
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_candidato', type: 'integer')]
    private $id;

    #[ORM\OneToOne(inversedBy: 'candidato', targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'id_usuario', referencedColumnName: 'id_usuario', nullable: false, onDelete: 'CASCADE')]
    private $usuario;

    #[ORM\Column(type: 'string', length: 100)]
    private $nombre;

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $telefono;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private $ciudad;

    #[ORM\Column(name: 'cv_pdf', type: 'string', length: 255, nullable: true)]
    private $cv_pdf;

    #[ORM\OneToMany(mappedBy: 'candidato', targetEntity: Postulacion::class, orphanRemoval: true)]
    private $postulaciones = [];

    #[ORM\OneToMany(mappedBy: 'candidato', targetEntity: Conversacion::class, orphanRemoval: true)]
    private $conversaciones = [];
    
    // Getters y Setters
    public function getId() { return $this->id; }
    public function getUsuario() { return $this->usuario; }
    public function setUsuario(Usuario $usuario) 
    {
        $this->usuario = $usuario;
        return $this;
    }
    public function getNombre() { return $this->nombre; }
    public function setNombre(string $nombre)
    {
        $this->nombre = $nombre;
        return $this;
    }
    public function getTelefono() { return $this->telefono; }
    public function setTelefono(string $telefono)
    {
        $this->telefono = $telefono;
        return $this;
    }
    public function getCiudad() { return $this->ciudad; }
    public function setCiudad(string $ciudad)
    {
        $this->ciudad = $ciudad;
        return $this;
    }
    public function getCvPdf() { return $this->cv_pdf; }
    public function setCvPdf(string $cv_pdf)
    {
        $this->cv_pdf = $cv_pdf;
        return $this;
    }
    public function getPostulaciones() { return $this->postulaciones; }
    public function getConversaciones() { return $this->conversaciones; }
}