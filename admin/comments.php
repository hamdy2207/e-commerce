<?php

/***
 * =============================
 * === COMMENTS PAGE
 * =============================
 */

session_start();

$title = "";
if (!empty($_SESSION)) {
    include "init.php";


    $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';


    if($action == 'Manage') {

        $stmt = $con->prepare("SELECT 
                                    comments.*,users.username,items.Name AS item_name
                                FROM 
                                    comments
                                JOIN 
                                    users
                                ON 
                                    users.UserId = comments.user_id 
                                JOIN
                                    items
                                ON
                                    comments.item_id = items.item_ID");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        ?>

    <h1 class='text-center'>Manage Items</h1>
    <div class="container">
        <div class="table-responsive">
            <table class="table main-table text-center table-bordered">
                <tr>
                    <td>#ID</td>
                    <td>Comment</td>
                    <td>Item Name</td>
                    <td>User Name</td>
                    <td>Added Date</td>
                    <td>Control</td>
                </tr>
                <?php
                foreach ($rows as $row) {
                    
                    echo 
                    " 
                    <tr>
                        <td>". $row["c_id"] ."</td>
                        <td>". $row["comment"] ."</td>
                        <td>". $row["item_name"] ."</td>
                        <td>". $row["username"] ."</td>
                        <td>". $row["comment_date"] ."</td>
                        <td class='contr'>"; 
                        echo "<a href='comments.php?action=Edit&comid=". $row['c_id'] ."' class='btn btn-success'>Edit</a>
                        <a href='comments.php?action=Delete&comid=". $row['c_id'] ."' class='btn btn-danger'>Delete</a>";
                        if ($row['status'] == 0){
                            echo "<a href='comments.php?action=Approve&comid=". $row['c_id'] ."' class='activate btn btn-info'>Approve</a>";
                        }
                        echo "</td></tr>";
                }
                ?>
            </table>
        </div>
    </div>
    
    <?php

    }elseif ($action == 'Edit') {  

        $comid = (isset($_GET['comid']) && is_numeric($_GET['comid'])) ? intval($_GET['comid']) : 0;

        $stmt = $con->prepare("SELECT 
                                    comments.*,users.username,items.Name AS item_name
                                FROM 
                                    comments
                                JOIN 
                                    users
                                ON 
                                    users.UserId = comments.user_id 
                                JOIN
                                    items
                                ON
                                    comments.item_id = items.item_ID
                                WHERE c_id = ?");
        $stmt->execute(array($comid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count  > 0) { 
        ?>   
        <h1 class='text-center'>Edit Comments</h1>
        <div class="container">
            <form class="form-horizontal" action="comments.php?action=Update" method='POST'>
                <input type="hidden" name='comid' value='<?echo $comid;?>'>
                <!-- comment -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Name</label>
                    <div class='col-sm-10'>
                        <input type="text" name="comment" value='<?echo $row["comment"]?>' class='form-control' />
                    </div>
                </div>
                <!-- end of comment -->
                <!-- members -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label' for='members'>Member</label>
                    <div class='col-sm-10'>
                        <select class="form-control" name="members" id="members">
                            <option value="0">...</option>
                            <?php 
                                $stmt = $con->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $users = $stmt->fetchAll();
                                foreach($users as $user){
                                    $selected = $user['username'] == $row['username'] ? 'selected' : '';
                                    echo "<option value=' " . $user['UserId'] . " ' " . $selected . " >" . $user['username'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- end of members -->                
                <!-- items -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label' for='items'>Item</label>
                    <div class='col-sm-10'>
                        <select class="form-control" name="items" id="items">
                            <option value="0">...</option>
                            <?php 
                                $stmt = $con->prepare("SELECT * FROM items");
                                $stmt->execute();
                                $items = $stmt->fetchAll();
                                foreach($items as $item){
                                    $selected = $item['Name'] == $row['item_name'] ? 'selected' : '';
                                    echo "<option value=' " . $item['Item_ID'] . " ' " . $selected . " >" . $item['Name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- end of items -->

                <!-- 
                    ******
                    ******
                 -->
                <!-- btn -->
                <div class='form-group'>
                    <div class='col-sm-offset-2 col-sm-10'>
                        <input type="submit" value="Save" class='btn btn-primary' />
                    </div>
                </div>
            </form>
        </div>
<?php   }
              
    }elseif ($action == "Update") {


           
        echo "<h1 class='text-center'>update Item</h1>";
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $comment   = $_POST['comment'];
            $members   = $_POST['members'];
            $items     = $_POST['items'];
            $comid     = $_POST['comid'];            
            
            echo "<div class='container'>";
            // form errors array
            $formError = [];
            // validation first then add
            // Validation of name
            if (empty($comment)) {
                $formError[] = "comment can't be empty";
            }
            // insert into the database 
            if (empty($formError)) {
                $stmt = $con->prepare("UPDATE `comments` 
                                        SET 
                                            `comment` = ?,
                                            `item_id` = ?,
                                            `user_id` = ? 
                                        WHERE 
                                            `comments`.`c_id` = ?;");
                
                $stmt->execute(array($comment,$items,$members,$comid));

                
                $msg =  $stmt->rowCount() ." record updated succesfully";
                redHome($msg,'comments','success'); 
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
    }elseif ($action == "Delete") {
        $comid = (isset($_GET['comid']) && is_numeric($_GET['comid'])) ? intval($_GET['comid']) : 0;
        $check = checkItem("c_id",'comments',$comid);
        // print_r($check);
        if ($check  > 0) { 
            $stmt = $con->prepare("DELETE FROM comments WHERE c_id = :id");
            $stmt->bindParam(":id", $comid);
            $stmt->execute();
            $successMsg = $stmt->rowCount() ." record deleted succesfully";
            redHome($successMsg,'comments','success');

        }else {
            $errorMsg = "There is no comments with that id";
            redHome($errorMsg);
        }
    }elseif ($action == "Approve") {
        echo "<div class='container'>";
        $comid = (isset($_GET['comid']) && is_numeric($_GET['comid'])) ? intval($_GET['comid']) : 0;
        $check = checkItem("c_id",'comments',$comid);
        if ($check  > 0) { 
            $stmt = $con->prepare("UPDATE comments SET status = 1 WHERE c_id = :zcomment");
            $stmt->bindParam(":zcomment", $comid);
            $stmt->execute();
            $successMsg = $stmt->rowCount() ." record approved succesfully";
            redHome($successMsg,'back','success');

        }else {
            $errorMsg = "There is no comment with that id";
            redHome($errorMsg);
        }
        echo "</div>";
    }
    

    include $tpl . "footer.php";
} else {
    header("Location: index.php"); 
    exit();   
}