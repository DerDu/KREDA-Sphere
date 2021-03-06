<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend;

use KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\SignIn;
use KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\SignOut;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\LockIcon;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Button\Structure\ButtonGroup;
use KREDA\Sphere\Common\AbstractFrontend;

/**
 * Class Authentication
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Frontend
 */
class Authentication extends AbstractFrontend
{

    /**
     * @return Stage
     */
    public static function stageStatus()
    {

        $View = new Stage();
        $View->setTitle( 'Willkommen' );
        $View->setDescription( 'KREDA Professional' );
        $View->setMessage( date( 'd.m.Y - H:i:s' ) );
        return $View;
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public static function stageSignInTeacher( $CredentialName, $CredentialLock, $CredentialKey )
    {

        return SignIn::stageTeacher( $CredentialName, $CredentialLock, $CredentialKey );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public static function stageSignInSystem( $CredentialName, $CredentialLock, $CredentialKey )
    {

        return SignIn::stageSystem( $CredentialName, $CredentialLock, $CredentialKey );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     *
     * @return Stage
     */
    public static function stageSignInStudent( $CredentialName, $CredentialLock )
    {

        return SignIn::stageStudent( $CredentialName, $CredentialLock );
    }

    /**
     * @param string $CredentialName
     * @param string $CredentialLock
     * @param string $CredentialKey
     *
     * @return Stage
     */
    public static function stageSignInManagement( $CredentialName, $CredentialLock, $CredentialKey )
    {

        return SignIn::stageManagement( $CredentialName, $CredentialLock, $CredentialKey );
    }

    /**
     * @return Stage
     */
    public static function stageSignInSwitch()
    {

        $View = new Stage();
        $View->setTitle( 'Anmeldung' );
        $View->setMessage( 'Bitte wählen Sie den Typ der Anmeldung' );
        $View->setContent(
            new ButtonGroup( array(
                new Primary(
                    'Schüler', '/Sphere/Gatekeeper/SignIn/Student', new LockIcon()
                ),
                new Primary(
                    'Lehrer', '/Sphere/Gatekeeper/SignIn/Teacher', new LockIcon()
                ),
                new Primary(
                    'Verwaltung', '/Sphere/Gatekeeper/SignIn/Management', new LockIcon()
                ),
                new Danger(
                    'System', '/Sphere/Gatekeeper/SignIn/System', new LockIcon()
                )
            ) )
        );
        return $View;
    }

    /**
     * @return Stage
     */
    public static function stageSignOut()
    {

        return SignOut::stageSignOut();
    }
}
