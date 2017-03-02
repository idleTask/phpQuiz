<?php

session_start();

require 'database.php';

if(isset($_SESSION['user_id'])){
	
	$records = $conn->prepare('SELECT id,email, password,score FROM users WHERE id = :id');
	$records->bindParam(':id', $_SESSION['user_id']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);
	
	$user = NULL;
	
	if (count($results) > 0){
		$user = $results;
	}
}


?>

<DOCTYPE html>
<html>
<head>
	<title>Willkommen zum Quizz</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	
	<header> 
		<a href="index.php">Quizz</a>
	</header>
	
	<?php if (!empty($user)): ?>
	
		<br />Welcome <?= $user['email']; ?>
		<br /><br />You are successfully logged in!
		<br /><br />Score: <?= $user['score']; ?>
		<section>
			<header>Frage 1</header>
			<h2>Ja oder Nein?</h2>
			<form action="index.php" method="POST">
				<button type="submit" name="ja" value="0">Ja</button>
				<button type="submit" name="nein" value="1">Nein</button>	
			</form>
			<?php
			if(isset($_POST['ja']))
			{
					echo "Richtig!"	;	
					$update = $conn->prepare('UPDATE users Set score = score + 1 WHERE id = :id');
					$update->bindParam(':id', $_SESSION['user_id']);
					$update->execute();
			}else if (isset($_POST['nein'])){
					echo "Falsch"	;	
			}  
			?>
		</section>
		<a href="logout.php">Logout?</a>
		
	<?php else: ?>
	
		<h1>Bitte einloggen oder registrieren!</h1>
		<a href="login.php">Login</a> or 
		<a href="register.php">Register</a>
	
	<?php endif; ?>

</body>
</html>