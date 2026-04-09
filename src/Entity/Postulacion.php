<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'postulaciones')]
class Postulacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_postulacion', type: 'integer')]
    private $id;

    #[ORM\ManyToOne(inversedBy: 'postulaciones', targetEntity: Candidato::class)]
    #[ORM\JoinColumn(name: 'id_candidato', referencedColumnName: 'id_candidato', nullable: false, onDelete: 'CASCADE')]
    private $candidato;

    #[ORM\ManyToOne(inversedBy: 'postulaciones', targetEntity: Oferta::class)]
    #[ORM\JoinColumn(name: 'id_oferta', referencedColumnName: 'id_oferta', nullable: false, onDelete: 'CASCADE')]
    private $oferta;

    #[ORM\Column(name: 'fecha_postulacion', type: 'datetime')]
    private $fecha_postulacion;

    #[ORM\Column(type: 'string', length: 20, options: ['default' => 'pendiente'])]
    private $estado = 'pendiente';

    public function getId() { return $this->id; }
    public function getCandidato() { return $this->candidato; }
    public function setCandidato(Candidato $candidato)
    {
        $this->candidato = $candidato;
        return $this;
    }
    public function getOferta() { return $this->oferta; }
    public function setOferta(Oferta $oferta)
    {
        $this->oferta = $oferta;
        return $this;
    }
    public function getFechaPostulacion() { return $this->fecha_postulacion; }
    public function setFechaPostulacion(\DateTimeInterface $fecha_postulacion)
    {
        $this->fecha_postulacion = $fecha_postulacion;
        return $this;
    }
    public function getEstado() { return $this->estado; }
    public function setEstado(string $estado)
    {
        $this->estado = $estado;
        return $this;
    }
}