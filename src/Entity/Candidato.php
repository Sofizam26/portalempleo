<?php

namespace App\Entity;

use App\Repository\CandidatoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CandidatoRepository::class)]
#[ORM\Table(name: 'candidatos')]
class Candidato
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_candidato", type: "integer")]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'candidato', targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'id_usuario', referencedColumnName: 'id_usuario', nullable: false, onDelete: 'CASCADE')]
    private ?Usuario $usuario = null;

    #[ORM\Column(length: 255)]
    private ?string $nombre = null;

    #[ORM\Column(length: 20)]
    private ?string $telefono = null;

    #[ORM\Column(length: 255)]
    private ?string $ciudad = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $cv_pdf = null;

    #[ORM\OneToMany(mappedBy: 'candidato', targetEntity: Postulacion::class, orphanRemoval: true)]
    private Collection $postulaciones;

    public function __construct()
    {
        $this->postulaciones = new ArrayCollection();
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

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): self
    {
        $this->nombre = $nombre;
        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(string $telefono): self
    {
        $this->telefono = $telefono;
        return $this;
    }

    public function getCiudad(): ?string
    {
        return $this->ciudad;
    }

    public function setCiudad(string $ciudad): self
    {
        $this->ciudad = $ciudad;
        return $this;
    }

    public function getCvPdf(): ?string
    {
        return $this->cv_pdf;
    }

    public function setCvPdf(?string $cv_pdf): self
    {
        $this->cv_pdf = $cv_pdf;
        return $this;
    }

    /**
     * @return Collection<int, Postulacion>
     */
    public function getPostulaciones(): Collection
    {
        return $this->postulaciones;
    }

    public function addPostulacione(Postulacion $postulacione): self
    {
        if (!$this->postulaciones->contains($postulacione)) {
            $this->postulaciones->add($postulacione);
            $postulacione->setCandidato($this);
        }

        return $this;
    }

    public function removePostulacione(Postulacion $postulacione): self
    {
        $this->postulaciones->removeElement($postulacione);
        return $this;
    }
}