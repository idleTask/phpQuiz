<?php

session_start();

if ( isset($_SESSION['user_id'])) {
	header("Location: index.php");
}

require 'database.php';

$message = '';

if(!empty($_POST['email']) && !empty($_POST['password'])):
	// Enter the new user in the database
	$sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam(':email', $_POST['email']);
	$stmt->bindParam(':password', password_hash($_POST['password'], PASSWORD_BCRYPT));
	if($stmt->execute() ):
		$message = 'Neuer Benutzer wurde erstellt!';
	else:
		$message = 'Es ist ein Fehler beim Erstellen des Benutzers aufgetreten.';
	endif;
endif;

?>

<!DOCTYPE html>
<html>
<head>
	<title>Register below</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	
	<header> 
		<a href="index.html">Quizz</a>
	</header>
	
	<?php if(!empty($message)): ?>
		<p><?= $message ?></p>
	<?php endif; ?>
	
	<h1>Registrieren!</h1>
	<span>or <a href="login.php">login here</a></span>

	<form action="register.php" method="POST">	
		<input type="text" placeholder="Enter your email" name="email">
		<input type="password" placeholder="and password" name="password">
		<input type="password" placeholder="confirm password" name="confirm_password">		
		<input type="submit">
	</form>
</body>
</html>