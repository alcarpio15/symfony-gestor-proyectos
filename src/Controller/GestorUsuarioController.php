<?php

namespace App\Controller;

use App\Entity\GestorUsuario;
use App\Form\GestorUsuarioSignupType;
use App\Form\GestorUsuarioType;
use App\Repository\GestorUsuarioRepository;
use App\Controller\BaseController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class GestorUsuarioController extends Controller
{
    /**
     * @Route("/usuarios", name="gestor_usuario_index", methods="GET")
     */
    public function index(GestorUsuarioRepository $gUsrRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Solo un Administrador puede Acceder esta Página.');

        return $this->render('gestor_usuario/index.html.twig', ['gestor_usuarios' => $gUsrRepo->findAll()]);
    }

    /**
     * @Route("/signup", name="signup", methods="GET|POST")
     */
    public function registrar(Request $request, UserPasswordEncoderInterface $passEncoder): Response
    {
        $user = new GestorUsuario();
        $form = $this->createForm(GestorUsuarioSignupType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('gestor_usuario_index');
        }

        return $this->render('security/signup.html.twig', [
            'gestor_usuario' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/usuarios/{id}", name="gestor_usuario_show", methods="GET")
     */
    public function show(GestorUsuario $gestorUsuario): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Solo un Administrador puede Acceder esta Página.');

        return $this->render('gestor_usuario/show.html.twig', ['gestor_usuario' => $gestorUsuario]);
    }

    /**
     * @Route("/usuarios/{id}/edit", name="gestor_usuario_edit", methods="GET|POST")
     */
    public function edit(Request $request, GestorUsuario $gestorUsuario, UserPasswordEncoderInterface $passEncoder): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Solo un Administrador puede Acceder esta Página.');

        $form = $this->createForm(GestorUsuarioType::class, $gestorUsuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $plain = $gestorUsuario->getPlainPassword();

            if ($plain) {
                $password = $passEncoder->encodePassword($gestorUsuario, $plain);
                $gestorUsuario->setPassword($password);
            }

            $remove = $form->get('remove_roles')->getData();
            $roles = $form->get('roles_options')->getData();

            if ($remove) {
                $gestorUsuario->removeRoles($roles);
            } else {
                $gestorUsuario->addRoles($roles);
            }

            if (!in_array('ROLE_DEVLPR', $gestorUsuario->getRoles())) {
                $gestorUsuario->setAreaDesarrollo(null);
            }            

            $this->getDoctrine()->getManager()->flush();

            //return $this->redirectToRoute('gestor_usuario_edit', ['id' => $gestorUsuario->getId()]);
            return $this->redirectToRoute('gestor_usuario_index');
        }

        return $this->render('gestor_usuario/edit.html.twig', [
            'gestor_usuario' => $gestorUsuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/usuarios/{id}/deactivate", name="gestor_usuario_deactivate", methods="GET|POST")
     */
    public function deactivate(Request $request, GestorUsuario $gestorUsuario): Response
    {
         if ($this->isCsrfTokenValid('deactivate'.$gestorUsuario->getId(), $request->request->get('_token'))){
            $em = $this->getDoctrine()->getManager();
            $gestorUsuario->desactivar();
            $em->flush();
         }

        return $this->redirectToRoute('gestor_usuario_index');        
    }

    /**
     * @Route("/usuarios/{id}/reactivate", name="gestor_usuario_reactivate", methods="GET|POST")
     */
    public function reactivate(Request $request, GestorUsuario $gestorUsuario): Response
    {
         if ($this->isCsrfTokenValid('reactivate'.$gestorUsuario->getId(), $request->request->get('_token'))){
            $em = $this->getDoctrine()->getManager();
            $gestorUsuario->activar();
            $em->flush();
         }

        return $this->redirectToRoute('gestor_usuario_index');        
    }

}
