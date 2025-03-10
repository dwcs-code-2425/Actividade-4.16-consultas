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
        return $this->autorRepository->findByFechaNac($fecha);
    }

    public function getMaxVentas(){
        return $this->libroRepository->findMaxUnidades();
    }

    public function getAutoresByMinUnidadesVendidas($unidades_minimas){
        return $this->autorRepository->findByVentas($unidades_minimas);
    }
    public function getAutoresByMinUnidadesVendidas4($unidades_minimas){
        return $this->autorRepository->findByVentas4($unidades_minimas);
    }

    public function getAutoresSuperVentas(){
        return $this->autorRepository->findAutoresSuperventas();
    }

    
}
