<?php

namespace App\Controller;

use App\Entity\SolicitudServicio;
use App\Form\SolicitudServicioType;
use App\Repository\SolicitudServicioRepository;
use App\Controller\BaseController;
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
     * @Route("/", name="solicitud_servicio_index", methods="GET")
     */
    public function index(SolicitudServicioRepository $solSrvcRepo, UserInterface $user, AuthorizationCheckerInterface $authChecker): Response
    {
        if ($authChecker->isGranted('ROLE_DIRECT')) {
            return $this->render('solicitud_servicio/index_direct.html.twig', ['solicitud_servicios' => $solSrvcRepo->findAll()]);
        }
        else {
            return $this->render('solicitud_servicio/index.html.twig', ['solicitud_servicios' => $solSrvcRepo->findAllByUser($user->getId())]);
        }
        
    }

    /**
     * @Route("/new", name="solicitud_servicio_new", methods="GET|POST")
     */
    public function new(Request $request, UserInterface $user): Response
    {
        $solicitudServicio = new SolicitudServicio();
        $form = $this->createForm(SolicitudServicioType::class, $solicitudServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solicitudServicio->setAutor($user);
            $em = $this->getDoctrine()->getManager();
            $em->persist($solicitudServicio);
            $em->flush();

            return $this->redirectToRoute('solicitud_servicio_index');
        }

        return $this->render('solicitud_servicio/new.html.twig', [
            'solicitud_servicio' => $solicitudServicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_servicio_show", methods="GET")
     */
    public function show(SolicitudServicio $solicitudServicio): Response
    {
        return $this->render('solicitud_servicio/show.html.twig', ['solicitud_servicio' => $solicitudServicio]);
    }

    /**
     * @Route("/{id}/edit", name="solicitud_servicio_edit", methods="GET|POST")
     */
    public function edit(Request $request, SolicitudServicio $solicitudServicio): Response
    {
        $form = $this->createForm(SolicitudServicioType::class, $solicitudServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('solicitud_servicio_edit', ['id' => $solicitudServicio->getId()]);
        }

        return $this->render('solicitud_servicio/edit.html.twig', [
            'solicitud_servicio' => $solicitudServicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_servicio_delete", methods="DELETE")
     */
    public function delete(Request $request, SolicitudServicio $solicitudServicio): Response
    {
        if (!$this->isCsrfTokenValid('delete'.$solicitudServicio->getId(), $request->request->get('_token'))) {
            return $this->redirectToRoute('solicitud_servicio_index');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($solicitudServicio);
        $em->flush();

        return $this->redirectToRoute('solicitud_servicio_index');
    }
}
