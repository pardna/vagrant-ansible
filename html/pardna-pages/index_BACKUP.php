<!DOCTYPE html>
<html lang="en" ng-app="explainerApp">
<head>

<!-- Basic Page Needs
------------------------------------------------------------------------->
  <meta charset="utf-8">
  <title>Pardna.com</title>
  <meta name="description" content="">
  <meta name="author" content="">

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
  <div class="container">
  	
<!--- Header
------------------------------------------------------------------------->
      <?php include 'inc/_header.php';?>

<!-- Hero
------------------------------------------------------------------------->
<div class="hero">
	<div class="hero__strapline eight columns">
	    	<h1>Save up the easy way with close friends and family. Safe, secure and hassle free.</h1>
        <h2>Finance your dreams responsibly with a Pardna!</h2>
    	<a href="register.php" class="button button-primary">Get Started!</a>
    </div>
</div>

<!-- Value Proposition
------------------------------------------------------------------------->
<div class="row valueprop">
	<div class="four columns valueprop__group">
    	<span class="icon"></span>
    	<h3>Group Savings &amp; Loans</h3>
        USe the power of your collective 
        Vivamus nec aliquet dolor. Quisque ut felis magna. Suspendisse convallis vestibulum pellentesque. 
    </div>
	<div class="four columns valueprop__secure">
    	<span class="icon"></span>
    	<h3>Secure & Safe</h3>
        Fusce convallis laoreet volutpat. Maecenas ornare lacus sit amet lacus aliquet, at ultrices dui tempor. 
    </div>
	<div class="four columns valueprop__rates">
    	<span class="icon"></span>
    	<h3>Low Fees</h3>
        Curabitur ex libero, accumsan in urna in, scelerisque sagittis eros. Integer feugiat.
    </div>
</div>

<!-- Carousel
------------------------------------------------------------------------->
    <div class="row carousel">
	<h1>How a Pardna Works</h1>
        <ul rn-carousel  rn-carousel-controls class="image">
          <li>slide #1</li>
          <li>slide #2</li>
          <li>slide #3</li>
          <li>slide #4</li>
          <li>slide #5</li>
          <li>slide #6</li>
        </ul>
    </div>
    
<!-- Panel
------------------------------------------------------------------------->      
<div class="row panel">    
    <div class="row twelve columns">
        <h1>The Trusted Way To Save Money The World Over</h1>
    </div>
    <div class="row">
        <div class="six columns world"></div>
        <div class="six columns">
        Donec pretium pharetra dapibus. Praesent vitae eros mauris. In viverra diam nec sapien mollis efficitur. Etiam accumsan leo ac elit tincidunt rutrum. Ut condimentum est dolor, a volutpat purus ultrices id. Pellentesque eu purus ut ex ornare venenatis eu eu augue. Quisque sed augue non mi pulvinar faucibus in ut purus. Donec molestie viverra hendrerit.
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
    <?php include 'inc/_javascript.php';?>
    <script src="js/angular-carousel.min.js"></script>
    <script>
        angular.module('explainerApp', ['angular-carousel']);
    </script>

</body>
</html>