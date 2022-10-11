<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: index.php");
}
$title = 'Login';
include "init.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['login'])){
        $user = $_POST['user'];
        $pass = $_POST['password'];

        $stmt = $con->prepare("SELECT username , password, UserId FROM users WHERE username = ? AND password = ?");
        $stmt->execute(array($user,$pass));

        $count = $stmt->rowCount();
        $fetchId = $stmt->fetch();
        
        if ($count > 0){
            $_SESSION["user"] = $user;
            $_SESSION["uid"] = $fetchId['UserId'];

            header("Location: index.php");
            exit();
        }
    }else{
        $formErrors = []; 

        $username   = $_POST['user'];
        $password   = $_POST['password'];
        $email      = $_POST['email'];
        $check      = checkItem('username','users',$username);

        
        echo "<div class='container'>";
        

        if (isset($_POST['user'])) {
            $filteredUser = filter_var($_POST['user'], FILTER_SANITIZE_STRING);
        
            if (strlen($filteredUser) < 4) {
                $formErrors[] = "User can't be less than 5 chars";
            }    
        }
        if(isset($_POST['password']) && isset($_POST['re_password'])){

            $filteredPass = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            $filteredPass_2 = filter_var($_POST['re_password'], FILTER_SANITIZE_STRING);
            

            if (!$filteredPass) {
                $formErrors[] = "type the password please!";
            }
            if ($filteredPass < 5) {
                $formErrors[] = "password must be more than 5 chars";
            }
            if ($filteredPass !== $filteredPass_2) {
                $formErrors[] = "Password doesn't match";
            }
        }
        if (isset($_POST['email'])) {
            $filteredEmail = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        
            if (filter_var($filteredEmail,FILTER_VALIDATE_EMAIL) != true) {
                $formErrors[] = "this Email is not Valid";
            }    
        }
    }       
    // insert the database 
    if (empty($formErrors)) {
        if ($check > 0) {
            $formErrors[] = 'Sorry the user name is already in use';
        }else{
            $stmt = $con->prepare("INSERT INTO `users` (`UserId`, `username`, `Email`, `password`, `GroubId`, `TrustId`, `FullName`, `RegStatus`, `Date`) 
            VALUES (NULL, ?, ?, ?, '0', '0', '', '0', now())");
            
            $stmt->execute(array($username,$email,$filteredPass));            
            $msg ="you registered succesfully";
        }
    }
}


?>
<div class="container login-box">
    <h1 class='text-center'>
        <span class="x selected" data-class='login'>Login</span> | 
        <span class='z' data-class='signup'>SignUp</span>
    </h1>
    <form class='login' method="POST" action="<?php echo $_SERVER['PHP_SELF']  ?>">
        <input class="form-control" type="text" name="user" placeholder='User name' autocomplete="off" >
        <input class="form-control" type="password" name="password" placeholder='Password' autocomplete="new-password" >
        <input class="btn btn-primary btn-block" type="submit" name="login" value="Login">
    </form>
    <form class='signup' method="POST" action="<?php echo $_SERVER['PHP_SELF']  ?>">
        <input class="form-control" type="text" name="user" placeholder='User name' autocomplete="off" >
        <input class="form-control" type="password" name="password" placeholder='Password' autocomplete="new-password" >
        <input class="form-control" type="password" name="re_password" placeholder='Retype the Password' autocomplete="new-password" >
        <input class="form-control" type="email" name="email" placeholder='email'>
        <input class="btn btn-success btn-block" type="submit" name="signup" value="Signup">
    </form>
</div>
<div class="the-errors text-center">
    <?
        if(!empty($formErrors)){
            foreach($formErrors as $error){
                echo "<div class='container' style='width: 350px;'> 
                        <div class='alert alert-danger text-center'> " . $error . "</div>
                    </div>";
            }
        }
        if(isset($msg)){
            echo "<div class='container' style='width: 350px;'> 
                        <div class='alert alert-success text-center'> " . $msg . "</div>
                    </div>";
        }
    ?>
</div>

<?php
include $tpl . "footer.php";
?>