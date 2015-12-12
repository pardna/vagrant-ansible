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
<body id="dashboard">

<!-- Primary Page Layout
------------------------------------------------------------------------->
  <div class="container">
  	
<!--- Header
------------------------------------------------------------------------->
    <?php include 'inc/_header.php';?>

<!-- Dashboard
------------------------------------------------------------------------->
<div class="row">
    <h1>Dashboard</h1>
</div>
<div class="dashboard">  
        <div class="four columns">
            <div class="dashboard__form">
                <h2>Your Personal Details</h2>
                <input class="button-primary edit" type="submit" value="Edit">
                <form id="dashboard" action="">
                    <label for="firstNameInput">First name</label>
                    <input class="u-full-width" type="text" placeholder="First name" id="firstNameInput" required />
                    <label for="lastNameInput">Last name</label>
                    <input class="u-full-width" type="text" placeholder="Last name" id="lastNameInput" required />
                    <hr />
                <h3>Your Bank Account</h3>
                    <label for="accountInput">Account number</label>
                    <input class="u-full-width" type="text" placeholder="Account number" id="accountInput"  ng-minlength=8  ng-maxlength=8 required />
                    <label for="sortcodeInput">Sort code</label>
                    <input class="u-full-width" type="number" placeholder="Sort code" id="sortcodeInput" required />
                    <hr />                    
                <h3>Your Mobile Number</h3>
                    <label for="mobileInput">Mobile number</label>
                    <input class="u-full-width" type="text" placeholder="Mobile phone number" id="mobileInput" />

                    <hr />  
                    <input class="u-full-width button-primary" type="submit" value="Submit" />
                </form>
            </div>
        </div>
        <div class="four columns">
            <div class="dashboard__new">
                xxx
            </div>
            <div class="dashboard__active">
                xxx
            </div>
            <div class="dashboard__history">
                xxx
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
	<script src="js/hideShowPassword.js"></script>    

</body>
</html>