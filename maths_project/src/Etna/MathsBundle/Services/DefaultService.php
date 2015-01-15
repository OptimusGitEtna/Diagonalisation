<?php
/**
 * Created by PhpStorm.
 * User: optimus
 * Date: 24/12/14
 * Time: 20:27
 */

namespace Etna\MathsBundle\Services;


class DefaultService {

     public function testService()
     {
        echo "coucou le service";
     }

    public function convertObjectToArray(&$oObject)
    {
        $aResult = array();
        $aResult['nom'] = $oObject->getNom();
        $aResult['resultat'] = 2;
        foreach ($oObject->getDigits() as $key => $oDigit)
        {
            $aResult[$oDigit->getPosition()] = $oDigit->getValue();
        }

        return $aResult;
    }
} 