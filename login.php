<?php

session_start();

if ( isset($_SESSION['user_id'])) {
	header("Location: index.php");
}

require 'database.php';

if(!empty($_POST['name']) && !empty($_POST['password'])):
	
	

	$records = $conn->prepare('SELECT id,name, password FROM users WHERE name = :name');
	$records->bindParam(':name', $_POST['name']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);
	
	$message = '';
	
	if(count($results) > 0 && password_verify($_POST['password'], $results['password'])){
	
		$_SESSION['user_id'] = $results['id'];
		header("Location: index.php");
		
	} else {
		$message = 'Password oder Benutzername falsch';
	}
	
endif;

?>

<!DOCTYPE html>
<html>
<head>
	<title>Unten Einloggen</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	
	<header> 
		<a href="index.php">Quiz</a>
	</header>
	
	<?php if(!empty($message)): ?>
		<p><?= $message ?></p>
	<?php endif; ?>
	
	<h1>Einloggen!</h1>
	<span>oder <a href="register.php">hier registrieren</a></span>
	
	<form action="login.php" method="POST">
		
		<input type="text" placeholder="Name:" name="name">
		<input type="password" placeholder="Passwort:" name="password">
		
		<input type="submit">
	</form>

</body>
</html>