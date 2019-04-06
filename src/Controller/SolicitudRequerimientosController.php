<?php

namespace App\Controller;

use App\Entity\SolicitudRequerimientos;
use App\Form\SolicitudRequerimientosType;
use App\Repository\SolicitudRequerimientosRepository;
use App\Entity\SolicitudServicio;
use App\Service\SolEstadoUpdater;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * Controlador para el Manejo de Solicitudes de Requerimientos. 
 *
 * @Route("/solicitud/requerimientos")
 */
class SolicitudRequerimientosController extends Controller
{
    /**
     * Metodo Indice:
     * Genera una Página de Indice Central para administrar las Solicitudes de Requerimientos Creadas.
     *
     * El Rol del Usuario determina cuantas Solicitudes pueden ser Mostradas:
     *  - Coordinadores de Áreas solo pueden ver Solicitudes de Requerimientos dirigidas a dichas Áreas.
     *  - Coordinadores Generales y Roles Superiores a ellos pueden ver todas las Solicitudes creadas.
     *  - Otros Roles no deberían tener acceso a la Página en primer lugar.
     *
     * @Route("/", name="solicitud_requerimientos_index", methods="GET")
     */
    public function index(SolicitudRequerimientosRepository $solReqRepo, UserInterface $user, AuthorizationCheckerInterface $authChecker): Response
    {
        $this->denyAccessUnlessGranted('manageSolReq', null, 'Solo Coordinadores Activos y sus Superiores pueden ver esta página.');

        if ($authChecker->isGranted('ROLE_CORDGN')){
            return $this->render('solicitud_requerimientos/index.html.twig', [
                'solicitud_requerimientos' => $solReqRepo->findAllWithServiceJoin()
            ]);
        }
        else{
            return $this->render('solicitud_requerimientos/index.html.twig', [
                'solicitud_requerimientos' => $solReqRepo->findAllByAreaCoordinatorWithJoins($user->getId())
            ]);
        }
        
    }

    /**
     * @Route("/new/{s_id}", name="solicitud_requerimientos_new", methods="GET|POST")
     * @ParamConverter("solServ", options={"id" = "s_id"})
     */
    public function new(Request $request, SolicitudServicio $solServ, SolEstadoUpdater $updStatus): Response
    {
        $this->denyAccessUnlessGranted('createSolReq', $solServ, 'La Solicitud solo puede ser creada por un Director Activo.');

        $solReq = new SolicitudRequerimientos();
        $solReq->setServicio($solServ);
        $form = $this->createForm(SolicitudRequerimientosType::class, $solReq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($solReq);
            $em->flush();

            //$updStatus->aceptarServicio($solServ);

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
        $this->denyAccessUnlessGranted('viewSolReq', $solReq, 'Solo el Coordinador del Area Correspondiente y sus Superiores pueden ver esta Solicitud.');

        return $this->render('solicitud_requerimientos/show.html.twig', ['solicitud_requerimiento' => $solReq]);
    }

    /**
     * @Route("/{id}/edit", name="solicitud_requerimientos_edit", methods="GET|POST")
     */
    public function edit(Request $request, SolicitudRequerimientos $solReq): Response
    {
        $this->denyAccessUnlessGranted('editSolReq', $solReq, 'La Solicitud solo puede ser editada por un Coordinador General Activo.');

        $form = $this->createForm(SolicitudRequerimientosType::class, $solReq);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            //return $this->redirectToRoute('solicitud_requerimientos_edit', ['id' => $solReq->getId()]);
            return $this->redirectToRoute('solicitud_requerimientos_index');
        }

        return $this->render('solicitud_requerimientos/edit.html.twig', [
            'solicitud_requerimiento' => $solReq,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/complete", name="solicitud_requerimientos_complete", methods="GET|POST")
     */
    public function complete(Request $request, SolicitudRequerimientos $solReq, SolEstadoUpdater $updStatus): Response
    {
        if (!$this->isCsrfTokenValid('complete'.$solReq->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_requerimientos_index');
        }

        //$updStatus->completarRequerimientos($solReq);

        return $this->redirectToRoute('solicitud_requerimientos_index');
    }

    /**
     * @Route("/{id}/cancel", name="solicitud_requerimientos_cancel", methods="GET|POST")
     */
    public function cancel(Request $request, SolicitudRequerimientos $solReq): Response
    {
        
        if (!$this->isCsrfTokenValid('cancel'.$solReq->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_requerimientos_index');
        }

        //$updStatus->cancelarRequerimientos($solReq);

        return $this->redirectToRoute('solicitud_requerimientos_index');
    }

    /**
     * @Route("/{id}", name="solicitud_requerimientos_delete", methods="DELETE")
     */
    public function delete(Request $request, SolicitudRequerimientos $solReq): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$solReq->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_requerimientos_index');
        }

        /*$em = $this->getDoctrine()->getManager();
        $em->remove($solReq);
        $em->flush();*/

        return $this->redirectToRoute('solicitud_requerimientos_index');
    }
}
