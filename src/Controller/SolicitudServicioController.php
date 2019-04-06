<?php

namespace App\Controller;

use App\Entity\SolicitudServicio;
use App\Form\SolicitudServicioType;
use App\Repository\SolicitudServicioRepository;
use App\Controller\BaseController;
use App\Service\SolEstadoUpdater;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/solicitud/servicio")
 */
class SolicitudServicioController extends Controller
{
    /**
     * Método Indice:
     * Genera una Página de Indice Central para administrar las Solicitudes de Servicio Creadas.
     *
     * El Rol del Usuario determina cuantas Solicitudes pueden ser Mostradas:
     *  - Directores y Administradores pueden ver todas las Solicitudes creadas.
     *  - Otros Roles solo pueden ver las Solicitudes creadas por ellos.
     *
     * @Route("/", name="solicitud_servicio_index", methods="GET")
     */
    public function index(SolicitudServicioRepository $solSrvcRepo, UserInterface $user, AuthorizationCheckerInterface $authChecker): Response
    {
        $this->denyAccessUnlessGranted('manageSolServ', null, 'No puede ver esta pagina sin ser un Usuario Activo.');

        if ($authChecker->isGranted('ROLE_DIRECT')) {
            return $this->render('solicitud_servicio/index_direct.html.twig', ['solicitud_servicios' => $solSrvcRepo->findAll()]);
        }
        else {
            return $this->render('solicitud_servicio/index.html.twig', ['solicitud_servicios' => $solSrvcRepo->findAllByUser($user->getId())]);
        }
        
    }

    /**
     * Método Crear Solicitud de Servicio:
     * Gestiona el Proceso de Creación de Solicitud de Servicio.
     * 
     * Genera un Formulario que permite al Usuario introducir
     *
     * 
     *
     * @Route("/new", name="solicitud_servicio_new", methods="GET|POST")
     */
    public function new(Request $request, UserInterface $user): Response
    {
        $this->denyAccessUnlessGranted('manageSolServ', null, 'No puede ver esta pagina sin ser un Usuario Activo.');

        $solServ = new SolicitudServicio();
        $form = $this->createForm(SolicitudServicioType::class, $solServ);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solServ->setAutor($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($solServ);
            $em->flush();

            return $this->redirectToRoute('solicitud_servicio_index');
        }

        return $this->render('solicitud_servicio/new.html.twig', [
            'solicitud_servicio' => $solServ,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Método Mostrar Solicitud de Servicio:
     * Redirige al Usuario a una Página con un mayor rango de Detalles acerca de una.
     * 
     * @Route("/{id}", name="solicitud_servicio_show", methods="GET")
     */
    public function show(SolicitudServicio $solServ): Response
    {
        $this->denyAccessUnlessGranted('viewSolServ', $solServ, 'No puede ver esta pagina sin estar Registrado.');

        return $this->render('solicitud_servicio/show.html.twig', ['solicitud_servicio' => $solServ]);
    }

    /**
     * @Route("/{id}/edit", name="solicitud_servicio_edit", methods="GET|POST")
     */
    public function edit(Request $request, SolicitudServicio $solServ): Response
    {
        $this->denyAccessUnlessGranted('editSolServ', $solServ, 'La Solicitud solo puede ser editada por un Director o el Autor de la misma.');

        $form = $this->createForm(SolicitudServicioType::class, $solServ);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('solicitud_servicio_edit', ['id' => $solServ->getId()]);
        }

        return $this->render('solicitud_servicio/edit.html.twig', [
            'solicitud_servicio' => $solServ,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/cancel", name="solicitud_servicio_cancel", methods="GET|POST")
     */
    public function cancel(Request $request, SolicitudServicio $solServ): Response
    {
        
        if (!$this->isCsrfTokenValid('cancel'.$solServ->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_servicio_index');
        }

        /*$em = $this->getDoctrine()->getManager();
        $em->remove($solServ);
        $em->flush();*/

        return $this->redirectToRoute('solicitud_servicio_index');
    }

    /**
     * @Route("/{id}", name="solicitud_servicio_delete", methods="DELETE")
     */
    public function delete(Request $request, SolicitudServicio $solServ): Response
    {
        
        if (!$this->isCsrfTokenValid('delete'.$solServ->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_servicio_index');
        }

        /*$em = $this->getDoctrine()->getManager();
        $em->remove($solServ);
        $em->flush();*/

        return $this->redirectToRoute('solicitud_servicio_index');
    }
}
