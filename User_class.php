<?php
class User {

	//private - klassi sees saab kasutada ainult
	private $connection;
	
	//klassi loomisel (new User)
	function __construct($mysqli) {
		
		//this tähendab selle klassi muutujat
		$this->connection = $mysqli;
		
	}
	function createUser($create_email, $hash){
		
		//teen objekti
		//seal on error, ->id ja ->message
		//või success ja sellel on ->message
		$response = new StdClass();
		
		//kas selline email on juba olemas
		$stmt = $this->connection->prepare("SELECT id FROM user_sample WHERE email=?");
		$stmt->bind_param("s", $create_email);
		$stmt->bind_result($id);
		$stmt->execute();
		//kontrollin, kas sain rea andmeid
		if($stmt->fetch()){			
			
			//annan errori - selline email on olemas
			$error = new StdClass();
			$error->id = 0;
			$error->message = "Sellise epostiga kasutaja on juba olemas!";
			
			$response->error = $error;
			
			//kõik mis on pärast returni enam ei käivitata
			return $response;
		}
		
		//panen eelmise päringu kinni
		$stmt->close();
		
		$stmt = $this->connection->prepare("INSERT INTO user_sample (email, password) VALUES (?,?)");
		$stmt->bind_param("ss", $create_email, $hash);
		//sai edukalt salvestatud,tekitan uue objekt
		if($stmt->execute()){
			$success = new StdClass();
			$success->message = "Kasutaja edukalt loodud!";
			
			$response->success = $success;
			return $response;
		}else{
			//midagi läks katki
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Midagi läks katki!";
			
			$response->error = $error;
		
		}
		
		$stmt->close();
		
	}
	
	function loginUser($email, $hash){
		
		$response = new StdClass();
		
		//kas selline email on juba olemas
		$stmt = $this->connection->prepare("SELECT id FROM user_sample WHERE email=?");
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id);
		$stmt->execute();
		
		// ! -> ei olnud sellist e-posti
		if(!$stmt->fetch()){
			
			$error = new StdClass();
			$error->id = 0;
			$error->message = "Sellist kasutajat ei ole!";
			
			$response->error = $error;
			return $response;
		}
		$stmt->close();
		
		
		//parool vale
		$stmt = $this->connection->prepare("SELECT id, email FROM user_sample WHERE email=? AND password=?");	
		$stmt->bind_param("ss", $email, $hash);
		$stmt->bind_result($id, $email);
		$stmt->execute();
		
		if($stmt->fetch()){
			//kõik õige
			$success = new StdClass();
			$success->message = "Kasutaja edukalt sisselogitud!";
			
			$response->success = $success;
			
			$user = new StdClass();
			$user->id = $id;
			$user->email = $email;
			
			$response->user = $user;
			
		}else{
			//parool vale
			$error = new StdClass();
			$error->id = 1;
			$error->message = "Sellise emailiga kasutajat pole olemas!";
		
			$response->error = $error;
		}
		
		$stmt->close();
		
		return $response;
		}
}
?>