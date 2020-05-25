<?php

namespace App\Repository;

use App\Entity\Ciudad;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ciudad|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ciudad|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ciudad[]    findAll()
 * @method Ciudad[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CiudadRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Ciudad::class);

        $this->manager = $manager;
    }

    /**
     * @param Ciudad $ciudad
     */
    public function guardarCiudad(Ciudad $ciudad): void
    {
        $this->manager->persist($ciudad);
        $this->manager->flush();
    }

    public function actualizarCiudad(Ciudad $ciudad): Ciudad
    {
        $this->manager->persist($ciudad);
        $this->manager->flush();

        return $ciudad;
    }


    public function eliminarCiudad(Ciudad $ciudad): void
    {
        $this->manager->remove($ciudad);
        $this->manager->flush();
    }
}
