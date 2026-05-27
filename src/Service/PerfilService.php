<?php

namespace App\Service;

use App\Entity\SolicitudCv;
use App\Entity\Usuario;
use App\Repository\CandidatoRepository;
use App\Repository\AnuncianteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use LogicException;

class PerfilService
{
    public function __construct(
        private CandidatoRepository $candidatoRepository,
        private AnuncianteRepository $anuncianteRepository,
        private EntityManagerInterface $em
    ) {
    }

    public function obtenerPerfil(Usuario $usuario): array
    {
        if ($usuario->getRol() === 'candidato') {
            $perfil = $usuario->getCandidato();

            if (!$perfil) {
                throw new LogicException('El candidato no tiene un perfil');
            }

            $solicitudesRecibidas = $this->em->getRepository(SolicitudCv::class)->findBy(
                ['candidato' => $perfil],
                ['fecha_solicitud' => 'DESC']
            );

            return [
                'vista' => 'perfil/miperfil_candidato.html.twig',
                'parametros' => [
                    'perfil' => $perfil,
                    'solicitudesRecibidas' => $solicitudesRecibidas
                ]
            ];
        }

        if ($usuario->getRol() === 'anunciante') {
            $perfil = $usuario->getAnunciante();

            if (!$perfil) {
                throw new LogicException('El anunciante no tiene un perfil');
            }

            return [
                'vista' => 'perfil/miperfil_anunciante.html.twig',
                'parametros' => [
                    'perfil' => $perfil,
                    'ofertas' => $perfil->getOfertas()
                ]
            ];
        }

        throw new LogicException('Rol no válido.');
    }

    public function obtenerPerfilCandidato(int $id, ?Usuario $usuario = null): array
    {
        $perfil = $this->candidatoRepository->find($id);

        if (!$perfil) {
            throw new LogicException('Candidato no encontrado');
        }

        $solicitudCv = null;

        if ($usuario && $perfil->getUsuario() !== $usuario) {
            $solicitudCv = $this->em->getRepository(SolicitudCv::class)->findOneBy([
                'usuarioSolicitante' => $usuario,
                'candidato' => $perfil
            ]);
        }

        return [
            'vista' => 'perfil/candidato.html.twig',
            'parametros' => [
                'perfil' => $perfil,
                'solicitudCv' => $solicitudCv,
                'usuarioActual' => $usuario
            ]
        ];
    }

    public function obtenerPerfilAnunciante(int $id): array
    {
        $perfil = $this->anuncianteRepository->find($id);

        if (!$perfil) {
            throw new LogicException('Anunciante no encontrado');
        }

        return [
            'vista' => 'perfil/anunciante.html.twig',
            'parametros' => [
                'perfil' => $perfil,
                'ofertas' => $perfil->getOfertas()
            ]
        ];
    }

    public function editarPerfil(Usuario $usuario, Request $request, string $projectDir): void
    {
        $tipo = $request->request->get('form_tipo');

        if ($tipo === 'foto') {
            $accion = $request->request->get('accion');

            if ($accion === 'eliminar') {
                $usuario->setFotoPerfil(null);
                $this->em->flush();
                return;
            }

            if ($accion === 'guardar') {
                $archivo = $request->files->get('nuevaFoto');

                if (!$archivo) {
                    throw new LogicException('No has seleccionado ninguna imagen.');
                }

                $mimeType = $archivo->getMimeType();
                if (!str_starts_with((string) $mimeType, 'image/')) {
                    throw new LogicException('El archivo debe de ser una imagen.');
                }

                $directorioRelativo = 'images/profiles/' . $usuario->getId();
                $directorioAbsoluto = $projectDir . '/public/' . $directorioRelativo;

                if (!is_dir($directorioAbsoluto)) {
                    mkdir($directorioAbsoluto, 0777, true);
                }

                $extension = $archivo->guessExtension() ?: 'jpg';

                $nombreUsuario = $usuario->getRol() === 'candidato'
                    ? $usuario->getCandidato()?->getNombre()
                    : $usuario->getAnunciante()?->getNombreAnunciante();
                $nombreUsuario = preg_replace('/[^A-Za-z0-9_-]/', '_', $nombreUsuario);

                $nombreArchivo = 'perfil_' . $nombreUsuario . '.' . $extension;

                $archivo->move($directorioAbsoluto, $nombreArchivo);
                $usuario->setFotoPerfil($directorioRelativo . '/' . $nombreArchivo);

                $this->em->flush();
                return;
            }
        }

        if ($tipo === 'datos') {
            if ($usuario->getRol() === 'candidato') {
                $perfil = $usuario->getCandidato();

                if (!$perfil) {
                    throw new LogicException('El candidato no tiene un perfil');
                }

                $nombre = trim((string) $request->request->get('nombre'));
                if ($nombre === '') {
                    throw new LogicException('El nombre no puede estar vacío.');
                }

                $perfil->setNombre($nombre);
                $perfil->setTelefono($request->request->get('telefono') ?: null);
                $perfil->setCiudad($request->request->get('ciudad') ?: null);
                $perfil->setTitulo($request->request->get('titulo') ?: null);
                $perfil->setDescripcion($request->request->get('descripcion') ?: null);
                $perfil->setCvPublico((bool) $request->request->get('cvPublico'));

                $cv = $request->files->get('cvPdf');

                if ($cv) {
                    $mimeType = $cv->getMimeType();
                    $extension = strtolower($cv->guessExtension() ?: '');

                    if ($mimeType != 'application/pdf' || $extension !== 'pdf') {
                        throw new LogicException('El currículum debe de ser un PDF.');
                    }

                    $directorioRelativo = 'cvs/' . $usuario->getId();
                    $directorioAbsoluto = $projectDir . '/var/' . $directorioRelativo;

                    if (!is_dir($directorioAbsoluto)) {
                        mkdir($directorioAbsoluto, 0777, true);
                    }

                    $nombreUsuario = $usuario->getCandidato()?->getNombre();
                    $nombreUsuario = preg_replace('/[^A-Za-z0-9_-]/', '_', $nombreUsuario);

                    $nombreCv = 'curriculum_' . $nombreUsuario . '.pdf';

                    $cv->move($directorioAbsoluto, $nombreCv);
                    $perfil->setCvPdf($directorioRelativo . '/' . $nombreCv);
                }
            }

            if ($usuario->getRol() === 'anunciante') {
                $perfil = $usuario->getAnunciante();

                if (!$perfil) {
                    throw new LogicException('El anunciante no tiene un perfil.');
                }

                $nombreAnunciante = trim((string) $request->request->get('nombreAnunciante'));
                if ($nombreAnunciante === '') {
                    throw new LogicException('El nombre del anunciante no puede estar vació.');
                }

                $tipoAnunciante = trim((string) $request->request->get('tipo'));
                if ($tipoAnunciante === '') {
                    throw new LogicException('El nombre del anunciante no puede estar vació.');
                }

                $perfil->setNombreAnunciante($nombreAnunciante);
                $perfil->setDescripcion($request->request->get('descripcion') ?: null);
                $perfil->setTipo($tipoAnunciante);
                $perfil->setCiudad($request->request->get('ciudad') ?: null);
                $perfil->setSitioWeb($request->request->get('sitioWeb') ?: null);
            }

            $this->em->flush();
        }
    }
}
?>