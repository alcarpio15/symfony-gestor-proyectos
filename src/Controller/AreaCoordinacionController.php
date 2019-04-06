<?php

namespace App\Controller;

use App\Entity\AreaCoordinacion;
use App\Form\AreaCoordinacionType;
use App\Repository\AreaCoordinacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CoordinadorUpdater;

/**
 * @Route("/areas")
 */
class AreaCoordinacionController extends Controller
{
    /**
     * @Route("/", name="area_coordinacion_index", methods="GET")
     */
    public function index(AreaCoordinacionRepository $areaCoordRepo): Response
    {
        $this->denyAccessUnlessGranted('manageAreaCord', null, 'Solo un Administrador puede Acceder esta Página.');

        return $this->render('area_coordinacion/index.html.twig', ['areaCoordinaciones' => $areaCoordRepo->findAll()]);
    }

    /**
     * @Route("/new", name="area_coordinacion_new", methods="GET|POST")
     */
    public function new(Request $request, AreaCoordinacionRepository $areaCoordRepo, CoordinadorUpdater $updCoord): Response
    {
        $this->denyAccessUnlessGranted('manageAreaCord', null, 'Solo un Administrador Activo puede Acceder esta Página.');

        $prevCoord = $updCoord->getCoordinadores($areaCoordRepo);
        $areaCoordinacion = new AreaCoordinacion();
        $form = $this->createForm(AreaCoordinacionType::class, $areaCoordinacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($areaCoordinacion);
            $em->flush();

            $modCoord = $updCoord->getCoordinadores($areaCoordRepo);
            $updated = $updCoord->updateRoles($prevCoord, $modCoord);

            return $this->redirectToRoute('area_coordinacion_index');
        }

        return $this->render('area_coordinacion/new.html.twig', [
            'area_coordinacion' => $areaCoordinacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="area_coordinacion_show", methods="GET")
     */
    public function show(AreaCoordinacion $areaCoordinacion): Response
    {
        $this->denyAccessUnlessGranted('manageAreaCord', null, 'Solo un Administrador puede Acceder esta Página.');

        return $this->render('area_coordinacion/show.html.twig', ['area_coordinacion' => $areaCoordinacion]);
    }

    /**
     * @Route("/{id}/edit", name="area_coordinacion_edit", methods="GET|POST")
     */
    public function edit(Request $request, AreaCoordinacion $areaCoordinacion, AreaCoordinacionRepository $areaCoordRepo, CoordinadorUpdater $updCoord): Response
    {
        $this->denyAccessUnlessGranted('manageAreaCord', null, 'Solo un Administrador puede Acceder esta Página.');
        
        $prevCoord = $updCoord->getCoordinadores($areaCoordRepo);

        $form = $this->createForm(AreaCoordinacionType::class, $areaCoordinacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $modCoord = $updCoord->getCoordinadores($areaCoordRepo);
            $updated = $updCoord->updateRoles($prevCoord, $modCoord);

            //return $this->redirectToRoute('area_coordinacion_edit', ['id' => $areaCoordinacion->getId()]);
            return $this->redirectToRoute('area_coordinacion_index');
        }

        return $this->render('area_coordinacion/edit.html.twig', [
            'area_coordinacion' => $areaCoordinacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="area_coordinacion_delete", methods="DELETE")
     */
    public function delete(Request $request, AreaCoordinacion $areaCoordinacion, AreaCoordinacionRepository $areaCoordRepo, CoordinadorUpdater $updCoord): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$areaCoordinacion->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('area_coordinacion_index');
        }

        $prevCoord = $updCoord->getCoordinadores($areaCoordRepo);

        $em = $this->getDoctrine()->getManager();
        $em->remove($areaCoordinacion);
        $em->flush();

        $modCoord = $updCoord->getCoordinadores($areaCoordRepo);
        $updated = $updCoord->updateRoles($prevCoord, $modCoord);

        return $this->redirectToRoute('area_coordinacion_index');
    }
}
