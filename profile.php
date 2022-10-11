<?php
session_start();
$title = 'Profile';
include "init.php";

if(isset($_SESSION['user'])){

    $getUserData = $con->prepare('SELECT * FROM users WHERE username = ?');
    $getUserData->execute(array($_SESSION['user']));
    $userInfo = $getUserData->fetch();
?>
<h1 class='text-center'>My Profile</h1>

<div class="information block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Information</div>
            <div class="panel-body">
                Name: <? echo $userInfo['username'] ?><br>
                Email: <? echo $userInfo['Email'] ?><br>
                Full Name: <? echo $userInfo['FullName'] ?><br>
                Register Date: <? echo $userInfo['Date'] ?><br>
                Favourite Category: 
            </div>
        </div>
    </div>
</div>

<div class="my-advertise block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Latest Ads</div>
            <div class="panel-body">
            <?php
                $getAds = getItem('Member_ID',$userInfo['UserId']);
                if (empty($getAds)) {
                    echo "There no Ads <br><a href='newad.php'> Creat New Ad </a>";
                }else{
                foreach($getAds as $item){
            ?>
            <div class="col-sm-6 col-md-3">
                <div class="thmbnail item-box">
                    <img src="phone.jpg" class='img-responsive' alt="">
                    <span class="price-tag">$<? echo $item['Price']?></span>
                    <h3><a href="items.php?itemId=<? echo $item['Item_ID']; ?>"><? echo $item['Name']?></a></h3>
                    <p><? echo $item['Description']?></p>
                    <p><? echo $item['Add_Date']?></p>
                </div>
            </div>    
            <?}}?>
            </div>
        </div>
    </div>
</div>

<div class="my-comments block">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">My Latest Comments</div>
            <div class="panel-body">
            <?
                $stmt = $con->prepare("SELECT comment FROM comments WHERE user_id = ?");
                $stmt->execute(array($userInfo['UserId']));
                $comments = $stmt->fetchAll();
                if(!empty($comments)){
                    foreach ($comments as $comment) {
                        echo '<p>'. $comment['comment'] .'</p><br>';
                    }
                }else{
                    echo 'There is no comment to show';
                }
            ?>

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