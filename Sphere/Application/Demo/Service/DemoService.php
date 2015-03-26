<?php
namespace KREDA\Sphere\Application\Demo\Service;

use KREDA\Sphere\Application\Demo\Service\DemoService\EntityAction;
use KREDA\Sphere\Common\Database\Handler;
use KREDA\Sphere\Common\Frontend\Form\AbstractForm;
use KREDA\Sphere\Common\Frontend\Redirect;

/**
 * Class DemoService
 *
 * @package KREDA\Sphere\Application\Demo\Service
 */
class DemoService extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Demo', 'DemoService', $this->getConsumerSuffix() );
    }

    /**
     * @param AbstractForm $abstractForm
     * @param string       $DemoCompleter
     *
     * @return AbstractForm
     */
    public function executeCreateDemo( AbstractForm $abstractForm, $DemoCompleter, $DemoTextArea )
    {

        if (null !== $DemoCompleter && empty( $DemoCompleter )) {
            $abstractForm->setError( 'DemoCompleter', 'Gib was ein' );
        }
        if (null !== $DemoTextArea && empty( $DemoTextArea )) {
            $abstractForm->setError( 'DemoTextArea', 'Gib was ein' );
        }
        if (!empty( $DemoCompleter )) {
            $this->actionCreateDemoCompleter( $DemoCompleter );
            $abstractForm->setSuccess( 'DemoCompleter', new Redirect( '/Sphere/Demo', 5 ) );
        }
        return $abstractForm;
    }

    /**
     * @return bool|DemoService\Entity\TblDemoCompleter[]
     */
    public function entityDemoCompleterAll()
    {

        return parent::entityDemoCompleterAll();
    }

}
