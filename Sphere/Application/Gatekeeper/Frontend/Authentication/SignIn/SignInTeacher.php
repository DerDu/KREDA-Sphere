<?php
namespace KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\SignIn;

use KREDA\Sphere\Application\Gatekeeper\Frontend\AbstractError;
use MOC\V\Component\Template\Exception\TemplateTypeException;
use MOC\V\Component\Template\Template;

/**
 * Class SignInTeacher
 *
 * @package KREDA\Sphere\Application\Gatekeeper\Frontend\Authentication\SignIn
 */
class SignInTeacher extends AbstractError
{

    /**
     * @throws TemplateTypeException
     */
    function __construct()
    {

        $this->Template = Template::getTemplate( __DIR__.'/SignInToken.twig' );
    }
}