<?php
require_once("conf.php");
global $yhendus;
session_start();
if (isset($_POST["register"])) {
    $login = htmlspecialchars(trim($_POST['login']));
    $pass = htmlspecialchars(trim($_POST['pass']));

    $sool = "taiestisuvalinetekst";
    $krypt = crypt($pass, $sool);

    global $yhendus;
    $kask = $yhendus->prepare("INSERT INTO kasutajad (kasutaja, parool) VALUES (?,?)");
    $kask->bind_param("ss", $login, $krypt);
    $success = $kask->execute();

    if ($success) {
        echo '<script>window.location.href ="haldustleht.php";</script>';

        exit();
    } else {
        echo "Registreerimine ebaõnnestus. Palun proovige uuesti.";
    }

    $kask->close();

}

?>
<h1>Registreerimine</h1>
<form action="" method="post">

    <br>
    <div id="registerModal" class="modal">

        <div class="modal-content">


            <h2>Register</h2>
            <form method="post" action="haldustleht.php" onsubmit="return validateRegistration()">
                <label for="login">Nickname:</label>
                <input type="text" id="login" name="login" required>

                <label for="pass">Password:</label>
                <input type="password" id="pass" name="pass" required>

                <label for="confirmPass">Confirm Password:</label>
                <input type="password" id="confirmPass" name="confirmPass" required>

                <input type="submit" name="register" value="Register">
            </form>
        </div>
    </div>
    <br>
</form>
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