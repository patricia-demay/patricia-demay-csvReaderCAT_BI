<?php

namespace App\Controller;


use App\Entity\Column;
use App\Entity\Value;
use App\Form\FileType;
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
use Symfony\Component\HttpFoundation\File\Exception\FileException;

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


    public function import($filePath,$draft,$name,$dateImport): Response
    {

        $file = new File();
        //création du rapport avec la date du jour .
        $file->setDateImport($dateImport);
        $file->setName($name);
        $file->setDraft($draft);
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

    /**
     * @Route(
     *     name="display_import_page",
     *     path="/repport",
     *     methods={"GET","POST"}
     * )
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {

        $form = $this->createForm(FileType::class);

        $form->handleRequest($request);
       // dump( $form->handleRequest($request));
        if ($form->isSubmitted() && $form->isValid()){
            $draft = $form['draft']->getData();
            $dateImport = $form['dateImport']->getData();
            $name=$form['name']->getData();
            $myFile = $_FILES["file"]["tmp_name"]["myFile"];
            return $this->import($myFile,$draft,$name,$dateImport);

        }

        return $this->render('import.html.twig', [
            'form' => $form->createView()
        ]);
    }

}

