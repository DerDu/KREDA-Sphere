<?php
namespace KREDA\Sphere\Common;

use KREDA\Sphere\Client\Component\Element\Repository\AbstractShell;
use KREDA\Sphere\Common\Frontend\Alert\Element\MessageDanger;
use KREDA\Sphere\Common\Frontend\Redirect;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class AbstractFrontend
 *
 *  - Methods MUST BE static
 *  - Methods MUST RETURN Stage
 *
 * @package KREDA\Sphere\Common
 */
abstract class AbstractFrontend extends AbstractShell
{

    /**
     * @param string $Route
     * @param int    $Timeout
     *
     * @return Redirect
     */
    final protected static function getRedirect( $Route, $Timeout = 15 )
    {

        return new Redirect( $Route, $Timeout );
    }

    /**
     * @param IBridgeInterface $Template
     * @param string           $RequestKey
     * @param string           $VariableName
     */
    final protected static function setRequestValue( IBridgeInterface &$Template, $RequestKey, $VariableName )
    {

        if (isset( $_REQUEST[$RequestKey] )) {
            $Template->setVariable( $VariableName, $_REQUEST[$RequestKey] );
        }
    }

    /**
     * @return string
     */
    final protected static function getUrlBase()
    {

        return self::extensionRequest()->getUrlBase();
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $Error = new MessageDanger( __METHOD__.' MUST NOT create content.' );
        return $Error->getContent();
    }

}
