<?php

namespace App\Entity;

use App\Repository\MensajeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MensajeRepository::class)]
#[ORM\Table(name: "mensajes")]
class Mensaje
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "id_mensaje", type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Conversacion::class, inversedBy: "mensajes")]
    #[ORM\JoinColumn(name: "id_conversacion", referencedColumnName: "id_conversacion", nullable: false, onDelete: "CASCADE")]
    private Conversacion $conversacion;

    #[ORM\Column(type: "string", length: 20)]
    private string $remitente;

    #[ORM\Column(type: "text")]
    private string $mensaje;

    #[ORM\Column(name: "fecha_envio", type: "datetime")]
    private \DateTimeInterface $fechaEnvio;

    #[ORM\Column(type: "boolean")]
    private bool $leido = false;

    public function __construct()
    {
        $this->fechaEnvio = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConversacion(): Conversacion
    {
        return $this->conversacion;
    }
    public function setConversacion(Conversacion $conversacion): self
    {
        $this->conversacion = $conversacion;
        return $this;
    }

    public function getRemitente(): string
    {
        return $this->remitente;
    }
    public function setRemitente(string $remitente): self
    {
        $this->remitente = $remitente;
        return $this;
    }

    public function getMensaje(): string
    {
        return $this->mensaje;
    }
    public function setMensaje(string $mensaje): self
    {
        $this->mensaje = $mensaje;
        return $this;
    }

    public function getFechaEnvio(): \DateTimeInterface
    {
        return $this->fechaEnvio;
    }

    public function isLeido(): bool
    {
        return $this->leido;
    }
    public function setLeido(bool $leido): self
    {
        $this->leido = $leido;
        return $this;
    }
}
