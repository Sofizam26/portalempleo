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
    
    #[ORM\Column(type: 'string', name: 'titulo_profesional', length: 150, nullable: true)]
    private $titulo_profesional;

    #[ORM\Column(type: 'text', name: 'descripcion', nullable: true)]
    private $descripcion;

    #[ORM\Column(type: 'boolean', name: 'cv_publico', options: ['default' => false])]
    private $cv_publico;

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

     public function getTitulo()
    {
        return $this->titulo_profesional;
    }

    public function setTitulo(?string $titulo_profesional)
    {
        $this->titulo_profesional = $titulo_profesional;
        return $this;
    }
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion)
    {
        $this->descripcion = $descripcion;
        return $this;
    }
    public function getCvPublico()
    {
        return $this->cv_publico;
    }

    public function setCvPublico(?bool $cv_publico)
    {
        $this->cv_publico = $cv_publico;
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
        if ($this->postulaciones->removeElement($postulacione)) {
            if ($postulacione->getCandidato() === $this) {
                $postulacione->setCandidato(null);
            }
        }

        return $this;
    }
}