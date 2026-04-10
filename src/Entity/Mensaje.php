<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'mensajes')]
class Mensaje
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'id_mensaje', type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Conversacion::class, inversedBy: 'mensajes')]
    #[ORM\JoinColumn(name: 'id_conversacion', referencedColumnName: 'id_conversacion', nullable: false, onDelete: 'CASCADE')]
    private $conversacion;

    #[ORM\Column(type: 'string', length: 20)]
    private $remitente;

    #[ORM\Column(type: 'text')]
    private $mensaje;

    #[ORM\Column(name: 'fecha_envio', type: 'datetime')]
    private $fecha_envio = [];

    #[ORM\Column(type: 'boolean')]
    private $leido = false;

    public function getId() { return $this->id; }
    public function getConversacion() { return $this->conversacion; }
    public function setConversacion(Conversacion $conversacion)
    {
        $this->conversacion = $conversacion;
        return $this;
    }
    public function getRemitente() { return $this->remitente; }
    public function setRemitente(string $remitente)
    {
        $this->remitente = $remitente;
        return $this;
    }
    public function getMensaje() { return $this->mensaje; }
    public function setMensaje(string $mensaje)
    {
        $this->mensaje = $mensaje;
        return $this;
    }
    public function getFechaEnvio() { return $this->fecha_envio; }
    public function isLeido() { return $this->leido; }
    public function setLeido(bool $leido)
    {
        $this->leido = $leido;
        return $this;
    }
}