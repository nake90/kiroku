<?php
ob_start();
require 'config.php';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbDatabase);

if ( $conn->connect_error )
{
    die ( 'Database connection error' );
}

function insert($what, $data, $who, $canary, $when = null)
{
    global $conn, $dbTable;
    if ( $when )
    {
        $stmt = $conn->prepare("INSERT INTO $dbTable (what, data, by_who, logtime, canary) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $what, $data, $who, $when, $canary);
        //echo "INSERT INTO db (what, data, by_who, logtime, canary) VALUES ($what, $data, $who, $when, $canary)";
    }
    else
    {
        $stmt = $conn->prepare("INSERT INTO $dbTable (what, data, by_who, canary) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $what, $data, $who, $canary);
        //echo "INSERT INTO db (what, data, by_who, canary) VALUES ($what, $data, $who, $canary)";
    }
    
    //$stmt->execute() or die(mysqli_error($conn));
    //return true;
    return ($stmt->execute() === TRUE);
}

function parseAndInsert($what, $canary)
{
    $who = 'nadie';
    $when = null;
    
    if (isset($_GET['horaManual']) && date_parse($_GET['horaManual'])['error_count'] == 0)
    {
        $when = new DateTime($_GET['horaManual']);
        $when = $when->format('Y-m-d H:i:s');
    }   

    if ($what === 'caca')
    {
        $colorcaca = '';
        if (isset($_GET['colorcaca']))
        {
            $colorcaca = (string)$_GET['colorcaca'];
        }
        insert($what, $colorcaca, $who, $canary, $when) or die ('Database insert error');
    }
    else if ($what === 'pipi')
    {
        insert($what, '', $who, $canary, $when) or die ('Database insert error');
    }
    else if ($what === 'biberon' || $what === 'biberon-mama')
    {
        if (isset($_GET['ml']))
        {
            $ml = (int)$_GET['ml'];
            
            if ($ml > 0 && $ml < 1000)
            {
                insert($what, $ml, $who, $canary, $when) or die ('Database insert error');
            }
            else
            {
                die ('Numero incorrecto');
            }
        }
    }
    else if ($what === 'bomba')
    {
        if (isset($_GET['ml']))
        {
            $ml = (int)$_GET['ml'];
            
            if ($ml <= 0 || $ml >= 1000)
            {
                die ('Numero incorrecto');
            }
            
            if (isset($_GET['bomba_izq']) && isset($_GET['bomba_der']))
            {
                $ml = $ml / 2;
            }
            
            if (isset($_GET['bomba_izq']))
            {
                insert('bomba-izq', $ml, $who, $canary, $when) or die ('Database insert error');
            }
            
            if (isset($_GET['bomba_der']))
            {
                insert('bomba-der', $ml, $who, $canary + 1, $when) or die ('Database insert error');
            }
            
        }
    }
    //canary=63058594&what=teta&izq=72&der=23&fmemo=1m10+-+23
    else if ($what === 'teta')
    {
        if (isset($_GET['izq']) && $_GET['izq'] !== '' && isset($_GET['der']) && $_GET['der'] !== '')
        {
            $izq = (float)$_GET['izq'] * 60.0;
            $der = (float)$_GET['der'] * 60.0;
            
            if ($izq > 0 && $izq < 60*60)
            {
                insert('teta_izq', strval($izq), $who, $canary, $when) or die ('Database insert error');
            }
            
            if ($der > 0 && $der < 60*60)
            {
                insert('teta_der', strval($der), $who, $canary + 1, $when) or die ('Database insert error');
            }
        }
    }
    else if ($what === 'notas')
    {
        $notas = (string)$_GET['fmemo'];
        insert($what, $notas, $who, $canary, $when) or die ('Database insert error');
    }
}

if (isset($_GET['canary']) && (int)$_GET['canary'] != 0)
{
    $canary = (int)$_GET['canary'];
    
    if (isset($_GET['what']) && !empty($_GET['what']))
    {
        parseAndInsert($_GET['what'], $canary);
    }

    if (isset($_GET['what2']) && !empty($_GET['what2']))
    {
        parseAndInsert($_GET['what2'], $canary + 10);
    }

    if (isset($_GET['fmemo']) && !empty($_GET['fmemo']))
    {
        parseAndInsert('notas', $canary + 20);
    }
}

if (isset($_GET['simple']) && $_GET['simple'] === 'simple')
{
    $fast_reload = false;
    $url = 'simple.php';
}
else
{
    $fast_reload = true;
    $url = 'index.php';
}

// clear out the output buffer
while (ob_get_status()) 
{
    ob_end_clean();
}

// no redirect
if ($fast_reload)
{
    header( "Location: $url" );
}
else
{
    header( "refresh:3; url=$url" );
}

echo '<h1 style="font-size: 16vw">OK!</h1>';

?>

