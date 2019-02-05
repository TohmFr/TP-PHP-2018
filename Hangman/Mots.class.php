<?php
/**
 * Created by PhpStorm.
 * User: Thomas BARDY
 * Date: 10/10/2018
 * Time: 12:35
 */

require_once 'MotsBD.class.php';


class Mots
{
    /**
     * Récuper un mot du dictionnaire
     * @return string mot générer
     */
    public static  function getRandomMot(){
        $ok = false;
        $mot ='';

        $nbMot = MotsBD::getInstance()->getMotsCount();

        while (!$ok) {
            $pos = mt_rand(1, (int)$nbMot);
            $mot = MotsBD::getInstance()->getMotAtPos($pos);
            $ok = strlen($mot)>3;
        }

        return $mot;
    }

    /**
     * Enléve les accents
     * @param $str
     * @return string
     */
    public static function stripAccents($str) {
        return strtr(utf8_decode($str), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÃÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAAACEEEEIIIINOOOOOUUUUY');
    }

}

