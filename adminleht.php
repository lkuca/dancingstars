<?php
require_once ('conf.php');

// punktide lisamine
session_start();

if(isset($_REQUEST["punktid0"])){
    global $yhendus;
    $kask=$yhendus->prepare("Update tantsud Set punktd=0 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["punktid0"]);
    $kask->execute();
}
//peitmine
if(isset($_REQUEST["peitmine"])){
    global $yhendus;
    $kask=$yhendus->prepare("Update tantsud Set avalik=0 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["peitmine"]);
    $kask->execute();
}
//näitmine
if(isset($_REQUEST["naitmine"])){
    global $yhendus;
    $kusk=$yhendus->prepare("Update tantsud set avalik=1 where id=?");
    $kusk->bind_param("i", $_REQUEST["naitmine"]);
    $kusk->execute();
}
if(isset($_REQUEST["pahatants"])){
    global $yhendus;
    $kusk=$yhendus->prepare("Update tantsud set punktd=punktd-1 where id=?");
    $kusk->bind_param("i", $_REQUEST["pahatants"]);
    $kusk->execute();
}
if(isset($_REQUEST["paarinimi"]) && !empty($_REQUEST["paarinimi"])){
    global $yhendus;
    $kask=$yhendus->prepare("insert into tantsud (tantsupaar, ava_paev) values(?,Now()) ");
    $kask->bind_param("s", $_REQUEST["paarinimi"]);
    $kask->execute();
    header("Location: $_REQUEST[PHP_SELF]");
    $yhendus->close();
}
if(isset($_REQUEST["kustuta"])){
    global $yhendus;
    $kask=$yhendus->prepare("delete from tantsud where id=?");
    $kask->bind_param("i", $_REQUEST["kustuta"]);
    $kask->execute();
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tantsud tähtetega</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<heder>
    <?php
    if(isset($_SESSION['kasutaja'])){
        ?>
    <h1>Tere, <?="$_SESSION[kasutaja]"?></h1>
    <a href="logout.php">Logi välja</a>
    <?php
    }else{
        ?>
    <a href="login.php">Logi sisse</a>
    <?php
    }
    ?>
</heder>
<h1>Tantsud tähtedega</h1>
<h2>Punktide lisamine</h2>
<h3><a href="haldustleht.php">kasutajaLeht</a></h3>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>punktid</th>
        <th>Paev</th>
        <th>kommentaar</th>
        <th>avalik</th>
    </tr>

<?php
global $yhendus;
$kask=$yhendus->prepare("Select id, tantsupaar, punktd, ava_paev,kommentaarid, avalik From tantsud");
$kask->bind_result($id,$tantsupaar, $punktd, $paev, $komment, $avalik);
$kask->execute();
while($kask->fetch()){
    $tekst="Näita";
    $seisund="naitamine";

    if($avalik==1){
        $tekst="Peida";
        $seisund="peitmine";
        $tekst2="kasutaja näeb";
    }
    else if($avalik==0){
        $tekst="Näita";
        $seisund="naitmine";
        $tekst2="kasutaja ei näe";
    }
    echo "<tr>";
    $tantsupaar=htmlspecialchars($tantsupaar);
    echo "<td>".$tantsupaar."</td>";
    echo "<td>".$punktd."</td>";
    echo "<td>".$paev."</td>";
    echo "<td>".$komment."</td>";
    echo "<td>".$avalik."/".$tekst2."</td>";
    echo "<td><a href='?punktid0=$id'>Punktid Nulliks</a></td><td><a href='?kustuta=$id'>kustuta</a></td>";
    echo"<td><a href='?$seisund=$id'>$tekst</a></td>";
    echo "</tr>";
}
?>

</table>

</body>
</html>

