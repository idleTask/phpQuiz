<?php

session_start();

require 'database.php';
$message = '';
if(isset($_SESSION['user_id'])){
	
	$records = $conn->prepare('SELECT id,name, password,score, admin FROM users WHERE id = :id');
	$records->bindParam(':id', $_SESSION['user_id']);
	$records->execute();
	$results = $records->fetch(PDO::FETCH_ASSOC);
	
	$user = NULL;
	
	if (count($results) > 0){
		$user = $results;
	}
}


if ($user['admin']) {
	if(!empty($_POST['frage']) && !empty($_POST['antwort1']) && !empty($_POST['antwort2']) && !empty($_POST['richtig'])) {	
	$sql = "INSERT INTO fragen (frage, antwort1, antwort2, richtig) VALUES (:frage, :antwort1, :antwort2, :richtig)";
	$stmt = $conn->prepare($sql);
	
	$stmt->bindParam(':frage', $_POST['frage']);
	$stmt->bindParam(':antwort1', $_POST['antwort1']);
	$stmt->bindParam(':antwort2', $_POST['antwort2']);
	$stmt->bindParam(':richtig', $_POST['richtig']);
	if($stmt->execute() ){
		$message = 'Neue Frage wurde erstellt!';
	}else{
		$message = 'Es ist ein Fehler beim Erstellen der Frage aufgetreten.';
	};
	};	
};

?>

<!DOCTYPE html>
<html>
<head>
	<title>Frage erstellen</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	
	<header> 
		<a href="index.php">Quizz</a>
	</header>
	
	<?php if(!empty($message)): ?>
		<p><?= $message ?></p>
	<?php endif; ?>
	
	<h1>Frage erstellen!</h1>
	<span>oder <a href="index.php">zurück</a></span>

	<form action="fragen.php" method="POST">	
		<input type="text" placeholder="Frage" name="frage">
		<input type="text" placeholder="1. Antwort" name="antwort1">
		<input type="text" placeholder="2. Antwort" name="antwort2">	
		<fieldset>
		Richtige Antwort:
		<br />
		<input type="radio" id="a1" name="richtig" value="1"> <label for="a1">1</label>
		<input type="radio" id="a2" name="richtig" value="2"> <label for="a2">2</label>
		</fieldset>
		<input type="submit">
	</form>
	
</body>
</html>