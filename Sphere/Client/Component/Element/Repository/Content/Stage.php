<?php
namespace KREDA\Sphere\Client\Component\Element\Repository\Content;

use KREDA\Sphere\Client\Component\Element\Repository\AbstractContent;
use KREDA\Sphere\Client\Component\IElementInterface;
use KREDA\Sphere\Client\Frontend\Button\AbstractType;
use KREDA\Sphere\Client\Frontend\Text\Type\Muted;
use MOC\V\Component\Template\Component\IBridgeInterface;

/**
 * Class Stage
 *
 * @package KREDA\Sphere\Client\Component\Element\Repository\Content
 */
class Stage extends AbstractContent implements IElementInterface
{

    /** @var IBridgeInterface $Template */
    private $Template = null;

    /** @var string $Title */
    private $Title = '';
    /** @var string $Description */
    private $Description = '';
    /** @var string $Message */
    private $Message = '';
    /** @var string $Content */
    private $Content = '';
    /** @var array $Menu */
    private $Menu = array();

    /**
     * @param null|string $Title
     * @param null|string $Description
     */
    function __construct( $Title = null, $Description = null )
    {
        $this->Template = $this->extensionTemplate( __DIR__.'/Stage.twig' );
        if (null !== $Title) {
            $this->setTitle( $Title );
        }
        if (null !== $Description) {
            $this->setDescription( $Description );
        }
    }

    /**
     * @param string $Value
     *
     * @return Stage
     */
    public function setTitle( $Value )
    {

        $this->Title = $Value;
        return $this;
    }

    /**
     * @param string $Value
     *
     * @return Stage
     */
    public function setDescription( $Value )
    {

        $this->Description = $Value;
        return $this;
    }


    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'StageTitle', $this->Title );
        $this->Template->setVariable( 'StageDescription', $this->Description );
        $this->Template->setVariable( 'StageMessage', $this->Message );
        $this->Template->setVariable( 'StageContent', $this->Content );
        $this->Template->setVariable( 'StageMenu', $this->Menu );

        return $this->Template->getContent();
    }

    /**
     * @param string $Content
     *
     * @return Stage
     */
    public function setContent( $Content )
    {

        $this->Content = $Content;
        return $this;
    }

    /**
     * @param string $Message
     *
     * @return Stage
     */
    public function setMessage( $Message )
    {

        $this->Message = new Muted( $Message );
        return $this;
    }

    /**
     * @param \KREDA\Sphere\Client\Frontend\Button\AbstractType $Button
     *
     * @return Stage
     */
    public function addButton( AbstractType $Button )
    {

        $this->Menu[] = $Button->__toString();
        return $this;
    }

}
