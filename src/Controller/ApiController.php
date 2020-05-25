<?php

namespace App\Controller;

use App\Repository\CiudadRepository;
use App\Service\UtilityService;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class ApiController
{
    private const NOMBRE = 'nombre';
    private const PAIS = 'pais';
    private const LONGITUD = 'longitud';
    private const LATITUD = 'latitud';
    private const TEMPERATURA = 'temperatura';
    private const PROVINCIA = 'provincia';
    private $ciudadRepository;
    private $utilityService;

    public function __construct(CiudadRepository $ciudadRepository, UtilityService $utilityService)
    {
        $this->ciudadRepository = $ciudadRepository;
        $this->utilityService = $utilityService;
    }

    /**
     * @Route("/api", name="index")
     */
    public function index(): JsonResponse
    {
        return new JsonResponse([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiController.php',
        ], Response::HTTP_OK);

    }

    /**
     * @Route("/api/ciudad/guardar", name="guardar_ciudad", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $nombre = $data[self::NOMBRE];
        $pais = $data[self::PAIS];
        $provincia = $data[self::PROVINCIA];
        $latitud = $data[self::LATITUD];
        $longitud = $data[self::LONGITUD];
        $temperatura = 0;

        if (empty($nombre) || empty($pais) || empty($provincia) || empty($latitud) || empty($longitud)) {
            throw new NotFoundHttpException('Los parametros son obligatorios');
        }

        $nuevaCiudad = $this->utilityService->ciudadArrayToEntity(
            $nombre,
            $pais,
            $provincia,
            $latitud,
            $longitud,
            $temperatura
        );

        $this->ciudadRepository->guardarCiudad($nuevaCiudad);

        return new JsonResponse(['status' => 'Coche guardado'], Response::HTTP_CREATED);
    }

    /**
     * @Route("api/ciudad/{id}", name="obtener_ciudad", methods={"GET"})
     * @param $id
     * @return JsonResponse
     * @throws NoResultException
     */
    public function get($id): JsonResponse
    {
        if ($id === null) {
            throw new MissingMandatoryParametersException('El parametro id es obligatorio');
        }

        $ciudad = $this->ciudadRepository->findOneBy(['id' => $id]);

        if ($ciudad === null) {
            throw new NoResultException;
        }

        $data = [
            'id' => $ciudad->getId(),
            self::NOMBRE => $ciudad->getNombre()
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("api/ciudades", name="obtener_ciudades", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $ciudads = $this->ciudadRepository->findAll();
        $data = [];

        foreach ($ciudads as $ciudad) {
            $data[] = [
                'id' => $ciudad->getId(),
                self::NOMBRE => $ciudad->getNombre(),
                self::PAIS => $ciudad->getPais(),
                self::TEMPERATURA => $ciudad->getTemperatura()
            ];
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }

    /**
     * @Route("api/ciudad/actualizar/{id}", name="actualizar_ciudad", methods={"PUT"})
     * @param $id
     * @param Request $request
     * @return JsonResponse
     * @throws NoResultException
     */
    public function update($id, Request $request): JsonResponse
    {
        $ciudad = $this->ciudadRepository->findOneBy(['id' => $id]);

        if ($ciudad === null) {
            throw new NoResultException();
        }

        $data = json_decode($request->getContent(), true);

        empty($data[self::NOMBRE]) ?: $ciudad->setNombre($data[self::NOMBRE]);

        $this->ciudadRepository->actualizarCiudad($ciudad);

        return new JsonResponse(['status' => 'Ciudad actualizada'], Response::HTTP_OK);
    }

    /**
     * @Route("api/ciudad/eliminar/{id}", name="eliminar_ciudad", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     * @throws NoResultException
     */
    public function delete($id): JsonResponse
    {
        $ciudad = $this->ciudadRepository->findOneBy(['id' => $id]);

        if ($ciudad === null) {
            throw new NoResultException();
        }

        $this->ciudadRepository->eliminarCiudad($ciudad);

        return new JsonResponse(['status' => 'Ciudad eliminada'], Response::HTTP_OK);
    }
}
