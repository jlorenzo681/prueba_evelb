<?php

namespace App\Controller;

use App\Repository\CiudadRepository;
use App\Service\UtilityService;
use App\Service\WebClientService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
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

        foreach($dataCiudades as $ciudad) {
            $nuevaCiudad = $this->utilityService->ciudadArrayToEntity(
                $ciudad['name'],
                $ciudad['countryName'],
                $ciudad['adminName2'],
                $ciudad['lat'],
                $ciudad['lng'],
                0
            );

            $this->ciudadRepository->guardarCiudad($nuevaCiudad);
            $ciudades[] = $nuevaCiudad;
        }

        return $this->render('resultado_busqueda.html.twig', [
            'ciudades' => $ciudades
        ]);
    }
}
