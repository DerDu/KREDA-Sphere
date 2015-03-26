<?php
namespace KREDA\Sphere\Application\Management\Service;

use KREDA\Sphere\Application\Management\Service\Education\Entity\TblCategory;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblGroup;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblLevel;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubject;
use KREDA\Sphere\Application\Management\Service\Education\Entity\TblSubjectGroup;
use KREDA\Sphere\Application\Management\Service\Education\EntityAction;
use KREDA\Sphere\Common\Database\Handler;

/**
 * Class Education
 *
 * @package KREDA\Sphere\Application\Management\Service
 */
class Education extends EntityAction
{

    /** @var null|Handler $DatabaseHandler */
    protected static $DatabaseHandler = null;

    /**
     *
     */
    final public function __construct()
    {

        $this->setDatabaseHandler( 'Management', 'Education', $this->getConsumerSuffix() );
    }

    public function setupDatabaseContent()
    {

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

        return parent::entityLevelById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblGroup
     */
    public function entityGroupById( $Id )
    {

        return parent::entityGroupById( $Id );
    }

    /**
     * @param int $Id
     *
     * @return bool|TblSubjectGroup
     */
    public function entitySubjectGroupById( $Id )
    {

        return parent::entitySubjectGroupById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblSubject
     */
    public function entitySubjectById( $Id )
    {

        return parent::entitySubjectById( $Id );
    }

    /**
     * @param integer $Id
     *
     * @return bool|TblCategory
     */
    public function entityCategoryById( $Id )
    {

        return parent::entityCategoryById( $Id );
    }
}
