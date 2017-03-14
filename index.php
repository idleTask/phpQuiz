<?php

session_start();

require 'database.php';

if(isset($_SESSION['user_id'])){
	
	$records = $conn->prepare('SELECT id,name, password,score, admin FROM users WHERE id = :id');
	$records->bindParam(':id', $_SESSION['user_id']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);
	$user = NULL;
	if (count($results) > 0){
		$user = $results;
	}
	$records2 = $conn->prepare('SELECT id,frage, antwort1,antwort2, richtig FROM fragen WHERE id = :id');
	
	$statement = $conn->prepare("SELECT COUNT(*) AS anzahl FROM fragen");
	$statement->execute();  
	$row = $statement->fetch();
	
	
	$_SESSION ["idR"] = rand(0, ($row['anzahl']-1));
	
	$records2->bindParam(':id', $_SESSION ["idR"]);
	$records2->execute();
	$results2 = $records2->fetch(PDO::FETCH_ASSOC);
	$fragen = NULL;
	if (count($results2) > 0){
		$fragen = $results2;
	}
	
}

unset($_POST);

?>

<DOCTYPE html>
<html>
<head>
	<title>Willkommen zum Quiz</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

	<header> 
		<?php if (!empty($user)): ?>
		<aside>
				<a href="logout.php">Logout?</a>
		</aside>
		<?php endif; ?>
		<a href="index.php">Quiz</a>
	</header>
	
	
	<?php if (!empty($user)): ?>
	
		<br />Willkommen <?= $user['name']; ?>
		<br />Eingeloggt als: 
		<?php if ($user['admin']) {?>
			Admin
			<br />Neue Frage <a href="fragen.php">eintragen?</a>
		<?php }else { ?>
			Nutzer
		<?php } ?>
		<br /><br />
		<br /><br />Score: <?= $user['score']; ?>
		<br /><br />Anzahl Fragen in der Datenbank: <?= $row['anzahl']; ?>
		<article>
		<section>
			
			
			
			
				<header>Frage <?= $fragen['id'] + 1; ?></header>
				<h2><?= $fragen['frage']; ?></h2>
				<form action="ergebnis.php" method="POST">
				<button type="submit" name="1" value="1"><?= $fragen['antwort1']; ?></button>
				<button type="submit" name="2" value="2"><?= $fragen['antwort2']; ?></button>			
				</form>
				
		</section>
		<section>
				
		</section>
		</article>
		
	<?php else: ?>
	
		<h1>Bitte einloggen oder registrieren!</h1>
		<a href="login.php">Einloggen</a> oder 
		<a href="register.php">Registrieren</a>
	
	<?php endif; ?>

</body>
</html>