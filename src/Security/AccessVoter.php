<?php

namespace App\Security;

use App\Entity\GestorUsuario;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class AccessVoter extends Voter
{
    const SOLSERV = 'manageSolServ';
    const SOLREQ = 'manageSolReq';
    const SOLTASK = 'manageSolTask';
    const USER = 'manageUsers';
    const AREA = 'manageAreaCord';
    const HIST = 'manadeHistory';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $subject)
    {
        return in_array($attribute, array(
            self::SOLSERV, self::SOLREQ, self::SOLTASK, self::USER, self::AREA, self::HIST
        ));
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $usuario = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$usuario instanceof GestorUsuario) {
            return false;
        }

        if (!$usuario->getActivo()) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::SOLSERV:
                return $this->security->isGranted('ROLE_USER');
                break;
            case self::SOLREQ:
                return $this->security->isGranted('ROLE_CORDAR');
                break;
            case self::SOLTASK:
                return $this->security->isGranted('ROLE_DEVLPR');
                break;
            case self::USER:
                return $this->security->isGranted('ROLE_ADMIN');
                break;
            case self::AREA:
                return $this->security->isGranted('ROLE_ADMIN');
                break;
            case self::HIST:
                return $this->security->isGranted('ROLE_ANALST');
                break;
        }

        return false;
    }
}
