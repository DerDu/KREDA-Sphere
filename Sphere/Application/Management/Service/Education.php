<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Gatekeeper\Gatekeeper;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblLevel;
use KREDA\Sphere\Application\Management\Service\Education\EntityAction;

/**
 * Class Education
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Education extends EntityAction
{

    /**
     *
     */
    function __construct()
    {

        if (false !== ( $tblConsumer = Gatekeeper::serviceConsumer()->entityConsumerBySession() )) {
            $Consumer = $tblConsumer->getDatabaseSuffix();
        } else {
            $Consumer = 'Annaberg';
        }
        $this->setDatabaseHandler( 'Management', 'Education', $Consumer );
    }

    public function setupDatabaseContent()
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        $this->actionCreateSubject( 'Astronomie', 'Ast' );
        $this->actionCreateSubject( 'Biologie', 'Bio' );
        $this->actionCreateSubject( 'Chemie', 'Ch' );
        $this->actionCreateSubject( 'Deutsch', 'De' );
        $this->actionCreateSubject( 'Deutsch als Zweitsprache', 'DaZ' );
        $this->actionCreateSubject( 'Englisch', 'En' );
        $this->actionCreateSubject( 'Ethik', 'Eth' );
        $this->actionCreateSubject( 'Französisch', 'Fr' );
        $this->actionCreateSubject( 'Förderunterricht Mathematik', 'FöMa' );
        $this->actionCreateSubject( 'Gemeinschaftskunde/Rechtserziehung', 'GK' );
        $this->actionCreateSubject( 'Geographie', 'Geo' );
        $this->actionCreateSubject( 'Geschichte', 'Ge' );
        $this->actionCreateSubject( 'Informatik', 'Inf' );
        $this->actionCreateSubject( 'Klassenleiterstunde', 'KL' );
        $this->actionCreateSubject( 'Kunst', 'Ku' );
        $this->actionCreateSubject( 'Künstlerisches Profil', 'Pk' );
        $this->actionCreateSubject( 'Latein', 'La' );
        $this->actionCreateSubject( 'Mathematik', 'Ma' );
        $this->actionCreateSubject( 'Musik', 'Mu' );
        $this->actionCreateSubject( 'Neigungskurs', 'Nk' );
        $this->actionCreateSubject( 'Physik', 'Ph' );
        $this->actionCreateSubject( 'Profil Geisteswissensch.', 'Pg' );
        $this->actionCreateSubject( 'Profil Naturwissenschaften', 'Pn' );
        $this->actionCreateSubject( 'Religion evangelisch', 'ReE' );
        $this->actionCreateSubject( 'Russisch', 'Ru' );
        $this->actionCreateSubject( 'Sorbisch', 'Sor' );
        $this->actionCreateSubject( 'Sport', 'Spo' );
        $this->actionCreateSubject( 'Technik und Natur', 'TuN' );
        $this->actionCreateSubject( 'Technik/Computer', 'TC' );
        $this->actionCreateSubject( 'Vertiefungskurs', 'VK' );
        $this->actionCreateSubject( 'Wirtschaft-Technik-Haushalt/Soziales', 'WTH' );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblLevel
     */
    public function entityLevelById( $Id )
    {

        $this->getDebugger()->addMethodCall( __METHOD__ );

        return parent::entityLevelById( $Id );
    }
}
