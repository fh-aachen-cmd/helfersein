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
		<?php include 'module/mod_navbar.php'; ?> <!-- Navbar -->

		<div class="container">
			<div class="row">
				<div class="col-md-8">

					<form class="form-horizontal" action="organisation_speichern.php?action=neu" method="POST">
						<div class="form-body">
							<fieldset>
								<legend><span>Organisation</span></legend>

								<div class="form-group">
									<label for="inputTitle" class="col-sm-4 control-label">Titel <span class="mandatory">*</span></label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="inputTitle" name="Titel" required>
									</div>
								</div>
								<div class="form-group">
									<label for="inputContactFirstname" class="col-sm-4 control-label">Kontaktperson <span class="mandatory">*</span></label>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputContactFirstname" name="ContactFirstname" placeholder="Vorname" required>
									</div>
									<div class="col-sm-4">
										<input type="text" class="form-control" id="inputContactLastname" name="ContactLastname" placeholder="Nachname">
									</div>
								</div>
								<div class="form-group">
									<label for="inputStreet" class="col-sm-4 control-label">Straße | Nr.</label>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="inputStreet" name="Street">
									</div>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="inputNr" name="Nr">
									</div>
								</div>
								<div class="form-group">
									<label for="inputPostal" class="col-sm-4 control-label">PLZ | Ort <span class="mandatory">*</span></label>
									<div class="col-sm-3">
										<input type="text" class="form-control" id="inputPostal" name="Postal" required>
									</div>
									<div class="col-sm-5">
										<input type="text" class="form-control" id="inputCity" name="City" required>
									</div>
								</div>
								<div class="form-group">
									<label for="inputPhone" class="col-sm-4 control-label">Telefon <span class="mandatory">*</span></label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="inputPhone" name="Phone" required>
									</div>
								</div>
								<div class="form-group">
									<label for="inputEmail" class="col-sm-4 control-label">E-Mail <span class="mandatory">*</span></label>
									<div class="col-sm-8">
										<input type="email" class="form-control" id="inputEmail" name="Email" required>
									</div>
								</div>
								<div class="form-group">
									<label for="inputWebsite" class="col-sm-4 control-label">Website <span class="mandatory">*</span></label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="inputWebsite" name="Website" required>
									</div>
								</div>
							</fieldset>

							<fieldset>
								<legend><span>Account Daten</span></legend>

								<div class="form-group">
									<label for="inputUser" class="col-sm-4 control-label">Username <span class="mandatory">*</span></label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="inputUser" placeholder="testy@westy.de" disabled>
									</div>
								</div>
								<div class="form-group">
									<label for="inputPassword" class="col-sm-4 control-label">Passwort <span class="mandatory">*</span></label>
									<div class="col-sm-8">
										<input type="password" class="form-control" id="inputPassword" required>
									</div>
								</div>
								<div class="form-group">
									<label for="inputPasswordRepeat" class="col-sm-4 control-label">Passwort wiederholen <span class="mandatory">*</span></label>
									<div class="col-sm-8">
										<input type="password" class="form-control" id="inputPasswordRepeat" required>
									</div>
								</div>
							</fieldset>

							<div class="form-group">
								<button type="submit" class="btn btn-default col-sm-offset-4">Jetzt anmelden</button>
							</div>
						</div>
					</form>
				</div>
				<div class="col-md-4">.col-md-4</div>
			</div>

		</div>
	</body>
</html>
