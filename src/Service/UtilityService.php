<?php
/**
 * Created by PhpStorm.
 * User: Suso
 * Date: 25/05/2020
 * Time: 16:49
 */

namespace App\Service;

class UtilityService
{
    private const AZUL = 'rgba(100, 100, 255, 1)';
    private const VERDE = 'rgba(100, 255, 100, 1)';
    private const ROJO = 'rgba(255, 100, 100, 1)';
    private const BLANCO = 'rgba(255, 255, 255, 1)';

    /**
     * UtilityService constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $resultadosTemperatura
     * @return float|int
     */
    public function obtenerTemperaturaMedia($resultadosTemperatura)
    {
        $arrayTemperatura = [];

        foreach ($resultadosTemperatura['weatherObservations'] as $data) {
            $arrayTemperatura[] = $data['temperature'];
        }

        $temperatura = 0;
        if (!empty($arrayTemperatura)) {
            $temperatura = array_sum($arrayTemperatura) / count($arrayTemperatura);
        }
        return $temperatura;
    }

    /**
     * @param $temperatura
     * @return string
     */
    public function decidirColorMarcador($temperatura): string
    {
        switch (true) {
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
        return $color;
    }
}