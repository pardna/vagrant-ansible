<?php

class booking_diary {


// Mysqli connection
function __construct($link) {
    $this->link = $link;	
}

/*
Settings you can change:

$booking_start_time:                The time of the first slot
$booking_end_time:                  The time of the last slot 
$booking_frequency:                 The amount of slots per hour, expressed in minutes 
$booking_slots_per_day:             The total number of slots avaliable in one day
*/

public $booking_start_time          = "09:30";  
public $booking_end_time            = "19:00";   
public $booking_frequency           = 30;     
public $booking_slots_per_day       = 20;

public $day, $month, $year, $selected_date, $first_day, $back, $back_month, $back_year, $forward, $forward_month, $forward_year, $bookings, $count, $days;


/*========================================================================================================================================================*/


function make_calendar($selected_date, $first_day, $back, $forward, $day, $month, $year) {

    // Add a value to these public variables  
    $this->day = $day;    
    $this->month = $month;
    $this->year = $year;
         
    $this->selected_date = $selected_date;    
    $this->first_day = $first_day;
    
    $this->back = $back;
    $this->back_month = date("m", $back);
    $this->back_year = date("Y", $back); // Minus one month back arrow
    
    $this->forward = $forward;
    $this->forward_month = date("m", $forward);
    $this->forward_year = date("Y", $forward); // Add one month forward arrow    
    
    // Make the booking array
    $this->make_booking_array($year, $month);
    
}


function make_booking_array($year, $month) { 

    $query = "SELECT * FROM bookings WHERE date LIKE '$year-$month%'"; 
    $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link)); 
	
    $this->count = mysqli_num_rows($result); 
    $this->bookings = '';  

    while ($row = mysqli_fetch_array($result)) {

        $this->bookings[] = array(
            "name" => $row['name'], 
            "date" => $row['date'], 
            "start" => $row['start'],
            "comments" => $row['comments']
            ); 
        }

    $this->make_days_array($year, $month);    
            
} // Close function

 
function make_days_array($year, $month) { 
    
    // Create an array of days in the month                 
    $num_days_month = cal_days_in_month(CAL_GREGORIAN, $month, $year); 
        
    // Make array called $day with the correct number of days
    for ($i = 1; $i <= $num_days_month; $i++) {
        $d = mktime(0, 0, 0, $month, $i, $year);
        $this->days[] = array("daynumber" => $i, "dayname" => date("l", $d)); 
    }

    // Add blank elements to start of array if the first day of the month is not a Monday.
    for ($j = 1; $j <= $this->first_day; $j++) {
        array_unshift($this->days, '0'); 
    }

    // Add blank elements to end of array if required.
    $pad_end = 7 - (count($this->days) % 7);

    if ($pad_end < 7) {
        for ($j = 1; $j <= $pad_end; $j++) {
        array_push($this-> days, '|'); }
    } // Close if

    $this->make_table_top();   

} // Close function
   
    
function make_table_top() {

        echo "
        <div class='left'>
    
        <table border='0' cellpadding='0' cellspacing='0' id='calendar'>
            <tr id='week'>
            <td align='left'><a href='?month=" . date("m", $this->back) . "&amp;year=" . date("Y", $this->back) . "'>&laquo;</a></td>
            <td colspan='5' id='center_date'>" . date("F, Y", $this->selected_date) . "</td>    
            <td align='right'><a href='?month=" . date("m", $this->forward) . "&amp;year=" . date("Y", $this->forward) . "'>&raquo;</a></td>
        </tr>
        <tr>
            <th>M</th><th>T</th><th>W</th><th>T</th><th>F</th><th>S</th><th>S</th>";
            
        $this->make_day_boxes($this->days, $this->bookings, $this->month, $this->year);
    
    } // Close function


function make_day_boxes() {

        $i=0;
 
        foreach ($this->days as $row) {

            $tag = ''; 

            if($i % 7 == 0)
                echo "</tr><tr>"; // Use modulus to give us a <tr> after every seven <td> cells

            if(isset($row['daynumber']) && $row['daynumber'] != 0) { // Padded days at the start of the month will have a 0 at the beginning

                echo "<td width='21' valign='top' class='days'>";

                if ($this->count > 0) {
                    
                    $day_count = 0;                    
                    foreach ($this->bookings as $booking_date) {

                        if ($booking_date['date'] == $this->year . '-' . $this->month . '-' . sprintf("%02s", $row['daynumber'])) {
                        $day_count++;           
                        } // Close if
              
                    } // Close foreach
                    
                } // Close if $count

                // Work out which colour day box to show
                                          
                if ($row['dayname'] == 'Sunday')
                    $tag = 2; // It's a Sunday

                if (mktime(0, 0, 0, $this->month, sprintf("%02s", $row['daynumber']) + 1, $this->year) < strtotime("now"))
                    $tag = 4; // Past Day  
                                        
                if ($day_count >= $this->booking_slots_per_day && $tag == '') 
                    $tag = 3;                    
              
                if ($day_count >0 && $tag == '')         
                    $tag = 1;           
                                             
                echo $this->day_switch($tag, $row['daynumber']) . 

                "<span>" . str_replace('|', '&nbsp;', $row['daynumber']) . "</span></td>";

            } else {
                echo "<td width='21' valign='top' class='days'><img src='images/block_null.gif' title='This day is unavailable' alt=''></td>";
            }
            
            $i++;
            
        } // Close foreach
        
    $this->make_key(); 
        
} // Close function


function day_switch($tag, $daynumber) {

        switch ($tag) {
        
        case (1): // Part booked day
            $txt = "<a href='calendar.php?month=" . $this->month . "&amp;year=" .  $this->year . "&amp;day=" . sprintf("%02s", $daynumber) . "'>
                    <img src='images/block_part.gif' title='This day is part booked' border='0' alt=''></a>\r\n";
            break;

        case (2): // Sunday
            $txt = "<img src='images/block_sunday.gif' title='This day is not available' border='0' alt=''>\r\n";
            break;

        case (3): // Fully booked day
            $txt= "<img src='images/block_fully_booked.gif' title='This day is fully booked' border='0' alt=''>\r\n";
            break;

        case (4): // Past day
            $txt = "<img src='images/block_past.gif' title='This day is not available' border='0' alt=''></a>\r\n";
            break;

        case (5): // Block booked out day
            $txt= "<img src='images/block_fully_booked.gif' title='This day is not available' border='0' alt=''>\r\n";
            break;
            
        default: // FREE
            $txt = "<a href='calendar.php?month=" .  $this->month . "&amp;year=" .  $this->year . "&amp;day=" . sprintf("%02s", $daynumber) . "'>
                    <img src='images/block_free.gif' title='This day is free' border='0' alt=''></a>\r\n";
            break;
    
        }
        
        return $txt;
   
    } // Close function


function make_key() {     
    
        // This key is displayed below the calendar to show what the colours represent
        
        echo "</tr></table>
        <table border='0' id='key' cellpadding='2' cellspacing='6'>
            <tr>
                <td id='key_1'>&nbsp;</td>
                <td id='key_2'>&nbsp;</td>
                <td id='key_3'>&nbsp;</td>
                <td id='key_4'>&nbsp;</td>
                <td id='key_5'>&nbsp;</td>
            </tr>
            <tr>
                <td>Fully Booked</td>
                <td>Sunday</td>
                <td>Part Booked</td>
                <td>Available</td>
                <td>Past Date</td>
            </tr>                
        </table></div>"; 
        
        $this->make_booking_slots();  
             
} // Close function

        
function make_booking_slots() {

    /*
    Variable $day has a default value of 0.  If a day has been clicked on, display it.
    If there is no date selected, show a msg.  Otherwise show the booking form.
    */  
        
    if($this->day == 0) { 
        $this->select_day();   
    }  else {       
        $this->make_form();
    }  
    
    } // Close function  


function select_day() {
    echo "<form method='post' action=''><div id='selected_date'>Please select a day</div>";    
}

function make_form() {
    
    // Create array of the booking times
    for($i = strtotime($this->booking_start_time); $i<= strtotime($this->booking_end_time); $i = $i + $this->booking_frequency * 60) {
        $slots[] = date("H:i:s", $i);  
    }
   
    echo "\r\n\r\n<form method='post' action=''><div id='selected_date'>Selected Date is: " . date("d F Y", mktime(0, 0, 0, $this->month, $this->day)) ."</div>";  
        
    $opt = "<select id='select' name='booking_time'><option value='selectvalue'>Please select a booking time</option>";
    
        if($this->count >= 1) { 
          
            foreach($this->bookings as $row) {  
              
            // Check for bookings and remove any previously booked slots                 
                
            foreach($slots as $i => $r) { 
                    if($row['start'] == $r && $row['date'] == $this->year . '-' . $this->month . '-' . $this->day) {  
                    unset($slots[$i]); 
                    }          
                  
                    } // Close foreach
                                  
            } // Close foreach 
                       
        } // If count bookings                   

        
        // Make select box from $slots array
                       
        foreach($slots as $booking_time) {
            $finish_time = strtotime($booking_time)+ $this->booking_frequency * 60; // Calculate finish time
            $opt .= "<option value='" . $booking_time . "'>" . $booking_time . " - " . date("H:i:s", $finish_time) . "</option>";
        } 
    
        echo $opt. "</select>";          

        
        echo "
        <table width='300' border='0' id='booking'>
        <tr>
            <td class='details'>Name</td>
            <td><input class='input' type='text' name='name' size='32'"; if(isset($_POST['name'])) echo " value='" . $_POST['name'] . "'"; echo "></td>
        </tr>
        <tr>
            <td class='details'>Email</td>
            <td><input class='input' type='text' name='email' size='32'"; if(isset($_POST['email'])) echo " value='" . $_POST['email'] . "'"; echo "></td>
        </tr>
        <tr>
            <td class='details'>Telephone</td>
            <td><input class='input' type='text' name='phone' size='32'"; if(isset($_POST['phone'])) echo " value='" . $_POST['phone'] . "'"; echo "></td>
        </tr>
        <tr>
            <td class='details'>Comments</td>
            <td><textarea rows='3' cols='30' name='comments' onclick='make_blank();'>";
        if(isset($_POST['comments'])) echo $_POST['comments'];
        echo "</textarea></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>
	       <input type='submit' value='' id='book'></td>
        </tr>
        </table></form>";
}


function after_post($month, $day, $year) {

    $alert=''; $msg=0;
    
    if(isset($_POST['name']) && empty($_POST['name'])) { $msg = 1; $alert .= "Please fill in the name box<br>"; } 
    if(isset($_POST['email']) && empty($_POST['email'])) { $msg = 1; $alert .= "Please fill in the email box<br>"; }    
    if(isset($_POST['phone']) && empty($_POST['phone'])) { $msg = 1; $alert .= "Please add a contact number<br>"; }     
    if(isset($_POST['booking_time']) && $_POST['booking_time'] == 'selectvalue') { $msg = 1; $alert .= "Please select a booking time"; }
    
    if($msg == 1) {
    echo "<div class='error'>" . $alert . "</div>";    
    
    } elseif($msg == 0) {

            $booking_date = date("Y-m-d", mktime(0, 0, 0, $month, $day, $year));
            $booking_time = $_POST['booking_time'];
            
            $query = "INSERT INTO bookings (date, start, name, email, phone, comments) VALUES ('$booking_date', '$booking_time',  '$_POST[name]', '$_POST[email]', '$_POST[phone]', '$_POST[comments]')";
            $result = mysqli_query($this->link, $query) or die(mysqli_error($this->link));      
       
            $this->confirm();  
                  
        } // Close else
   } // Close function  


    function confirm() {    
        echo "<div class='success'>Thank you for your booking.</div>";        
    } // Close function  
                 
} // Close Class

?>