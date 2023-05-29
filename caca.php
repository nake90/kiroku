<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
<title>記緑 - Caca</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="colores.css">
</head>


<body style="height:100%">

<div style="text-align:center;">
    <h1>記緑 - Caca</h1>
</div>

<form action=add.php>

<!--<input type="hidden" id="colorcaca" name="colorcaca" value="">-->
<?php
require 'colors.php';
$canary = rand();
echo "<input type=\"hidden\" id=\"canary\" name=\"canary\" value=\"$canary\">";
?>

<div class="addNuevo">
    <a href="index.php" class="cancelar">Cancelar</a>
    <input type="submit" value="Guardar">
</div>

<hr>

<table>
<tr>
    <th><label style="font-size: 18vw"><input type="checkbox" id="what" name="what" value="caca" />💩️</label></th>
    <th><label style="font-size: 18vw"><input type="checkbox" id="what2" name="what2" value="pipi" checked />💧️</label></th>
</tr>
<tr>
    <td><label style="font-size: 12vw; color: #ece8ba"><input type="radio" id="colorcaca" name="colorcaca" value="1" />&#9632;</label></td>
    <td></td>
</tr>
<tr>
    <td><label style="font-size: 12vw; color: #dcd7a3"><input type="radio" id="colorcaca" name="colorcaca" value="2" />&#9632;</label></td>
    <td></td>
</tr>
<tr>
    <td><label style="font-size: 12vw; color: #d8b86e"><input type="radio" id="colorcaca" name="colorcaca" value="3" />&#9632;</label></td>
    <td></td>
</tr>
<tr>
    <td><label style="font-size: 12vw; color: #fcc33d"><input type="radio" id="colorcaca" name="colorcaca" value="4" checked />&#9632;</label></td>
    <td></td>
</tr>
<tr>
    <td><label style="font-size: 12vw; color: #a66414"><input type="radio" id="colorcaca" name="colorcaca" value="5" />&#9632;</label></td>
    <td></td>
</tr>
<tr>
    <td><label style="font-size: 12vw; color: #ad5e1a"><input type="radio" id="colorcaca" name="colorcaca" value="6" />&#9632;</label></td>
    <td></td>
</tr>
<tr>
    <td><label style="font-size: 12vw; color: #61390a"><input type="radio" id="colorcaca" name="colorcaca" value="7" />&#9632;</label></td>
    <td></td>
</tr>
<tr>
    <td><label style="font-size: 12vw; color: #1b130f"><input type="radio" id="colorcaca" name="colorcaca" value="N" />&#9632;</label></td>
    <td></td>
</tr>
<tr>
    <td><label style="font-size: 12vw; color: #a60000"><input type="radio" id="colorcaca" name="colorcaca" value="R" />&#9632;</label></td>
    <td></td>
</tr>

</table>

<div class="memoInput">
    <label for="fmemo">Notas:</label>
    <textarea rows=4" id="fmemo" name="fmemo" class="memoInputText"></textarea>
</div>

<hr>

<label for="horaManual">Hora de registro:</label>
<input type="datetime-local" id="horaManual" name="horaManual">
<!--
<input type="datetime-local" id="meeting-time"
       name="meeting-time" value="2018-06-12T19:30"
       min="2018-06-07T00:00" max="2018-06-14T00:00">
-->
</form>

</body>
</html>


