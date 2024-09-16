<?php


require_once('./config.php');

$username = $password = "";
$err = ""; // Initialize the error variable

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (empty(trim($_POST['username'])) || empty(trim($_POST['password']))) {
        $err = "Please enter username + password";
    } else {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }

    if (empty($err)) {
        $sql = "SELECT id, username, password FROM users WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            $param_username = $username;

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
            
                    if (mysqli_stmt_fetch($stmt)) {
                        
                            $_SESSION["username"] = $username;
                            $_SESSION["id"] = $id;
                            $_SESSION["loggedin"] = true;
                            
                            //Redirect user to welcome page
                            header("location: index.php");
                            exit(); // Ensure script stops executing after redirection
                        
                    }
                }
            } 
            
            mysqli_stmt_close($stmt);
        } 
    }
}

// If there are any errors, you can handle them here or display them in your HTML form
echo $err;
?>
