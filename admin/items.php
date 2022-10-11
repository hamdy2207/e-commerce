<?php

/***
 * =============================
 * === ITEMS PAGE
 * =============================
 */

session_start();

$title = "Items";
if (!empty($_SESSION)) {
    include "init.php";


    $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';

    if($action == 'Manage') {
        $stmt = $con->prepare("SELECT
                                    items.*,
                                    categories.Name AS category_name,
                                    users.username 
                                FROM 
                                    items
                                INNER JOIN
                                    categories
                                ON
                                    categories.ID = items.Cat_ID
                                INNER JOIN
                                    users
                                ON
                                    users.UserId = items.Member_ID");
        $stmt->execute();
        $rows = $stmt->fetchAll();
        
        
        ?>

    <h1 class='text-center'>Manage Items</h1>
    <div class="container">
        <div class="table-responsive">
            <table class="table main-table text-center table-bordered">
                <tr>
                    <td>#ID</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Adding Date</td>
                    <td>Category</td>
                    <td>Username</td>
                    <td>Control</td>
                </tr>
                <?php
                foreach ($rows as $row) {
                    
                    echo 
                    " 
                    <tr>
                        <td>". $row["Item_ID"] ."</td>
                        <td>". $row["Name"] ."</td>
                        <td>". $row["Description"] ."</td>
                        <td>". $row["Price"] ."</td>
                        <td>". $row["Add_Date"] ."</td>
                        <td>". $row["category_name"] ."</td>
                        <td>". $row["username"] ."</td>
                        <td class='contr'>"; 
                        echo "<a href='items.php?action=Edit&itemid=". $row['Item_ID'] ."' class='btn btn-success'>Edit</a>
                        <a href='items.php?action=Delete&itemid=". $row['Item_ID'] ."' class='btn btn-danger'>Delete</a>";
                        if ($row['Approve'] == 0){
                            echo "<a href='items.php?action=Approve&itemid=". $row['Item_ID'] ."' class='activate btn btn-info'>Approve</a>";
                        }
                        echo "</td></tr>";
                }
                ?>
            </table>
            <a href="items.php?action=Add" class="btn btn-primary"><i class="fa-solid fa-plus"></i>Add New Item</a>
        </div>
    </div>
    
    <?php

    }elseif($action == 'Add') {


        ?>   
        <h1 class='text-center'>Add Item</h1>
        <div class="container">
            <form class="form-horizontal" action="items.php?action=Insert" method='POST'>
                <!-- name -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Name</label>
                    <div class='col-sm-10'>
                        <input type="text" name="name" class='form-control' />
                    </div>
                </div>
                <!-- end of name -->
                <!-- description -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Description</label>
                    <div class='col-sm-10'>
                        <input type="text" name="description" class='form-control' />
                    </div>
                </div>
                <!-- end of description -->
                <!-- price -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Price</label>
                    <div class='col-sm-10'>
                        <input type="text" name="price" class='form-control' autocomplete='off' />
                    </div>
                </div>
                <!-- end of price -->
                <!-- country made -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Country made</label>
                    <div class='col-sm-10'>
                        <input type="text" name="country" class='form-control' autocomplete='off' />
                    </div>
                </div>
                <!-- end of country -->
                <!-- status -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label' for='status'>Status</label>
                    <div class='col-sm-10'>
                        <select class="form-control" name="status" id="status">
                            <option value="0">...</option>
                            <option value="1">New</option>
                            <option value="2">Like New</option>
                            <option value="3">Old</option>
                        </select>
                    </div>
                </div>
                <!-- end of status -->
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
                                    echo "<option value='" . $user['UserId'] . "'>" . $user['username'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- end of members -->
                <!-- caregories -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label' for='categories'>Category</label>
                    <div class='col-sm-10'>
                        <select class="form-control" name="categories" id="categories">
                            <option value="0">...</option>
                            <?php 
                                $stmt = $con->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach($cats as $cat){
                                    echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- end of categories -->
                <!-- 
                    ******
                    ******
                 -->
                <!-- btn -->
                <div class='form-group'>
                    <div class='col-sm-offset-2 col-sm-10'>
                        <input type="submit" value="Add" class='btn btn-primary' />
                    </div>
                </div>
            </form>
        </div>
<?php   
    }elseif ($action == 'Insert') {
           
        echo "<h1 class='text-center'>Add Item</h1>";
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name           = $_POST['name'];
            $description    = $_POST['description'];
            $price          = $_POST['price'];
            $country        = $_POST['country'];
            $status         = $_POST['status'];
            $members        = $_POST['members'];
            $categories     = $_POST['categories'];

            
            
            echo "<div class='container'>";
            // form errors array
            $formError = [];
            // validation first then add
            // Validation of name
            if (empty($name)) {
                $formError[] = "name can't be empty";
            }
            // valdation of description
            if (empty($description)) {
                $formError[] = "description can't be empty";
            }
            // validation of price
            if (empty($price)) {
                $formError[] = "price can't be empty";
            }// validation of country
            if (empty($country)) {
                $formError[] = "country can't be empty";
            }
            // insert into the database 
            if (empty($formError)) {
                $stmt = $con->prepare("INSERT INTO `items` 
                    (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Status`,`Cat_ID`,`Member_ID`) 
                VALUES 
                    (NULL, :zname, :zdesc, :zprice, now(), :zcountry, :zstatus, :zcategory, :zmember);");
                
                $stmt->execute(array(
                    'zname'     => $name,
                    'zdesc'     => $description,
                    'zprice'    => $price,
                    'zcountry'  => $country,
                    'zstatus'   => $status,
                    'zcategory' => $categories,
                    'zmember'   => $members
                ));
                
                $msg =  $stmt->rowCount() ." record added succesfully";
                redHome($msg,'items','success'); 
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
    }elseif ($action == 'Edit') {  

        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;

        $stmt = $con->prepare("SELECT
                                    items.*,
                                    categories.Name AS category_name,
                                    users.username 
                                FROM 
                                    items
                                INNER JOIN
                                    categories
                                ON
                                    categories.ID = items.Cat_ID
                                INNER JOIN
                                    users
                                ON
                                    users.UserId = items.Member_ID 
                                WHERE Item_ID = ?");
        $stmt->execute(array($itemid));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count  > 0) { 
        ?>   
        <h1 class='text-center'>Edit Item</h1>
        <div class="container">
            <form class="form-horizontal" action="items.php?action=Update" method='POST'>
                <input type="hidden" name='itemid' value='<?echo $itemid;?>'>
                <!-- name -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Name</label>
                    <div class='col-sm-10'>
                        <input type="text" name="name" value='<?echo $row["Name"]?>' class='form-control' />
                    </div>
                </div>
                <!-- end of name -->
                <!-- description -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Description</label>
                    <div class='col-sm-10'>
                        <input type="text" name="description" value='<?echo $row["Description"]?>' class='form-control' />
                    </div>
                </div>
                <!-- end of description -->
                <!-- price -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Price</label>
                    <div class='col-sm-10'>
                        <input type="text" name="price" value='<?echo $row["Price"]?>' class='form-control' autocomplete='off' />
                    </div>
                </div>
                <!-- end of price -->
                <!-- country made -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label'>Country made</label>
                    <div class='col-sm-10'>
                        <input type="text" name="country" value='<?echo $row["Country_Made"]?>'  class='form-control' autocomplete='off' />
                    </div>
                </div>
                <!-- end of country -->
                <!-- status -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label' for='status'>Status</label>
                    <div class='col-sm-10'>
                        <select class="form-control" name="status" id="status">
                            <option value="0" >...</option>
                            <option value="1" <?if($row['Status'] == 1){echo 'selected';}?> >New</option>
                            <option value="2" <?if($row['Status'] == 2){echo 'selected';}?>>Like New</option>
                            <option value="3" <?if($row['Status'] == 3){echo 'selected';}?>>Old</option>
                        </select>
                    </div>
                </div>
                <!-- end of status -->
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
                <!-- categories -->
                <div class='form-group'>
                    <label class='col-sm-2 control-label' for='categories'>Category</label>
                    <div class='col-sm-10'>
                        <select class="form-control" name="categories" id="category">
                            <option value="0">...</option>
                            <?php 
                                $stmt = $con->prepare("SELECT * FROM categories");
                                $stmt->execute();
                                $cats = $stmt->fetchAll();
                                foreach($cats as $cat){
                                    $selectedCat = $cat['Name'] == $row['category_name'] ? 'selected' : '';
                                    echo "<option value=' " . $cat['ID'] . " ' " . $selectedCat . " >" . $cat['Name'] . "</option>";
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- end of categories -->
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
            $name           = $_POST['name'];
            $description    = $_POST['description'];
            $price          = $_POST['price'];
            $country        = $_POST['country'];
            $status         = $_POST['status'];
            $members        = $_POST['members'];
            $categories     = $_POST['categories'];
            $itemid         = $_POST['itemid'];

            
            
            echo "<div class='container'>";
            // form errors array
            $formError = [];
            // validation first then add
            // Validation of name
            if (empty($name)) {
                $formError[] = "name can't be empty";
            }
            // valdation of description
            if (empty($description)) {
                $formError[] = "description can't be empty";
            }
            // validation of price
            if (empty($price)) {
                $formError[] = "price can't be empty";
            }// validation of country
            if (empty($country)) {
                $formError[] = "country can't be empty";
            }
            // insert into the database 
            if (empty($formError)) {
                $stmt = $con->prepare("UPDATE `items`  
                                        SET 
                                        `Name`          = ?,
                                        `Description`   = ?,
                                        `Price`         = ?,
                                        `Country_Made`  = ?,
                                        `Status`        = ?,
                                        `Cat_ID`        = ?,
                                        `Member_ID`     = ? 
                                        WHERE 
                                            `items`.`Item_ID` = ?");
                
                $stmt->execute(array($name,$description,$price,$country,$status,$categories,$members,$itemid));

                
                $msg =  $stmt->rowCount() ." record updated succesfully";
                redHome($msg,'items','success'); 
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
        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;
        $check = checkItem("Item_ID",'items',$itemid);
        // print_r($check);
        if ($check  > 0) { 
            $stmt = $con->prepare("DELETE FROM items WHERE Item_ID = :id");
            $stmt->bindParam(":id", $itemid);
            $stmt->execute();
            $successMsg = $stmt->rowCount() ." record deleted succesfully";
            redHome($successMsg,'items','success');

        }else {
            $errorMsg = "There is no items with that id";
            redHome($errorMsg);
        }
    }elseif ($action == "Approve") {
        echo "<div class='container'>";
        $itemid = (isset($_GET['itemid']) && is_numeric($_GET['itemid'])) ? intval($_GET['itemid']) : 0;
        $check = checkItem("Item_ID",'items',$itemid);
        if ($check  > 0) { 
            $stmt = $con->prepare("UPDATE items SET Approve = 1 WHERE Item_ID = :zitem");
            $stmt->bindParam(":zitem", $itemid);
            $stmt->execute();
            $successMsg = $stmt->rowCount() ." record approved succesfully";
            redHome($successMsg,'back','success');

        }else {
            $errorMsg = "There is no user with that id";
            redHome($errorMsg);
        }
        echo "</div>";
    }
    

    include $tpl . "footer.php";
} 
else {
    header("Location: index.php"); 
    exit();   
}
