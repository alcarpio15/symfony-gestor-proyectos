<?php

namespace App\Controller;

use App\Entity\SolicitudTarea;
use App\Form\SolicitudTareaType;
use App\Repository\SolicitudTareaRepository;
use App\Entity\SolicitudRequerimientos;
use App\Entity\HistorialTarea;
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
 * Controlador para el Manejo de Solicitudes de Tareas. 
 *
 * @Route("/solicitud/tarea")
 */
class SolicitudTareaController extends Controller
{
    /**
     * Metodo Indice:
     * Genera una Página de Indice Central para administrar las Solicitudes de Tareas Creadas.
     *
     * El Rol del Usuario determina cuantas Solicitudes pueden ser Mostradas:
     *  - Desarrolladores solo pueden ver Solicitudes asignadas a ellos mismos.
     *  - Coordinadores de Áreas solo pueden ver Solicitudes creadas para Requerimientos dirigidos a dichas Áreas.
     *  - Coordinadores Generales y Roles Superiores a ellos pueden ver todas las Solicitudes creadas.
     *  - Otros Roles no deberían tener acceso a la Página en primer lugar.
     *
     * @Route("/", name="solicitud_tarea_index", methods="GET")
     */
    public function index(SolicitudTareaRepository $solTaskRepo, UserInterface $user, AuthorizationCheckerInterface $authChecker): Response
    {
        $this->denyAccessUnlessGranted('manageSolTask', null, 'Solo Desarrolladores Activos y sus Superiores pueden ver esta página.');

        if ($authChecker->isGranted('ROLE_DEVLPR')) {
            return $this->render('solicitud_tarea/index.html.twig', ['solicitud_tareas' => $solTaskRepo->findByUserWithRequerimentJoin($user->getId())]);
        }
        elseif ($authChecker->isGranted('ROLE_CORDAR')) {
            return $this->render('solicitud_tarea/index.html.twig', ['solicitud_tareas' => $solTaskRepo->findAllByAreaCoordinatorWithJoins($user->getId())]);
        }
        else{
            return $this->render('solicitud_tarea/index.html.twig', ['solicitud_tareas' => $solTaskRepo->findAllWithRequerimentJoin()]);
        }
        
    }

    /**
     * Método Crear Nueva Solicitud Tarea:
     * Gestiona el Proceso de crear una nueva Solicitud de Tarea.
     * Acceso Restringido a Coordinadores de Area o Roles Superiores.
     * 
     * 
     * Genera una Página con un Formulario donde el Usuario 
     *
     * @Route("/new/{r_id}", name="solicitud_tarea_new", methods="GET|POST")
     * @ParamConverter("solReq", options={"id" = "r_id"})
     */
    public function new(Request $request, SolicitudRequerimientos $solReq, SolEstadoUpdater $updStatus): Response
    {
        $this->denyAccessUnlessGranted('createSolTask', $solReq, 'Solo Desarrolladores Activos y sus Superiores pueden ver esta página.');

        $solTask = new SolicitudTarea();
        $solTask->setRequerimiento($solReq);
        $form = $this->createForm(SolicitudTareaType::class, $solTask, array(
            'area' => $solReq->getArea()->getId()
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $historyEntry = new HistorialTarea();
            $historyEntry->setEstado(0);
            $historyEntry->setDescripcion("Tarea Creada.");

            $solTask->addHistorial($historyEntry);

            $em = $this->getDoctrine()->getManager();
            $em->persist($solTask);
            $em->flush();

            $justAccepted = $updStatus->aceptarRequerimientos($solReq);

            return $this->redirectToRoute('solicitud_tarea_index');
        }

        return $this->render('solicitud_tarea/new.html.twig', [
            'solicitud_tarea' => $solTask,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_tarea_show", methods="GET")
     */
    public function show(SolicitudTarea $solTask): Response
    {
        return $this->render('solicitud_tarea/show.html.twig', ['solicitud_tarea' => $solTask]);
    }

    /**
     * @Route("/{id}/edit", name="solicitud_tarea_edit", methods="GET|POST")
     */
    public function edit(Request $request, SolicitudTarea $solTask, SolEstadoUpdater $updStatus): Response
    {
        $prevDev = $solTask->getDesarrollador();

        $form = $this->createForm(SolicitudTareaType::class, $solTask, array(
            'area' => $solTask->getRequerimiento()->getArea()->getId()
        ));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newDev = $form->get('desarrollador')->getData();

            if ($prevDev->getId() != $newDev->getId()) {
                //$updStatus->reasignarTarea($solTask);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('solicitud_tarea_edit', ['id' => $solTask->getId()]);
        }

        return $this->render('solicitud_tarea/edit.html.twig', [
            'solicitud_tarea' => $solTask,
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/{id}/deliver", name="solicitud_tarea_deliver", methods="GET|POST")
     */
    public function deliver(Request $request, SolicitudTarea $solTask, SolEstadoUpdater $updStatus): Response
    {
        if (!$this->isCsrfTokenValid('deliver'.$solTask->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_tarea_index');
        }

        //$updStatus->entregarTarea($solTask);
        

        return $this->redirectToRoute('solicitud_tarea_index');
    }
    

    /**
     * @Route("/{id}/complete", name="solicitud_tarea_complete", methods="GET|POST")
     */
    public function complete(Request $request, SolicitudTarea $solTask, SolEstadoUpdater $updStatus): Response
    {
        if (!$this->isCsrfTokenValid('complete'.$solTask->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_tarea_index');
        }

        $updStatus->completarTarea($solTask);
        

        return $this->redirectToRoute('solicitud_tarea_index');
    }

    /**
     * @Route("/{id}/cancel", name="solicitud_tarea_cancel", methods="GET|POST")
     *
     * 
     */
    public function cancel(Request $request, SolicitudTarea $solTask, SolEstadoUpdater $updStatus): Response
    {
        if (!$this->isCsrfTokenValid('cancel'.$solTask->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_tarea_index');
        }

        $updStatus->cancelarTarea($solTask);
        

        return $this->redirectToRoute('solicitud_tarea_index');
    }

    /**
     * @Route("/{id}/justify", name="solicitud_tarea_justify", methods="GET|POST")
     */
    public function justify(Request $request, SolicitudTarea $solTask, SolEstadoUpdater $updStatus): Response
    {
        if (!$this->isCsrfTokenValid('justify'.$solTask->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_tarea_index');
        }

        //$updStatus->excusarTarea($solTask);
        

        return $this->redirectToRoute('solicitud_tarea_index');
    }

    /**
     * @Route("/{id}/correct", name="solicitud_tarea_correct", methods="GET|POST")
     */
    public function correct(Request $request, SolicitudTarea $solTask, SolEstadoUpdater $updStatus): Response
    {
        if (!$this->isCsrfTokenValid('correct'.$solTask->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_tarea_index');
        }

        //$updStatus->excusarTarea($solTask);
        

        return $this->redirectToRoute('solicitud_tarea_index');
    }

    /**
     * @Route("/{id}", name="solicitud_tarea_delete", methods="DELETE")
     *
     */
    public function delete(Request $request, SolicitudTarea $solTask): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$solTask->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_tarea_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($solTask);
        $em->flush();

        return $this->redirectToRoute('solicitud_tarea_index');
    }
}
