<?php
require_once ('conf.php');
// punktide lisamine
session_start();
if(isset($_REQUEST["heatants"])){
    global $yhendus;
    $kask=$yhendus->prepare("Update tantsud Set punktd=punktd+1 WHERE id=?");
    $kask->bind_param("i", $_REQUEST["heatants"]);
    $kask->execute();
}
if(isset($_REQUEST["pahatants"])){
    global $yhendus;
    $kusk=$yhendus->prepare("Update tantsud set punktd=punktd-1 where id=?");
    $kusk->bind_param("i", $_REQUEST["pahatants"]);
    $kusk->execute();
}
if(isset($_REQUEST["paarinimi"]) && !empty($_REQUEST["paarinimi"]) && isAdmin()){
    global $yhendus;
    $kask=$yhendus->prepare("insert into tantsud (tantsupaar, ava_paev) values(?,Now()) ");
    $kask->bind_param("s", $_REQUEST["paarinimi"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
if(isset($_REQUEST["kustuta"])){
    global $yhendus;
    $kask=$yhendus->prepare("delete from tantsud where id=?");
    $kask->bind_param("i", $_REQUEST["kustuta"]);
    $kask->execute();
}
function isAdmin(){
    return isset($_SESSION['onAdmin']) && $_SESSION['onAdmin'];
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
<?php
if(isAdmin()){
    echo"<h3><a href='adminleht.php'>AdminLeht</a></h3>";
}
?>
<?php
if(isset($_SESSION["kasutaja"])) {

?>
<table>
    <tr>
        <th>Tantsupaari nimi</th>
        <th>punktid</th>
        <th>Paev</th>
        <th>Kommentaarid</th>
    </tr>
<?php

global $yhendus;
$kask=$yhendus->prepare("Select id, tantsupaar, punktd, ava_paev, kommentaarid From tantsud where avalik=1");
$kask->bind_result($id,$tantsupaar, $punktd, $paev, $komment);
$kask->execute();
while($kask->fetch()){

    echo "<tr>";
    $tantsupaar=htmlspecialchars($tantsupaar);
    echo "<td>".$tantsupaar."</td>";
    echo "<td>".$punktd."</td>";
    echo "<td>".$paev."</td>";
    echo "<td>".$komment."</td>";
    echo"<td>
<form action='?'>
<input type='hidden' value='$id' name='komment'>
<input type='text' name='uuskomment' id='uuskomment'>
        <input type='submit' value='ok'>
</form>
";
    if(isAdmin()){

    }
    else if(!isAdmin()){
        echo"<td><a href='?heatants=$id'>Lisa+1punkt</a></td><td><a href='?pahatants=$id'>  emalda-1punkt</a></td>";
    }
    echo "<td><a href='?kustuta=$id'>kustuta</a></td>";
    echo "</tr>";
}
?>
</table>
<?php

if(!isAdmin()){ ?>


    <form action="?">
        <label for="paarinimi">Lisa uus paar</label>
        <input type="text" name="paarinimi" id="paarinimi">
        <input type="submit" value="ok">
    </form>
    <?php } }  ?>


</body>
</html>

