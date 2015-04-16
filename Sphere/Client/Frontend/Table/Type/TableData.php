<?php
namespace KREDA\Sphere\Client\Frontend\Table\Type;

use KREDA\Sphere\Client\Frontend\Table\Structure\TableBody;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableColumn;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableHead;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableRow;
use KREDA\Sphere\Client\Frontend\Table\Structure\TableTitle;
use KREDA\Sphere\Common\AbstractEntity;

/**
 * Class TableData
 *
 * @package KREDA\Sphere\Common\Frontend\Table
 */
class TableData extends Table
{

    /**
     * @param string|AbstractEntity[] $DataList
     * @param TableTitle              $TableTitle
     * @param array                   $ColumnDefinition
     * @param bool|array              $Interactive
     */
    public function __construct(
        $DataList,
        TableTitle $TableTitle = null,
        $ColumnDefinition = array(),
        $Interactive = true
    )
    {

        /**
         *
         */
        if (is_bool( $DataList )) {
            $DataList = array();
        }

        /**
         * Server-Side-Processing
         */
        if (is_string( $DataList ) && ( $Interactive || is_array( $Interactive ) )) {

            $DataColumns = array_keys( $ColumnDefinition );
            array_walk( $DataColumns, function ( &$V ) {

                $V = array( 'data' => $V );
            } );
            if (is_array( $Interactive )) {
                $Interactive = array_merge_recursive( $Interactive, array(
                    "processing" => true,
                    "serverSide" => true,
                    "ajax"       => ( false === strpos( self::getUrlBase().$DataList,
                        '?' ) ? self::getUrlBase().$DataList.'?REST=true' : self::getUrlBase().$DataList.'&REST=true' ),
                    "columns"    => $DataColumns
                ) );
            } else {
                $Interactive = array(
                    "processing" => true,
                    "serverSide" => true,
                    "ajax" => ( false === strpos( self::getUrlBase().$DataList, '?' )
                        ? self::getUrlBase().$DataList.'?REST=true'
                        : self::getUrlBase().$DataList.'&REST=true'
                    ),
                    "columns"    => $DataColumns
                );
            }
            $DataList = array();
        }

        /**
         *
         */
        if (!is_array( $DataList )) {
            $DataList = array( $DataList );
        }
        if (empty( $ColumnDefinition ) && !empty( $DataList )) {
            if (is_object( current( $DataList ) )) {
                /** @var AbstractEntity[] $DataList */
                $GridHead = array_keys( current( $DataList )->__toArray() );
            } else {
                $GridHead = array_keys( current( $DataList ) );
            }
        } elseif (!empty( $ColumnDefinition )) {
            // Rename by ShowCol
            $GridHead = array_values( $ColumnDefinition );
        } else {
            $GridHead = array();
        }

        array_walk( $GridHead, function ( &$V ) {

            $V = new TableColumn( $V );
        } );

        /** @var TableRow[] $DataList */
        /** @noinspection PhpUnusedParameterInspection */
        array_walk( $DataList, function ( &$Row, $Index, $Content ) {

            array_walk( $Row, function ( &$Column, $Index, $Content ) {

                /**
                 * With object, use getter instead of property (if available)
                 */
                if (is_object( $Column ) && method_exists( $Content[1], 'get'.substr( trim( $Index ), 2 ) )) {
                    $Column = $Content[1]->{'get'.substr( trim( $Index ), 2 )}();
                }
                /**
                 * Other values
                 */
                if (empty( $Content[0] )) {
                    $Column = new TableColumn( $Column );
                } elseif (in_array( preg_replace( '!^[^a-z0-9_]*!is', '', $Index ), array_keys( $Content[0] ) )) {
                    $Column = new TableColumn( $Column );
                } else {
                    $Column = false;
                }
            }, array( $Content, $Row ) );
            // Convert to Array
            if (is_object( $Row )) {
                /** @var AbstractEntity $Row */
                $Row = array_filter( $Row->__toArray() );
            } else {
                $Row = array_filter( $Row );
            }
            /** @var array $Row */
            // Sort by ShowCol
            $Row = array_merge( array_flip( array_keys( $Content ) ), $Row );
            /** @noinspection PhpParamsInspection */
            $Row = new TableRow( $Row );
        }, $ColumnDefinition );

        if (count( $DataList ) > 0 || $Interactive) {
            parent::__construct(
                new TableHead( new TableRow( $GridHead ) ), new TableBody( $DataList ), $TableTitle,
                $Interactive, null
            );
        } else {
            parent::__construct(
                new TableHead( new TableRow( $GridHead ) ), new TableBody( $DataList ), $TableTitle, false, null
            );
        }
    }

}
