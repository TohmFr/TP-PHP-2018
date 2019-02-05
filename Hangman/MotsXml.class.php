<?php
/**
 * Created by PhpStorm.
 * User: tbardy
 * Date: 12/10/2018
 * Time: 09:47
 */

require_once 'MotsBD.class.php';

class MotsXml
{
    private static $XML_PATH = './hangman.xml';
    private static $XML_LEVEL = <<<XML
  <levels>
    <level level="1" nbLive="10"></level>
    <level level="2" nbLive="5"></level>
    <level level="3" nbLive="3"></level>
  </levels>
XML;

    private static function calculateLevel($word){
        $nbDifferentLetter = count( array_unique(str_split($word)));

        $level =1;
        if($nbDifferentLetter>10){
            $level = 3;
        }elseif ($nbDifferentLetter>6){
            $level=2;
        }
        else{
            $level=1;
        }
        return $level;
    }

    public static function generateXML(){

        $id=0;
        $xmlWriter = new XMLWriter();

        $xmlWriter->openUri(self::$XML_PATH);
        $xmlWriter->setIndent(true);
        $xmlWriter->startDocument('1.0','utf-8', 'yes');

            $xmlWriter->startElement('hangman');
                $xmlWriter->writeComment("Level");
                $xmlWriter->writeRaw(self::$XML_LEVEL);
                $xmlWriter->writeComment("Words");
                $xmlWriter->startElement('words');
                $xmlWriter->writeAttribute('count',MotsBD::getInstance()->getMotsFullCount());
                foreach( MotsBD::getInstance()->getGeneratorListMots() as $mot){
                    $xmlWriter->startElement('word');
                        $xmlWriter->writeAttribute('id',"$id");
                        $xmlWriter->writeAttribute('nbFound','0');
                        $xmlWriter->writeAttribute('nbNotFound','0');
                        $xmlWriter->writeAttribute('level',(string)self::calculateLevel($mot) );

                        $xmlWriter->writeRaw($mot);
                    $xmlWriter->endElement();
                    $id++;
                }

                $xmlWriter->endElement();//word
            $xmlWriter->endElement();//hangman
        $xmlWriter->endDocument();
        $xmlWriter->flush();
    }

    public static function getRandomWord(){
        $xmlReader = new XMLReader();
        $xmlReader->open(self::$XML_PATH,'UTF-8');


        $xmlLevel = null;
        $xmlWord = null;
        $pos = 10;
        while($xmlReader->read() && $pos>0) {
            if ($xmlReader->nodeType == XMLReader::ELEMENT) {
                switch ($xmlReader->name) {
                    case 'levels':
                        $xmlLevel = new SimpleXMLElement($xmlReader->readOuterXml());
                        break;
                    case 'words':
                        $count = (int)$xmlReader->getAttribute("count");
                        $pos = mt_rand(0,$count);
                        break;
                    case 'word':
                        $pos --;
                        if($pos===0) {
                            $xmlWord = new SimpleXMLElement($xmlReader->readOuterXml());
                        }
                        break;

                }

            }
        }

        $xmlReader->close();


        $result = [];
        $result['id'] = (string) $xmlWord['id'];
        $level = (string) $xmlWord['level'];
        $result['level'] = (int) $level;
        $result['mot'] = (string)  $xmlWord;
        //var_dump($result);
        $nbLive = $xmlLevel->xpath("level[@level=\"$level\"]")[0]["nbLive"];

        $result['nbLive'] = (int) $nbLive;
        return $result;
    }

    public static function incrementWord($id, $found=false){

        $doc  = new DOMDocument();

        $doc->loadXML(file_get_contents(SELF::$XML_PATH));

        $xpath = new DOMXpath($doc);

        $word = $xpath->query("*/word[@id=$id]")[0];

        $attribut = ($found)? 'nbFound':'nbNotFound';

        $count = (int) $word->getAttribute($attribut);

        ++$count;

        $word->setAttribute($attribut,(string)$count);

        file_put_contents (SELF::$XML_PATH, $doc->saveXML());
    }


}