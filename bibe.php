<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
<title>記緑 - Biberón</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="colores.css">

<style>

th, td {
    border: 1px solid black;
    padding: 5px;
}

</style>

<script>
function bombaIzqDerClick()
{
    var c = document.getElementsByClassName('bomba');
    
    for (var i = 0; i < c.length; i++)
    {
        if (c[i].type == 'radio')
        {
            c[i].checked = true;
        }
    }
}

function bombaCheckAll()
{
    var c = document.getElementsByClassName('bomba_lado');
    
    for (var i = 0; i < c.length; i++)
    {
        if (c[i].type == 'checkbox')
        {
            c[i].checked = true;
        }
    }
}

function bombaUncheckAll()
{
    var c = document.getElementsByClassName('bomba_lado');
    
    for (var i = 0; i < c.length; i++)
    {
        if (c[i].type == 'checkbox')
        {
            c[i].checked = false;
        }
    }
}
</script>

</head>


<body style="height:100%">

<?php
require 'config.php';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbDatabase);

if ( $conn->connect_error )
{
    die ( 'Database connection error' );
}
?>

<div style="text-align:center;">
    <h1>記緑 - Biberón</h1>
</div>


<form action=add.php>

<?php
$canary = rand();
echo "<input type=\"hidden\" id=\"canary\" name=\"canary\" value=\"$canary\">";

function roundUpToAny($n,$x=5) {
    return (round($n)%$x === 0) ? round($n) : round(($n+$x/2)/$x)*$x;
}

$stmt = $conn->prepare("SELECT what, data, UNIX_TIMESTAMP(logtime) as unixtime FROM $dbTable WHERE logtime >= ? AND (what = 'bomba-izq' OR what = 'bomba-der' OR what = 'biberon-mama') ORDER BY logtime ASC LIMIT 100");
$stmt->bind_param("s", $startStr);

$diaActual = new DateTimeImmutable();
$offsetDias = new DateInterval('P6D');
$diaStart = $diaActual->sub($offsetDias);

$startStr = $diaStart->format('c');

if ( $stmt == false )
{
    die ('Database query error');
}

$stmt->execute();
$result = $stmt->get_result();

$maxDiffSeconds = 30*60; // 30min
$mergeTimeStart = 0;
$botellas = array();
$first = true;
$arrayIndexNext = 0;
while ($row = $result->fetch_assoc())
{
    //if (str_starts_with($row['what'], 'bomba-'))
    if (substr( $row['what'], 0, 6 ) === 'bomba-')
    {
        if ($first || $row['unixtime'] - $mergeTimeStart > $maxDiffSeconds)
        {
            //printf("<p>NUEVA Botella %d : %d ml</p>\n", sizeof($botellas), (int)$row['data']);
            
            // New bottle
            $first = false;
            $botellas[] = (int)$row['data']; // push
        }
        else
        {
            //printf("<p>ADD %d ml</p>\n", (int)$row['data']);
            // Last bottle
            $key = array_key_last($botellas);
            $botellas[$key] += (int)$row['data'];
        }
        
        $mergeTimeStart = $row['unixtime'];
    }
    elseif ($row['what'] === 'biberon-mama')
    {
        $toRemove = (int)$row['data'];
        
        //printf("<p>BEBE %d ml</p>\n", $toRemove);
        
        for ($index = $arrayIndexNext ; $index < sizeof($botellas) ; $index++)
        {
            if ($botellas[$index] - $toRemove <= 0) // Fully drank
            {
                $toRemove -= $botellas[$index];
                $botellas[$index] = 0;
                $arrayIndexNext = $index +1;
                
                //printf("<p>Botella %d BEBIDA. Left %d por beber</p>\n", $index, $toRemove);
                
                if ($toRemove <= 5)
                {
                    $toRemove = 0;
                    break;
                }
            }
            else
            {
                $botellas[$index] -= $toRemove;
                $toRemove = 0;
                $botellas[$index] = roundUpToAny($botellas[$index], 5);
                if ($botellas[$index] <= 5)
                {
                    $botellas[$index] = 0;
                    $arrayIndexNext = $index +1;
                }
                
                //printf("<p>Botella %d BEBIDA PARCIAL. Left %d en la botella</p>\n", $index, $botellas[$index]);
                
                break;
            }
        }
    }
}

$botellas = array_slice($botellas, $arrayIndexNext); // Remove extra zeros

// Round to multiple of 5
for ($index = 0 ; $index < sizeof($botellas) ; $index++)
{
    $botellas[$index] = roundUpToAny($botellas[$index], 5);
}

echo '<h3>Botellas estimadas:</h3>';

for ($index = 0 ; $index < sizeof($botellas) ; $index++)
{
    printf("<p>Botella %d : %d ml</p>\n", $index, $botellas[$index]);
}

?>


<input type="hidden" id="what" name="what" value="biberon">

<div class="addNuevo">
    <a href="index.php" class="cancelar">Cancelar</a>
    <input type="submit" value="Guardar">
</div>

<hr>

<table>
<tr>
    <td colspan="2">
        <label for="ml">Cantidad:</label>
        <input type="text" inputmode="decimal" id="ml" name="ml" /> ml
    </td>
</tr>

<tr>
    <td>
        <label style="font-size: 12vw"><input type="radio" id="what" name="what" value="biberon" onclick="bombaUncheckAll()" checked />🥫🍼</label>
    </td>
    <td>
        <label style="font-size: 12vw"><input type="radio" id="what" name="what" value="biberon-mama" onclick="bombaUncheckAll()" />💧👨‍🍼</label>
    </td>
</tr>

<tr>
    <td rowspan=2>
        <label style="font-size: 12vw"><input class="bomba" type="radio" id="what" name="what" value="bomba" onclick="bombaCheckAll()" />💧⬇️</label>
    </td>
    <td>
        <label style="font-size: 12vw"><input class="bomba_lado" type="checkbox" id="bomba_izq" name="bomba_izq" onclick="bombaIzqDerClick()" />左</label>
    </td>
</tr>
<tr>
    <td>
        <label style="font-size: 12vw"><input class="bomba_lado" type="checkbox" id="bomba_der" name="bomba_der" onclick="bombaIzqDerClick()" />右</label>
    </td>
</tr>

</table>

<div class="memoInput">
    <label for="fmemo">Notas:</label>
    <textarea rows=4" id="fmemo" name="fmemo" class="memoInputText"></textarea>
</div>

<hr>

<label for="horaManual">Hora de registro:</label>
<input type="datetime-local" id="horaManual" name="horaManual">

</form>

</body>
</html>


