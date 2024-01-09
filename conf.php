<?php
$kasutaja='luca';
$serverinimi='localhost';
$parool='123456';
$andmebaas='luca';
$yhendus=new mysqli($serverinimi, $kasutaja, $parool, $andmebaas);
$yhendus->set_charset('UTF8');

?>
