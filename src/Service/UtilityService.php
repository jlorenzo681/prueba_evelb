<?php
/**
 * Created by PhpStorm.
 * User: Suso
 * Date: 25/05/2020
 * Time: 16:49
 */

namespace App\Service;

use App\Entity\Ciudad;

class UtilityService
{
    /**
     * UtilityService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $nombre
     * @param $pais
     * @param $provincia
     * @param $latitud
     * @param $longitud
     * @param int $temperatura
     * @return Ciudad
     */
    public function ciudadArrayToEntity($nombre, $pais, $provincia, $latitud, $longitud, $temperatura = 0): Ciudad
    {
        $nuevaCiudad = new Ciudad();

        $nuevaCiudad->setNombre($nombre);
        $nuevaCiudad->setPais($pais);
        $nuevaCiudad->setProvincia($provincia);
        $nuevaCiudad->setLatitud($latitud);
        $nuevaCiudad->setLongitud($longitud);
        $nuevaCiudad->setTemperatura($temperatura);

        return $nuevaCiudad;
    }
}