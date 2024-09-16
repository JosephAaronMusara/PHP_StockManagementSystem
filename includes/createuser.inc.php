<?php

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //grab dta
    $username = $_POST["username"];
    $pwd = $_POST["pwd"];
    $confirm_pwd = $_POST["confirm_pwd"];
    $email = $_POST["email"];

    if($pwd == $confirm_pwd){
        try {
            require_once "dbh.inc.php";
    
            $query = "INSERT INTO users(username,pwd,email) VALUES(:username,:pwd,:email);";
    
            $statement = $pdo -> prepare($query);
    
            $statement -> bindParam(":username",$username);
            $statement -> bindParam(":pwd",password_hash($pwd,PASSWORD_BCRYPT,['cost'=>12]));
            //cost is for hashing dificulty. The higher, the harder. Slows loadin. Recomemted 10 to 12
            $statement -> bindParam(":email",$email);
            $statement -> execute();
    
            $pdo = null;
            $statement = null;
    
            header("Location: ../accounts/login.php");
    
            die();
    
        } catch (PDOException $e) {
            die("Query failed : ". $e->getMessage());
        }

    }

    else{
        echo "Passwords did not match";
    }
    

}else{
    header("Location: ../accounts/register.php");
}
?>