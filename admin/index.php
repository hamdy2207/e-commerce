<?php

session_start();
if (!empty($_SESSION)) {
    header("Location: dashbord.php");
}
$noNav = '';
$title = 'Login';



include "init.php";

?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = $_POST['user'];
    $password = $_POST['password'];
    $hashedpass = $password;

    $stmt = $con->prepare("SELECT UserId,username , password FROM users WHERE username = ? 
    AND 
    password = ? 
    AND 
    GroubId = 1 
    LIMIT 1");
    $stmt->execute(array($username,$hashedpass));
    $row = $stmt->fetch();

    $count = $stmt->rowCount();
    
    if ($count > 0){
        $_SESSION["Username"] = $username;
        $_SESSION["ID"] = $row['UserId'];

        header("Location: dashbord.php");
        exit();
    }

}

?>




<form class="login" method="POST" action="<?php echo $_SERVER['PHP_SELF']  ?>">
    <h4 class="text-center" >Admin Login</h4>
    <input class="form-control" type="text" name="user" placeholder='Username' autocomplete="off" >
    <input class="form-control" type="password" name="password" placeholder='Password' autocomplete="new-password" >
    <input class="btn btn-primary btn-block" type="submit" value="Login">
</form>


<?php
include $tpl . "footer.php";
?>