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
//komentaaridw lisamine
if(isset($_REQUEST["komment"])) {
    if(isset($_REQUEST["uuskomment"])&& !empty($_REQUEST["uuskomment"])){
    global $yhendus;
    $kask=$yhendus->prepare("Update tantsud SET kommentaarid=CONCAT(kommentaarid, ?)where id=? ");
    $kommentplus= $_REQUEST["uuskomment"]. "\n";
    $kask->bind_param("si", $kommentplus, $_REQUEST["komment"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
    }
if (isset($_REQUEST["kustutakomment"])) {
    global $yhendus;
    $kask = $yhendus->prepare("UPDATE tantsud SET kommentaarid='' WHERE id=?");
    $kask->bind_param("i", $_REQUEST["kustutakomment"]);
    $kask->execute();
    header("Location: $_SERVER[PHP_SELF]");
    $yhendus->close();
}
if (isset($_POST["register"])) {
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));

    $sool = "taiestisuvalinetekst";
    $krypt = crypt($pass, $sool);

    global $yhendus;
    $kask = $yhendus->prepare("INSERT INTO kasutajad (kasutaja, parool) VALUES (?, ?)");
    $kask->bind_param("ss", $login, $krypt);
    $success = $kask->execute();

    if ($success) {
        header("Location: $_SERVER[PHP_SELF]");
        exit();
    } else {
        echo "Registreerimine ebaõnnestus. Palun proovige uuesti.";
    }

    $kask->close();
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
    <h1>Tantsud tähtedega</h1>
    <?php
    if(isset($_SESSION['kasutaja'])){
        ?>


        <h1>Tere, <?="$_SESSION[kasutaja]"?></h1>
        <a href="logout.php">Logi välja</a>
        <br>
        <?php
    }else{
        ?>
        <a href="login.php">Logi sisse</a>
        <br>
        <a href="registreerimine.php">Registreeri siin</a>
        <br>

        <br>
        <?php
    }
    ?>



</heder>


<?php
if(isAdmin()){
    echo"<h3><a href='adminleht.php'>Vaata AdminLeht</a></h3>";
}

?>
<?php
if(isset($_SESSION["kasutaja"])) {

?>
    <h2>Punktide Lisamine</h2>
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
    echo"<td>".$komment=nl2br(htmlspecialchars($komment))."</td>";
    echo"<td>
<form action='?'>
<input type='hidden' value='$id' name='komment'>lisa uus kommentaar
<input type='text' name='uuskomment' id='uuskomment'>
<input type='submit' value='ok'>

</form>
";
    if(isAdmin()){
        echo "<td><a href='?kustuta=$id'>kustuta</a></td>";
        echo "<td><a href='?kustutakomment=$id'>Kustuta kommentaar</a></td>";

    }
    else if(!isAdmin()){
        echo"<td><a href='?heatants=$id'>Lisa+1punkt</a></td><td><a href='?pahatants=$id'>  emalda-1punkt</a></td>";

    }
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
<script>


    function openRegisterModal() {

        document.getElementById('registerModal').style.display = 'block';
    }

    function closeRegisterModal() {
        document.getElementById('registerModal').style.display = 'none';
    }

    window.onclick = function (event) {
        var modal = document.getElementById('registerModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
    function validateRegistration() {
        var password = document.getElementById('pass').value;
        var confirmPass = document.getElementById('confirmPass').value;

        // Check if passwords match
        if (password !== confirmPass) {
            alert('Paroolid ei vasta.');
            return false;
        }



        return true;
    }
</script>
</html>

