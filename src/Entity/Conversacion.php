<?php

namespace App\Entity;

use App\Repository\ConversacionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConversacionRepository::class)]
#[ORM\Table(name: "conversaciones")]
class Conversacion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_conversacion", type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'conversaciones')]
    #[ORM\JoinColumn(name: "id_candidato", referencedColumnName: "id_candidato", nullable: false, onDelete: "CASCADE")]
    private ?Candidato $candidato = null;

    #[ORM\ManyToOne(inversedBy: 'conversaciones')]
    #[ORM\JoinColumn(name: "id_anunciante", referencedColumnName: "id_anunciante", nullable: false, onDelete: "CASCADE")]
    private ?Anunciante $anunciante = null;

    #[ORM\ManyToOne(inversedBy: 'conversaciones')]
    #[ORM\JoinColumn(name: "id_oferta", referencedColumnName: "id_oferta", nullable: true, onDelete: "SET NULL")]
    private ?Oferta $oferta = null;

    #[ORM\Column(name: "fecha_creacion", type: "datetime")]
    private \DateTime $fechaCreacion;

    #[ORM\OneToMany(
        mappedBy: "conversacion",
        targetEntity: Mensaje::class,
        cascade: ["persist", "remove"],
        orphanRemoval: true
    )]
    #[ORM\OrderBy(["fechaEnvio" => "ASC"])]
    private Collection $mensajes;

    public function __construct()
    {
        $this->fechaCreacion = new \DateTime();
        $this->mensajes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCandidato(): Candidato
    {
        return $this->candidato;
    }
    public function setCandidato(Candidato $candidato): self
    {
        $this->candidato = $candidato;
        return $this;
    }

    public function getAnunciante(): Anunciante
    {
        return $this->anunciante;
    }
    public function setAnunciante(Anunciante $anunciante): self
    {
        $this->anunciante = $anunciante;
        return $this;
    }

    public function getOferta(): ?Oferta
    {
        return $this->oferta;
    }
    public function setOferta(?Oferta $oferta): self
    {
        $this->oferta = $oferta;
        return $this;
    }

    public function getFechaCreacion(): \DateTime
    {
        return $this->fechaCreacion;
    }

    public function getMensajes(): Collection
    {
        return $this->mensajes;
    }

    public function getUltimoMensaje(): ?Mensaje
    {
        if ($this->mensajes->isEmpty()) {
            return null;
        }

        return $this->mensajes->last();
    }
}
