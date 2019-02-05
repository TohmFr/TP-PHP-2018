<?php
/**
 * Created by PhpStorm.
 * User: Thomas BARDY
 * Date: 11/10/2018
 * Time: 09:51
 */

class MotsBD
{
    static private $_instance;


    /**
     * Ovtient le singleton
     * @return MotsBD
     */
    static public function getInstance(){
        if(empty(self::$_instance)){
            self::$_instance = new MotsBD();
        }
        return self::$_instance;
    }


    /**
     * @var PDO
     */
    private $_pdo;

    protected function __construct()
    {
        $stringConnect = 'mysql:host=127.0.0.1;dbname=tp;charset=utf8';
        $this->_pdo = new PDO($stringConnect, 'root', '');


    }

    /**
     * Retourn le nombre de mot
     * @return int
     */
    public function getMotsCount(){
        $query = <<<SQL
        SELECT count(*) count 
        FROM mots
        WHERE mot not in (SELECT mot FROM motstrouvees)
SQL;


        $resultCount = $this->_pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        return (int) $resultCount['count'];
    }

    /**
     * Retourn le nombre de mot sans filtrage sur les mots trouvés
     * @return int
     */
    public function getMotsFullCount(){
        $query = <<<SQL
        SELECT count(*) count 
        FROM mots
SQL;



        $resultCount = $this->_pdo->query($query)->fetch(PDO::FETCH_ASSOC);
        return (int) $resultCount['count'];
    }

    /**
     * Retourn un mot a un position
     * @param int $pos
     * @return string
     */
    public function getMotAtPos($pos)
    {
        $query = <<<SQL
          SELECT TRIM(mot) mot 
          FROM mots 
          WHERE mot not in (SELECT mot FROM motstrouvees)
          LIMIT 1 OFFSET $pos
SQL;

        $stmtMot = $this->_pdo->query($query);
        $stmtMot->execute();
        $resultMot = $stmtMot->fetch(PDO::FETCH_ASSOC);
        return (string) $resultMot['mot'];
    }

    /**
     * Enregistre un mot dans la table motTrouvees
     * @param string $mot
     * @param bool $trouve
     * @return bool
     */
    public function saveMotTrouvee($mot, $trouve = false){
        $stmtInsert = $this->_pdo->prepare("INSERT INTO motsTrouvees (mot, trouve) VALUE (:mot, :trouve)");
        $stmtInsert->bindValue(":mot",$mot, PDO::PARAM_STR);
        $stmtInsert->bindValue(":trouve", $trouve, PDO::PARAM_BOOL);
        return $stmtInsert->execute();
    }

    /**
     * Obtient la liste des mots trouvées
     * @return array
     */
    public function getMotsTrouves(){
        $this->_pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES ,false);
        $stmt = $this->_pdo->query("SELECT * FROM motstrouvees");
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);

    }

    /**
     * @return Generator
     * @yield string
     */
    public function getGeneratorListMots(){
        $this->_pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
        $this->_pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES ,false);
        $stmt = $this->_pdo->query("SELECT `mots`.mot FROM `mots`");
        $stmt->execute();
        while($mot = $stmt->fetch()){
            yield $mot["mot"];
        }

    }
}