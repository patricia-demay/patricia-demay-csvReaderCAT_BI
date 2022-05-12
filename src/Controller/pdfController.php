<?php

namespace App\Controller;

use App\Repository\ValeurRepository;
use App\Service\FilterService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Include Dompdf required namespaces
use Dompdf\Dompdf;
use Dompdf\Options;

class pdfController  extends AbstractController
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
     *     name="export_pdf",
     *     path="/exportPDF/{bureau}/{projet}",
     *     methods={"GET"}
     * )
     * @param Request $request
     * @return Response
     */
    public function printPDF(Request $request)
    {
        $bureau = $request->get('bureau');
        $projet = $request->get('projet');


        $vals = $this->valeurRepository->findProjectByBureau($bureau);
        $filtredTab = $this->filterService->filtreByOneProject($vals,$projet);
        //return $this->render('projetPDF.html.twig',['project' =>  $filtredTab,'bureau' =>$bureau, 'projet' =>$projet ]);


        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $bureau = $request->get('bureau');
        $projet = $request->get('projet');


        $vals = $this->valeurRepository->findProjectByBureau($bureau);
        $filtredTab = $this->filterService->filtreByOneProject($vals,$projet);
        //return $this->render('projetPDF.html.twig',['project' =>  $filtredTab,'bureau' =>$bureau, 'projet' =>$projet ]);

        $html =  $this->renderView('projetPDF.html.twig',['project' =>  $filtredTab,'bureau' =>$bureau, 'projet' =>$projet ]);


        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => true,'compress'=>1
        ]);
       // var_dump($dompdf);
        die();

    }


}