<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'solicitudes_cv')]
#[ORM\UniqueConstraint(name: 'uniq_solicitud_cv', columns: ['id_usuario_solicitante', 'id_candidato'])]
class SolicitudCv
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer', name: 'id_solicitud')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Usuario::class)]
    #[ORM\JoinColumn(name: 'id_usuario_solicitante', referencedColumnName: 'id_usuario', nullable: false, onDelete: 'CASCADE')]
    private $usuarioSolicitante;

    #[ORM\ManyToOne(targetEntity: Candidato::class)]
    #[ORM\JoinColumn(name: 'id_candidato', referencedColumnName: 'id_candidato', nullable: false, onDelete: 'CASCADE')]
    private $candidato;

    #[ORM\Column(type: 'string', name: 'estado', length: 20, options: ['default' => 'pendiente'])]
    private $estado = 'pendiente';

    #[ORM\Column(type: 'text', name: 'mensaje', nullable: true)]
    private $mensaje;

    #[ORM\Column(type: 'datetime', name: 'fecha_solicitud')]
    private $fecha_solicitud;

    #[ORM\Column(type: 'datetime', name: 'fecha_respuesta', nullable: true)]
    private $fecha_respuesta;

    public function __construct()
    {
        $this->fecha_solicitud = new \DateTime();
        $this->estado = 'pendiente';
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsuarioSolicitante()
    {
        return $this->usuarioSolicitante;
    }

    public function setUsuarioSolicitante(Usuario $usuarioSolicitante)
    {
        $this->usuarioSolicitante = $usuarioSolicitante;
        return $this;
    }

    public function getCandidato()
    {
        return $this->candidato;
    }

    public function setCandidato(Candidato $candidato)
    {
        $this->candidato = $candidato;
        return $this;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function setEstado(string $estado)
    {
        $this->estado = $estado;
        return $this;
    }

    public function getMensaje()
    {
        return $this->mensaje;
    }

    public function setMensaje(?string $mensaje)
    {
        $this->mensaje = $mensaje;
        return $this;
    }

    public function getFechaSolicitud()
    {
        return $this->fecha_solicitud;
    }

    public function setFechaSolicitud(\DateTimeInterface $fecha_solicitud)
    {
        $this->fecha_solicitud = $fecha_solicitud;
        return $this;
    }

    public function getFechaRespuesta()
    {
        return $this->fecha_respuesta;
    }

    public function setFechaRespuesta(?\DateTimeInterface $fecha_respuesta)
    {
        $this->fecha_respuesta = $fecha_respuesta;
        return $this;
    }
}