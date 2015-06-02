<?php
namespace KREDA\Sphere\Application\Billing\Service;
use KREDA\Sphere\Application\Billing\Service\Banking\EntityAction;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Frontend\Form\AbstractType;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Redirect;

/**
 * Class Banking
 *
 * @package KREDA\Sphere\Application\Billing\Service
 */
class Banking extends EntityAction
{
    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     * @throws \Exception
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Billing', 'Banking', $this->getConsumerSuffix() );
    }

    /**
     *
     */
    public function setupDatabaseContent()
    {
        /**
         * CommodityType
         */
    }

    /**
     * @param AbstractType $View
     * @param $Debtor
     * @param $Id
     * @return AbstractType|string
     */
    public function executeAddDebtor(
        AbstractType &$View = null,
        $Debtor, $Id )
    {
        if ( null === $Debtor){
            return $View;
        }
        $Error = false;
        if (isset($Debtor['DebtorNumber']) && empty( $Debtor['DebtorNumber'])) {
            $View->setError( '$Debtor[DebtorNumber]', 'Bitte geben sie die Debitorennummer an' );
            $Error = true;
        }
        if (isset($Debtor['LeadTimeFirst']) && empty( $Debtor['LeadTimeFirst'])) {
            $View->setError( '$Debtor[LeadTimeFirst]', 'Bitte geben sie den Ersteinzug an' );
            $Error = true;
        }
        if (isset($Debtor['LeadTimeFollow']) && empty( $Debtor['LeadTimeFollow'])) {
            $View->setError( '$Debtor[LeadTimeFollow]', 'Bitte geben sie den Folgeeinzug an' );
            $Error = true;
        }

        if (!$Error) {
            $this->actionAddDebtor( $Debtor['DebtorNumber'], $Debtor['LeadTimeFirst'], $Debtor['LeadTimeFollow'], Management::servicePerson()->entityPersonById( $Id) );
            return new Success( 'Der Debitor ist erfasst worden' )
            .new Redirect( '/Sphere/Billing/Banking', 2 );
        }

        return $View;

    }




}