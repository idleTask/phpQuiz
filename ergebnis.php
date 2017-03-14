<?php
session_start();

require 'database.php';


function iScore ($conn) {
	$update = $conn->prepare('UPDATE users Set score = score + 1 WHERE id = :id');
	$update->bindParam(':id', $_SESSION['user_id']);
	$update->execute();
}

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
	
	
	
	$records2->bindParam(':id', $_SESSION["idR"]);
	$records2->execute();
	$results2 = $records2->fetch(PDO::FETCH_ASSOC);
	$fragen = NULL;
	if (count($results2) > 0){
		$fragen = $results2;
	}
	
}
?>

<DOCTYPE html>
<html>
<head>
	<title>Ergebnis</title>
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
	
	

		<header>
			
		</header>
		<article>
		<section>
			<header>
				<h2>Ergebnis: </h2>
			
			<?php
				
				
				if (isset($_POST['1'])){
					$_SESSION['a1'] = $_POST['1'];
					if ($fragen['richtig'] == '1'){
						$richtig = true;
					} else {
						$richtig = false;
					}
				}
				if (isset($_POST['2'])) {
					$_SESSION['a2'] = $_POST['2'];
					if ($fragen['richtig'] == '2'){
						$richtig = true;
					} else {
						$richtig = false;
					}
				}	
				if ($richtig){
					echo "Richtig!"	;	
					iScore($conn);
					
					
						
				} else if(!$richtig){
					echo "Falsch!";
					$next = false;
				}
				
				
				
				?>
				</header>
			<br /><br />Score: 
			<?php 
			if ($richtig){
				echo $user['score'] + 1; 
			} else {
				echo $user['score'];
			}
				
			?>
			
			
		</section>
		<section>
				<br /><br /><a href="index.php">Weiter</a>
		</section>
		</article>
		
	<?php else: ?>
	
		<h1>Bitte einloggen oder registrieren!</h1>
		<a href="login.php">Einloggen</a> oder 
		<a href="register.php">Registrieren</a>
	
	<?php endif; ?>

</body>
</html>