<?php
/**
 * Created by PhpStorm.
 * User: Suso
 * Date: 25/05/2020
 * Time: 16:49
 */

namespace App\Service;

use App\Entity\Ciudad;
use App\Repository\CiudadRepository;

class CiudadService
{
    private $ciudadRepository;
    private $webClientService;

    /**
     * UtilityService constructor.
     * @param CiudadRepository $ciudadRepository
     * @param WebClientService $webClientService
     */
    public function __construct(
        CiudadRepository $ciudadRepository,
        WebClientService $webClientService
)
    {
        $this->ciudadRepository = $ciudadRepository;
        $this->webClientService = $webClientService;
    }

    /**
     * @param $nombre
     * @param $pais
     * @param $provincia
     * @param $bbox
     * @param $latitud
     * @param $longitud
     * @param int $temperatura
     * @return Ciudad
     */
    public function ciudadArrayToEntity($nombre, $pais, $provincia, $bbox, $latitud, $longitud, $temperatura = 0): Ciudad
    {
        $nuevaCiudad = new Ciudad();

        $nuevaCiudad->setNombre($nombre);
        $nuevaCiudad->setPais($pais);
        $nuevaCiudad->setProvincia($provincia);
        $nuevaCiudad->setNorte((double) $bbox['north']);
        $nuevaCiudad->setSur((double) $bbox['south']);
        $nuevaCiudad->setEste((double) $bbox['east']);
        $nuevaCiudad->setOeste((double) $bbox['west']);
        $nuevaCiudad->setLatitud($latitud);
        $nuevaCiudad->setLongitud($longitud);
        $nuevaCiudad->setTemperatura($temperatura);

        return $nuevaCiudad;
    }

    /**
     * @param $id
     * @return \App\Entity\Ciudad|null
     */
    public function obtenerCiudad($id): ?Ciudad
    {
        return $this->ciudadRepository->find($id);
    }

    /**
     * @param $nombre
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function buscarCiudadasPorNombre($nombre): array
    {
        $data = $this->webClientService->buscarCiudad($nombre);

        $dataCiudades = $data['geonames'];

        $ciudades = [];

        foreach ($dataCiudades as $ciudad) {
            if (isset($ciudad['bbox'])) {
                $nuevaCiudad = $this->ciudadArrayToEntity(
                    $ciudad['name'],
                    $ciudad['countryName'],
                    $ciudad['adminName2'],
                    $ciudad['bbox'],
                    (float)$ciudad['lat'],
                    (float)$ciudad['lng'],
                    0
                );

                $this->ciudadRepository->guardarCiudad($nuevaCiudad);
                $ciudades[] = $nuevaCiudad;
            }
        }
        return $ciudades;
    }

    /**
     * @param Ciudad $ciudad
     * @return array
     */
    public function extraerCoordenadas($ciudad): array
    {
        $center = [
            'lat' => ($ciudad->getLatitud()),
            'lon' => ($ciudad->getLongitud())
        ];

        $bbox = [
            'norte' => $ciudad->getNorte(),
            'sur' => $ciudad->getSur(),
            'este' => $ciudad->getEste(),
            'oeste' => $ciudad->getOeste()
        ];
        return array($center, $bbox);
    }
}