<?php
/**
 * Created by PhpStorm.
 * User: tbardy
 * Date: 10/10/2018
 * Time: 11:42
 */
$file = file('T:\\PHP\\TP\\Hangman\\liste.de.mots.francais.frgut.txt');


$stringConnect = 'mysql:host=127.0.0.1;dbname=tp;charset=utf8';
$pdo = new PDO($stringConnect,'root', '');

$pdo->query("truncate table mots")->execute();

$stmt = $pdo->prepare('INSERT mots (mot) value(:mot)');

foreach ($file as $mot){
    $stmt->bindValue("mot",$mot);
    $stmt->execute();
}
