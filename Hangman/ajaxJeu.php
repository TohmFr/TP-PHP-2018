<?php
/**
 * Created by PhpStorm.
 * User: Thomas BARDY
 * Date: 10/10/2018
 * Time: 14:06
 */

//ob_start();

CONST MODE_XML = true;

header('Content-type: application/json');
session_start();

require_once 'Mots.class.php';
require_once 'MotsXml.class.php';
//require_once 'WorkerThread.class.php';

$action = "game";

if(!empty($_POST['action'])){
    $action = $_POST['action'];
}
if(!empty($_GET['action'])){
    $action = $_GET['action'];
}
$result =null;

switch ($action) {
    case 'listMotsTrouves':
        $result=MotsBD::getInstance()->getMotsTrouves();
        break;
    case 'generateXML':
        MotsXml::generateXML();

        break;
    //debug
    case 'getRandomWordXML':
        $result = MotsXml::getRandomWord();
        break;

    case 'incrementWord':
        $result=MotsXml::incrementWord($_GET["id"]);
        break;
    case 'game':

        $mot = '';
        $vie = 0;
        $motPartial = '';
        if (!empty($_POST['init'])||!empty($_GET['init'])) {

            if(MODE_XML){
                $motXML = MotsXml::getRandomWord();
                $mot = $motXML['mot'];
                $vie = (int)$motXML['nbLive'];
                $_SESSION['idMot'] = $motXML['id'] ;

            }else{
                $mot = Mots::getRandomMot();
                if (!empty($_POST['vie'])) {
                    $vie = (int)$_POST['vie'];
                }
            }


            $motPartial = Mots::stripAccents($mot);
            $motPartial = str_replace('-','_',$motPartial);
            $_SESSION['vie']=$vie;
            $_SESSION['mot'] = $mot;
        } else {
            $mot = $_SESSION['mot'];
            $vie = (int)$_SESSION['vie'];
            $motPartial = $_SESSION['motPartial'];
        }

        $lettre = '';
        if (!empty($_POST['lettre'])) {
            $lettre = $_POST['lettre'];

            if (strpos($motPartial, $lettre) !== false) {

                $motPartial = str_replace($lettre, '_', $motPartial);
            } else {
                $vie--;
            }
        }


        $arrMotPartial = preg_split('//u', $motPartial, null, PREG_SPLIT_NO_EMPTY);
        $arrMot = preg_split('//u', $mot, null, PREG_SPLIT_NO_EMPTY);

        $motPartielAAfficher = '';
        foreach ($arrMotPartial as $key => $value) {
            if ($value === '_') {
                $motPartielAAfficher .= $arrMot[$key];
            } else {
                $motPartielAAfficher .= '_';
            }

        }

        $gagne = (strpos($motPartielAAfficher, '_') === false);
        $fini = $vie <= 0 || $gagne;

        if ($fini) {
            if (MODE_XML) {
                if (!empty($_SESSION['idMot'])) {
                    MotsXml::incrementWord($_SESSION['idMot'], $gagne);
                    $_SESSION['idMot'] = null;
                }
            } else {
                MotsBD::getInstance()->saveMotTrouvee($mot, $gagne);
            }

        }


        $_SESSION['vie'] = $vie;
        $_SESSION['motPartial'] = $motPartial;


        $result = [
            'motPartiel' => $motPartielAAfficher, //$motPartielAAfficher
            'vie' => $vie,
            'fini' => $fini,
            'gagne' => $gagne,
            'motPartial'=>$motPartial
        ];


        if ($fini) {
            $result['mot'] = $mot;
        }
        break;
}

echo json_encode($result,JSON_NUMERIC_CHECK);




//ob_end_flush();
