<?php

namespace App\Controller;

use App\Repository\CiudadRepository;
use App\Service\UtilityService;
use App\Service\WebClientService;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private const AZUL = 'rgba(100, 100, 255, 1)';
    private const VERDE = 'rgba(100, 255, 100, 1)';
    private const ROJO = 'rgba(255, 100, 100, 1)';
    private const BLANCO = 'rgba(255, 255, 255, 1)';

    private $ciudadRepository;
    private $webClientService;
    private $utilityService;

    public function __construct(
        CiudadRepository $ciudadRepository,
        WebClientService $webClientService,
        UtilityService $utilityService)
    {
        $this->ciudadRepository = $ciudadRepository;
        $this->webClientService = $webClientService;
        $this->utilityService = $utilityService;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('inicio.html.twig');
    }

    /**
     * @Route("/buscarciudad", name="buscar_ciudad")
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function buscarCiudad(): Response
    {
        $request = Request::createFromGlobals();
        $nombre = $request->get('ciudad');
        $data = $this->webClientService->buscarCiudad($nombre);

        $dataCiudades = $data['geonames'];

        $ciudades = [];

        foreach ($dataCiudades as $ciudad) {

            if (isset($ciudad['bbox'])) {
                $nuevaCiudad = $this->utilityService->ciudadArrayToEntity(
                    $ciudad['name'],
                    $ciudad['countryName'],
                    $ciudad['adminName2'],
                    $ciudad['bbox'],
                    (float)$ciudad['lat'],
                    (float) $ciudad['lng'],
                    0
                );

                $this->ciudadRepository->guardarCiudad($nuevaCiudad);
                $ciudades[] = $nuevaCiudad;
            }
        }

        return $this->render('resultado_busqueda.html.twig', [
            'ciudades' => $ciudades
        ]);
    }

    /**
     * @Route("/consultartemperatura", name="consultar_temperatura")
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws NoResultException
     */
    public function consultarTemperatura(): Response
    {
        $arrayTemperatura = [];
        $request = Request::createFromGlobals();
        $id = $request->get('id');

        $ciudad = $this->ciudadRepository->find($id);

        if ($ciudad === null) {
            throw new NoResultException;
        }

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

        $data = $this->webClientService->consultarTemperatura(
            $bbox
        );

        foreach($data['weatherObservations'] as $data) {
            $arrayTemperatura[] = $data['temperature'];
        }

        $temperatura = 0;
        if (!empty($arrayTemperatura)) {
            $temperatura = array_sum($arrayTemperatura)/count($arrayTemperatura);
        }

        switch(true) {
            case $temperatura <= 10:
                $color = self::AZUL;
                break;
            case $temperatura <= 25:
                $color = self::VERDE;
                break;
            case $temperatura <= 40:
                $color = self::ROJO;
                break;
            default:
                $color = self::BLANCO;
                break;
        }

        return $this->render('mapa_temperatura.html.twig', [
            'temperatura' => $temperatura,
            'bbox' => $bbox,
            'lon' => $center['lon'],
            'lat' => $center['lat'],
            'color' => $color
        ]);
    }
}
