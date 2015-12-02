<?php
require_once 'database_access/access.php';

require_once 'core/functions.select_media.php';
require_once 'core/functions.select_helper.php';
require_once 'core/functions.select_responsible.php';
require_once 'core/functions.select_organisation.php';

//$helper = getHelper(1);
//$responsible = getResponsible(1);
//$organisation = getOrganisation(1);

$helper = allHelper();
$organisation = allOrganisation();
$responsible = allResponsible();
?>

<!DOCTYPE html>
<html lang="en">
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
    <?php include 'module/mod_navbar.php'; ?> <!-- Navbar -->

    <?php
    echo '<pre>';
    print_r($helper);
    echo '</pre>';

    echo '<pre>';
    print_r($organisation);
    echo '</pre>';

    echo '<pre>';
    print_r($responsible);
    echo '</pre>';
    ?>

  </body>
</html>
