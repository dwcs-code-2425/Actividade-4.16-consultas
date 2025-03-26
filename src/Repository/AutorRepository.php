<?php

namespace App\Repository;

use App\Entity\Autor;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Autor>
 *
 * @method Autor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Autor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Autor[]    findAll()
 * @method Autor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AutorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, private EntityManagerInterface $em)
    {
        parent::__construct($registry, Autor::class);
    }

    public function findByFechaNac(DateTime $fechaNac): array
    {

        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT a FROM App\Entity\Autor a WHERE a.fechaNacimiento >= :fechaNac order by a.fechaNacimiento");
        return $query->setParameter("fechaNac", $fechaNac)->getResult();

    }

    public function findByFechaNacQB(DateTime $fechaNac): array
    {

        $query = $this->createQueryBuilder("a")
            ->where("a.fechaNacimiento >= :fechaNac")
            ->orderBy("a.fechaNacimiento")
            ->setParameter("fechaNac", $fechaNac)
            ->getQuery();

        return $query->getResult();


    }

    public function findByVentas(int $ventas): array
    {

        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT a FROM App\Entity\Autor a join a.libros l WHERE l.unidadesVendidas > ?1");
        return $query->setParameter(1, $ventas)->getResult();

    }

    public function findByVentasQB(int $ventas): array
    {

        return $this->createQueryBuilder("a")
            //->addSelect("li")
            //En esta consulta no es necesario cargar los libros en la propiedad
            //libros de autor, pero se podrían cargar usando addSelect
            ->join("a.libros", "li")
            ->where("li.unidadesVendidas > ?1")
            ->setParameter(1, $ventas)
            ->getQuery()
            ->getResult();

    }



    public function findByVentas4(int $ventas): array
    {

        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT a, sum(l.unidadesVendidas) FROM App\Entity\Autor a join a.libros l WHERE l.unidadesVendidas > ?1 group by a.id");
        return $query->setParameter(1, $ventas)->getResult();

    }


    public function findByVentas4QB(int $ventas): array
    {

        return $this->createQueryBuilder("a")
            ->addSelect("sum(li.unidadesVendidas)")
            ->join("a.libros", "li")
            ->where("li.unidadesVendidas > ?1")
            ->setParameter(1, $ventas)
            ->groupBy("a.id")
            ->getQuery()
            ->getArrayResult();

    }


    public function findAutoresSuperventas(): array
    {

        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT a FROM App\Entity\Autor a join a.libros l WHERE l.unidadesVendidas = (select max(l2.unidadesVendidas) from App\Entity\Libro l2 )");
        return $query->getResult();

    }

    public function findAutoresSuperventasQB(
    
    ): array
    {




        // Subconsulta para obtener el máximo de unidadesVendidas de la entidad Libro
        //Como estamos en AutorRepository, tenemos que indicar en el from que queremos obtener de la 
        //entidad libro. No podemos usar el método $this->createQueryBuilder() del repositorio, pero sí el de
        //EntityManagerInterface
        $subquery = $this->em->createQueryBuilder() 
            ->select("MAX(l_max.unidadesVendidas)")
            ->from('App\Entity\Libro', 'l_max'); // Alias diferente

        // Consulta principal
        $qb = $this->createQueryBuilder('a')
            ->addSelect("li")
            ->innerJoin('a.libros', 'li') // Relación con libros
            ->andWhere("li.unidadesVendidas = (" . $subquery->getDQL() . ")") // Se usa la subconsulta
            ->getQuery();

        return $qb->getResult();
    }


    //    /**
    //     * @return Autor[] Returns an array of Autor objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Autor
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
