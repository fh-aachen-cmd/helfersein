<?php
# this is the main script to access the database at localhost
# do not use anything else!

// which database is to be used?
// all queries within the modules will use this var.
$dataBaseToUse = "db596492883";

class DB {
	function connectDB($database='')
	{
        // access information
		$myServer = "localhost";
		$myUser = "dbo596492883";
		//$myPass = "";

		//connection to the database
		$db = @new mysqli($myServer, $myUser, $myPass, $dataBaseToUse, 3306);
		if (mysqli_connect_errno() != 0)
		{
			echo 'Die Datenbank konnte nicht erreicht werden. Folgender Fehler trat auf: <span class="hinweis">' .mysqli_connect_errno(). ' : ' .mysqli_connect_error(). '</span>';
			exit();
		}
        $db -> query("SET NAMES 'utf8'");

		return $db;
	}
}
?>
