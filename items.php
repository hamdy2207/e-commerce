<?php

session_start();
$title = 'Profile';
include "init.php";
$itemId = $_GET['itemId'];
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
        $stmt->execute(array($itemId));
        $item = $stmt->fetch();
        
?>

<h1 class='text-center'><? echo $item['Name']; ?></h1>
<div class="container">
    <div class="row">
        <div class="col-md-3">
            <img src="phone.jpg" class='img-responsive img-thumbnail center-block'>
        </div>
        <div class="col-md-9">
            <h2><? echo $item['Name']; ?></h2>
            <p><? echo $item['Description']; ?></p>
            <ul class='list-unstyled'>
                <li><? echo $item['Add_Date']; ?></li>
                <li>Price: <? echo $item['Price']; ?></li>
                <li>Made In: <? echo $item['Country_Made']; ?></li>
                <li>Category: <? echo $item['category_name']; ?></li>
                <li>Added By: <? echo $item['username']; ?></li>
            </ul>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-offset-3">
            <? if(isset($_SESSION['user'])){ ?>
            <div class="add-comment">
                <h3>Add Comment</h3>
                <form method='POST' action="<? echo $_SERVER['PHP_SELF'] . '?itemId=' . $item['Item_ID']; ?>">
                    <textarea name='comment_text' class='form-control'></textarea>
                    <input type="submit" class='btn btn-primary' value="Add Comment">
                </form>
                <? if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $comment   = filter_var($_POST['comment_text'],FILTER_SANITIZE_STRING);
                    $itid      = $item['Item_ID'];
                    $memberId  = $item['Member_ID'];
                    if(empty($comment)){
                        echo 'Please Add a valid Comment';
                    }
                    if(!empty($comment)){
                        $stmt = $con->prepare("INSERT INTO `comments` 
                        (`c_id`, `comment`, `status`, `comment_date`, `item_id`, `user_id`) 
                        VALUES (NULL, :zcomment, '0', now(), :zitem_id, :zuser_id)");
                        $stmt->execute(array(
                            ':zcomment' => $comment,
                            ':zitem_id' => $itid,
                            ':zuser_id' => $memberId
                        ));
                        if ($stmt) {
                            echo "<div class='alert alert-success'>Comment Added</div>";
                        }
                    }

                } ?>
            </div>
            <? }else{ echo 'login or register to add a comment <a href="login.php">Login/SignUp</a>'; } ?>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3">
            User image
        </div>
        <div class="col-md-9">
            user comment
        </div>
    </div>
</div>




<?php
include $tpl . "footer.php";
?>