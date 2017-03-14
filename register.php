<?php

session_start();

if ( isset($_SESSION['user_id'])) {
	header("Location: index.php");
}

require 'database.php';

$message = '';

if(!empty($_POST['name']) && !empty($_POST['password']) && (($_POST['password']) == ($_POST['confirm_password']))):
	// Enter the new user in the database
	$sql = "INSERT INTO users (name, password) VALUES (:name, :password)";
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam(':name', $_POST['name']);
	$stmt->bindParam(':password', password_hash($_POST['password'], PASSWORD_BCRYPT));
	if($stmt->execute() ):
		$message = 'Neuer Benutzer wurde erstellt!';
	else:
		$message = 'Es ist ein Fehler beim Erstellen des Benutzers aufgetreten.';
	endif;
else:
	$message = 'Passwörter stimmen nicht überein.';
endif;

?>

<!DOCTYPE html>
<html>
<head>
	<title>Unten Registrieren</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	
	<header> 
		<a href="index.php">Quiz</a>
	</header>
	
	<?php if(!empty($message)): ?>
		<p><?= $message ?></p>
	<?php endif; ?>
	
	<h1>Registrieren!</h1>
	<span>oder <a href="login.php">hier einloggen</a></span>

	<form action="register.php" method="POST">	
		<input type="text" placeholder="Name:" name="name">
		<input type="password" placeholder="Passwort: " name="password">
		<input type="password" placeholder="Passwort:" name="confirm_password">		
		<input type="submit">
	</form>
</body>
</html>