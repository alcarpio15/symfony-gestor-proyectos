<?php

namespace App\Security;

use App\Entity\SolicitudRequerimientos;
use App\Entity\GestorUsuario;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class SolicitudRequerimientosVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'viewSolReq';
    const EDIT = 'editSolReq';
    const CREATE = 'createSolTask';

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

        // only vote on SolicitudRequerimientos objects inside this voter
        if (!$subject instanceof SolicitudRequerimientos) {
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

        // you know $subject is a SolicitudRequerimientos object, thanks to supports
        /** @var SolicitudRequerimientos $solReq */
        $solReq = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($solReq, $usuario);
            break;
            case self::EDIT:
                return $this->canEdit($solReq, $usuario);
            break;
            case self::CREATE:
                return $this->canCreate($solReq, $usuario);
            break;
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(SolicitudRequerimientos $solReq, GestorUsuario $usuario)
    {
        return (
            (
                ($usuario === $solReq->getArea()->getCoordinador()) && $this->security->isGranted('ROLE_CORDAR')
            ) || $this->security->isGranted('ROLE_CORDGN')
        );
    }

    private function canEdit(SolicitudRequerimientos $solReq, GestorUsuario $usuario)
    {
        return $this->security->isGranted('ROLE_CORDGN');
    }

    private function canCreate(SolicitudRequerimientos $solReq, GestorUsuario $usuario)
    {
        return (
            (
                ($usuario === $solReq->getArea()->getCoordinador()) && $this->security->isGranted('ROLE_CORDAR')
            ) || $this->security->isGranted('ROLE_CORDGN')
        );
    }
}