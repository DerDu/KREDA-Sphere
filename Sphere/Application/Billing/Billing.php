<?php
namespace KREDA\Sphere\Application\Billing;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyIcon;
use KREDA\Sphere\Client\Configuration;
use KREDA\Sphere\Common\AbstractApplication;

/**
 * Class Billing
 *
 * @package KREDA\Sphere\Application\Billing
 */
class Billing extends AbstractApplication
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     */
    public static function registerApplication( Configuration $Configuration )
    {

        /**
         * Configuration
         */
        self::setupApplicationAccess( 'Billing' );
        self::$Configuration = $Configuration;
        /**
         * Navigation
         */
        if (Gatekeeper::serviceAccess()->checkIsValidAccess( 'Application:Billing' )) {
            self::addClientNavigationMain( self::$Configuration, '/Sphere/Billing', 'Fakturierung', new MoneyIcon() );
        }

        self::registerClientRoute( self::$Configuration, '/Sphere/Billing',
            __CLASS__.'::frontendBilling' );
    }

    /**
     * @return Stage
     */
    public static function frontendBilling()
    {

        self::setupModuleNavigation();
        $View = new Stage();
        $View->setTitle('Fraktuierung');
        $View->setMessage('Bitte wählen Sie ein Thema');
        return $View;
    }

    /**
     * @return Service\Account
     */
    public static function serviceAccount()
    {

        return Service\Account::getApi();
    }

    /**
     * @return Service\Commodity
     */
    public static function serviceCommodity()
    {

        return Service\Commodity::getApi();
    }

    /**
     * @return void
     */
    protected static function setupModuleNavigation()
    {

    }
}
