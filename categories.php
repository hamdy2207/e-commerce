<?php $title = 'Categories';include "init.php";?>
<div class="container">
    <h1 class='text-center'><? echo str_replace('-',' ',$_GET['pagename']);?></h1>
    <?php
          foreach(getItem('Cat_ID',$_GET['pageid']) as $item){
        ?>
            <div class="col-sm-6 col-md-3">
                <div class="thmbnail item-box">
                    <img src="phone.jpg" class='img-responsive' alt="">
                    <span class="price-tag"><? echo $item['Price']?></span>
                    <h3><a href="items.php?itemId=<? echo $item['Item_ID']; ?>"><? echo $item['Name']?></a></h3>
                    <p><? echo $item['Description']?></p>
                </div>
            </div>    
        <?}?>
</div>
<?php include $tpl . "footer.php";?>