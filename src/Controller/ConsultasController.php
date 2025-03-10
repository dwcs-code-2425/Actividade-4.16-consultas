<?php

namespace App\Controller;

use App\Repository\AutorRepository;
use App\Service\ConsultasService;
use DateTime;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ConsultasController extends AbstractController
{

    public function __construct(private ConsultasService $consultasService) {
        
    }

    #[Route('/consultas/{fecha}', name: 'app_consultas')]
    public function index(DateTime $fecha ):Response
    {

        $autores =$this->consultasService->getAutoresByFechaNac($fecha);

        return $this->render('consultas/index.html.twig', [
            'controller_name' => 'ConsultasController',
            'autores' => $autores
        ]);
    }


    #[Route('/consultas/maxVentas', name: 'app_consultas_maxVentas', priority: 100)]
    public function getMaxUnidades( ):Response
    {

        $maxVentas = $this->consultasService->getMaxVentas();

        return $this->render('consultas/index.html.twig', [
            'controller_name' => 'ConsultasController',
            'maxVentas' => $maxVentas
        ]);
    }


    #[Route('/consultas/autores/{ventas<\d+>}', name: 'app_consultas_autores_min_unidades')]
    public function getAutoresByMinUnidadesVentas(int $ventas ):Response
    {
        $autores = $this->consultasService->getAutoresByMinUnidadesVendidas($ventas);

        return $this->render('consultas/index.html.twig', [
            'controller_name' => 'ConsultasController',
            'autoresVentas' => $autores
        ]);
    }

    #[Route('/consultas/autores4/{ventas<\d+>}', name: 'app_consultas_autores_min_unidades_sum_unidades')]
    public function getAutoresByMinUnidadesVentas4(int $ventas ):Response
    {
        $autores = $this->consultasService->getAutoresByMinUnidadesVendidas4($ventas);

        return $this->render('consultas/index.html.twig', [
            'controller_name' => 'ConsultasController',
            'autoresVentasSuma' => $autores
        ]);
    }

    #[Route('/consultas/autores/superventas', name: 'app_consultas_autores_superventas', priority:2)]
    public function getAutoresSuperVentas():Response
    {
        $autores = $this->consultasService->getAutoresSuperVentas();

        return $this->render('consultas/index.html.twig', [
            'controller_name' => 'ConsultasController',
            'autoresSuperVentas' => $autores
        ]);
    }

}
