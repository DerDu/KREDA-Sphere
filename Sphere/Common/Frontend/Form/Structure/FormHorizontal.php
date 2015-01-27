<?php
namespace KREDA\Sphere\Common\Frontend\Form\Structure;

use KREDA\Sphere\Common\Frontend\Form\AbstractForm;
use MOC\V\Component\Template\Exception\TemplateTypeException;

/**
 * Class FormHorizontal
 *
 * @package KREDA\Sphere\Common\Frontend\Form
 */
class FormHorizontal extends AbstractForm
{

    /**
     * @param GridFormGroup|GridFormGroup[] $GridGroupList
     * @param string                        $FormSubmit
     * @param string                        $FormAction
     *
     * @throws TemplateTypeException
     */
    function __construct( $GridGroupList, $FormSubmit = 'Absenden', $FormAction = '' )
    {

        if (!is_array( $GridGroupList )) {
            $GridGroupList = array( $GridGroupList );
        }
        $this->GridGroupList = $GridGroupList;

        $this->Template = $this->extensionTemplate( __DIR__.'/FormHorizontal.twig' );
        $this->Template->setVariable( 'UrlBase', $this->extensionRequest()->getUrlBase() );
        $this->Template->setVariable( 'FormAction', $FormAction );
        $this->Template->setVariable( 'FormSubmit', $FormSubmit );
    }

    /**
     * @return string
     */
    public function getContent()
    {

        $this->Template->setVariable( 'GridGroupList', $this->GridGroupList );
        $this->Template->setVariable( 'Hash', $this->getHash() );
        return $this->Template->getContent();
    }

}