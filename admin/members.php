<?php
session_start();

$title = "Members";
if (!empty($_SESSION)) {
    include "init.php";


    $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

    // start of front end part
    
    // Manage page 
    if($action == 'Manage') {
        $query = '';
        if(isset($_GET['page']) && $_GET['page'] == 'pending'){
            $query = 'AND RegStatus = 0';
        }
         
        $stmt = $con->prepare("SELECT * FROM users WHERE GroubId != 1 $query");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        
        
        ?>

    <h1 class='text-center'>Manage Members</h1>
    <div class="container">
        <div class="table-responsive">
            <table class="table main-table text-center table-bordered">
                <tr>
                    <td>#ID</td>
                    <td>Username</td>
                    <td>Email</td>
                    <td>Full Name</td>
                    <td>Registerd Date</td>
                    <td>Control</td>
                </tr>
                <?php
                foreach ($rows as $row) {
                    
                    echo 
                    " 
                    <tr>
                        <td>". $row["UserId"] ."</td>
                        <td>". $row["username"] ."</td>
                        <td>". $row["Email"] ."</td>
                        <td>". $row["FullName"] ."</td>
                        <td>". $row["Date"] ."</td>
                        <td class='contr'>
                        <a href='members.php?action=Edit&userid=". $row['UserId'] ."' class='btn btn-success'>Edit</a>
                        <a href='members.php?action=Delete&userid=". $row['UserId'] ."' class='confirm btn btn-danger'>Delete</a>";
                        if ($row['RegStatus'] == 0){
                            echo "<a href='members.php?action=Activate&userid=". $row['UserId'] ."' class='activate btn btn-info'>Activate</a>";
                        }
                        echo "</td></tr>";
                }
                ?>
            </table>
            <a href="members.php?action=Add" class="btn btn-primary"><i class="fa-solid fa-plus"></i>Add New Member</a>
        </div>
    </div>
    
    <?php
    }
    // Add page 
    elseif($action == 'Add') {
        ?>   
        <h1 class='text-center'>Add Member</h1>
        <div class="container">
            <form class="form-horizontal" action="members.php?action=Insert" method='POST'>
                <!-- username -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Username</label>
                    <div class='col-sm-10'>
                        <input type="text" name="username" class='form-control' autocomplete='off' />
                    </div>
                </div>
                <!-- password -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Password</label>
                    <div class='col-sm-10'>
                        <input type="password" name="password" class='password form-control' autocomplete='new-password' />
                        <i class="show-pass fa-solid fa-eye-slash"></i>
                        <!-- <i class="fa-solid fa-eye"></i> -->
                    </div>
                </div>
                <!-- Email -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Email</label>
                    <div class='col-sm-10'>
                        <input type="text" name="email" class='form-control' autocomplete='off' />
                    </div>
                </div>
                <!-- full name -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Full Name</label>
                    <div class='col-sm-10'>
                        <input type="text" name="fullname" class='form-control' autocomplete='off' />
                    </div>
                </div>
                <!-- btn -->
                <div class='form-group'>
                    <div class='col-sm-offset-2 col-sm-10'>
                        <input type="submit" value="Add" class='btn btn-primary' />
                    </div>
                </div>
            </form>
        </div>
<?php    
}
    // Edit page 
    elseif ($action == 'Edit') { 
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        // echo $userid;

        $stmt = $con->prepare("SELECT * FROM users WHERE UserId = ? LIMIT 1");
        $stmt->execute(array($userid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count  > 0) { 
            ?>
            
            
                    
                    
                    <h1 class='text-center'>Edit Member</h1>
                    <div class="container">
                        <form class="form-horizontal" action="members.php?action=Update" method='POST'>
                            <input type="hidden" name="userid" value="<?php echo $userid;?>">
                            <!-- username -->
                            <div class='form-group'>
                                <label class='col-sm-2 control-label'>Username</label>
                                <div class='col-sm-10'>
                                    <input type="text" name="username" value="<?php echo $row['username']?>" class='form-control' autocomplete='off' />
                                </div>
                            </div>
                            <!-- password -->
                            <div class='form-group'>
                                <label class='col-sm-2 control-label'>Password</label>
                                <div class='col-sm-10'>
                                    <input type="password" name="password" class='form-control' value="<?php echo $row['password']?>" autocomplete='new-password' />
                                </div>
                            </div>
                            <!-- Email -->
                            <div class='form-group'>
                                <label class='col-sm-2 control-label'>Email</label>
                                <div class='col-sm-10'>
                                    <input type="text" name="email" class='form-control' value="<?php echo $row['Email']?>" autocomplete='off' />
                                </div>
                            </div>
                            <!-- full name -->
                            <div class='form-group'>
                                <label class='col-sm-2 control-label'>Full Name</label>
                                <div class='col-sm-10'>
                                    <input type="text" name="fullname" class='form-control' value="<?php echo $row['FullName']?>" autocomplete='off' />
                                </div>
                            </div>
                            <!-- btn -->
                            <div class='form-group'>
                                <div class='col-sm-offset-2 col-sm-10'>
                                    <input type="submit" value="Save" class='btn btn-primary' />
                                </div>
                            </div>
                        </form>
                    </div>
        <?php
        } else {
            echo "<div class='container'>";
            $errorMsg = 'there is no such user here';
            redHome($errorMsg);
            echo "</div>";

        }
    } 
    //end of front end part


/* 
************
***************
************
***************
************
***************
************
***************
************
***************

*/

    //***** the backend part */
    // update functions
    elseif ($action == "Update") {
        echo "<h1 class='text-center'>Update Member</h1>";
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id   = $_POST['userid'];
            $username   = $_POST['username'];
            $password   = $_POST['password'];
            $email   = $_POST['email'];
            $fullname   = $_POST['fullname'];

            echo "<div class='container'>";
            // form errors array
            $formError = [];
            // validation first then update
            // Validation of username
            if (empty($username)) {
                $formError[] = "username can't be empty";
            }elseif(strlen($username) < 5){
                $formError[] = "the username can't be less than five chars";
            }
            // valdation of password
            if (empty($password)) {
                $formError[] = "password can\'t be empty";
            }elseif(strlen($password) < 5){
                $formError[] = "the password can't be less than five chars";
            }
            // validation of email
            if (empty($email)) {
                $formError[] = "email can\'t be empty";
            }
            // validation of fullname
            if (empty($fullname)) {
                $formError[] = "full name can\'t be empty";
            }elseif(strlen($fullname) < 5){
                $formError[] = "the full name can't be less than five chars";
            }
            // update the database 
            if (empty($formError)) {

                $stmt2 = $con->prepare("SELECT *
                                        FROM 
                                            users
                                        WHERE 
                                            username = ?
                                        AND
                                            UserId != ?");
                $stmt2->execute(array($username,$id));
                $checkUser = $stmt2->rowCount();
                
                if($checkUser == 1){
                    $formError[] = "this username is already exist";
                }else {
                    $stmt = $con->prepare(" UPDATE users 
                                                        SET 
                                                            username = ? ,
                                                            password = ? , 
                                                            Email = ? , 
                                                            FullName = ? 
                                                        WHERE UserId = ?");
                    
                    $stmt->execute(array($username,$password,$email,$fullname,$id));
        
                    $successMsg = $stmt->rowCount() . " record updated succesfully";
                    redHome($successMsg,'members','success');
                }


    
        
            }
            // print_r($formError);
            foreach ($formError as $error) {
                echo "<div class='alert alert-danger'>".$error."</div>";  
            }
        }else {
            echo "<div class='container'>";
            $errorMsg = "sorry you can't brouse this page direct";
            redHome($errorMsg);
            echo "</div>";

        }
        echo "</div>";
    }
    // Insert functions 
    elseif ($action == 'Insert') {
        echo "<h1 class='text-center'>Add Member</h1>";
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username   = $_POST['username'];
            $password   = $_POST['password'];
            $email   = $_POST['email'];
            $fullname   = $_POST['fullname'];
            $check = checkItem('username','users',$username);

            
            
            echo "<div class='container'>";
            // form errors array
            $formError = [];
            // validation first then update
            // Validation of username
            if (empty($username)) {
                $formError[] = "username can't be empty";
            }elseif ($check > 0) {
                $formError[] = "the username is already in use";
            }
            // valdation of password
            if (empty($password)) {
                $formError[] = "password can't be empty";
            }elseif(strlen($password) < 5){
                $formError[] = "the password ca't be less than five chars";
            }
            // validation of email
            elseif (empty($email)) {
                $formError[] = "email can't be empty";
            }
            // validation of fullname
            elseif (empty($fullname)) {
                $formError[] = "full name can't be empty";
            }
            // update the database 
            if (empty($formError)) {
                $stmt = $con->prepare("INSERT 
                                        users (username,password,Email,FullName,RegStatus,Date)
                                        values (:zuser,:zpass,:zemail,:zname,1,now())"
                                        );
                
                $stmt->execute(array(
                ":zuser" => $username,
                ":zpass" => $password,
                ":zemail" => $email,
                ":zname" => $fullname
                ));
                
                $msg =  $stmt->rowCount() ." record added succesfully";
                redHome($msg,'members','success'); 
            }
            // print_r($formError);
            foreach ($formError as $error) {
                echo "<div class='alert alert-danger'>".$error."</div>";
            }
        }else {
            $errorMsg = "sorry you can't brouse this page direct";
            redHome($errorMsg);

        }
        echo "</div>";    
        // delete the data
    }elseif ($action == "Delete") {
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $check = checkItem("userid",'users',$userid);
        if ($check  > 0 && $userid != 1) { 
            $stmt = $con->prepare("DELETE FROM users WHERE UserId = :user");
            $stmt->bindParam(":user", $userid);
            $stmt->execute();
            $successMsg = $stmt->rowCount() ." record deleted succesfully";
            redHome($successMsg,'members','success');

        }else {
            $errorMsg = "There is no user with that id";
            redHome($errorMsg);
        }
    }elseif ($action == "Activate") {
        echo "<div class='container'>";
        $userid = (isset($_GET['userid']) && is_numeric($_GET['userid'])) ? intval($_GET['userid']) : 0;
        $check = checkItem("userid",'users',$userid);
        if ($check  > 0 && $userid != 1) { 
            $stmt = $con->prepare("UPDATE users SET RegStatus = 1 WHERE UserId = :user");
            $stmt->bindParam(":user", $userid);
            $stmt->execute();
            $successMsg = $stmt->rowCount() ." record activated succesfully";
            redHome($successMsg,'back','success');

        }else {
            $errorMsg = "There is no user with that id";
            redHome($errorMsg);
        }
        echo "</div>";
    }
    //end of the backend part




    include $tpl . "footer.php";
} else {
    header("Location: index.php"); 
    exit();   
}