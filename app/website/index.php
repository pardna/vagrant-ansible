<!DOCTYPE html>
<html lang="en" ng-app="explainerApp">
<head>

<!-- Basic Page Needs
------------------------------------------------------------------------->
  <meta charset="utf-8">
  <title>Pardna.com</title>
  <meta name="description" content="Pardna.com. The group saving, lending and borrowing platform">
  <meta name="author" content="Pardna.com">

<!-- Mobile Specific Metas
------------------------------------------------------------------------->
  <meta name="viewport" content="width=device-width, initial-scale=1">
<!-- CSS
------------------------------------------------------------------------->
    <?php include 'inc/_css.php';?>
	<link rel="stylesheet" href="css/angular-carousel.css" type="text/css" />

<!-- Favicon
------------------------------------------------------------------------->
    <link rel="icon" type="image/png" href="img/favicon.png">

</head>
<body id="home">

<!-- Primary Page Layout
------------------------------------------------------------------------->


<!--- Header
------------------------------------------------------------------------->
      <?php include 'inc/_header.php';?>    
    
 
<!--- Container
------------------------------------------------------------------------->
    <div class="container">


<!-- Hero 
------------------------------------------------------------------------->
<div class="hero">
	<div class="hero__strapline six columns">
	    	<h1>The group saving, lending and borrowing platform for close friends and family</h1>
        <h2>Leverage the power of the collective to achieve your financial goals</h2>
    	<a href="../frontend/dist/#/signup" class="button button-primary six columns">Get Started!</a>
    </div>
</div>
        
<!-- Hero - Mobile
------------------------------------------------------------------------->
<div class="hero-mobile">
	<div class="hero__strapline-mobile twelve columns">
	    	<h1>The group saving, lending and borrowing platform for close friends and family</h1>
        <h2>Leverage the power of the collective to achieve your financial goals</h2>
    	<a href="../frontend/dist/#/signup" class="button button-primary">Get Started!</a>
    </div>
</div>        

<!-- Value Proposition
------------------------------------------------------------------------->
<div class="row valueprop">
	<div class="four columns valueprop__group">
    	<span class="icon"></span>
    	<h3>The Trust Network</h3>
        Pardna with your most trusted social group. Close friends and family are your social network of trust 
    </div>
	<div class="four columns valueprop__secure">
    	<span class="icon"></span>
    	<h3>Secure and Safe</h3>
        We provide a secure platform for your Pardna. Two factor authentication, data encryption and secure SSL are just the start
    </div>
	<div class="four columns valueprop__rates">
    	<span class="icon"></span>
    	<h3>Low Fees</h3>
        We charge a super low fee on payouts. The longer you wait for your payout, the lower the fee - from as little as zero!
    </div>
</div>

        
<!-- Video
------------------------------------------------------------------------->        
<div class="row video">
<style>.embed-container { position: relative; height: 480px; overflow: hidden; max-width: 100%; } .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style><div class='embed-container'><iframe src='video/index.html?w=854&h=480&a=0&r=0&c=1' frameborder='0'></iframe></div>
</div>     
      

<!-- Panel
------------------------------------------------------------------------->
<div class="row panel">
    <div class="row twelve columns">
        <h1>The Trusted Way To Save And Lend Money The World Over</h1>
    </div>
    <div class="row">
        <div class="six columns world"></div>
        <div class="six columns">
        Pardna.com is based on one of the oldest financial models in history. This model is used the world over and is know by many names, but  the basic operation is the same. A group of individuals come together to make repeated contributions and withdrawals to and from a common fund. Here at Pardna.com you can conduct your group's saving and lending on a secure platform that makes the whole process easy!    
        
    	<div>
    	<a href="../frontend/dist/#/signup" class="button button-primary">Get Started!</a>
            </div>
    </div>
    </div>
</div>

<!-- Footer
------------------------------------------------------------------------->
<?php include 'inc/_footer.php';?>

<!-- End Container
------------------------------------------------------------------------->
</div>

<!-- Javascript
----------------------------------------------------------------------->
    <?php include 'inc/_video.php';?>
    <?php include_once("inc/_analyticstracking.php") ?>

</body>
</html>
