<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Content;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\AbstractContent;
use KREDA\Sphere\Client\Component\IElementInterface;
use KREDA\Sphere\Client\Script;
use KREDA\Sphere\Client\Style;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class Screen
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Shell
 */
class Screen extends AbstractContent implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;
    /** @var Container[] $PositionNavigation */
    private $PositionNavigation = array();
    /** @var Container[] $PositionContent */
    private $PositionContent = array();

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = $this->extensionTemplate( __DIR__.'/Screen.twig' );
        $this->Template->setVariable( 'PathBase', $PathBase = $this->extensionRequest()->getPathBase() );
    }

    /**
     * @param Container $Container
     *
     * @return Screen
     */
    public function addToNavigation( Container $Container )
    {

        array_push( $this->PositionNavigation, $Container->getContent() );
        return $this;
    }

    /**
     * @param \Exception $E
     *
     * @return Screen
     */
    public function addError( \Exception $E )
    {

        return $this->addMessageToContent( $E, 'Error' );

    }

    /**
     * @param \Exception $E
     * @param string     $Name
     *
     * @return $this
     */
    private function addMessageToContent( \Exception $E, $Name = 'Error' )
    {

        $TraceList = '';
        foreach ((array)$E->getTrace() as $Index => $Trace) {
            $TraceList .= '<br/><samp class="text-info">'
                .( isset( $Trace['type'] ) && isset( $Trace['function'] ) ? '<br/>Method: '.$Trace['type'].$Trace['function'] : '<br/>Method: ' )
                .( isset( $Trace['class'] ) ? '<br/>Class: '.$Trace['class'] : '<br/>Class: ' )
                .( isset( $Trace['file'] ) ? '<br/>File: '.$Trace['file'] : '<br/>File: ' )
                .( isset( $Trace['line'] ) ? '<br/>Line: '.$Trace['line'] : '<br/>Line: ' )
                .'</samp>';
        }
        $Hit = '<samp class="text-danger"><p class="h6">'.$E->getMessage().'</p><br/>File: '.$E->getFile().'<br/>Line: '.$E->getLine().'</samp>'.$TraceList;
        $this->addToContent( new Container( new Error(
            $E->getCode() == 0 ? $Name : $E->getCode(), $Hit
        ) ) );
        return $this;

    }

    /**
     * @param Container $Container
     *
     * @return Screen
     */
    public function addToContent( Container $Container )
    {

        array_push( $this->PositionContent, $Container->getContent() );
        return $this;
    }

    /**
     * @param \Exception $E
     * @param string     $Identifier
     *
     * @return Screen
     */
    public function addException( \Exception $E, $Identifier = 'Unhandled Exception' )
    {

        return $this->addMessageToContent( $E, $Identifier );

    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'StyleManager', Style::getManager() );
        $this->Template->setVariable( 'ScriptManager', Script::getManager() );
        $this->Template->setVariable( 'PositionNavigation', implode( '', $this->PositionNavigation ) );
        $this->Template->setVariable( 'PositionContent', implode( '', $this->PositionContent ) );
        $Debug = $this->extensionDebugger();
        $this->Template->setVariable( 'PositionDebugger', $Debug->getProtocol() );
        $this->Template->setVariable( 'PositionRuntime', $Debug->getRuntime() );
        $Consumer = Gatekeeper::serviceConsumer()->entityConsumerBySession();
        $this->Template->setVariable( 'PositionConsumer',
            ( $Consumer ? $Consumer->getName().' ('.$Consumer->getDatabaseSuffix().')' : '-NA-' ) );

        return $this->Template->getContent();
    }

}