<?php

namespace App\Service;

use App\Entity\RegistroPendiente;
use App\Entity\Usuario;
use App\Entity\Candidato;
use App\Entity\Anunciante;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistroService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
        private UserPasswordHasherInterface $passwordHasher
    ) {
    }

    public function crearRegistroPendienteCandidato(RegistroPendiente $pendiente, string $passwordPlano): array
    {
        $repoUsuario = $this->em->getRepository(Usuario::class);
        if ($repoUsuario->findOneBy(['email' => $pendiente->getEmail()])) {
            return ['error' => 'Este correo ya está registrado.', 'mensaje' => null];
        }

        $repoPendiente = $this->em->getRepository(RegistroPendiente::class);
        if ($repoPendiente->findOneBy(['email' => $pendiente->getEmail()])) {
            return ['error' => 'Ya existe un registro pendiente con este correo.', 'mensaje' => null];
        }

        $token = bin2hex(random_bytes(16));
        $usuarioTemp = new Usuario();
        $hash = $this->passwordHasher->hashPassword($usuarioTemp, $passwordPlano);

        $pendiente->setPassword($hash);
        $pendiente->setRol('candidato');
        $pendiente->setToken($token);

        $this->em->persist($pendiente);
        $this->em->flush();

        $this->enviarEmailValidacion($pendiente->getEmail(), $token);

        return ['error' => null, 'mensaje' => 'Revisa tu correo para validar la cuenta.'];
    }
    public function crearRegistroPendienteAnunciante(RegistroPendiente $pendiente, string $passwordPlano): array
    {
        $repoUsuario = $this->em->getRepository(Usuario::class);
        if ($repoUsuario->findOneBy(['email' => $pendiente->getEmail()])) {
            return ['error' => 'Este correo ya está registrado.', 'mensaje' => null];
        }

        $repoPendiente = $this->em->getRepository(RegistroPendiente::class);
        if ($repoPendiente->findOneBy(['email' => $pendiente->getEmail()])) {
            return ['error' => 'Ya existe un registro pendiente con este correo.', 'mensaje' => null];
        }

        $token = bin2hex(random_bytes(16));
        $usuarioTemp = new Usuario();
        $hash = $this->passwordHasher->hashPassword($usuarioTemp, $passwordPlano);

        $pendiente->setPassword($hash);
        $pendiente->setRol('anunciante');
        $pendiente->setToken($token);

        $this->em->persist($pendiente);
        $this->em->flush();

        $this->enviarEmailValidacion($pendiente->getEmail(), $token);

        return ['error' => null, 'mensaje' => 'Revisa tu correo para validar la cuenta.'];
    }

    public function validarRegistro(string $token): array
    {
        $repoPendiente = $this->em->getRepository(RegistroPendiente::class);
        $pendiente = $repoPendiente->findOneBy(['token' => $token]);

        if (!$pendiente) {
            return ['error' => 'El enlace no es válido o ya fue usado.', 'mensaje' => null];
        }

        $usuario = new Usuario();
        $usuario->setEmail($pendiente->getEmail());
        $usuario->setPassword($pendiente->getPassword());
        $usuario->setRol($pendiente->getRol());

        $this->em->persist($usuario);
        $this->em->flush();

        if ($pendiente->getRol() === 'candidato') {
            $candidato = new Candidato();
            $candidato->setUsuario($usuario);
            $candidato->setNombre($pendiente->getNombre());
            $candidato->setTelefono($pendiente->getTelefono());
            $candidato->setCiudad($pendiente->getCiudad());
            $candidato->setCvPdf($pendiente->getCvPdf());
            $this->em->persist($candidato);
        } else {
            $anunciante = new Anunciante();
            $anunciante->setUsuario($usuario);
            $anunciante->setNombreAnunciante($pendiente->getNombreAnunciante());
            $anunciante->setTipo($pendiente->getTipo());
            $anunciante->setDescripcion($pendiente->getDescripcion());
            $anunciante->setSitioWeb($pendiente->getSitioWeb());
            $anunciante->setCiudad($pendiente->getCiudad());
            $this->em->persist($anunciante);
        }

        $this->em->remove($pendiente);
        $this->em->flush();

        return ['error' => null, 'mensaje' => 'Tu cuenta ha sido validada correctamente.'];
    }

    private function enviarEmailValidacion(string $email, string $token): void
    {
        $enlace = "http://localhost:8000/validar/" . $token;

        $emailObj = (new Email())
            ->from(new Address('noreply@portalempleo.com', 'Portal Empleo'))
            ->to($email)
            ->subject('Valida tu registro')
            ->html("
                <h1>Bienvenido/a</h1>
                <p>Haz clic en el siguiente enlace para validar tu cuenta:</p>
                <a href='$enlace'>$enlace</a>
            ");

        $this->mailer->send($emailObj);
    }
}