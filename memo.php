<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
<title>記緑 - Notas</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="colores.css">
</head>


<body style="height:100%">

<div style="text-align:center;">
    <h1>記緑 - Notas</h1>
</div>


<form action=add.php>

<?php
$canary = rand();
echo "<input type=\"hidden\" id=\"canary\" name=\"canary\" value=\"$canary\">";
?>

<div class="addNuevo">
    <a href="index.php" class="cancelar">Cancelar</a>
    <input type="submit" value="Guardar">
</div>

<hr>

<div class="memoInput">
    <label for="fmemo">Notas:</label>
    <textarea rows="4" id="fmemo" name="fmemo" class="memoInputText"></textarea>
</div>

<hr>

<label for="horaManual">Hora de registro:</label>
<input type="datetime-local" id="horaManual" name="horaManual">

</form>

</body>
</html>


