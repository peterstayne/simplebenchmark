<?php

ini_set('display_errors', 'on');
error_reporting(E_ALL);

?><!doctype html>
<html>
<head>
	<title>This is a test</title>
</head>
<body>

<?php

// some setup for the dummy php work ahead
$chars = 'abcdefghijklmnopqrstuvwxyz0123456789 ';
$random_string_length = 50000;
$string = '';

for ( $i = 0; $i < $random_string_length; $i++ ) {
	$string .= $chars[ rand( 0, strlen( $chars ) - 1 ) ];
}

// include the benchmark class
require_once( 'Benchmark.php' );

// initialize the class
$bm = new Benchmark;

// set the initial marker
$bm->marker( 'start' );

// do some php stuff that you want to bench
$string_explode = explode( ' ', $string );

// set another marker
$bm->marker( 'initial explode' );

// do some more php
foreach( $string_explode as $k => $v ) {
	$string_explode[ $k ] = explode( '0', $v );
}

// set a final marker
$bm->marker( 'end' );


// now that the markers are set and stored, we can output the results:
?>

<h1>OutputHTML</h1>
<?php
echo $bm->outputHTML();
?>

<h1>OutputText</h1>
<pre>
<?php 
echo $bm->outputText(); 
?>
</pre>

<?php 
echo $bm->outputConsoleLog(); 
?>

<?php 
echo $bm->outputConsoleTable(); 
?>

</body>
</head>