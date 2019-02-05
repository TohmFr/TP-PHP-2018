<?php
/**
 * Created by PhpStorm.
 * User: tbardy
 * Date: 10/10/2018
 * Time: 09:58
 */

/*$sql = mysqli_connect('localhost','root','','Tp');
mysqli_begin_transaction($sql);
*/
$stringConnect = 'mysql:host=127.0.0.1;dbname=tp';
$pdo = new PDO($stringConnect,'root', '');


$stmt = $pdo->query('SELECT table_name  FROM information_schema.tables;');
/*while($results = $stmt->fetch()){

}*/
$results = $stmt->fetchAll(PDO::FETCH_ASSOC| PDO::ATTR_CASE);
var_dump($results);