<?php

namespace App\Service;

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
    ) {}

    public function obtenerPerfil(Usuario $usuario): array
    {
        if ($usuario->getRol() === 'candidato') {
            $perfil = $usuario->getCandidato();

            if (!$perfil) {
                throw new LogicException('El candidato no tiene un perfil');
            }

            return [
                'vista' => 'perfil/miperfil_candidato.html.twig',
                'parametros' => ['perfil' => $perfil]
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

    public function obtenerPerfilCandidato(int $id): array
    {
        $perfil = $this->candidatoRepository->find($id);

        if (!$perfil) {
            throw new LogicException('Candidato no encontrado');
        }

        return [
            'vista' => 'perfil/candidato.html.twig',
            'parametros' => ['perfil' => $perfil]
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

                $directorioRelativo = 'images/profiles/' . $usuario->getId();
                $directorioAbsoluto = $projectDir . '/public/' . $directorioRelativo;

                if (!is_dir($directorioAbsoluto)) {
                    mkdir($directorioAbsoluto, 0777, true);
                }
            }
        }
    }
}
?>