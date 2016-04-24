<?php

include('php/connect.php'); 
include('classes/class_calendar.php');

$calendar = new booking_diary($link);

if (isset($_GET['month'])) $month = $_GET['month']; else $month = date("m");
if (isset($_GET['year'])) $year = $_GET['year']; else $year = date("Y");
if (isset($_GET['day'])) $day = $_GET['day']; else $day = 0;

$selected_date = mktime(0, 0, 0, $month, 01, $year); // Make a timestamp based on the GET values
$first_day = date("N", $selected_date) - 1; // Gives numeric representation of the day of the week 1 (for Monday) through 7 (for Sunday)
$back = strtotime("-1 month", $selected_date);
$forward = strtotime("+1 month", $selected_date);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Calendar</title>
<link href="style.css" rel="stylesheet" type="text/css">

<script language="javascript" type="text/javascript">
/* Preload book now image */
sub_button = new Image(); 
sub_button.src = "images/book_mo.png";
</script>

</head>
<body>

<?php     
        
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $calendar->after_post($month, $day, $year);  
}   

// Call calendar function
$calendar->make_calendar($selected_date, $first_day, $back, $forward, $day, $month, $year);

?>

</body>
</html>
