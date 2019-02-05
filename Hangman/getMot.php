<?php
/**
 * Created by PhpStorm.
 * User: tbardy
 * Date: 10/10/2018
 * Time: 11:59
 */

$stringConnect = 'mysql:host=127.0.0.1;dbname=tp;charset=utf8';
$pdo = new PDO($stringConnect,'root', '');

$resultCount = $pdo->query('select count(*) count from mots')->fetch(PDO::FETCH_ASSOC);
$nbMot = $resultCount["count"];


$offset = mt_rand(1,(int)$nbMot);
$stmtMot = $pdo->prepare("SELECT TRIM(mot) mot FROM mots LIMIT 1 OFFSET $offset");
$stmtMot->execute();
$resultMot = $stmtMot->fetch(PDO::FETCH_ASSOC);

echo json_encode(  $resultMot );
