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
						Uživ. jmeno:
					</td>
					<td>
						<input type='text' name='username'>
					</td>
				</tr>
				<tr>
					<td>
						Heslo:
					</td>
					<td>
						<input type='password' name='password'>
					</td>
				</tr>
				<tr>
					<td>
						<input type='submit' name='login' value='Přihlásit se'>
					</td>
				</tr>
			</table>
			<?php 
				if(isset($_POST['login'])) {
					$username = $_POST['username'];
					$password = $_POST['password'];
					
					$stmt = $handler->prepare("SELECT * FROM studenti WHERE uzivJmeno=:username");
					$stmt->execute(array(
							":username" => $username
					));
					
					$check = $stmt->fetch();
					
					if(!$check) {
						die("<span style='color:red'>Tento uživatel neexistuje!</span>");
					}
					
					$controlPW = $check['heslo'];
					
					$password = hash("sha512", $username . "$" . $password);
					
					if($password != $controlPW) {
						die("<span style='color:red'>Špatné heslo!</span>");
					}
					$id = $check['ID'];
					
					$sessionid = mt_rand() . mt_rand() . mt_rand();
					
					$contSes = hash("sha256", $sessionid);
					
					$stmt = $handler->prepare("UPDATE studenti SET sessionid=:sessionid WHERE ID=:id");
					$stmt->execute(array(
							":sessionid" => $contSes,
							":id" => $id
					));
					
					
					setcookie("id", $id, time()+60*60*24*30, "/");
					setcookie("sessionid", $sessionid, time()+60*60*24*30, "/");
					echo("<script>window.location.reload(false); </script>");
				}
				?>
		</form>
	</body>
</html>