<?php

namespace App\Service;

use App\Entity\Column;
use App\Repository\ValeurRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

class exportCSVService
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
     * Insert Column into bdd
     * @param $bureau
     * @param $polesParam
     * @return
     */
    public function getData($bureau,$polesParam,$intervenantsParam,$projetsParam )
    {

        $vals = $this->valeurRepository->findProjectByBureau($bureau);
        $filtredTab = $vals;

        if($projetsParam !== null ){

           $filtredTab = $this->filterService->filtreByProject($vals,$projetsParam);
       }
       if($polesParam !== null ){
            $filtredTab = $this->filterService->filtreByPole($vals,$polesParam);
       }
       if($intervenantsParam !== null ){
            $filtredTab = $this->filterService->filtreByIntervenant($vals,$intervenantsParam);
       }
       return array_values($filtredTab);
    }

    function generateCSV ($header,$data){
        $filename = 'reporting_'.date('Ymd').'.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");
        $file = fopen('php://output','w');
        fputcsv($file, $header);

        // Write to the csv
        foreach ($data as $key=>$line){

            fputcsv($file,$line);
        }
        // Close the file
        fclose($file);
        exit;
    }
}