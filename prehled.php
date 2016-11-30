<!DOCTYPE html>
<html>
	<head>
		<meta charset='UTF-8'>
		<title>Schoolbook</title>
	</head>
	<body>
		<form method='POST'>
			<table>
				<tr>
					<td>
						Zpráva:
					</td>
					<td>
						<textarea name='text'></textarea>
					</td>
				</tr>
				<tr>
					<td>
						<input type='submit' name='send' value='Odeslat'>
					</td>
				</tr>
			</table>
			<?php
			if(isset($_POST['send'])) {
				$sessionid = $_COOKIE['sessionid'];
				$id = $_COOKIE['id'];
				
				$stmt = $handler->prepare("SELECT * FROM studenti WHERE ID=:id");
				$stmt->execute(array(
						":id" => $id
				));
				
				$r = $stmt->fetch();
				
				if(!$r) {
					die("<script>window.location.reload(false); </script>");
				} else if(hash("sha256", $sessionid) != $r['sessionid']) {
					die("<script>window.location.reload(false); </script>");
				}
				
						$text = $_POST['text'];
						
						if(strlen($text) <= 3 || strlen($text) > 300) {
							die("<span style='color:red'>Zpráva musí mít minimálně 4 znaky, a maximálně 300 znaků!</span>");
						}
						
						$stmt = $handler->prepare("INSERT INTO zpravychatu (studentID, datumPridani, zprava) VALUES (:student, NOW(), :zprava)");
						$stmt->execute(array(
								":student" => $id,
								":zprava" => $text
						));
				}
				?>
		</form>
		
		<br /><br /><br />
		
		<table cellpadding='10px' cellspacing='10px'>
			<?php
				
			$id = $_COOKIE['id'];
			
			$stmt = $handler->prepare("SELECT * FROM studenti WHERE ID=:id");
			$stmt->execute(array(
					":id" => $id
			));
			
				$r = $stmt->fetch();
				
				
				$trida = $r['tridaID'];
			
			
				$query = $handler->prepare("
							SELECT zpravychatu.datumPridani, zpravychatu.zprava, studenti.tridaID, studenti.pohlavi, studenti.prijmeni, studenti.jmeno FROM zpravychatu
							INNER JOIN studenti ON zpravychatu.studentID=studenti.ID
							WHERE studenti.tridaID=:trida
							ORDER BY zpravychatu.ID DESC
						");
				$query->execute(array(
						":trida" => $trida
				));
				
				while($r = $query->fetch(PDO::FETCH_OBJ)) {
					$prijmeni = $r->prijmeni;
					$jmeno = $r->jmeno;
					$datumPridani = $r->datumPridani;
					$zprava = htmlspecialchars($r->zprava);
					$kodBarvy = "#6495ED";
					
					if($r->pohlavi == 1) {
						$kodBarvy = "#F0A8C0";
					}
					
					
					echo("
							<tr>
								<td><span style='font-weight: bold; color: $kodBarvy'>$jmeno $prijmeni</span></td>
								<td>$datumPridani</td>
								<td >$zprava</td>
							</tr>
						");
				}
				?>
		</table>
		
	</body>
</html>