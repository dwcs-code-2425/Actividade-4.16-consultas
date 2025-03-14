<?php
namespace App\Service;

use App\Repository\AutorRepository;
use App\Repository\LibroRepository;
use DateTime;

class ConsultasService
{

    public function __construct(private AutorRepository $autorRepository,
     private LibroRepository $libroRepository)
    {
    }

    public function getAutoresByFechaNac(DateTime $fecha){
        return $this->autorRepository->findByFechaNacQB($fecha);
    }

    public function getMaxVentas(){
        return $this->libroRepository->findMaxUnidadesQB();
    }

    public function getAutoresByMinUnidadesVendidas($unidades_minimas){
        return $this->autorRepository->findByVentasQB($unidades_minimas);
    }
    public function getAutoresByMinUnidadesVendidas4($unidades_minimas){
        return $this->autorRepository->findByVentas4QB($unidades_minimas);
    }

    public function getAutoresSuperVentas(){
        return $this->autorRepository->findAutoresSuperventas();
    }

    
}
