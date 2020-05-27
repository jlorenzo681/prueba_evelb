<?php

namespace App\Controller;

use App\Service\CiudadService;
use App\Service\UtilityService;
use App\Service\WebClientService;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    private $webClientService;
    private $utilityService;
    private $ciudadService;

    public function __construct(
        WebClientService $webClientService,
        UtilityService $utilityService,
        CiudadService $ciudadService)
    {
        $this->webClientService = $webClientService;
        $this->utilityService = $utilityService;
        $this->ciudadService = $ciudadService;
    }

    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $ciudades = $this->ciudadService->historico();

        return $this->render('inicio.html.twig', [
            'historicoCiudades' => $ciudades
        ]);
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

        $ciudades = $this->ciudadService->buscarCiudadasPorNombre($nombre);

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
        $request = Request::createFromGlobals();
        $id = $request->get('id');

        $ciudad = $this->ciudadService->obtenerCiudad($id);

        if ($ciudad === null) {
            throw new NoResultException;
        }

        [$center, $bbox] = $this->ciudadService->extraerCoordenadas($ciudad);

        $resultadosTemperatura = $this->webClientService->consultarTemperatura($bbox);

        $temperatura = $this->utilityService->obtenerTemperaturaMedia($resultadosTemperatura);

        $color = $this->utilityService->decidirColorMarcador($temperatura);

        return $this->render('mapa_temperatura.html.twig', [
            'temperatura' => $temperatura,
            'bbox' => $bbox,
            'lon' => $center['lon'],
            'lat' => $center['lat'],
            'color' => $color
        ]);
    }
}
