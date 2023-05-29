<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
<title>記緑</title>
<meta charset="UTF-8"> 
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="refresh" content="60">
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
require 'colors.php';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbDatabase);

if ( $conn->connect_error )
{
    die ( 'Database connection error' );
}
?>

<div style="text-align:center;">
    <h1>記緑</h1>
</div>

<div class="addNuevo">
    <a href="teta.php" class="addNuevo">🤱️</a>
    <a href="bibe.php" class="addNuevo">🍼️</a>
    <a href="caca.php" class="addNuevo">🚼️</a>
    <a href="memo.php" class="addNuevo">📝️</a>
    <a href="chart.php" class="addNuevo">📈</a>
</div>

<hr>

<div class="cambiofecha">
<?php
    $diaActual = new DateTimeImmutable();
    
    if ( isset($_GET['dia']) )
    {
        $tmpdate = DateTimeImmutable::createFromFormat('Ymd', $_GET['dia'] );
        if ( $tmpdate && $tmpdate->format('Ymd') == $_GET['dia'] )
        {
            $diaActual = $tmpdate;
        }
    }

    $undia = new DateInterval('P1D');
    
    $dateFmt = datefmt_create(
        'es_ES',
        \IntlDateFormatter::FULL, // date
        \IntlDateFormatter::NONE, // time
        'Asia/Tokyo',
        IntlDateFormatter::GREGORIAN,
        'd MMM'
    );
    
    $diaAnterior = $diaActual->sub($undia);
    $diaAnteriorStr = $dateFmt->format($diaAnterior);
    $diaAnteriorUrl = $diaAnterior->format('Ymd');
    
    $diaSiguiente = $diaActual->add($undia);
    $diaSiguienteStr = $dateFmt->format($diaSiguiente);
    $diaSiguienteUrl = $diaSiguiente->format('Ymd');
    
    $diaActualStr = $dateFmt->format($diaActual);
    $diaActualUrl = $diaActual->format('Ymd');
    
    //$hoyUrl = date('Ymd', time());

    echo "<div><a href=\"index.php?dia=$diaAnteriorUrl\" class=\"cambiofecha\">&laquo; $diaAnteriorStr</a></div>\n";
    echo "<div><a href=\"index.php?dia=$diaActualUrl\" class=\"cambiofecha\">$diaActualStr</a></div>\n";
    echo "<div>\n";
    echo "<a href=\"index.php?dia=$diaSiguienteUrl\" class=\"cambiofecha\">$diaSiguienteStr &raquo;</a>\n";
    //echo "<a href=\"index.php?dia=$hoyUrl\" class=\"cambiofecha\">A hoy</a>\n";
    echo "<a href=\"index.php\" class=\"cambiofecha\">A hoy</a>\n";
    echo "</div>\n";
?>
</div>

<table style="">
    <thead><tr>
        <th class="hour">時間</th>
        <th>Teta<br>左</th>
        <th>Teta<br>右</th>
        <th>Pump<br>⛽</th>
        <th>Bibe<br>🍼️</th>
        <th>Pañal<br>🚼️</th>
        <th style="width: 100%">Notas</th>
    </tr></thead>
    <tbody>
    <?php
        
        $stmt = $conn->prepare("SELECT what, data FROM $dbTable WHERE logtime >= ? AND logtime <= ? ORDER BY logtime ASC LIMIT 100");
        $stmt->bind_param("ss", $startStr, $endStr);
        
        if ( $stmt == false )
        {
            die ('Database query error');
        }
    
        for ($hora = 0 ; $hora < 24 ; $hora += 1)
        {
            $teta_izq = 0;
            $teta_der = 0;
            $biberon = 0;
            $biberon_mama = 0;
            $bomba_izq = 0;
            $bomba_der = 0;
            $pipi = '';
            $caca = '';
            $notas = '';
            $tetaset = false;
            
            $start = $diaActual->setTime($hora, 0);
            $end = $diaActual->setTime($hora, 59, 59);
            
            $startStr = $start->format('c');
            $endStr = $end->format('c');
            
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc())
            {
                if ( $row['what'] == 'caca' )
                {
                    $caca .= '💩️';
                    $colornum = $row['data'];
                    if ($colornum != '' && $colornum != '4' && $colornum != '5' && $colornum != '6' && $colornum != '7')
                    {
                        if (isset($COLOR_CACA[$colornum]) && $COLOR_CACA[$colornum] != '')
                        {
                            $color = $COLOR_CACA[$row['data']];
                        }
                        else
                        {
                            $color = 'magenta';
                        }
                        $notas .= "COLOR CACA: $colornum! <div style=\"color:$color\">&#9632;</div>";
                    }
                }
                elseif ( $row['what'] == 'pipi' )
                {
                    $pipi .= '💧️';
                }
                elseif ( $row['what'] == 'teta_izq' )
                {
                    $teta_izq += (int)$row['data'];
                    $tetaset = true;
                }
                elseif ( $row['what'] == 'teta_der' )
                {
                    $teta_der += (int)$row['data'];
                    $tetaset = true;
                }
                elseif ( $row['what'] == 'biberon' )
                {
                    $biberon += (int)$row['data'];
                }
                elseif ( $row['what'] == 'biberon-mama' )
                {
                    $biberon_mama += (int)$row['data'];
                }
                elseif ( $row['what'] == 'bomba-izq' )
                {
                    $bomba_izq += (int)$row['data'];
                }
                elseif ( $row['what'] == 'bomba-der' )
                {
                    $bomba_der += (int)$row['data'];
                }
                elseif ( $row['what'] == 'notas' )
                {
                    if ( $notas !== '' )
                    {
                        $notas .= "</br>\n";
                    }
                    
                    $notas .= $row['data'];
                }
            }
            
            echo "<tr>\n";
            printf("<td class=\"hour nowrap\"><a href=\"hora.php?dia=$diaActualUrl&hora=$hora\" class=\"hour\">%02d</a></td>\n", $hora);
            
            if ($tetaset)
            {
                $teta_izq_str = '' . round($teta_izq / 60.0);
                printf("<td class=\"nowrap\">%s</td>\n", $teta_izq_str);
            }
            else
            {
                echo "<td></td>\n";
            }
            
            if ($tetaset)
            {
                $teta_der_str = '' . round($teta_der / 60.0);
                printf("<td class=\"nowrap\">%s</td>\n", $teta_der_str);
            }
            else
            {
                echo "<td></td>\n";
            }
            
            echo '<td>';
            
            if ($bomba_izq > 0)
            {
                printf("%d左 ", $bomba_izq);
            }
            if ($bomba_der > 0)
            {
                printf("%d右", $bomba_der);
            }
            
            echo "</td>\n";
            
            echo '<td class="nowrap">';
            
            if ($biberon > 0)
            {
                printf("%d", $biberon);
            }
            
            if ($biberon > 0 && $biberon_mama > 0)
            {
                echo '🍼️ ';
            }
            
            if ($biberon_mama > 0)
            {
                printf("%d🤱", $biberon_mama);
            }
            
            echo "</td>\n";
            
            $excremento = $pipi . $caca;
            
            printf("<td class=\"nowrap\">%s</td>\n", $excremento);
            printf("<td>%s</td>\n", $notas);
            echo "</tr>\n";
        }
        
        $stmt->close();
    ?>
    </tbody>
</table>

</body>
</html>


