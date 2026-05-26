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
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistroService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
        private UserPasswordHasherInterface $passwordHasher,
        private UrlGeneratorInterface $urlGenerator,
    ) {
    }

    public function crearRegistroPendiente(
        RegistroPendiente $pendiente,
        string $passwordPlano,
        string $rol
    ): array {

        if ($this->em->getRepository(Usuario::class)->findOneBy(['email' => $pendiente->getEmail()])) {
            return ['error' => 'Este correo ya está registrado.', 'mensaje' => null];
        }

        $repoPendiente = $this->em->getRepository(RegistroPendiente::class);
        $existente = $repoPendiente->findOneBy(['email' => $pendiente->getEmail()]);
        if ($existente) {
            $this->em->remove($existente);
            $this->em->flush();
        }

        $token = bin2hex(random_bytes(32));

        $hash = $this->passwordHasher->hashPassword(new Usuario(), $passwordPlano);

        $pendiente->setPassword($hash);
        $pendiente->setRol($rol);
        $pendiente->setToken($token);

        try {
            $this->em->persist($pendiente);
            $this->em->flush();
        } catch (\Throwable $e) {
            return ['error' => 'No se pudo crear el registro. Inténtalo más tarde.', 'mensaje' => null];
        }

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

        if (method_exists($pendiente, 'getFechaCreacion') && $pendiente->getFechaCreacion()) {
            $expira = (clone $pendiente->getFechaCreacion())->modify('+24 hours');
            if (new \DateTime() > $expira) {
                $this->em->remove($pendiente);
                $this->em->flush();
                return ['error' => 'El enlace ha caducado. Vuelve a registrarte.', 'mensaje' => null];
            }
        }
        $this->em->beginTransaction();
        try {
            $usuario = new Usuario();
            $usuario->setEmail($pendiente->getEmail());
            $usuario->setPassword($pendiente->getPassword());
            $usuario->setRol($pendiente->getRol());

            $this->em->persist($usuario);

            if ($pendiente->getRol() === 'candidato') {
                $candidato = new Candidato();
                $candidato->setUsuario($usuario);
                $candidato->setNombre($pendiente->getNombre());
                $candidato->setTelefono($pendiente->getTelefono());
                $candidato->setCiudad($pendiente->getCiudad());
                $candidato->setCvPdf($pendiente->getCvPdf());
                $candidato->setCvPublico(false);
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
            $this->em->commit();
        } catch (\Throwable $e) {
            $this->em->rollback();
            return ['error' => 'No se pudo validar la cuenta. Inténtalo más tarde.', 'mensaje' => null];
        }

        return ['error' => null, 'mensaje' => 'Tu cuenta ha sido validada correctamente.'];
    }

    private function enviarEmailValidacion(string $email, string $token): void
    {
        $enlace = $this->urlGenerator->generate(
            'validar_registro',
            ['token' => $token],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $emailObj = (new Email())
            ->from(new Address('noreply@portalempleo.com', 'Portal Empleo'))
            ->to($email)
            ->subject('Valida tu registro')
            ->html("
                <h1>Bienvenido/a</h1>
                <p>Haz clic en el siguiente enlace para validar tu cuenta:</p>
                <a href='{$enlace}'>{$enlace}</a>
                <p>Este enlace caduca en 24 horas.</p>
            ");

        $this->mailer->send($emailObj);
    }
}