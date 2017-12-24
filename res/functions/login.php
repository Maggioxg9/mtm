<?php

	session_start();

	if(count($_POST) > 0){

			//database constants
			$servername = "localhost";
			$username = "root";
			$password = "accelmkgt123";
			$dbname = "accelparts";

			//get login info the user typed
			$newuser= htmlspecialchars($_POST["username"]);
			$newpass= htmlspecialchars($_POST["password"]);
			echo "Here";

			try{

				//create a database connection
				$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	
				//create and execute sql query to check login info
				$stmt= $conn->prepare("select userid, username, password from users where username= :name");
				$stmt->execute(array(':name' => "$newuser"));

				//check to see if user record was found
				if($stmt->rowcount() > 0){

					//username found check password
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					$credentials=$result["password"];
			
					//put account details into local variables to be assigned later if login successfull
	
					//check if passwords match
					if(password_verify($newpass, $credentials )){
						//passwords are a match, setup session variables for website use
						$_SESSION['adminmode']=true;
					
						//assign global session variables to be used throughout the website
						$_SESSION['badlogin'] = false;
			
						//close database connection
						$conn = null;
			
						//redirect to homepage
						header("Location: ../../index.html");
						exit();
					}else{

						//passwords dont match, set variable to impose a try again message
						$_SESSION['badlogin'] = true;

						//close database connection
						$conn = null;

						//implement a 2 second sleep to prevent brute-force hacking attempts
						sleep(2);

						//redirect back to login page where a try again message will appear
						header("Location: ../../login.html");
						exit();
					}
				}else{
	
					//no username found, set variable to impose a try again message
					$_SESSION['badlogin'] = true;

					//close database connection
					$conn = null;

					//implement a 2 second sleep to prevent brute-force hacking attempts
					sleep(2);

					//redirect back to login page where a try again message will appear
					header("Location: ../../login.html");
					exit();
				}
			}catch(PDOException $e){
		
				//print error details to screen
				echo $result . "<br>" . $e->getMessage();

				//close database connection
				$conn = null;
			}
	}else{
		//request didnt come from login.html, redirect to that
		header("Location: ../../login.html");
		exit();
	}
?>