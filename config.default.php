<?php
// You might want to enable the following for debugging
/*
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
*/

$dbHost='localhost';
$dbUser='kiroku';
$dbPass='MY_MYSQL_VERY_DIFFICULT_PASSWORD'; // CHANGE THIS
$dbDatabase='kiroku';
$dbTable='eventlog';

/*
create table eventlog (
uid int auto_increment primary key,
what varchar(64) not null,
data text,
by_who varchar(64),
logtime TIMESTAMP NOT NULL,
canary int not null unique );
*/
?>

