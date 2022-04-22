<?php

namespace App\Controller;

use App\Repository\ColumnRepository;
use App\Repository\ValeurRepository;
use App\Service\FilterService;
use App\Service\ImportService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class getValueController extends AbstractController
{

    private $em;
    private $valeurRepository;
    private $filterService;

    public function __construct(ManagerRegistry $doctrine,ValeurRepository $valeurRepository,FilterService $filterService)
    {
        $this->em = $doctrine->getManager('default');
        $this->valeurRepository = $valeurRepository;
        $this->filterService = $filterService;

    }
    /**
     * @Route(
     *     name="get_repport",
     *     path="/bureaux",
     *     methods={"GET"}
     * )
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $val = $this->valeurRepository->findByOrderCol(0);
        //var_dump($val);
        $bureaux = $request->get('bureau');
        if($bureaux !== null ){

            $val = $this->filterService->filtreByBureau($val,$bureaux);
            return $this->render('bureau.html.twig',['values' => $val]);

        }
        return $this->render('bureau.html.twig',['values' => $val]);

    }
}