<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
<title>記緑 - Hora</title>
<meta charset="UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="colores.css">

<style>

th, td {
    border: 1px solid black;
    padding: 5px;
}

tr:nth-child(even) {
    background-color: var(--cancel-color);
}

</style>
</head>

<body>

<?php
require 'config.php';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbDatabase);

if ( $conn->connect_error )
{
    die ( 'Database connection error' );
}
?>

<div style="text-align:center;">
    <h1>記緑 - Hora</h1>
</div>

<hr>

<?php
    $diaActual = new DateTime();
    
    if ( isset($_GET['dia']) )
    {
        $tmpdate = DateTime::createFromFormat('Ymd', $_GET['dia'] );
        if ( $tmpdate && $tmpdate->format('Ymd') == $_GET['dia'] )
        {
            $diaActual = $tmpdate;
        }
        else
        {
            die('No se ha especificado el dia');
        }
    }
    else
    {
        die('No se ha especificado el dia');
    }
    
    if ( isset($_GET['hora']) && (int)$_GET['hora'] >= 0 && (int)$_GET['hora'] < 24 )
    {
        $hora = (int)$_GET['hora'];
        $diaActual->setTime((int)$_GET['hora'], 0);
    }
    else
    {
        die('No se ha especificado la hora');
    }

    $dateFmt = datefmt_create(
        'es_ES',
        \IntlDateFormatter::FULL, // date
        \IntlDateFormatter::NONE, // time
        'Asia/Tokyo',
        IntlDateFormatter::GREGORIAN,
        'd MMM'
    );
    
    //$diaActualStr = $dateFmt->format($diaActual);
    //$diaActualUrl = $diaActual->format('Ymd');
?>

<?php
$canary = rand();
echo "<input type=\"hidden\" id=\"canary\" name=\"canary\" value=\"$canary\">";
?>

<div class="addNuevo">
    <a href="index.php" class="cancelar">Volver</a>
</div>

<hr>

<table style="">
    <thead><tr>
        <th class="hour">時間</th>
        <th>Evento</th>
        <th style="width: 100%">Datos</th>
        <!--<th>Quién</th>-->
        <th>Editar</th>
    </tr></thead>
    <tbody>
    <?php
        $stmt = $conn->prepare("SELECT uid, what, data, by_who, DATE_FORMAT(logtime, '%H:%i:%s') as hora FROM $dbTable WHERE logtime >= ? AND logtime <= ? ORDER BY logtime ASC LIMIT 100");
        $stmt->bind_param("ss", $startStr, $endStr);
        
        if ( $stmt == false )
        {
            die ('Database query error');
        }
        
        $startStr = $diaActual;
        $startStr->setTime($hora, 0, 0);
        $startStr = $startStr->format('c');
        
        $endStr = $diaActual;
        $endStr->setTime($hora, 59, 59);
        $endStr = $endStr->format('c');
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc())
        {
            $logtime = $row['hora'];
            $evento = $row['what'];
            $datos = $row['data'];
            $quien = $row['by_who'];
            $units = '';
            $inputmode = '';
            $noedit = false;
            
            if ( $row['what'] == 'caca' )
            {
                $evento = '💩️';
            }
            elseif ( $row['what'] == 'pipi' )
            {
                $evento = '💧️';
                $noedit = true;
            }
            elseif ( $row['what'] == 'teta_izq' )
            {
                $evento = '🤱️左';
                $units = 'min';
                $datos = round($datos / 60.0, 2);
                $inputmode= 'inputmode="decimal"';
            }
            elseif ( $row['what'] == 'teta_der' )
            {
                $evento = '🤱️右';
                $units = 'min';
                $datos = round($datos / 60.0, 2);
                $inputmode= 'inputmode="decimal"';
            }
            elseif ( $row['what'] == 'biberon' )
            {
                $evento = '🥫🍼️';
                $units = 'ml';
                $inputmode= 'inputmode="decimal"';
            }
            elseif ( $row['what'] == 'biberon-mama' )
            {
                $evento = '🤱→👨‍🍼';
                $units = 'ml';
                $inputmode= 'inputmode="decimal"';
            }
            elseif ( $row['what'] == 'bomba-izq' )
            {
                $evento = '⛽左';
                $units = 'ml';
                $inputmode= 'inputmode="decimal"';
            }
            elseif ( $row['what'] == 'bomba-der' )
            {
                $evento = '⛽右';
                $units = 'ml';
                $inputmode= 'inputmode="decimal"';
            }
            elseif ( $row['what'] == 'notas' )
            {
                $evento = '📝️';
            }
            
            if ($units != '')
            {
                $eventoStr = $evento . ' (' . $units . ')';
            }
            else
            {
                $eventoStr = $evento;
            }
            
            echo "<tr><form action=\"edit.php\">\n";
            
            printf("<td class=\"hour nowrap\">%s</td>\n", $logtime);
            printf("<td>%s</td>\n", $eventoStr);
            //printf("<td class=\"nowrap\"><textarea rows=\"1\" id=\"newdata\" name=\"newdata\" class=\"memoInputText\" style=\"font-size: 4vw\">%s</textarea></td>\n", $datos);
            echo '<td class="nowrap">';
            if ($noedit == false)
            {
                printf("<input type=\"text\" %s id=\"newdata\" name=\"newdata\" class=\"memoInputText\" style=\"font-size: 4vw\" value=\"%s\" />", $inputmode, $datos);
            }
            echo '</td>\n';
            //printf("<td>%s</td>\n", $quien);
            
            echo '<td>';
            $uid = $row['uid'];
            $dia = $_GET['dia'];
            echo "<input type=\"hidden\" id=\"uid\" name=\"uid\" value=\"$uid\">";
            echo "<input type=\"hidden\" id=\"dia\" name=\"dia\" value=\"$dia\">";
            echo "<input type=\"hidden\" id=\"hora\" name=\"hora\" value=\"$hora\">";
            echo "<input type=\"hidden\" id=\"units\" name=\"units\" value=\"$units\">";
            echo '<input type="submit" name="accion" value="💾️" style="font-size: 4vw">';
            echo '<input type="submit" name="accion" value="💣️" style="font-size: 4vw"  onclick="return confirm(\'¿Seguro que quieres borrarlo?\');">';
            echo "</td>\n";
            
            echo "</form></tr>\n";
        }
        
        $stmt->close();
    ?>
    </tbody>
</table>

</body>
</html>


