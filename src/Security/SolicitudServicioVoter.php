<?php

namespace App\Security;

use App\Entity\SolicitudServicio;
use App\Entity\GestorUsuario;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class SolicitudServicioVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'viewSolServ';
    const EDIT = 'editSolServ';
    const CREATE = 'createSolReq';

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

        // only vote on SolicitudServicio objects inside this voter
        if (!$subject instanceof SolicitudServicio) {
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

        // you know $subject is a SolicitudServicio object, thanks to supports
        /** @var SolicitudServicio $solServ */
        $solServ = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($solServ, $usuario);
            break;
            case self::EDIT:
                return $this->canEdit($solServ, $usuario);
            break;
            case self::CREATE:
                return $this->canCreate($solServ, $usuario);
            break;
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(SolicitudServicio $solServ, GestorUsuario $usuario)
    {
        return $this->security->isGranted('ROLE_USER');
    }

    private function canEdit(SolicitudServicio $solServ, GestorUsuario $usuario)
    {
        return (
            ($usuario === $solServ->getAutor()) || $this->security->isGranted('ROLE_DIRECT')
        );
    }

    private function canCreate(SolicitudServicio $solServ, GestorUsuario $usuario)
    {
        return $this->security->isGranted('ROLE_DIRECT');
    }
}