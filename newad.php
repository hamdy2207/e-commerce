<?php
session_start();
$title = 'NEW AD';
include "init.php";

if(isset($_SESSION['user'])){

    $userId = $_SESSION['uid'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $name           = filter_var($_POST['name'],FILTER_SANITIZE_STRING);
        $description    = filter_var($_POST['description'],FILTER_SANITIZE_STRING);
        $price          = filter_var($_POST['price'],FILTER_SANITIZE_NUMBER_INT);
        $country        = filter_var($_POST['country'],FILTER_SANITIZE_STRING);
        $status         = filter_var($_POST['status'],FILTER_SANITIZE_NUMBER_INT);
        $categories     = filter_var($_POST['categories'],FILTER_SANITIZE_NUMBER_INT);

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
        }// validation of status
        if (empty($status)) {
            $formError[] = "status can't be empty";
        }// validation of category
        if (empty($categories)) {
            $formError[] = "category can't be empty";
        }
        // insert into the database 
        if (empty($formError)) {
            $stmt = $con->prepare("INSERT INTO 
            `items` 
            (`Item_ID`, `Name`, `Description`, `Price`, `Add_Date`, `Country_Made`, `Image`, `Status`, `Rating`, `Approve`, `Cat_ID`, `Member_ID`) 
            VALUES (NULL, :zname, :zdesc, :zprice, now(), :zcountry, NULL, :zstatus, NULL, '0', :zcategory, :zmember);");
            
            $stmt->execute(array(
                'zname'     => $name,
                'zdesc'     => $description,
                'zprice'    => $price,
                'zcountry'  => $country,
                'zstatus'   => $status,
                'zcategory' => $categories,
                'zmember'   => $userId
            ));
            
            
            $msg =  $stmt->rowCount() ." Item added succesfully";
            redHome($msg,'profile','success'); 
        }else{
            foreach ($formError as $error) {
                echo "<div class='alert alert-danger'>".$error."</div>";
            }
        }
    }

?>
<h1 class='text-center'>Creat a New Ad</h1>

<div class="ads block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">New AD</div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8">
                        <form class="form-horizontal" action="<? echo $_SERVER['PHP_SELF']; ?>" method='POST'>
                        
                            <!-- name -->
                            <div class='form-group'>
                                <label class='col-sm-2 control-label'>Name</label>
                                <div class='col-sm-10'>
                                    <input type="text" name="name" class='form-control live-name' />
                                </div>
                            </div>
                            <!-- end of name -->
                            <!-- description -->
                            <div class='form-group'>
                                <label class='col-sm-2 control-label'>Description</label>
                                <div class='col-sm-10'>
                                    <input type="text" name="description" class='form-control live-description' />
                                </div>
                            </div>
                            <!-- end of description -->
                            <!-- price -->
                            <div class='form-group'>
                                <label class='col-sm-2 control-label'>Price</label>
                                <div class='col-sm-10'>
                                    <input type="text" name="price" class='form-control live-price' autocomplete='off' />
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
                            <!-- end btn -->
                        </form>    
                    </div>
                    <div class="col-md-4">
                        <div class="thmbnail item-box live-preview">
                            <img src="phone.jpg" class='img-responsive' alt="">
                            <span class="price-tag">$</span>
                            <div class="caption">
                                <h3>title</h3>
                                <p>description</p>
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
} else {
    header('Location: login.php');
    exit();
}

include $tpl . "footer.php";
?>