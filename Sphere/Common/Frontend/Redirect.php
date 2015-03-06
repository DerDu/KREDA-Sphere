<?php
namespace KREDA\Sphere\Common\Frontend;

use KREDA\Sphere\Client\Component\Element\Element;
use KREDA\Sphere\Common\Signature\Type\GetSignature;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class Redirect
 *
 * @package KREDA\Sphere\Common\Frontend
 */
class Redirect extends Element
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /**
     * @param string $Route
     * @param int    $Timeout
     * @param array  $Data
     */
    function __construct( $Route, $Timeout = 15, $Data = array() )
    {

        if (!empty( $Data )) {
            $Signature = new GetSignature();
            $Data = '?'.http_build_query( $Signature->createSignature( $Data, $Route ) );
        } else {
            $Data = '';
        }

        $this->Template = $this->extensionTemplate( __DIR__.'/Redirect.twig' );
        $this->Template->setVariable( 'Route', '/'.trim( $Route, '/' ).$Data );
        $this->Template->setVariable( 'Timeout', $Timeout );
        $this->Template->setVariable( 'UrlBase', $this->extensionRequest()->getUrlBase() );

    }

    /**
     * @return string
     */
    public function getContent()
    {

        return $this->Template->getContent();
    }
}
