<?php
namespace KREDA\Sphere\Application\Billing\Frontend;

use KREDA\Sphere\Application\Billing\Billing;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtor;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblDebtorCommodity;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblPaymentType;
use KREDA\Sphere\Application\Billing\Service\Banking\Entity\TblReference;
use KREDA\Sphere\Application\Billing\Service\Commodity\Entity\TblCommodity;
use KREDA\Sphere\Application\Management\Management;
use KREDA\Sphere\Client\Component\Element\Repository\Content\Stage;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BarCodeIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\BuildingIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ConversationIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\DisableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EditIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\EnableIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\GroupIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\ListIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MinusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\MoneyIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\NameplateIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PersonIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\PlusIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\RemoveIcon;
use KREDA\Sphere\Client\Component\Parameter\Repository\Icon\TimeIcon;
use KREDA\Sphere\Client\Frontend\Button\Form\SubmitPrimary;
use KREDA\Sphere\Client\Frontend\Button\Link\Danger;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormColumn;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormGroup;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormRow;
use KREDA\Sphere\Client\Frontend\Form\Structure\FormTitle;
use KREDA\Sphere\Client\Frontend\Input\Type\DatePicker;
use KREDA\Sphere\Client\Frontend\Input\Type\SelectBox;
use KREDA\Sphere\Client\Frontend\Input\Type\TextField;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutColumn;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutGroup;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutRow;
use KREDA\Sphere\Client\Frontend\Layout\Structure\LayoutTitle;
use KREDA\Sphere\Client\Frontend\Layout\Type\Layout;
use KREDA\Sphere\Client\Frontend\Layout\Type\LayoutPanel;
use KREDA\Sphere\Client\Frontend\Message\Type\Success;
use KREDA\Sphere\Client\Frontend\Table\Type\TableData;
use KREDA\Sphere\Common\AbstractFrontend;
use KREDA\Sphere\Client\Frontend\Button\Link\Primary;
use KREDA\Sphere\Client\Frontend\Form\Type\Form;

/**
 * Class Account
 *
 * @package KREDA\Sphere\Application\Billing\Frontend
 */
class Banking extends AbstractFrontend
{
    /**
     * @return Stage
     */
    public static function frontendBanking()
    {
        $View = new Stage();
        $View->setTitle( 'Debitoren' );
        $View->setDescription( 'Übersicht' );
        $View->setMessage( 'Zeigt die verfügbaren Debitoren an' );
        $View->addButton(
            new Primary( 'Debitor anlegen', '/Sphere/Billing/Banking/Person', new PlusIcon() )
        );

        $tblDebtorAll = Billing::serviceBanking()->entityDebtorAll();

        if (!empty( $tblDebtorAll )) {
            array_walk( $tblDebtorAll, function ( TblDebtor &$tblDebtor ) {
                $referenceCommodityList = Billing::serviceBanking()->entityReferenceByDebtor( $tblDebtor );
                $referenceCommodity = '';
                if($referenceCommodityList)
                {
                    for ($i = 0; $i < count($referenceCommodityList); $i++)
                    {
                        $tblCommodity = $referenceCommodityList[$i]->getServiceBillingCommodity();
                        if ($tblCommodity)
                        {
                            if ($i === 0)
                            {
                                $referenceCommodity .= $tblCommodity->getName();
                            }
                            else
                            {
                                $referenceCommodity .= ', ' . $tblCommodity->getName() ;
                            }
                        }
                    }
                }
                $tblDebtor->ReferenceCommodity = $referenceCommodity;

                $debtorCommodityList = Billing::serviceBanking()->entityCommodityDebtorAllByDebtor( $tblDebtor );
                $debtorCommodity = '';
                if($debtorCommodityList)
                {
                    for ($i = 0; $i < count($debtorCommodityList); $i++)
                    {
                        $tblCommodity = $debtorCommodityList[$i]->getServiceBillingCommodity();
                        if ($tblCommodity)
                        {
                            if ($i === 0)
                            {
                                $debtorCommodity .= $tblCommodity->getName();
                            }
                            else
                            {
                                $debtorCommodity .= ', ' . $tblCommodity->getName() ;
                            }
                        }
                    }
                }
                $tblDebtor->DebtorCommodity = $debtorCommodity;


                $tblPerson = $tblDebtor->getServiceManagementPerson();
                if(!empty($tblPerson))
                {
                    $tblDebtor->FirstName = $tblPerson->getFirstName();
                    $tblDebtor->LastName = $tblPerson->getLastName();
                }
                else
                {
                    $tblDebtor->FirstName = 'Person nicht vorhanden';
                    $tblDebtor->LastName = 'Person nicht vorhanden';
                }

                $tblDebtor->Edit =
                    (new Primary( 'Leistungen auswählen', '/Sphere/Billing/Banking/Commodity/Select',
                        new ListIcon(), array(
                            'Id' => $tblDebtor->getId()
                        ) ))->__toString().
                    (new Primary( 'Bearbeiten', '/Sphere/Billing/Banking/Debtor/Edit',
                        new EditIcon(), array(
                            'Id' => $tblDebtor->getId()
                        ) ) )->__toString().
                    (new Danger( 'Löschen', '/Sphere/Billing/Banking/Delete',
                        new RemoveIcon(), array(
                            'Id' => $tblDebtor->getId()
                        ) ) )->__toString();

                $Bankname = $tblDebtor->getBankName();
                $IBAN = $tblDebtor->getIBAN();
                $BIC = $tblDebtor->getBIC();
                $Owner = $tblDebtor->getOwner();
                if(!empty( $Bankname ) && !empty( $IBAN ) && !empty( $BIC ) && !empty( $Owner ) )
                {
                    $tblDebtor->BankInformation = new Success( new EnableIcon().' OK' );
                }
                else
                {
                    $tblDebtor->BankInformation = new \KREDA\Sphere\Client\Frontend\Message\Type\Danger( new DisableIcon().' Nicht OK' );
                }

            } );
        }

        $View->setContent(
            new Layout(array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new TableData( $tblDebtorAll, null,
                                array(
                                    'DebtorNumber' => 'Debitoren-Nr',
                                    'FirstName' => 'Vorname',
                                    'LastName' => 'Nachname',
                                    'ReferenceCommodity' => 'Mandatsreferenzen',
                                    'DebtorCommodity' => 'Leistungszuordnung',
                                    'BankInformation' => 'Bankdaten',
                                    'Edit' => 'Verwaltung'
                                ))
                            ))
                        ))
                    ))
                ))
            );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingDelete( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Debitor' );
        $View->setDescription( 'Entfernen' );

        $tblDebtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $View->setContent(Billing::serviceBanking()->executeBankingDelete( $tblDebtor ));

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingCommoditySelect( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Leistungen' );
        $View->setDescription( 'Hinzufügen' );
        $View->addButton( new Primary( 'Zurück','/Sphere/Billing/Banking' ) );

        $IdPerson = Billing::serviceBanking()->entityDebtorById( $Id )->getServiceManagementPerson();
        $tblPerson = Management::servicePerson()->entityPersonById( $IdPerson );
        if(!empty($tblPerson))
        {
            $Person = $tblPerson->getFullName();
        }
        else
        {
            $Person = 'Person nicht vorhanden';
        }
        $DebtorNumber = Billing::serviceBanking()->entityDebtorById( $Id )->getDebtorNumber();
//        $View->setMessage('Name: '.$Person.'<br/> Debitorennummer: '.Billing::serviceBanking()->entityDebtorById( $Id )->getDebtorNumber());

        $tblDebtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $tblCommodityAll = Billing::serviceCommodity()->entityCommodityAll();

        $tblDebtorCommodityList = Billing::serviceBanking()->entityCommodityDebtorAllByDebtor( $tblDebtor );
        $tblCommodityByDebtorList = Billing::serviceBanking()->entityCommodityAllByDebtor( $tblDebtor );

        if (!empty( $tblCommodityByDebtorList )) {
            $tblCommodityAll = array_udiff( $tblCommodityAll, $tblCommodityByDebtorList,
                function ( TblCommodity $ObjectA, TblCommodity $ObjectB ) {

                    return $ObjectA->getId() - $ObjectB->getId();
                }
            );
        }

        if (!empty( $tblDebtorCommodityList )) {
            array_walk( $tblDebtorCommodityList, function ( TblDebtorCommodity &$tblDebtorCommodity ) {

                $tblCommodity = $tblDebtorCommodity->getServiceBillingCommodity();
                $tblDebtorCommodity->Name = $tblCommodity->getName();
                $tblDebtorCommodity->Description = $tblCommodity->getDescription();
                $tblDebtorCommodity->Type = $tblCommodity->getTblCommodityType()->getName();
                $tblDebtorCommodity->Option =
                    ( new Danger( 'Entfernen', '/Sphere/Billing/Banking/Commodity/Remove',
                        new MinusIcon(), array(
                            'Id' => $tblDebtorCommodity->getId()
                        ) ) )->__toString();
            } );
        }

        if (!empty( $tblCommodityAll )) {
            array_walk( $tblCommodityAll, function ( TblCommodity &$tblCommodity, $Index, TblDebtor $tblDebtor ) {
                $tblCommodity->Type = $tblCommodity->getTblCommodityType()->getName();
                $tblCommodity->Option =
                    ( new Primary( 'Hinzufügen', '/Sphere/Billing/Banking/Commodity/Add',
                        new PlusIcon(), array(
                            'Id' => $tblDebtor->getId(),
                            'CommodityId' => $tblCommodity->getId()
                        ) ) )->__toString();
            }, $tblDebtor );
        }

        $View->setContent(
            new Layout( array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new LayoutPanel( new PersonIcon(). ' Debitor', $Person, LayoutPanel::PANEL_TYPE_SUCCESS
                                    )
                                ),6),
                            new LayoutColumn( array(
                                new LayoutPanel( new BarCodeIcon(). ' Debitornummer', $DebtorNumber, LayoutPanel::PANEL_TYPE_SUCCESS
                                    )
                                ),6)
                            ))
                ) /*, new LayoutTitle( 'Debitor' )*/ ),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblDebtorCommodityList, null,
                                    array(
                                        'Name'        => 'Name',
                                        'Description' => 'Beschreibung',
                                        'Type'        => 'Leistungsart',
                                        'Option' => 'Option'
                                    ))
                                ))
                            )),
                ), new LayoutTitle( 'zugewiesene Leistungen' ) ),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblCommodityAll, null,
                                    array(
                                        'Name'        => 'Name',
                                        'Description' => 'Beschreibung',
                                        'Type'        => 'Leistungsart',
                                        'Option' => 'Option'
                                    ))
                                ))
                            )),
                        ), new LayoutTitle( 'mögliche Leistungen' ) )
                    ))
                );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingCommodityRemove( $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Leistung' );
        $View->setDescription( 'Entfernen' );

        $tblDebtorCommodity = Billing::serviceBanking()->entityDebtorCommodityById( $Id );
        $View->setContent( Billing::serviceBanking()->executeRemoveDebtorCommodity( $tblDebtorCommodity ) );

        return $View;
    }

    /**
     * @param $Id
     * @param $CommodityId
     *
     * @return Stage
     */
    public static function frontendBankingCommodityAdd( $Id, $CommodityId )
    {
        $View = new Stage();
        $View->setTitle( 'Leistung' );
        $View->setDescription( 'Hinzufügen' );

        $tblDebtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $tblCommodity = Billing::serviceCommodity()->entityCommodityById( $CommodityId );
        $View->setContent( Billing::serviceBanking()->executeAddDebtorCommodity( $tblDebtor, $tblCommodity ) );

        return $View;
    }

    /**
     * @return Stage
     */
    public static function frontendBankingPerson()
    {
        $View = new Stage();
        $View->setTitle( 'Debitorensuche' );
        $View->addButton( new Primary( 'Zurück','/Sphere/Billing/Banking' ) );

        $tblPerson = Management::servicePerson()->entityPersonAll();

        if (!empty($tblPerson))
        {
            foreach ($tblPerson as $Person)
            {
                $PersonType = Management::servicePerson()->entityPersonById( $Person->getId() )->getTblPersonType();
                $Person->Option=
                    ( ( new Primary( 'Debitor erstellen', '/Sphere/Billing/Banking/Person/Select',
                        new EditIcon(), array(
                            'Id' => $Person->getId()
                        ) ) )->__toString());
                $Person->PersonType = $PersonType->getName();
            }
        }

        $View->setContent(
            new Layout(array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                new TableData( $tblPerson, null,
                                    array(
                                        'FirstName' => 'Vorname',
                                        'MiddleName' => 'Zweitname',
                                        'LastName' => 'Nachname',
                                        'PersonType' => 'Persontyp',
                                        'Option' => 'Debitor hinzufügen'
                                    )),
                                ))
                            ))
                        ))
                    ))
                );
        return $View;
    }

    /**
     * @param $Id
     * @param $Reference
     *
     * @return Stage
     */
    public static function frontendBankingReferenceSelect( $Id, $Reference )
    {
        $View = new Stage();
        $View->setTitle( 'Referenz' );
        $View->setDescription( 'hinzufügen' );
        $Debtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $Person = Management::servicePerson()->entityPersonById( $Debtor->getServiceManagementPerson()->getId() )->getFullName();
        $DebtorNumber = $Debtor->getDebtorNumber();
        $View->setMessage( $Person );
        $View->setContent(
            new Layout( array(
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutPanel( 'Debitor', $Person, LayoutPanel::PANEL_TYPE_PRIMARY
                            )),6),
                        new LayoutColumn( array(
                            new LayoutPanel( 'Debitornummer', $DebtorNumber, LayoutPanel::PANEL_TYPE_PRIMARY
                            )),6),
                    ))
                )),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                                Billing::serviceBanking()->executeAddReference(
                                    new Form( array(
                                        new FormGroup( array(
                                            new FormRow( array(
                                                new FormColumn(
                                                    new TextField( 'Reference[Reference]', 'Referenznummer', 'Referenz', new BarCodeIcon()
                                                    ), 6),
                                                new FormColumn(
                                                    new DatePicker( 'Reference[ReferenceDate]', 'Datum', 'Referenzdatum', new TimeIcon()
                                                    ), 6),
                                            )),
                                        ))
                                    ),  new SubmitPrimary( 'Hinzufügen' ))
                                , $Debtor, $Reference )
                            ))
                        ))
                    ))
                ))
            );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingReferenceDelete( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Referenz' );
        $View->setDescription( 'entfernt' );
        $Debtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $View->setContent(
            Billing::serviceBanking()->executeDeleteReference( $Debtor ) );

        return $View;
    }

    /**
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingReferenceDeactivate( $Id )
    {
        $View = new Stage();
        $View->setTitle( 'Referenz' );
        $View->setDescription( 'deaktiviert' );

        $tblReference = Billing::serviceBanking()->entityReferenceById( $Id );
        $View->setContent(Billing::serviceBanking()->setBankingReferenceDeactivate( $tblReference ));

        return $View;
    }

    /**
     * @param $Id
     * @param $Debtor
     * @param $Reference
     *
     * @return Stage
     */
    public static function frontendBankingDebtorEdit( $Id, $Debtor, $Reference )
    {
        $View = new Stage();
        $View->setTitle( 'Bankdaten' );
        $View->setDescription( 'bearbeiten' );
        $View->addButton( new Primary( 'Zurück','/Sphere/Billing/Banking' ) );
        $tblDebtor = Billing::serviceBanking()->entityDebtorById( $Id );
        $Person = Management::servicePerson()->entityPersonById( $tblDebtor->getServiceManagementPerson() );
        if (!empty($Person))
        {
            $Name = $Person->getFullName();
            $tblDebtorList = Billing::serviceBanking()->entityDebtorAllByPerson( $Person );
        }
        else
        {
            $Name = 'Person nicht vorhanden';
            $tblDebtorList = false;
        }

        $DebtorNumber = $tblDebtor->getDebtorNumber();
        $ReferenceEntityList = Billing::serviceBanking()->entityReferenceByDebtor( $tblDebtor );

        $tblPaymentType = Billing::serviceBanking()->entityPaymentTypeAll();
        $PaymentType = Billing::serviceBanking()->entityPaymentTypeById( Billing::serviceBanking()->entityDebtorById( $Id )->getPaymentType() )->getName();


        $DebtorArray = array();
        $DebtorArray[] = $tblDebtor;
        if ($tblDebtorList && $DebtorArray)
        {
            $tblDebtorList = array_udiff($tblDebtorList, $DebtorArray,
                function(TblDebtor $invoiceA, TblDebtor $invoiceB){
                    return $invoiceA->getId() - $invoiceB->getId();
                });
        }

        if ($ReferenceEntityList)
            {
                foreach ($ReferenceEntityList as $ReferenceEntity)
                {
                    $ReferenceReal = Billing::serviceCommodity()->entityCommodityById( $ReferenceEntity->getServiceBillingCommodity() );
                    if($ReferenceReal !== false)
                    { $ReferenceEntity->Commodity = $ReferenceReal->getName(); }
                    else
                    { $ReferenceEntity->Commodity = 'Leistung nicht vorhanden'; }


                    $ReferenceEntity->Option =
                        (new Danger( 'Deaktivieren', '/Sphere/Billing/Banking/Reference/Select/Deactivate',
                            new RemoveIcon(), array(
                                'Id' => $ReferenceEntity->getId()
                            ) ))->__toString();
                }
            }

        $tblCommoditySelectBox = Billing::serviceCommodity()->entityCommodityAll();
        $tblReferenceList = Billing::serviceBanking()->entityReferenceByDebtor( $tblDebtor );
        $tblCommodityUsed = array();
        foreach($tblReferenceList as $tblReference)
        {
            $tblCommodityUsedReal = Billing::serviceCommodity()->entityCommodityById( $tblReference->getServiceBillingCommodity() );
            if($tblCommodityUsedReal !== false)
            { $tblCommodityUsed[] = $tblCommodityUsedReal; }
        }
        if ($tblCommoditySelectBox && $tblCommodityUsed)
        {
            $tblCommoditySelectBox = array_udiff($tblCommoditySelectBox, $tblCommodityUsed,
                function(TblCommodity $invoiceA, TblCommodity $invoiceB){
                    return $invoiceA->getId() - $invoiceB->getId();
                });
        }

        $Global = self::extensionSuperGlobal();
        if (!isset( $Global->POST['Debtor']) )
        {
            $Global->POST['Debtor']['Description'] = $tblDebtor->getDescription();
            $Global->POST['Debtor']['PaymentType'] = Billing::serviceBanking()->entityPaymentTypeByName( $PaymentType )->getId();
            $Global->POST['Debtor']['Owner'] = $tblDebtor->getOwner();
            $Global->POST['Debtor']['IBAN'] = $tblDebtor->getIBAN();
            $Global->POST['Debtor']['BIC'] = $tblDebtor->getBIC();
            $Global->POST['Debtor']['CashSign'] = $tblDebtor->getCashSign();
            $Global->POST['Debtor']['BankName'] = $tblDebtor->getBankName();
            $Global->POST['Debtor']['LeadTimeFirst'] = $tblDebtor->getLeadTimeFirst();
            $Global->POST['Debtor']['LeadTimeFollow'] = $tblDebtor->getLeadTimeFollow();
            $Global->savePost();
        }

            $View->setContent(
                new Layout( array(
                    new LayoutGroup( array(
                        new LayoutRow( array(
                            new LayoutColumn( array(
                                new LayoutPanel( new PersonIcon(). ' Person', $Name, LayoutPanel::PANEL_TYPE_WARNING )
                            ),6),
                            new LayoutColumn( array(
                                new LayoutPanel( new BarCodeIcon(). ' Debitornummer', $DebtorNumber, LayoutPanel::PANEL_TYPE_WARNING )
                            ),6),
                            new LayoutColumn( array(
                                    Billing::serviceBanking()->executeEditDebtor(
                                        new Form( array(
                                            new FormGroup( array(
                                                new FormRow( array(
                                                    new FormColumn(
                                                        new TextField( 'Debtor[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                                                        ), 12 ),
                                                    new FormColumn(
                                                        new SelectBox( 'Debtor[PaymentType]', 'Bezahlmethode', array( TblPaymentType::ATTR_NAME => $tblPaymentType ) , new ConversationIcon()
                                                        ), 4 ),
                                                    new FormColumn(
                                                        new TextField( 'Debtor[LeadTimeFirst]', 'In Tagen', 'Ersteinzug', new TimeIcon()
                                                        ), 4 ),
                                                    new FormColumn(
                                                        new TextField( 'Debtor[LeadTimeFollow]', 'In Tagen', 'Folgeeinzug', new TimeIcon()
                                                        ), 4 ),
                                                ))
                                            ), new FormTitle('Debitor') ),
                                            new FormGroup( array(
                                                new FormRow( array(
                                                    new FormColumn(
                                                        new TextField( 'Debtor[Owner]', 'Vorname Nachname', 'Inhaber', new PersonIcon()
                                                        ), 6 ),
                                                    new FormColumn(
                                                        new TextField( 'Debtor[BankName]', 'Bank', 'Name der Bank', new BuildingIcon()
                                                        ), 6 ),
                                                    new FormColumn(
                                                        new TextField( 'Debtor[IBAN]', 'XX XX XXXXXXXX XXXXXXXXXX', 'IBAN', new BarCodeIcon()
                                                        ), 4 ),
                                                    new FormColumn(
                                                        new TextField( 'Debtor[BIC]', 'XXXX XX XX XXX', 'BIC', new BarCodeIcon()
                                                        ), 4 ),
                                                    new FormColumn(
                                                        new TextField( 'Debtor[CashSign]', ' ', 'Kassenzeichen', new NameplateIcon()
                                                        ), 4 ),
                                                ) )
                                            ), new FormTitle('Bankdaten'))
                                        ), new SubmitPrimary( 'Änderungen speichern' )),$tblDebtor, $Debtor))
                            ),
                        ))
                    )),
                    ( !empty( $tblCommoditySelectBox ) ) ?
                    new LayoutGroup( array(
                        new LayoutRow( array(
                            new LayoutColumn( array(
                                Billing::serviceBanking()->executeAddReference(
                                    new Form( array(
                                        new FormGroup( array(
                                            new FormRow( array(
                                                new FormColumn(
                                                    new TextField( 'Reference[Reference]', 'Referenz', 'Mandatsreferenz', new BarCodeIcon()
                                                    ), 4),
                                                new FormColumn(
                                                    new DatePicker( 'Reference[ReferenceDate]', 'Datum', 'Erstellungsdatum', new TimeIcon()
                                                    ), 4),
                                                new FormColumn(
                                                    new SelectBox( 'Reference[Commodity]', 'Leistung', array( 'Name' => $tblCommoditySelectBox ) ,  new TimeIcon()
                                                    ), 4),
                                            )),
                                        ))
                                    ),  new SubmitPrimary( 'Hinzufügen' ))
                                    , $tblDebtor, $Reference, $Id )
                            ))
                        ))
                    ), new LayoutTitle( 'Referenz hinzufügen' )) : null,
                    ( !empty($ReferenceEntityList) ) ?
                        new LayoutGroup( array(
                            new LayoutRow( array(
                                new LayoutColumn( array(
                                    new TableData( $ReferenceEntityList, null,
                                        array(
                                            'Reference' => 'Mandatsreferenz',
                                            'ReferenceDate' => 'Datum',
                                            'Commodity' => 'Leistung',
                                            'Option' => 'Deaktivieren'
                                        ))
                                ))
                            ))
                        ), new LayoutTitle( 'Mandatsreferenz' ) ) : null ,
                    ( count($tblDebtorList) >= 1 ) ?
                    new LayoutGroup( array(
                        new LayoutRow( array(
                            new LayoutColumn( array(

                                new Form( array(
                                    new FormGroup( array(
                                        new FormRow( array(
                                            new FormColumn( array(
                                                new TableData( $tblDebtorList, null, array(
                                                    'DebtorNumber' => 'Debitorennummer',
                                                    'BankName' => 'Name der Bank',
                                                    'IBAN' => 'IBAN',
                                                    'BIC' => 'BIC',
                                                    'Owner' => 'Inhaber'
                                                ))
                                            ))
                                        ))
                                    ))
                                ))
                            ),12)
                        ))
                    ), new LayoutTitle( 'Weitere Debitorennummer(n)' ) )
                    : null ,
                ))
            );

        return $View;
    }

    /**
     * @param $Debtor
     * @param $Id
     *
     * @return Stage
     */
    public static function frontendBankingPersonSelect( $Debtor, $Id )
    {

        $View = new Stage();
        $View->setTitle( 'Debitoreninformationen' );
        $View->addButton( new Primary( 'Zurück','/Sphere/Billing/Banking/Person' ) );

        $PersonName = Management::servicePerson()->entityPersonById( $Id )->getFullName();
        $PersonType = Management::servicePerson()->entityPersonById( $Id )->getTblPersonType();
        $tblPaymentType = Billing::serviceBanking()->entityPaymentTypeAll();
        $tblCommodity = Billing::serviceCommodity()->entityCommodityAll();
        $tblPerson = Management::servicePerson()->entityPersonById( $Id );

        $tblStudent = Management::serviceStudent()->entityStudentByPerson( $tblPerson );
        if ($tblStudent)
        {
            if ( $tblStudent->getStudentNumber() === 0 )
            {
                $tblStudent->setStudentNumber( 'Nicht vergeben' );
            }
        }

        $Global = self::extensionSuperGlobal();
        $Global->POST['Debtor']['Owner'] = $PersonName;

        if( !isset( $Global->POST['Debtor']['PaymentType'] ) ) {
            $Global->POST['Debtor']['PaymentType'] = Billing::serviceBanking()->entityPaymentTypeByName( 'SEPA-Lastschrift' )->getId();
        }
        if ( Billing::serviceBanking()->entityDebtorByServiceManagementPerson( $Id ) == true )
        {
            $tblDebtor = Billing::serviceBanking()->entityDebtorByServiceManagementPerson( $Id );
        }

        $Global->savePost();
        $View->setContent(
            new Layout( array(
                new LayoutGroup( array(
                    ( empty( $tblStudent ) ) ?
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutPanel( new PersonIcon().' Debitor', $PersonName, LayoutPanel::PANEL_TYPE_WARNING
                            )),6),
                        new LayoutColumn( array(
                            new LayoutPanel( new GroupIcon().'. Personengruppe', $PersonType->getName(), LayoutPanel::PANEL_TYPE_WARNING
                            )),6),
                    )): null,
                    ( !empty( $tblStudent ) ) ?
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new LayoutPanel( new PersonIcon().' Debitor', $PersonName, LayoutPanel::PANEL_TYPE_WARNING
                            )),4),
                        new LayoutColumn( array(
                            new LayoutPanel( new GroupIcon().'. Schülernummer', $tblStudent->getStudentNumber(), LayoutPanel::PANEL_TYPE_PRIMARY
                            )),4),
                        new LayoutColumn( array(
                            new LayoutPanel( new GroupIcon().'. Personengruppe', $PersonType->getName(), LayoutPanel::PANEL_TYPE_WARNING
                            )),4),
                    )): null,
                )),
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            Billing::serviceBanking()->executeAddDebtor(
                                new Form( array(
                                    new FormGroup( array(
                                        new FormRow( array(
                                            new FormColumn(
                                                new TextField( 'Debtor[DebtorNumber]', 'Debitornummer', 'Debitornummer', new BarCodeIcon()
                                                ), 12),
                                            new FormColumn(
                                                new SelectBox( 'Debtor[PaymentType]', 'Bezahlmethode', array( TblPaymentType::ATTR_NAME => $tblPaymentType ), new MoneyIcon()
                                                ), 4),
                                            new FormColumn(
                                                new TextField( 'Debtor[LeadTimeFirst]', 'Vorlaufzeit in Tagen', 'Ersteinzug', new TimeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new TextField( 'Debtor[LeadTimeFollow]', 'Vorlaufzeit in Tagen', 'Folgeeinzug', new TimeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new TextField( 'Debtor[Description]', 'Beschreibung', 'Beschreibung', new ConversationIcon()
                                                ), 12),
                                        ))
                                    ),new FormTitle( 'Debitor' )),
                                    new FormGroup( array(
                                        new FormRow( array(
                                            new FormColumn(
                                                new TextField( 'Debtor[Owner]', 'Vorname Nachname', 'Inhaber', new PersonIcon()
                                                ), 6),
                                            new FormColumn(
                                                new TextField( 'Debtor[BankName]', 'Name der Bank', 'Name der Bank', new BuildingIcon()
                                                ), 6),
                                            new FormColumn(
                                                new TextField( 'Debtor[IBAN]', 'XX XX XXXXXXXX XXXXXXXXXX', 'IBAN', new BarCodeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new TextField( 'Debtor[BIC]', 'XXXX XX XX XXX', 'BIC', new BarCodeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new TextField( 'Debtor[CashSign]', 'Kassenzeichen', 'Kassenzeichen', new NameplateIcon()
                                                ), 4),
                                        ))
                                    ),new FormTitle( 'Bankdaten' )),
                                    new FormGroup( array(
                                        new FormRow( array(
                                            new FormColumn(
                                                new TextField( 'Debtor[Reference]', 'Referenz', 'Mandatsreferenz', new BarCodeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new DatePicker( 'Debtor[ReferenceDate]', 'Datum', 'Erstellungsdatum', new TimeIcon()
                                                ), 4),
                                            new FormColumn(
                                                new SelectBox( 'Debtor[Commodity]', 'Leistung', array( 'Name' => $tblCommodity ) , new TimeIcon()
                                                ), 4),
                                        )),
                                    ),new FormTitle( 'Mandatsreferenz' ))
                                ), new SubmitPrimary( 'Hinzufügen' )), $Debtor, $Id )
                        ))
                    ))
                )),
                ( !empty( $tblDebtor ) ) ?
                new LayoutGroup( array(
                    new LayoutRow( array(
                        new LayoutColumn( array(
                            new Form( array(
                                new FormGroup( array(
                                    new FormRow( array(
                                        new FormColumn( array(
                                            new TableData( $tblDebtor, null, array(
                                                'DebtorNumber' => 'Debitorennummer',
                                                'BankName' => 'Name der Bank',
                                                'IBAN' => 'IBAN',
                                                'BIC' => 'BIC',
                                                'Owner' => 'Inhaber'
                                            ))
                                        ))
                                    ))
                                ))
                            ))
                        ),12)
                    ))
                ), new LayoutTitle( 'Vorhandene Debitorennummer(n)' ) ) : null,
            ))
        );

        return $View;
    }

}