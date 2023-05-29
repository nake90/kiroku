<?php
ob_start();
require 'config.php';

$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbDatabase);

if ( $conn->connect_error )
{
    die ( 'Database connection error' );
}

if (isset($_GET['uid']) && is_numeric($_GET['uid']))
{
    $uid = (int)$_GET['uid'];
}
else
{
    die ( 'Input data error' );
}

$dia = $_GET['dia'];
$hora = $_GET['hora'];
$url = "hora.php?dia=$dia&hora=$hora";

if ($_GET['accion'] == '💾️')
{
    if (isset($_GET['newdata']))
    {
        $newdata = $_GET['newdata'];
        
        if (isset($_GET['units']) && $_GET['units'] == 'min')
        {
            $newdata = $newdata * 60;
        }
    }
    else
    {
        $newdata = '';
    }

    $stmt = $conn->prepare("UPDATE $dbTable SET data = ?, logtime = logtime WHERE uid = ?");
    $stmt->bind_param("si", $newdata, $uid);
    $stmt->execute() or die(mysqli_error($conn));
}
else if ($_GET['accion'] == '💣️')
{
    $stmt = $conn->prepare("DELETE FROM $dbTable WHERE uid = ?");
    $stmt->bind_param("i", $uid);
    $stmt->execute() or die(mysqli_error($conn));
}
else
{
    die ( 'Input data error' );
}



// clear out the output buffer
while (ob_get_status())
{
    ob_end_clean();
}

// no redirect
header( "Location: $url" );
die("header redirect did not work?");
?>

