<?php

namespace App\Controller;

use App\Entity\SolicitudRequerimientos;
use App\Form\SolicitudRequerimientosType;
use App\Repository\SolicitudRequerimientosRepository;
use App\Entity\SolicitudServicio;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/solicitud/requerimientos")
 */
class SolicitudRequerimientosController extends Controller
{
    /**
     * @Route("/", name="solicitud_requerimientos_index", methods="GET")
     */
    public function index(SolicitudRequerimientosRepository $solReqRepo): Response
    {
        return $this->render('solicitud_requerimientos/index.html.twig', [
            'solicitudRequerimientos' => $solReqRepo->findAllWithServiceJoin()
        ]);
    }

    /**
     * @Route("/new/{s_id}", name="solicitud_requerimientos_new", methods="GET|POST")
     * @ParamConverter("solServ", options={"id" = "s_id"})
     */
    public function new(Request $request, SolicitudServicio $solServ): Response
    {
        $solReq = new SolicitudRequerimientos();
        $solReq->setServicio($solServ);
        $form = $this->createForm(SolicitudRequerimientosType::class, $solReq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($solReq);
            $em->flush();

            return $this->redirectToRoute('solicitud_requerimientos_index');
        }

        return $this->render('solicitud_requerimientos/new.html.twig', [
            'solicitud_requerimiento' => $solReq,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_requerimientos_show", methods="GET")
     */
    public function show(SolicitudRequerimientos $solReq): Response
    {
        return $this->render('solicitud_requerimientos/show.html.twig', ['solicitud_requerimiento' => $solReq]);
    }

    /**
     * @Route("/{id}/edit", name="solicitud_requerimientos_edit", methods="GET|POST")
     */
    public function edit(Request $request, SolicitudRequerimientos $solReq): Response
    {
        $form = $this->createForm(SolicitudRequerimientosType::class, $solReq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('solicitud_requerimientos_edit', ['id' => $solReq->getId()]);
        }

        return $this->render('solicitud_requerimientos/edit.html.twig', [
            'solicitud_requerimiento' => $solReq,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_requerimientos_delete", methods="DELETE")
     */
    public function delete(Request $request, SolicitudRequerimientos $solReq): Response
    {
        /*if (!$this->isCsrfTokenValid('delete'.$solReq->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_requerimientos_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($solReq);
        $em->flush();*/

        return $this->redirectToRoute('solicitud_requerimientos_index');
    }
}
