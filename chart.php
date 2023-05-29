<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Expires: 0");
?>

<!DOCTYPE html>
<html lang="es" xml:lang="es">
<head>
<title>記緑 - Estadísticas</title>
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

.rotate90 {
    display:block;
    position:relative;
    width: 3em;
    vertical-align:middle;
    -webkit-transform: rotate(-90.0deg);  /* Chrome, Opera 15+, Safari 3.1+ */
        -ms-transform: rotate(-90.0deg);  /* IE 9 */
            transform: rotate(-90.0deg);  /* Firefox 16+, IE 10+, Opera */
}

.hide {
    visibility: hidden;
    border: none;
}

</style>

<!-- YOU MIGHT WANT TO REMOVE THIS if you want 100% local stuff -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>

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
    <h1>記緑 - Estadísticas</h1>
</div>

<div class="addNuevo">
    <a href="index.php" class="addNuevo">🔙</a>
</div>

<hr>

<h2>Resumen</h2>


<?php

$diaActual = new DateTimeImmutable();

$movingAverageDays = 3; // Change the P3D below too!!

$startTime = $diaActual->sub(new DateInterval('P1M'))->sub(new DateInterval('P3D'));
$daysInMonth = $startTime->diff($diaActual)->format('%a');
$startStr = $startTime->format('c');

$endStr = $diaActual;
$endStr = $endStr->format('c');

//echo "FROM: $startStr to $endStr <br>\n";

$stmt = $conn->prepare("SELECT what, data, UNIX_TIMESTAMP(logtime) as unixtime FROM $dbTable WHERE logtime >= ? AND logtime <= ? ORDER BY logtime ASC LIMIT 5000");

if ( $stmt == false )
{
    die ('Database query error');
}

$stmt->bind_param("ss", $startStr, $endStr);

if ( $stmt == false )
{
    die ('Database query error');
}

$hour_teta = array();
for ($i = 0; $i < 24 ; $i++)
{
    $hour_teta[$i] = 0;
}

$stat_count = array();
$stat_count['teta'] = array();
$stat_count['biberon'] = array();
$stat_count['bomba'] = array();
$stat_count['caca'] = array();
$stat_count['pipi'] = array();

$stat_count['teta']['dia'] = 0;
$stat_count['teta']['semana'] = 0;
$stat_count['teta']['mes'] = 0;

$stat_count['biberon']['dia'] = 0;
$stat_count['biberon']['semana'] = 0;
$stat_count['biberon']['mes'] = 0;

$stat_count['bomba']['dia'] = 0;
$stat_count['bomba']['semana'] = 0;
$stat_count['bomba']['mes'] = 0;

$stat_count['caca']['dia'] = 0;
$stat_count['caca']['semana'] = 0;
$stat_count['caca']['mes'] = 0;

$stat_count['pipi']['dia'] = 0;
$stat_count['pipi']['semana'] = 0;
$stat_count['pipi']['mes'] = 0;

$stat_quantity = array();
$stat_quantity['teta'] = array();
$stat_quantity['teta_izq'] = array();
$stat_quantity['teta_der'] = array();
$stat_quantity['biberon-mama'] = array();
$stat_quantity['biberon'] = array();

$stat_quantity['teta']['dia'] = 0;
$stat_quantity['teta']['semana'] = 0;
$stat_quantity['teta']['mes'] = 0;

$stat_quantity['teta_izq']['dia'] = 0;
$stat_quantity['teta_izq']['semana'] = 0;
$stat_quantity['teta_izq']['mes'] = 0;

$stat_quantity['teta_der']['dia'] = 0;
$stat_quantity['teta_der']['semana'] = 0;
$stat_quantity['teta_der']['mes'] = 0;

$stat_quantity['biberon-mama']['dia'] = 0;
$stat_quantity['biberon-mama']['semana'] = 0;
$stat_quantity['biberon-mama']['mes'] = 0;

$stat_quantity['biberon']['dia'] = 0;
$stat_quantity['biberon']['semana'] = 0;
$stat_quantity['biberon']['mes'] = 0;

$comida_array = array(); // In minutes from $startTime
$comida_array['comida'] = array();
$comida_array['teta'] = array();
$comida_array['formula'] = array();

$comida_array['comida']['dia'] = array();
$comida_array['comida']['semana'] = array();
$comida_array['comida']['mes'] = array();

$comida_array['teta']['dia'] = array();
$comida_array['teta']['semana'] = array();
$comida_array['teta']['mes'] = array();

$comida_array['formula']['dia'] = array();
$comida_array['formula']['semana'] = array();
$comida_array['formula']['mes'] = array();

$same_meal_range_minutes = 30; // If multiple entries happen in less than this, they are considered the same meal

function increment_count($what, $eventTime)
{
    global $stat_count, $diaActual, $daysInMonth;
    
    $interval = $diaActual->diff($eventTime);
    $interval = $interval->h + $interval->days * 24;
    
    if ($interval <= $daysInMonth * 24)
    {
        $stat_count[$what]['mes'] += 1;

        if ($interval <= 7 * 24)
        {
            $stat_count[$what]['semana'] += 1;

            if ($interval <= 1 * 24)
            {
                $stat_count[$what]['dia'] += 1;
            }
        }
    }
}

function increment_quantity($what, $eventTime, $quantity)
{
    global $stat_quantity, $diaActual, $daysInMonth;
    
    $interval = $diaActual->diff($eventTime);
    $interval = $interval->h + $interval->days * 24;

    if ($interval <= $daysInMonth * 24)
    {
        $stat_quantity[$what]['mes'] += $quantity;

        if ($interval <= 7 * 24)
        {
            $stat_quantity[$what]['semana'] += $quantity;

            if ($interval <= 1 * 24)
            {
                $stat_quantity[$what]['dia'] += $quantity;
            }
        }
    }
}

function check_insert_comida_sub($type, $diffTimeMin, $sub)
{
    global $comida_array, $same_meal_range_minutes;

    $array = &$comida_array[$type][$sub];

    if ( sizeof($array) > 0 )
    {
        if ($diffTimeMin - $array[sizeof($array) - 1] < $same_meal_range_minutes)
        {
            // Too close, this is the same meal
            return;
        }
    }

    // This is a new meal
    array_push($array, $diffTimeMin);
}

function check_insert_comida($type, $eventTime)
{
    global $comida_array, $startTime, $diaActual, $same_meal_range_minutes, $daysInMonth;

    $diffTime = $startTime->diff($eventTime);
    $diffTimeMin = $diffTime->days * 24 * 60 + $diffTime->h * 60 + $diffTime->i;

    $interval = $diaActual->diff($eventTime);
    $interval = $interval->h + $interval->days * 24;

    if ($interval <= $daysInMonth * 24)
    {
        check_insert_comida_sub($type, $diffTimeMin, 'mes');

        if ($interval <= 7 * 24)
        {
            check_insert_comida_sub($type, $diffTimeMin, 'semana');

            if ($interval <= 1 * 24)
            {
                check_insert_comida_sub($type, $diffTimeMin, 'dia');
            }
        }
    }
}

$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc())
{
    $when = DateTimeImmutable::createFromFormat('U', $row['unixtime']);
    $what = $row['what'];
    $data = $row['data'];
    
    if ( $what == 'caca' )
    {
        increment_count($what, $when);
    }
    elseif ( $what == 'pipi' )
    {
        increment_count($what, $when);
    }
    elseif ( $what == 'teta_izq' || $what == 'teta_der' )
    {
        increment_quantity($what, $when, (int)$data);

        $what = 'teta';
        increment_quantity($what, $when, (int)$data); // sec!
        increment_count($what, $when);
        check_insert_comida('comida', $when);
        check_insert_comida('teta', $when);

        $hour_teta[(int)$when->format('H')] += 1;
    }
    elseif ( $what == 'biberon' )
    {
        increment_quantity($what, $when, (int)$data);
        increment_count($what, $when);
        check_insert_comida('comida', $when);
        check_insert_comida('formula', $when);
    }
    elseif ( $what == 'biberon-mama' )
    {
        increment_quantity($what, $when, (int)$data);
        check_insert_comida('comida', $when);
        // increment_count($what, $when);
    }
    elseif ( $what == 'bomba-izq' || $what == 'bomba-der' )
    {
        $what = 'bomba';
        increment_count($what, $when);
    }
    else
    {
        continue;
    }
}

foreach ($stat_count as &$entry)
{
    $entry['semana'] /= 7.0;
    $entry['mes'] /= (float)$daysInMonth;
}

foreach ($stat_quantity as &$entry)
{
    $entry['semana'] /= 7.0;
    $entry['mes'] /= (float)$daysInMonth;
}

$stat_quantity['teta']['dia'] /= 60.0; // sec -> min
$stat_quantity['teta']['semana'] /= 60.0; // sec -> min
$stat_quantity['teta']['mes'] /= 60.0; // sec -> min

$stat_quantity['teta_izq']['dia'] /= 60.0; // sec -> min
$stat_quantity['teta_izq']['semana'] /= 60.0; // sec -> min
$stat_quantity['teta_izq']['mes'] /= 60.0; // sec -> min

$stat_quantity['teta_der']['dia'] /= 60.0; // sec -> min
$stat_quantity['teta_der']['semana'] /= 60.0; // sec -> min
$stat_quantity['teta_der']['mes'] /= 60.0; // sec -> min

$comida_interval = array();
foreach ($comida_array as $key => &$array) // comida, teta, formula
{
    $comida_interval[$key] = array();

    foreach ($array as $keyRange => &$sub) // dia, semana, mes
    {
        $comida_interval[$key][$keyRange] = 0;

        for ($i = 1 ; $i < sizeof($sub) ; $i++)
        {
            $comida_interval[$key][$keyRange] += $sub[$i] - $sub[$i-1];
        }

        if (sizeof($sub) > 1)
        {
            $comida_interval[$key][$keyRange] /= (float)sizeof($sub);
        }
    }
}

function print_summary_table_row_data($what)
{
    printf('<td>%.1f</td><td>%.1f</td><td>%.1f</td>', $what['dia'], $what['semana'], $what['mes']);
}

function format_hora($minutos)
{
    return sprintf("%dh%02d", floor($minutos / 60), floor($minutos % 60));
}

function print_summary_table_row_data_h($what)
{
    printf('<td>%s</td><td>%s</td><td>%s</td>', format_hora($what['dia']), format_hora($what['semana']), format_hora($what['mes']));
}

?>

<table>
<thead><tr>
    <th class="hide"></th><th class="hide"></th><th>24h</th><th>7d<br>(avg 1d)</th><th>1mes<br>(avg 1d)</th>
</tr></thead>
<tr>
    <td rowspan="10"><div class="rotate90">Cantidad</div></td><td>Teta (回数)</td>
    <?php
    print_summary_table_row_data($stat_count['teta']);
    ?>
</tr>
<tr>
    <td>Bomba (回数)</td>
    <?php
    print_summary_table_row_data($stat_count['bomba']);
    ?>
</tr>
<tr>
    <td>Fórmula (回数)</td>
    <?php
    print_summary_table_row_data($stat_count['biberon']);
    ?>
</tr>
<tr>
    <td>Teta (min)</td>
    <?php
    print_summary_table_row_data($stat_quantity['teta']);
    ?>
</tr>
<tr>
    <td>Teta 左 (min)</td>
    <?php
    print_summary_table_row_data($stat_quantity['teta_izq']);
    ?>
</tr>
<tr>
    <td>Teta 右 (min)</td>
    <?php
    print_summary_table_row_data($stat_quantity['teta_der']);
    ?>
</tr>
<tr>
    <td>Bomba (ml)</td>
    <?php
    print_summary_table_row_data($stat_quantity['biberon-mama']);
    ?>
</tr>
<tr>
    <td>Fórmula (ml)</td>
    <?php
    print_summary_table_row_data($stat_quantity['biberon']);
    ?>
</tr>
<tr>
    <td>Caca (回数)</td>
    <?php
    print_summary_table_row_data($stat_count['caca']);
    ?>
</tr>
<tr>
    <td>Pipi (回数)</td>
    <?php
    print_summary_table_row_data($stat_count['pipi']);
    ?>
</tr>

<tr>
    <td rowspan="3"><div class="rotate90">Tiempo entre</div></td><td>Comidas (h)</td>
    <?php
    print_summary_table_row_data_h($comida_interval['comida']);
    ?>
</tr>
<tr>
    <td>Teta (h)</td>
    <?php
    print_summary_table_row_data_h($comida_interval['teta']);
    ?>
</tr>
<tr>
    <td>Fórmula (h)</td>
    <?php
    print_summary_table_row_data_h($comida_interval['formula']);
    ?>
</tr>
</table>

<hr>

<h2>Gráficas</h2>

<div class="cambiofecha">
<?php
/*
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
*/
?>
</div>

<canvas id="myChart" style="width:100%;max-width:700px"></canvas>

<script>
var xyValues = [
<?php
$total = 0;
for ($h = 0 ; $h < 24 ; $h++)
{
    $total += $hour_teta[$h];
}

if ($total == 0)
{
    $total = 1.0;
}

for ($h = 0 ; $h < 24 ; $h++)
{
    //printf("{x:%d, y:%.4f}%s\n", $h, 100.0*$hour_teta[$h] / $total, $h == 23 ? '' : ',');
    printf("%.4f%s\n", $hour_teta[$h] / $total, $h == 23 ? '' : ',');
}
?>
];

new Chart("myChart", {
    type: 'bar',
    options: {
    },
    data: {
        labels:
        [
            <?php
            for ($h = 0 ; $h < 24 ; $h++) printf("%02d%s", $h, $h == 23 ? '' : ', ');
            ?>
        ],
        datasets: [{
            label: 'Teta por hora',
            pointRadius: 4,
            pointBackgroundColor: "rgb(0,0,255)",
            data: xyValues
        }]
    }
});
</script>

</body>
</html>


