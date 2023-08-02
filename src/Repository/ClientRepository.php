<?php

namespace App\Repository;

use App\Entity\Client;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Client>
 *
 * @method Client|null find($id, $lockMode = null, $lockVersion = null)
 * @method Client|null findOneBy(array $criteria, array $orderBy = null)
 * @method Client[]    findAll()
 * @method Client[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ClientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Client::class);
    }

//    /**
//     * @return Client[] Returns an array of Client objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Client
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findBySearch(string $recherche, string $colonne): array
{
    $queryBuilder = $this->createQueryBuilder('c');

    // Vérifier la colonne de recherche pour construire la clause WHERE de manière conditionnelle
    switch ($colonne) {
        case 'nom':
            $queryBuilder->where('c.nom LIKE :recherche');
            break;
        case 'address':
            $queryBuilder->where('c.address LIKE :recherche');
            break;
        case 'tel':
            $queryBuilder->where('c.Tel LIKE :recherche');
            break;
        case 'statut':
            $queryBuilder->where('c.statut LIKE :recherche');
            break;
        default:
            throw new \InvalidArgumentException('Colonne de recherche invalide.');
    }

    // Paramétrage du terme de recherche en utilisant le paramètre nommé "recherche"
    $queryBuilder->setParameter('recherche', '%' . $recherche . '%');

    // Exécution de la requête et récupération des résultats sous forme d'objets Client
    $result = $queryBuilder->getQuery()->getResult();

    return $result;
}
}
