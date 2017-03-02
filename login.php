<?php

session_start();

if ( isset($_SESSION['user_id'])) {
	header("Location: index.php");
}

require 'database.php';

if(!empty($_POST['email']) && !empty($_POST['password'])):
	
	

	$records = $conn->prepare('SELECT id,email, password FROM users WHERE email = :email');
	$records->bindParam(':email', $_POST['email']);
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
	<title>Login Below</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	
	<header> 
		<a href="index.php">Quizz</a>
	</header>
	
	<?php if(!empty($message)): ?>
		<p><?= $message ?></p>
	<?php endif; ?>
	
	<h1>Einloggen!</h1>
	<span>or <a href="register.php">register here</a></span>
	
	<form action="login.php" method="POST">
		
		<input type="text" placeholder="Enter your email" name="email">
		<input type="password" placeholder="and password" name="password">
		
		<input type="submit">
	</form>

</body>
</html>