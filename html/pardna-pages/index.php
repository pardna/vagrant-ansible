<!DOCTYPE html>
<html lang="en" ng-app="explainerApp">
<head>

<!-- Basic Page Needs
------------------------------------------------------------------------->
  <meta charset="utf-8">
  <title>Pardna.com - Group Saving And Lending</title>
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

    

<!-- Facebook Open Graph
------------------------------------------------------------------------->    
<meta property="og:title" content="Register for early access" />
<meta property="og:type" content="company" />
<meta property="og:site_name" content="Register for early access" />
<meta property="og:url" content="www.pardnermoney.com/pardna-pages/" />
<meta property="og:image" content="https://launchrock-assets.s3.amazonaws.com/facebook-files/0WUJB8Z9_1374750449395.jpg?_=0" />    
    
</head>
<body id="home">

<!-- Primary Page Layout
------------------------------------------------------------------------->
  <div class="container">
  	
<!--- Header
------------------------------------------------------------------------->
	<div class="row header">
    	<div class="four columns logo"><a href="index.php"></a></div>
    </div>

<!-- Hero
------------------------------------------------------------------------->
<div class="hero">
    <div class="hero_darken">
        <div class="hero__strapline eight columns">
                <h1>Finance your dreams with close friends and family.</h1>
            <h2>Pardna.com - the group saving and lending platform</h2>
            <!-- Begin LaunchRock Widget -->
            <div id="lr-widget" rel="0WUJB8Z9"></div>
            <script type="text/javascript" src="//ignition.launchrock.com/ignition-current.min.js"></script>
            <!-- End LaunchRock Widget -->
        </div>
    </div>    
</div>

<!-- Value Proposition
------------------------------------------------------------------------->
<div class="row valueprop">
	<div class="four columns valueprop__group">
    	<span class="icon"></span>
    	<h3>Group Saving &amp; Lending</h3>
        Achieve your financial goals with the people you love and trust the most.
    </div>
	<div class="four columns valueprop__secure">
    	<span class="icon"></span>
    	<h3>Secure & Safe</h3>
        We provide a secure platform to conduct your group saving and lending activities. 
    </div>
	<div class="four columns valueprop__rates">
    	<span class="icon"></span>
    	<h3>Low Fees</h3>
        Low and transparent fees so you keep more of your money for you.
    </div>
</div>

    
<!-- Panel
------------------------------------------------------------------------->      
<div class="row panel">    
    <div class="row twelve columns">
        <h1>The Trusted Way To Save and Lend Money The World Over</h1>
    </div>
    <div class="row">
        <div class="six columns world"></div>
        <div class="six columns">
            Pardna.com operates the rotating saving and credit method of money management. This method is one of the oldest financial tools in history and is known by many names all over the world: <i>Susu</i> or <i>Likelemba</i> in Western Africa, <i>Committee</i> or <i>Hui</i> in Asia, <i>Pardna</i> in the Caribbean. The basic operation is the same: a group of individuals come together to make repeated contributions and withdrawals to and from a common fund. Pardna.com - the trusted way to save and lend money the world over.
        </div>
    </div>
</div>

<!-- Footer
------------------------------------------------------------------------->
<div class="row footer">
    <div class="six columns footer__social" style="text-align:center">
        <span class="footer__social--twitter"><a href="http://www.twitter.com/PardnaMoney">@PardnaMoney</a></span>
    </div>
    <div class="six columns footer__social" style="text-align:center">
        <span class="footer__social--email"><a href="#">contact@pardna.com</a></span>
    </div>    
</div>

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