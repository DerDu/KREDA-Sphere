<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Shell;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Client\Component\Element\Repository\Shell;
use KREDA\Sphere\Client\Component\IElementInterface;
use MOC\V\Component\Template\Component\IBridgeInterface;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;
use MOC\V\Core\HttpKernel\HttpKernel;

/**
 * Class Screen
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Shell
 */
class Screen extends Shell implements IElementInterface
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

        $this->Template = Template::getTemplate( __DIR__.'/Screen.twig' );
        $this->Template->setVariable( 'PathBase', $PathBase = HttpKernel::getRequest()->getPathBase() );

        $this->Template->setVariable( 'Style', array(
            $PathBase.'/Library/Bootstrap/3.2.0/dist/css/bootstrap.min.css',
            $PathBase.'/Library/Bootstrap.Glyphicons/1.9.0/glyphicons_halflings/web/html_css/css/glyphicons-halflings.css',
            $PathBase.'/Library/Bootstrap.Glyphicons/1.9.0/glyphicons/web/html_css/css/glyphicons.css',
            $PathBase.'/Library/Bootstrap.Glyphicons/1.9.0/glyphicons_filetypes/web/html_css/css/glyphicons-filetypes.css',
            $PathBase.'/Library/Bootstrap.Glyphicons/1.9.0/glyphicons_social/web/html_css/css/glyphicons-social.css',
            $PathBase.'/Library/Bootflat/2.0.4/bootflat/css/bootflat.min.css',
            $PathBase.'/Library/Twitter.Typeahead.Bootstrap/1.0.0/typeaheadjs.css',
            $PathBase.'/Library/Bootstrap.DateTimePicker/3.1.3/build/css/bootstrap-datetimepicker.min.css',
            $PathBase.'/Library/jQuery.DataTables.Plugins/1.0.1/integration/bootstrap/3/dataTables.bootstrap.css',
            $PathBase.'/Sphere/Client/Style/Style.css',
        ) );
        $this->Template->setVariable( 'Script', array(
            $PathBase.'/Library/jQuery/1.11.1/dist/jquery.min.js',
            $PathBase.'/Library/Moment.Js/2.8.4/min/moment-with-locales.min.js',
            $PathBase.'/Library/Bootstrap/3.2.0/dist/js/bootstrap.min.js',
            $PathBase.'/Library/Bootflat/2.0.4/bootflat/js/icheck.min.js',
            $PathBase.'/Library/jQuery.Selecter/3.2.3/jquery.fs.selecter.min.js',
            $PathBase.'/Library/jQuery.Stepper/3.0.8/jquery.fs.stepper.min.js',
            $PathBase.'/Library/jQuery.DataTables/1.10.4/media/js/jquery.dataTables.min.js',
            $PathBase.'/Library/jQuery.DataTables.Plugins/1.0.1/integration/bootstrap/3/dataTables.bootstrap.min.js',
            $PathBase.'/Library/Bootstrap.DateTimePicker/3.1.3/build/js/bootstrap-datetimepicker.min.js',
            $PathBase.'/Library/Twitter.Typeahead/0.10.5/dist/typeahead.bundle.min.js',
            $PathBase.'/Sphere/Client/Script/ModTable.js',
            $PathBase.'/Sphere/Client/Script/ModPicker.js',
            $PathBase.'/Sphere/Client/Script/ModSelecter.js',
            $PathBase.'/Sphere/Client/Script/ModCompleter.js',
        ) );
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

        $this->Template->setVariable( 'PositionNavigation', implode( '', $this->PositionNavigation ) );
        $this->Template->setVariable( 'PositionContent', implode( '', $this->PositionContent ) );
        $Debug = $this->getDebugger();
        $this->Template->setVariable( 'PositionDebugger', $Debug->getProtocol() );
        $this->Template->setVariable( 'PositionRuntime', $Debug->getRuntime() );
        $Consumer = Gatekeeper::serviceConsumer()->entityConsumerBySession();
        $this->Template->setVariable( 'PositionConsumer',
            ( $Consumer ? $Consumer->getName().' ('.$Consumer->getDatabaseSuffix().')' : '-NA-' ) );

        return $this->Template->getContent();
    }

}
