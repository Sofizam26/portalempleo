<?php

namespace App\Entity;

use App\Repository\CandidatoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatoRepository::class)]
#[ORM\Table(name: 'candidatos')]
class Candidato
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_conversacion', type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Candidato::class, inversedBy: 'conversaciones')]
    #[ORM\JoinColumn(name: 'id_candidato', referencedColumnName: 'id_candidato', nullable: false, onDelete: 'CASCADE')]
    private $candidato;

    #[ORM\ManyToOne(targetEntity: Anunciante::class, inversedBy: 'conversaciones')]
    #[ORM\JoinColumn(name: 'id_anunciante', referencedColumnName: 'id_anunciante', nullable: false, onDelete: 'CASCADE')]
    private $anunciante;

    #[ORM\ManyToOne(targetEntity: Oferta::class)]
    #[ORM\JoinColumn(name: 'id_oferta', referencedColumnName: 'id_oferta', nullable: true, onDelete: 'SET NULL')]
    private $oferta;

    #[ORM\Column(name: 'fecha_creacion', type: 'datetime')]
    private $fecha_creacion = [];

    #[ORM\OneToMany(mappedBy: 'conversacion', targetEntity: Mensaje::class, orphanRemoval: true)]
    private $mensajes = [];

    // Getters y Setters
    public function getId() { return $this->id; }
    public function getCandidato() { return $this->candidato; }
    public function setCandidato(Candidato $candidato)
    {
        $this->candidato = $candidato;
        return $this;
    }
    public function getAnunciante() { return $this->anunciante; }
    public function setAnunciante(Anunciante $anunciante)
    {
        $this->anunciante = $anunciante;
        return $this;
    }
    public function getOferta() { return $this->oferta; }
    public function setOferta(?Oferta $oferta)
    {
        $this->oferta = $oferta;
        return $this;
    }
    public function getFechaCreacion() { return $this->fecha_creacion; }
    public function getMensajes() { return $this->mensajes; }
}