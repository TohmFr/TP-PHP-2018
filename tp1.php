<?php
session_start();

    if(!isset($_SESSION['value']) || (isset($_GET['init']) && $_GET['init']==='1' ) ) {
        $_SESSION['value'] = rand(0, 100);
        $_SESSION['histo'] = [];
    }
    $value = $_SESSION['value'];
$histo=[];
$nbTry = 0;
    if (!empty($_GET['v'])){

        $userValue = $_GET['v'];

        $histo = $_SESSION['histo'];
        $nbTry = count($histo);
    }
?>
<!DOCTYPE html>
<head>
<meta charset="UTF-8">
</head>
<body>
<h1>Trouver un nombre entre 0 et 100 </h1>
<?php
    if (isset($userValue)){

        $sens = '';
        if($value>$userValue){
            $sens = '>';
            echo '<h2>Valeur est plus grande</h2>';
        } elseif($value<$userValue){
            $sens = '<';
            echo '<h2>Valeur est plus petite</h2>';
        } else {
            $nbTry = $nbTry + 1;
            echo "<h2>Gagné en $nbTry coups</h2>";
        }
        if($sens!==''){
            $_SESSION['histo'][] =$sens. ' '. $userValue  ;
            $histo = $_SESSION['histo'];
            $nbTry = count($histo);
        }
    }
?>
<form action="">

    <input type="input" name="v" />
    <input type="submit" value="Envoyer" />
</form>
<br />
<form action="">
    <input type="hidden" name="init" value="1" />
    <input type="submit" value="Initialiser" />
</form>
<?php
     if($nbTry>0) {
         echo '<ul>';
        foreach ($histo as $v) {
            echo "<li>$v</li>";
        }
        echo '</ul>';
    }
?>
</body>