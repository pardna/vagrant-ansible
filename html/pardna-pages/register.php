<!DOCTYPE html>
<html lang="en">
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

<!-- Favicon
------------------------------------------------------------------------->
    <link rel="icon" type="image/png" href="img/favicon.png">

</head>
<body id="register">

<!-- Primary Page Layout
------------------------------------------------------------------------->
  <div class="container">
  	
<!--- Header
------------------------------------------------------------------------->
    <?php include 'inc/_header.php';?>

<!-- Register Form
------------------------------------------------------------------------->
<div class="form">
	<div class="four columns">
        <h1>Get started immediately</h1>
        <h2>Open your Pardna account in seconds</h2>
        Start saving with friends and family. Open an account and set up your Pardna saving group straight away.
    </div>
	<div class="four columns">
        <form id="register" name="register" ng-app="myApp" ng-controller="mainCtrl" action="dashboard.php">
            <input class="u-full-width" type="email" placeholder="Your email" id="emailInput" required />
            <input class="u-full-width" placeholder="Password" id="passwordInput" type="{{inputType}}"  ng-pattern="[a-zA-Z0-9]" ng-minlength=6 required>
            <input type="checkbox" id="checkbox" ng-model="passwordCheckbox" ng-click="hideShowPassword()">
            <label for="checkbox" ng-if="passwordCheckbox">Hide password</label>
            <label for="checkbox" ng-if="!passwordCheckbox">Show password</label>
            <input class="u-full-width button-primary" type="submit" value="Submit">
        </form>
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
	<script src="js/hideShowPassword.js"></script>    

</body>
</html>