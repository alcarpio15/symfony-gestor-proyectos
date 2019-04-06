<?php

namespace App\Security;

use App\Entity\SolicitudTarea;
use App\Entity\GestorUsuario;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class SolicitudTareaVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'viewSolTask';
    const EDIT = 'editSolTask';
    const CREATE = 'justifySolTask';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::CREATE))) {
            return false;
        }

        // only vote on SolicitudTarea objects inside this voter
        if (!$subject instanceof SolicitudTarea) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $usuario = $token->getUser();

        if (!$usuario instanceof GestorUsuario) {
            return false;
        }

        if (!$usuario->getActivo()) {
            return false;
        }

        // you know $subject is a SolicitudTarea object, thanks to supports
        /** @var SolicitudTarea $solTask */
        $solTask = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($solTask, $usuario);
            break;
            case self::EDIT:
                return $this->canEdit($solTask, $usuario);
            break;
            case self::CREATE:
                return $this->canCreate($solTask, $usuario);
            break;
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(SolicitudTarea $solTask, GestorUsuario $usuario)
    {
        return (
            (
                ($usuario === $solTask->getDesarrollador()) && $this->security->isGranted('ROLE_DEVLPR')
            ) || (
                (
                    $usuario === $solTask->getRequerimiento()->getArea()->getCoordinador()
                ) && $this->security->isGranted('ROLE_CORDAR')
            ) || $this->security->isGranted('ROLE_CORDGN')
        );
    }

    private function canEdit(SolicitudTarea $solTask, GestorUsuario $usuario)
    {
        return (
            (
                (
                    $usuario === $solTask->getRequerimiento()->getArea()->getCoordinador()
                ) && $this->security->isGranted('ROLE_CORDAR')
            ) || $this->security->isGranted('ROLE_CORDGN')
        );
    }

    private function canCreate(SolicitudTarea $solTask, GestorUsuario $usuario)
    {
        return (
            (
                ($usuario === $solTask->getDesarrollador()) && $this->security->isGranted('ROLE_DEVLPR')
            ) || (
                (
                    $usuario === $solTask->getRequerimiento()->getArea()->getCoordinador()
                ) && $this->security->isGranted('ROLE_CORDAR')
            ) || $this->security->isGranted('ROLE_CORDGN')
        );
    }
}