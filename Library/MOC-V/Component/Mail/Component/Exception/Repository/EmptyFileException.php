<?php
namespace MOC\V\Component\Mail\Component\Exception\Repository;

use MOC\V\Component\Mail\Component\Exception\ComponentException;

/**
 * Class EmptyFileException
 *
 * @package MOC\V\Component\Mail\Component\Exception
 */
class EmptyFileException extends ComponentException
{

    /**
     * @param string $Message
     * @param int    $Code
     * @param null   $Previous
     */
    public function __construct( $Message = "", $Code = 0, $Previous = null )
    {

        $Message = 'File location must not be empty!';

        parent::__construct( $Message, $Code, $Previous );
    }

}
