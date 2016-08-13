<!DOCTYPE html>
<html lang="en" ng-app="explainerApp">
<head>

<!-- Basic Page Needs
------------------------------------------------------------------------->
  <meta charset="utf-8">
  <title>About Us</title>
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
<body id="about">

<!-- Primary Page Layout
------------------------------------------------------------------------->


<!--- Header
------------------------------------------------------------------------->
      <?php include 'inc/_header.php';?>    
    
 
<!--- Container
------------------------------------------------------------------------->
    <div class="container">



<!-- The Team
------------------------------------------------------------------------->
<div class="row title">
	<div class="twelve columns">
	    	<h1>The team</h1>
    </div>
</div>        
        

<div class="row team">
	<div class="four columns team_member">
    	<span class="icon"></span>
    	<h3>Paul Henriques</h3> 
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam pellentesque felis tincidunt lorem scelerisque efficitur. Maecenas aliquam vehicula leo eget iaculis. 
    </div>
	<div class="four columns team_member">
    	<span class="icon"></span>
    	<h3>Pa-Essa Jabang</h3>
        Cras sodales nunc nec varius varius. Phasellus ut blandit nisl, at ultricies enim. Integer laoreet tempus tempor. Morbi et accumsan felis.     </div>
	<div class="four columns team_member">
    	<span class="icon"></span>
    	<h3>Hyane Moussassa</h3>
        Fusce et convallis risus. Vivamus vel tempor est. In euismod dignissim ullamcorper. Interdum et malesuada fames ac ante ipsum primis in faucibus.
    </div>
</div>

       
<!-- Company
------------------------------------------------------------------------->
<div class="row title">
	<div class="twelve columns">
	    	<h1>The company</h1>
    </div>
</div>          


        
<div class="row company">
	<div class="eight columns company_panel ">
        Everybody needs help, in some way or other. Our vision is born from the desire to enable everybody
        We wanted to build the best platform possible to enable you 
        
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam pellentesque felis tincidunt lorem scelerisque efficitur. Maecenas aliquam vehicula leo eget iaculis. 
    </div>
	<div class="four columns company_panel ">
    Pardna Ltd<br /> 7288 Pardna House<br /> PO Box 6945<br /> London<br /> W1A 6US
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
