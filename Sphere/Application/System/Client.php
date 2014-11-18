<?php
namespace KREDA\Sphere\Application\System;

use KREDA\Sphere\Application\Application;
use KREDA\Sphere\Application\System\Service\Database;
use KREDA\Sphere\Application\System\Service\Update;
use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Client\Component\Element\Repository\Shell\Landing;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GearIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\WrenchIcon;
use KREDA\Sphere\Client\Configuration;

/**
 * Class Client
 *
 * @package KREDA\Sphere\Application\System
 */
class Client extends Application
{

    /** @var Configuration $Config */
    private static $Configuration = null;

    /**
     * @param Configuration $Configuration
     *
     * @return Configuration
     */
    public static function setupApi( Configuration $Configuration )
    {

        self::$Configuration = $Configuration;
        self::addClientNavigationMeta( self::$Configuration,
            '/Sphere/System', 'System', new WrenchIcon()
        );
        self::buildRoute( self::$Configuration, '/Sphere/System', __CLASS__.'::apiMain' );

        self::buildRoute( self::$Configuration, '/Sphere/System/Update', __CLASS__.'::apiUpdate' );
        self::buildRoute( self::$Configuration, '/Sphere/System/Update/Simulation', __CLASS__.'::apiUpdateSimulation' );
        self::buildRoute( self::$Configuration, '/Sphere/System/Update/Perform', __CLASS__.'::apiUpdatePerform' );

        self::buildRoute( self::$Configuration, '/Sphere/System/Database/Status', __CLASS__.'::apiDatabaseStatus' );

        return $Configuration;
    }

    /**
     * @return Element|Landing
     */
    public function apiMain()
    {

        $this->setupModuleNavigation();
        $View = new Landing();
        $View->setTitle( 'Systemeinstellungen' );
        $View->setMessage( 'Bitte wählen Sie ein Thema' );
        return $View;
    }

    public function setupModuleNavigation()
    {

        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Database/Status', 'Datenbanken', new GearIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Update', 'Update', new GearIcon()
        );
        self::addModuleNavigationMain( self::$Configuration,
            '/Sphere/System/Account', 'Benutzerkonten', new PersonIcon()
        );
    }

    /**
     * @return Landing
     */
    public function apiUpdate()
    {

        $this->setupModuleNavigation();
        $this->setupServiceUpdate();
        return Update::getApi()->apiUpdate();
    }

    public function setupServiceUpdate()
    {

        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Update/Simulation', 'Simulation durchführen', new GearIcon()
        );
        self::addApplicationNavigationMain( self::$Configuration,
            '/Sphere/System/Update/Perform', 'Update durchführen', new GearIcon()
        );

    }

    /**
     * @return Landing
     */
    public function apiUpdateSimulation()
    {

        $this->setupModuleNavigation();
        $this->setupServiceUpdate();
        return Update::getApi()->apiUpdateSimulation();
    }

    /**
     * @return Landing
     */
    public function apiUpdatePerform()
    {

        $this->setupModuleNavigation();
        $this->setupServiceUpdate();
        return Update::getApi()->apiUpdatePerform();
    }

    /**
     * @return Landing
     */
    public function apiDatabaseStatus()
    {

        $this->setupModuleNavigation();
        return Database::getApi( '/Sphere/System/Database' )->apiStatus();
    }

}
