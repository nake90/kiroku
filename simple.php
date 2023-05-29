<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
<title>記緑</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="colores.css">
<style>
    input.larger
    {
        width: 50px;
        height: 50px;
    }
</style>
</head>


<body style="height:100%; font-size: 10vw">

<form action=add.php>

<!--<input type="hidden" id="colorcaca" name="colorcaca" value="">-->
<?php
require 'colors.php';
$canary = rand();
echo "<input type=\"hidden\" id=\"canary\" name=\"canary\" value=\"$canary\">";
?>

<div class="addNuevo">
    <input type="submit" value="Guardar">
</div>

<input type="hidden" id="simple" name="simple" value="simple">

<table style="padding: 0px; margin-top: 0px">
<tr style="padding: 0px; margin-top: 0px">
    <th colspan="5"><label style="font-size: 16vw"><input type="checkbox" class="larger" id="what" name="what" value="caca" />💩️</label></th>
    <th colspan="4"><label style="font-size: 16vw"><input type="checkbox" class="larger" id="what2" name="what2" value="pipi" checked />💧️</label></th>
</tr>
<tr style="padding: 0px; margin-top: 0px">
    <td><label style="font-size: 10vw; color: #ece8ba"><input type="radio" class="larger" id="colorcaca" name="colorcaca" value="1" />&#9632;</label></td>
    <td><label style="font-size: 10vw; color: #dcd7a3"><input type="radio" class="larger" id="colorcaca" name="colorcaca" value="2" />&#9632;</label></td>
    <td><label style="font-size: 10vw; color: #d8b86e"><input type="radio" class="larger" id="colorcaca" name="colorcaca" value="3" />&#9632;</label></td>
    <td><label style="font-size: 10vw; color: #fcc33d"><input type="radio" class="larger" id="colorcaca" name="colorcaca" value="4" checked />&#9632;</label></td>
    <td><label style="font-size: 10vw; color: #a66414"><input type="radio" class="larger" id="colorcaca" name="colorcaca" value="5" />&#9632;</label></td>
    <td><label style="font-size: 10vw; color: #ad5e1a"><input type="radio" class="larger" id="colorcaca" name="colorcaca" value="6" />&#9632;</label></td>
    <td><label style="font-size: 10vw; color: #61390a"><input type="radio" class="larger" id="colorcaca" name="colorcaca" value="7" />&#9632;</label></td>
    <td><label style="font-size: 10vw; color: #1b130f"><input type="radio" class="larger" id="colorcaca" name="colorcaca" value="N" />&#9632;</label></td>
    <td><label style="font-size: 10vw; color: #a60000"><input type="radio" class="larger" id="colorcaca" name="colorcaca" value="R" />&#9632;</label></td>
</tr>

</table>

</form>

</body>
</html>


