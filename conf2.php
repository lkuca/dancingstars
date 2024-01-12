<?php
$kasutaja='d123175_luca';
$sererinimi='d123175.mysql.zonevs.eu';
$parool='pentagonanetu27';
$andmebaas='d123175_andmebaas';
$yhendus=new mysqli($sererinimi,$kasutaja,$parool,$andmebaas);
$yhendus->set_charset('UTF8');
?>


if(isset($_SESSION[!'kasutaja']) && isset($_SESSION[!'onAdmin'])) {
