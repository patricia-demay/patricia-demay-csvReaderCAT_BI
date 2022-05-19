<?php

namespace App\Service;


class FilterService{

     public function __construct()
    {

    }

    function redesign ($tab)
    {
        $lignes = array();
        for ($i=0;$i<count($tab);$i++){
                $name = trim(str_replace(' ', '', $tab[$i]["headerName"]));
                $index = $tab[$i]["ligne"];
                $lignes[$index][$name] = $tab[$i]["val"];
        }
        return $lignes;
    }

    function filtreByProject($tab,$projects){

       $projects = explode( ',', $projects );
       $filtredTab = array();
       foreach ($tab as $ligne){
           if(in_array($ligne['Nomduprojet'], $projects)){
               array_push($filtredTab,$ligne);
           }
        }
       return $filtredTab;
    }
    function filtreByPole($tab,$poles){
        $poles = explode( ',', $poles );
        $filtredTab = array();
        foreach ($tab as $ligne){
            if(in_array($ligne['Pole'], $poles)){
                array_push($filtredTab,$ligne);
            }
        }
        return $filtredTab;
    }
    function filtreByIntervenant($tab,$intervenants){

        $intervenants = explode( ',', $intervenants );
        $filtredTab = array();
        foreach ($tab as $ligne){
            foreach ( $intervenants as $intervenat){
                $res = $this->removeSpecialChar($ligne['IntervenantsCAT']);
                $outputString = $this->removeSpace($res);
                $res1 = $this->removeSpecialChar($intervenat);
                $outputString1 = $this->removeSpace($res1);

                if(str_contains($outputString, $outputString1) && !in_array($ligne, $filtredTab)){
                    array_push($filtredTab,$ligne);
                }
            }
        }
        return $filtredTab;
    }
    function removeSpecialChar($chaine){
        return  preg_replace('/[^a-zA-Z0-9_ -]/s',' ', $chaine);
    }
    function removeSpace($chaine){
        $searchString = " ";
        $replaceString = "";
        return str_replace($searchString, $replaceString, $chaine);
    }
    function filtreByOneProject($tab,$project){
        $filtredTab = array();
        foreach ($tab as $ligne){
            if($ligne['Nomduprojet']=== $project){
                return $ligne;
            }
        }
        return $filtredTab;

    }

    function filtreByBureau($tab,$bureaux){
        $filtredTab = array();
        foreach ($tab as $ligne){
            foreach ($bureaux as $bureau){
                if($ligne->getVal() === $bureau){
                    array_push($filtredTab,$ligne);
                }
            }
        }
        return $filtredTab;
    }
    function extractColValue($tab,$headerCol){
        $val = array();
        array_walk_recursive($tab, function($v, $k) use($headerCol, &$val){
            if($k == $headerCol) array_push($val, $v);
        });
        return count($val) > 1 ? $val : array_pop($val);
    }
    function dedoublonneTab($tab){

         return array_unique($tab);

    }

    function extract($tab){
        $architects= array();
        $integrateurs= array();
        foreach ($tab as $ligne){
            $values = explode('Intégrateur :', $ligne);
            if(count($values)>=2){
                array_push($integrateurs ,'Intégrateur :'. $values[1]);
            }
            if(!empty($values[0])){
                array_push($architects ,$values[0]);
            }
        }
        return  array_merge($architects, $integrateurs);

    }
}