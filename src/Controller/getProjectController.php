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

class getProjectController extends AbstractController
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
     *     name="get_project",
     *     path="/project",
     *     methods={"GET"}
     * )
     * @param Request $request
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $name ="Nom du projet" ;
        $pjcts = array("AAI - DECOMMISSIONNEMENT","ACAP v2 FQE");
        $vals = $this->valeurRepository->findProjectBy($pjcts,$name);


        $response = new Response(json_encode($vals));
        $response->headers->set('Content-Type', 'application/json');
        //var_dump($response->getContent());
        return $response;


    }
    /**
     * @Route(
     *     name="get_projects_by_bureau",
     *     path="/bureaux/{bureau}",
     *     methods={"GET"},
     *     requirements={"bureau"=".+"}
     * )
     * @param Request $request
     * @return Response
     */
    public function getProject(Request $request): Response
    {

        $bureau               = $request->get('bureau');
        $projets              = $request->get('projets');
        $polesParam           = $request->get('poles');
        $intervenantsParam    = $request->get('intervenants');
        $vals                 = $this->valeurRepository->findProjectByBureau($bureau);
        $poles                = $this->filterService->extractColValue($vals,'Pole');
        $poles                = $this->filterService->dedoublonneTab($poles);
        $intervenats          = $this->filterService->extractColValue($vals,'IntervenantsCAT');
        $intervenats          = $this->filterService->extract($intervenats);
        $intervenats          = $this->filterService->dedoublonneTab($intervenats);
        $projects             = $this->filterService->extractColValue($vals,'Nomduprojet');
        $projects             = $this->filterService->dedoublonneTab($projects);
        if($projets !== null ){
            $filtredTab = $this->filterService->filtreByProject($vals,$projets);
            return $this->render('project.html.twig',
               ['values'      => $filtredTab,
                'bureau'      => $bureau,
                'projets'     => $projets,
                'projects'    => $projects,
                'poles'       => $poles,
                'intervenats' => $intervenats]);

        }
        if($polesParam !== null ){
            $filtredTab = $this->filterService->filtreByPole($vals,$polesParam);
            return $this->render('project.html.twig',
                ['values'      => $filtredTab,
                 'bureau'      => $bureau,
                 'projets'     => $projets,
                 'projects'    => $projects,
                 'poles'       => $poles,
                 'intervenats' => $intervenats]);

        }
        if($intervenantsParam !== null ){
            $filtredTab = $this->filterService->filtreByIntervenant($vals,$intervenantsParam);
            return $this->render('project.html.twig',
                ['values'      => $filtredTab,
                    'bureau'      => $bureau,
                    'projets'     => $projets,
                    'projects'    => $projects,
                    'poles'       => $poles,
                    'intervenats' => $intervenats]);

        }
        $response = new Response(json_encode($vals));
        $response->headers->set('Content-Type', 'application/json');
        return $this->render('project.html.twig',
            ['values'      => $vals,
             'bureau'      => $bureau,
             'poles'       => $poles,
             'projects'    => $projects,
             'projets'     => $projets,
             'intervenats' => $intervenats]);

    }


    /**
     * @Route(
     *     name="detail_project",
     *     path="/bureaux/{bureau}/Projets/{projet}",
     *     methods={"GET"}
     * )
     * @param Request $request
     * @return Response
     */
    public function detailProject(Request $request): Response
    {
        $bureau = $request->get('bureau');
        $projet = $request->get('projet');

        $vals = $this->valeurRepository->findProjectByBureau($bureau);
        $filtredTab = $this->filterService->filtreByOneProject($vals,$projet);
        $response = new Response(json_encode($filtredTab));
        $response->headers->set('Content-Type', 'application/json');
        return $this->render('detailProject.html.twig',['project' =>  $filtredTab,'bureau' =>$bureau ]);

    }
}