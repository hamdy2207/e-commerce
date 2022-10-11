<?php
session_start();

$title = 'Dashboard';

if (!empty($_SESSION)) {
    include "init.php";

    $Latest = 5;
    $latestUsers    = getLatest('*','users','UserId',$Latest);
    $latestItems    = getLatest('*','items','Item_ID',$Latest);
    $statementCom = $con->prepare("SELECT 
                            comments.*,users.username
                        FROM 
                            comments
                        JOIN 
                            users
                        ON 
                            users.UserId = comments.user_id 
                        LIMIT $Latest");
    $statementCom->execute();
    $latestComments = $statementCom->fetchAll();


    ?>
    <!-- start -->

    <div class="container home-stats text-center">
        <h1>Dashboard</h1>
        <div class="row">
            <div class="col-md-3">
                <div class="stat st-members">
                    <a href="members.php">
                        Total Members
                        <span><?php echo countItem('UserId','users');?></span>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-pend">
                    <a href="members.php?page=pending">
                        pending Members
                        <span><?php echo checkItem('RegStatus','users',0)?></span>
                    </a>    

                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-items">
                    <a href="items.php">
                        Total Items
                        <span><?php echo countItem('Item_ID','items');?></span>
                    </a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat st-comments">
                <a href="comments.php">
                        Total Comments
                        <span><?php echo countItem('c_id','comments');?></span>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="latest">
        <div class="container">
            <div class="row">
                <!-- latest members -->
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-users"></i>
                            Latest <?echo $Latest?> Registerd Users
                        </div>
                        <div class="panel-body">
                            <ul class='list-group latest-user'>
                        <?php 
                            foreach ($latestUsers as $latestUser) {
                                echo "<li class='list-group-item'>".$latestUser['username'];
                                echo "<div>";
                                if ($latestUser['RegStatus'] == 0){
                                    echo "<a href='members.php?action=Activate&userid=". 
                                    $latestUser['UserId'] ."' class='activate btn btn-info'>Activate</a>";
                                }
                                echo "<a href='members.php?action=Edit&userid=". 
                                $latestUser['UserId'] ."' class='btn btn-success'>Edit</a>";
                                echo "</div></li>";
                            }   
                        ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of latest members -->
                <!-- start of latest items -->
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tags"></i>
                            Latest <?echo $Latest?> Inserted Items
                        </div>
                        <div class="panel-body">
                            <ul class='list-group latest-item'>
                        <?php 
                            foreach ($latestItems as $latestItem) {
                                echo "<li class='list-group-item'>".$latestItem['Name'];
                                echo "<div>";
                                if ($latestItem['Approve'] == 0){
                                    echo "<a href='items.php?action=Approve&itemid=". 
                                    $latestItem['Item_ID'] ."' class='activate btn btn-info'>Approve</a>";
                                }
                                echo "<a href='items.php?action=Edit&itemid=". 
                                $latestItem['Item_ID'] ."' class='btn btn-success'>Edit</a>";
                                echo "</div></li>";
                            }   
                        ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of the latest items -->
                
                <!-- start of latest comments -->
                <div class="col-sm-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <i class="fa fa-tags"></i>
                            Latest <?echo $Latest?> Comments
                        </div>
                        <div class="panel-body">
                            <ul class='list-group latest-item'>
                        <?php 
                            foreach ($latestComments as $latestComment) {
                                echo "<div class='latestComments'>";
                                echo "<li class='list-group-item'><div class='test_3'>".$latestComment['username']."</div><div class='com'>".$latestComment['comment']."</div>";
                                echo "<div>";
                                if ($latestComment['status'] == 0){
                                    echo "<a href='comments.php?action=Approve&comid=". 
                                    $latestComment['c_id'] ."' class='activate btn btn-info'>Approve</a>";
                                }
                                echo "<a href='comments.php?action=Edit&comid=". 
                                $latestComment['c_id'] ."' class='btn btn-success'>Edit</a>";
                                echo "</div></li>";
                                echo "</div>";
                            }   
                        ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- end of the latest comments -->
            </div>
        </div>
    </div>


    <!-- end -->
    <?php
    
    include $tpl . "footer.php";
} else {
    header("Location: index.php"); 
    exit();   
}