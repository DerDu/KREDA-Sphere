<?php
namespace KREDA\Sphere\Common\Database\Connection;

use MOC\V\Component\Database\Component\IBridgeInterface;

/**
 * Class Register
 *
 * @package KREDA\Sphere\Common\Database
 */
class Register
{

    /** @var Register $Singleton */
    private static $Singleton = null;
    /** @var IBridgeInterface[] $Register */
    private $Register = array();

    /**
     * @return Register
     */
    final public static function getSingleton()
    {

        if (null === self::$Singleton) {
            $Class = __CLASS__;
            self::$Singleton = new $Class;
        }
        return self::$Singleton;
    }

    /**
     * @param Identifier       $Identifier
     * @param IBridgeInterface $Connection
     *
     * @return Register
     */
    final public function addDatabase( Identifier $Identifier, IBridgeInterface $Connection )
    {

        if (!$this->hasDatabase( $Identifier )) {
            $this->Register[$Identifier->getIdentifier()] = $Connection;
        }
        return $this;
    }

    /**
     * @param Identifier $Identifier
     *
     * @return bool
     */
    final public function hasDatabase( Identifier $Identifier )
    {

        return isset( $this->Register[$Identifier->getIdentifier()] );
    }

    /**
     * @param Identifier $Identifier
     *
     * @throws \Exception
     * @return IBridgeInterface
     */
    final public function getDatabase( Identifier $Identifier )
    {

        if ($this->hasDatabase( $Identifier )) {
            return $this->Register[$Identifier->getIdentifier()];
        } else {
            throw new \Exception();
        }
    }
}
