<?php

namespace App\Controller;


use App\Entity\Column;
use App\Entity\Value;
use App\Repository\ColumnRepository;
use App\Service\ImportService;
use Doctrine\Persistence\ManagerRegistry;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\File;

class ImportController extends AbstractController
{

    private $importService;
    private $em;
    private $rep;

    public function __construct(ManagerRegistry $doctrine,ImportService $importService,ColumnRepository $columnRepository)
    {
        $this->em = $doctrine->getManager('default');
        $this->rep = $columnRepository;
        $this->importService = $importService;
    }

    /**
     * @Route(
     *     name="post_repport",
     *     path="/repport/upload",
     *     methods={"POST"}
     * )
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {

        $filePath = $request->files->get('myFile')->getRealPath();
//        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filePath);
//        $nbSheet = $spreadsheet->getSheetCount();
//        //On parcours l'ensemble des onglets jusqu'à trouver le bon ou arriver à la fin
//        for ($i=0; $i<$nbSheet; $i++) {
//            $activeSheet = $spreadsheet->getSheet($i);
//            $activeSheetArray = $activeSheet->toArray();
//
//            $file = new File();
//            //création du rapport avec la date du jour .
//            $dateOfToday = date("Ymd");
//            $file->setDateImport(new \DateTime());
//            $file->setName('repport'.$dateOfToday);
//            $file->setDraft(true);
//            $this->em->persist((object)$file);
//            //$data = array_map("utf8_encode", $activeSheetArray); //added
//            var_dump($activeSheetArray);
//            for ($j=1; $j<count($activeSheetArray); $j++) {
//            //parcours de ligne par ligne
//            $entry = $this->importService->addEntry($j,$file);
//
//                for ($z=0;$z<count($activeSheetArray[$j]); $z++){
//                    //recuperation des entetes des colonnes
//                    $this->importService->addColumn($activeSheetArray[0][$z],$z);
//                    // recuperation des valeurs
//                    $this->importService->addValue($activeSheetArray[$j][$z],$this->rep->findOneBy(array('ordre' => $z)),$entry);
//                }
//
//            }
//            //mise a jour du rapport .
//            //$this->em->refresh($file);
//            //$this->em->flush();
//        }

        // $fp is file pointer to file sample.csv
        if(!file_exists($filePath) || !is_readable($filePath)) {
            return FALSE;
        }

        $file = new File();
        //création du rapport avec la date du jour .
        $dateOfToday = date("Ymd");
        $file->setDateImport(new \DateTime());
        $file->setName('repportTest');
        $file->setDraft(true);
        $this->em->persist((object)$file);
        $handle = fopen($filePath, "r");

        $data = fgetcsv($handle,2500,';');
        for ($j=0; $j<count($data); $j++) {
            // recuperation les entetes des colonnes
            $this->importService->addColumn(utf8_encode($data[$j]),$j);
        }
        $numLigne=1;
        while (($data = fgetcsv($handle,2500,';')) !== FALSE) {
            //parcours de ligne par ligne
            $entry = $this->importService->addEntry($numLigne,$file);
            for ($j=0; $j<count($data); $j++) {
                // recuperation des valeurs
               $this->importService->addValue(utf8_encode($data[$j]),$this->rep->findOneBy(array('ordre' => $j)),$entry);
            }
            $numLigne++ ;
        }
        fclose($handle);
        //mise a jour du rapport .
        $this->em->refresh($file);
        $this->em->flush();
        return new Response('done') ;
    }
}

