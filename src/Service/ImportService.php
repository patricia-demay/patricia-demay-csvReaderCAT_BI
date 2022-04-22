<?php

namespace App\Service;

use App\Entity\Column;
use App\Entity\Entry;
use App\Entity\Valeur;
use App\Repository\ColumnRepository;
use Doctrine\Persistence\ManagerRegistry;

class ImportService
{
    private $em;
    private $rep;

    public function __construct( ManagerRegistry $doctrine,ColumnRepository $columnRepository)
    {
        $this->rep = $columnRepository;
        $this->em = $doctrine->getManager('default');
    }

    /**
     * Insert Column into bdd
     * @param $headerName
     * @param $ordre
     * @return Column
     */
    public function addColumn($headerName,$ordre)
    {
        $column = new Column();
        $column = $this->hydrateColumn($column,$headerName,$ordre);
        if($this->rep->findOneBy(array('ordre' => $ordre,'headerName'=>$headerName))===null){
            $this->rep->add($column);
            return $column;
        }
    }


    /**
     * @param Column $column
     * @param $headerName
     * @param $ordre
     * @return Column
     */
    private function hydrateColumn(Column $column, $headerName, $ordre)
    {
        $column->setHeaderName($headerName);
        $column->setOrdre($ordre);
        return $column;
    }


    /**
     * Insert Value into bdd
     * @param $valeur
     * @param $column
     * @param $entry
     * @return Valeur
     */
    public function addValue($valeur, $column, $entry)
    {
        $valueObj = new Valeur();
        $valueObj = $this->hydrateValue($valueObj,$valeur,$column,$entry);
        $this->em->persist((object)$valueObj);
        $this->em->flush();
        return $valueObj;
    }


    /**
     * @param Valeur $value
     * @param $valeur
     * @param $column
     * @param $entry
     * @return Valeur
     */
    private function hydrateValue(Valeur $value, $valeur, $column, $entry)
    {
        $value->setVal($valeur);
        $value->setCol($column);
        $value->setEntry($entry);
        return $value;
    }


    /**
     * Insert Entry to BDD
     * @param $indice
     * @param $file
     * @return Entry
     */
    public function addEntry($indice,$file)
    {
        $entry = new Entry();
        $entry = $this->hydrateEntry($entry,$indice,$file);
        $this->em->persist((object)$entry);
        return $entry;
    }


    /**
     * @param Entry $entry
     * @param $indice
     * @param $file
     * @return Entry
     */
    private function hydrateEntry(Entry $entry,$indice, $file)
    {
        $entry->setIndice($indice);
        $entry->setFile($file);
        return $entry;
    }
}