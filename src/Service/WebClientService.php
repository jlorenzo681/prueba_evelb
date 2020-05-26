<?php
/**
 * Created by PhpStorm.
 * User: Suso
 * Date: 25/05/2020
 * Time: 16:49
 */

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class WebClientService
{
    private $httpClient;

    /**
     * WebClientService constructor.
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param $nombre
     * @return Array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function buscarCiudad($nombre): Array
    {
        $url = 'http://api.geonames.org/searchJSON';
        $response = $this->httpClient->request('GET', $url, [
            'query' => [
                'q' => $nombre,
                'maxRows' => 20,
                'startRow' => 0,
                'lang' => 'en',
                'isNameRequired' => 'true',
                'style' => 'FULL',
                'username' => 'ilgeonamessample'
            ]
        ]);

        $data = $response->getContent();

        return json_decode($data, true);
    }

    /**
     * @param $bbox
     * @return Array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function consultarTemperatura($bbox): Array
    {
        $url = 'http://api.geonames.org/weatherJSON?north='
            . round($bbox['norte'], 1)
            . '&south='. round($bbox['sur'], 1)
            . '&east=' . round($bbox['este'], 1)
            . '&west=' . round($bbox['oeste'], 1)
            . '&username=ilgeonamessample';
        $response = $this->httpClient->request('GET', $url);

        $data = $response->getContent();

        return json_decode($data, true);
    }
}