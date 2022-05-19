<?php

namespace App\Controller;

use App\Service\exportCSVService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class exportController extends AbstractController
{
    private $exportCSVService;

    public function __construct(exportCSVService $exportCSVService)
    {
        $this->exportCSVService = $exportCSVService;
    }
    /**
     * @Route(
     *     name="export_csv",
     *     path="/exportCSV/{bureau}",
     *     methods={"GET"}
     * )
     * @param Request $request
     * @return Response
     */
    public function exportCSV(Request $request)
    {
        $bureau     = $request->get('bureau');
        $polesParam = $request->get('poles');
        $intervenantsParam = $request->get('intervenants');
        $projetsParam = $request->get('projets');
        $filtredTab = $this->exportCSVService->getData($bureau ,$polesParam,$intervenantsParam,$projetsParam);
        $csvValues = array();

        $csvHeader = array_keys($filtredTab[0]);

        foreach ($filtredTab as $ligne){
            array_push($csvValues,$ligne);
        }
        $this->exportCSVService->generateCSV($csvHeader,$csvValues);
    }

}