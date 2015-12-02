<?php
/*
require 'database_access/access.php';

$db = @DB::connectDB();
*/

require 'database_access/access_temp.php';

if(isset($_GET['action'])) {
	$action = $_GET["action"];
}
else {
	echo 'Keine Aktion möglich.';
}

// Variablen
$firstname = $_POST["Firstname"];
 $lastname = $_POST["Lastname"];
   $street = $_POST["Street"].' '.$_POST["Nr"];
   $postal = $_POST["Postal"];
     $city = $_POST["City"];
    $email = $_POST["Email"];
    $phone = $_POST["Phone"];

// SQL Query
$query = "INSERT INTO tl_helper (id, firstname, lastname, street, postal, city, email, phone)
			   VALUES ('', '".$firstname."', '".$lastname."', '".$street."', '".$postal."', '".$city."', '".$email."', '".$phone."')";

if ($action == 'neu') {
	// SQL Abfrage ausfuehren
	$db -> query($query);
}
?>

<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<title>helfersein.de</title>

		<link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic">
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="css/styles.css">

	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
		<script src="js/custom.js"></script>

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
		<![endif]-->
	</head>

	<body>
		<div class="container">
			<p>Datensatz angelegt.</p>
		</div>

	</body>
</html>
