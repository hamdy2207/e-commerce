<?php
session_start();

$title = "Categories";
if (!empty($_SESSION)) {
    include "init.php";


    $action = isset($_GET['action']) ? $_GET['action'] : 'Manage';


    if($action == 'Manage') {
        $sort = 'ASC';
        $sort_option = array('ASC','DESC');
        if (isset($_GET['sort']) && in_array($_GET['sort'],$sort_option)){
            $sort = $_GET['sort'];
        }
         
        $stmt = $con->prepare("SELECT * FROM categories ORDER BY Ordering $sort");
        $stmt->execute();
        $cats = $stmt->fetchAll();
        
        
        ?>

    <h1 class='text-center'>Manage Categories</h1>
    <div class="container">
        <div class="ordering pull-lift">
            Order:
            <button class="btn btn-primary <?php if($sort == "ASC"){echo 'active';}?>">
                <a href="?sort=ASC"><i class="fa-solid fa-arrow-up"></i></a>
            </button>
            <button class="btn btn-primary <?php if($sort == "DESC"){echo 'active';}?>">
                <a href="?sort=DESC"><i class="fa-solid fa-arrow-down"></i></a>
            </button>
        </div>
        <div class="table-responsive">
            <table class="table main-table text-center table-bordered">
                <tr>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Ordering</td>
                    <td>Visibility</td>
                    <td>Allow Comments</td>
                    <td>Allow Ads</td>
                    <td>Controls</td>
                </tr>
                <?php
                foreach ($cats as $cat) {
                    
                    echo 
                    " 
                    <tr>
                        <td>". $cat["Name"] ."</td>
                        <td>". $cat["Description"] ."</td>
                        <td>". $cat["Ordering"] ."</td>";
                        if ($cat['Visibility'] == 1) {
                            echo "<td class='status hidden-vis'><div>Hidden</div></td>"; 
                        }elseif ($cat['Visibility'] == 0) {
                            echo "<td class='status visible-vis'><div>Visible</div></td>"; 
                        }
                        if ($cat['Allow_comment'] == 0) {
                            echo "<td class='status visible-comment'><div>Comments Allowed</div></td>"; 
                        }elseif ($cat['Allow_comment'] == 1) {
                            echo "<td class='status hidden-comment'><div>Comments Disabled</div></td>"; 
                        }
                        if ($cat['Allow_ads'] == 0) {
                            echo "<td class='status visible-ads'><div>Ads Allowed</div></td>"; 
                        }elseif ($cat['Allow_ads'] == 1) {
                            echo "<td class='status hidden-ads'><div>Ads Disabled</div></td>"; 
                        }
                        echo "<td class='contr'>
                        <a href='categories.php?action=Edit&ID=". $cat['ID'] ."' class='btn btn-success'>Edit</a>
                        <a href='categories.php?action=Delete&ID=". $cat['ID'] ."' class='confirm btn btn-danger'>Delete</a>";
                        echo "</td></tr>";
                }
                ?>
            </table>
            <a href="categories.php?action=Add" class="btn btn-primary"><i class="fa-solid fa-plus"></i>Add New Category</a>
        </div>
    </div>
    
    <?php    
    }elseif ($action == 'Edit') {  
        $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : 0;

        $stmt = $con->prepare("SELECT * FROM categories WHERE ID = ? LIMIT 1");
        $stmt->execute(array($ID));
        $row = $stmt->fetch();
        $count = $stmt->rowCount();
        if ($count  > 0) { 
            ?>
            
            
                    
                    
                    <h1 class='text-center'>Edit Categories</h1>
                    <div class="container">
                        <form class="form-horizontal" action="categories.php?action=Update" method='POST'>
                            <input type="hidden" name="ID" value="<?php echo $ID;?>">
                            <!-- name -->
                            <div class='form-group'>
                                <label class='col-sm-2 control-label'>Name</label>
                                <div class='col-sm-10'>
                                    <input type="text" name="name" value="<?php echo $row['Name']?>" class='form-control' autocomplete='off' />
                                </div>
                            </div>
                            <!-- Description -->
                            <div class='form-group'>
                                <label class='col-sm-2 control-label'>Description</label>
                                <div class='col-sm-10'>
                                    <input type="text" name="description" class='form-control' value="<?php echo $row['Description']?>" autocomplete='off' />
                                </div>
                            </div>
                            <!-- ordering -->
                            <div class='form-group'>
                                <label class='col-sm-2 control-label'>Ordering</label>
                                <div class='col-sm-10'>
                                    <input type="text" name="order" class='form-control' value="<?php echo $row['Ordering']?>" autocomplete='off' />
                                </div>
                            </div>
                         <!-- visibility -->
                         <div class='form-group'>
                            <label class='col-sm-2 control-label'>Visibile</label>
                            <div class='col-sm-10'>
                                <div>
                                    <input type="radio" value='0' name="vis" id="vis-yes" <?if($row['Visibility'] == 0){echo 'checked';}?> >
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" value='1' name="vis" id="vis-no" <?if($row['Visibility'] == 1){echo 'checked';}?>>
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end of visibility -->

                        <!-- comments -->
                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>Comments</label>
                            <div class='col-sm-10'>
                                <div>
                                    <input type="radio" value='0' name="comment" id="comment-yes" <?if($row['Allow_comment'] == 0){echo 'checked';}?>>
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" value='1' name="comment" id="comment-no" <?if($row['Allow_comment'] == 1){echo 'checked';}?>>
                                    <label for="comment-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end of comments -->

                        <!-- ads -->
                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>Ads</label>
                            <div class='col-sm-10'>
                                <div>
                                    <input type="radio" value='0' name="ads" id="ads-yes" <?if($row['Allow_ads'] == 0){echo 'checked';}?>>
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" value='1' name="ads" id="ads-no" <?if($row['Allow_ads'] == 1){echo 'checked';}?>>
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end of ads -->
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

     }elseif ($action == "Update") {

           
        echo "<h1 class='text-center'>Add Category</h1>";
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $ID             = $_POST['ID'];
            $name           = $_POST['name'];
            $description    = $_POST['description'];
            $order          = $_POST['order'];
            $vis            = $_POST['vis'];
            $comments       = $_POST['comment'];
            $ads            = $_POST['ads'];
            $check          = checkItem('Name','categories',$name);   
            
            echo "<div class='container'>";
            // form errors array
            $formError = [];
            // validation first then update
            // Validation of name
            if (empty($name)) {
                $formError[] = "name can't be empty";
            }elseif ($check > 0) {
                $formError[] = "the name is already in use";
            }
            // update the database 
            if (empty($formError)) {
                $stmt = $con->prepare(" UPDATE categories 
                                        SET 
                                            Name = ? ,
                                            Description = ? , 
                                            Ordering = ? , 
                                            Visibility = ?,
                                            Allow_comment = ?,
                                            Allow_ads = ?
                                        WHERE ID = ?");
    
                $stmt->execute(array($name,$description,$order,$vis,$comments,$ads,$ID));
    
                $successMsg = $stmt->rowCount() . " record updated succesfully";
                redHome($successMsg,'categories','success');
            }else{
                foreach ($formError as $error) {
                    echo "<div class='alert alert-danger'>".$error."</div>";
                }
            }
        }else {
            $errorMsg = "sorry you can't brouse this page direct";
            redHome($errorMsg);

        }
        echo "</div>";

    }elseif($action == 'Add') {

                ?>   
                <h1 class='text-center'>Add Category</h1>
                <div class="container">
                    <form class="form-horizontal" action="categories.php?action=Insert" method='POST'>
                        <!-- name -->
                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>Name</label>
                            <div class='col-sm-10'>
                                <input type="text" name="name" class='form-control' autocomplete='off' />
                            </div>
                        </div>
                        <!-- description -->
                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>description</label>
                            <div class='col-sm-10'>
                                <input type="text" name="description" class='form-control' />
                            </div>
                        </div>
                        <!-- order -->
                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>Order</label>
                            <div class='col-sm-10'>
                                <input type="text" name="order" class='form-control' autocomplete='off' />
                            </div>
                        </div>
                        <!-- visibility -->
                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>Visibile</label>
                            <div class='col-sm-10'>
                                <div>
                                    <input type="radio" value='0' name="vis" id="vis-yes" checked>
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" value='1' name="vis" id="vis-no">
                                    <label for="vis-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end of visibility -->

                        <!-- comments -->
                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>Comments</label>
                            <div class='col-sm-10'>
                                <div>
                                    <input type="radio" value='0' name="comment" id="comment-yes" checked>
                                    <label for="vis-yes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" value='1' name="comment" id="comment-no">
                                    <label for="comment-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end of comments -->

                        <!-- ads -->
                        <div class='form-group'>
                            <label class='col-sm-2 control-label'>Ads</label>
                            <div class='col-sm-10'>
                                <div>
                                    <input type="radio" value='0' name="ads" id="ads-yes" checked>
                                    <label for="ads-yes">Yes</label>
                                </div>
                                <div>
                                    <input type="radio" value='1' name="ads" id="ads-no">
                                    <label for="ads-no">No</label>
                                </div>
                            </div>
                        </div>
                        <!-- end of ads -->

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
           
        echo "<h1 class='text-center'>Add Category</h1>";
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name   = $_POST['name'];
            $description   = $_POST['description'];
            $order   = $_POST['order'];
            $vis   = $_POST['vis'];
            $comments   = $_POST['comment'];
            $ads   = $_POST['ads'];
            $check = checkItem('Name','categories',$name);   
            
            echo "<div class='container'>";
            // form errors array
            $formError = [];
            // validation first then update
            // Validation of name
            if (empty($name)) {
                $formError[] = "name can't be empty";
            }elseif ($check > 0) {
                $formError[] = "the name is already in use";
            }
            // insert the database 
            if (empty($formError)) {
                $stmt = $con->prepare("INSERT INTO `categories` 
                                        (`ID`, `Name`, `Description`, `Ordering`, `Visibility`, `Allow_comment`, `Allow_ads`) 
                                        VALUES 
                                            (NULL, ?, ?, ?, ?, ?, ?)");
                
                $stmt->execute(array($name,$description,$order,$vis,$comments,$ads));
                
                $msg =  $stmt->rowCount() ." record added succesfully";
                redHome($msg,'categories','success'); 
            }else{
                foreach ($formError as $error) {
                    echo "<div class='alert alert-danger'>".$error."</div>";
                }
            }
        }else {
            $errorMsg = "sorry you can't brouse this page direct";
            redHome($errorMsg);

        }
        echo "</div>";    
    }elseif ($action == "Delete") {

        $ID = (isset($_GET['ID']) && is_numeric($_GET['ID'])) ? intval($_GET['ID']) : 0;
        $check = checkItem("ID",'categories',$ID);
        // print_r($check);
        if ($check  > 0) { 
            $stmt = $con->prepare("DELETE FROM categories WHERE ID = :id");
            $stmt->bindParam(":id", $ID);
            $stmt->execute();
            $successMsg = $stmt->rowCount() ." record deleted succesfully";
            redHome($successMsg,'categories','success');

        }else {
            $errorMsg = "There is no category with that id";
            redHome($errorMsg);
        }
    }
    

    include $tpl . "footer.php";
} else {
    header("Location: index.php"); 
    exit();   
}
