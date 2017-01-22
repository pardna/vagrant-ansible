<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no"/>
    <meta name="description" content="Save, lend and borrow with your close friends and family. Leverage the power of the collective and reach your financial goals. PARDNA.COM is the online platform to conduct your susu, likelemba or pardna!" />
  <title>PARDNA - Rotating Savings and Credit Online Platform, Coming Soon</title>


<!-- CSS
------------------------------------------------------------------------->
<?php include 'inc/_css.php';?>
    
</head>
<body>
<!--- Header
------------------------------------------------------------------------->
<?php include 'inc/_header.php';?>   

  <div id="index-banner" class="parallax-container">
    <div class="row section no-pad-bot">
      <div class="col l6 s10 offset-l3 offset-s1">
          
          <div class="row">
          <h1>The financial platform for saving, lending and borrowing between close friends and family.</h1>
          </div>
          
          
        <!-- Begin MailChimp Signup Form -->
        <div id="mc_embed_signup">
            <form action="//pardna.us12.list-manage.com/subscribe/post?u=e52e743c61189f9899150fd54&amp;id=16a0669dc3" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                <div id="mc_embed_signup_scroll">

                    <div class="mc-field-group col l8 s12">
                        <input type="email" value="" placeholder="Type Your Email Address" name="EMAIL" class="required email white" id="mce-EMAIL">
                    </div>

                    <div id="mce-responses" class="clear">
                        <div class="response" id="mce-error-response" style="display:none"></div>
                        <div class="response" id="mce-success-response" style="display:none"></div>
                    </div>    

                    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_e52e743c61189f9899150fd54_16a0669dc3" tabindex="-1" value=""></div>
                    <div class="clear submit-button-container"><input type="submit" value="Get Early Access!" name="subscribe" id="mc-embedded-subscribe" class="btn-large teal col l4 s12"></div>

                </div>
        </form>
        </div>
        <!--End mc_embed_signup-->  

      </div>
    </div>
    <div class="parallax banner__image">
        <img class="banner__image--large" src="img/banner-image-large.jpg" alt="Pardna Friends and Family" /> 
    </div>
  </div>

    <div class="row white promoboxes">
  <div class="container">
    <div class="section">

      <!--   Icon Section   -->
      <div class="row">
        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center group"><i class="icon"></i></h2>
            <h5 class="center">The Trust Network</h5>

              <p class="light">Pardna with your most trusted social group. Close friends and family are your social network of trust</p></div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center secure"><i class="icon"></i></h2>
            <h5 class="center">Secure and Safe</h5>

            <p class="light">We provide a secure platform for your Pardna. Two factor authentication, data encryption and secure SSL are just the start</p>
          </div>
        </div>

        <div class="col s12 m4">
          <div class="icon-block">
            <h2 class="center rates"><i class="icon"></i></h2>
            <h5 class="center">Low Fees</h5>

            <p class="light">We charge a super low fee on payouts. The longer you wait for your payout, the lower the fee - from as little as zero!</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

    
<div>
<!-- Video
------------------------------------------------------------------------->  
    <div class="row video purple">
        <div id="play-video-button" class="white">Play Video</div>
    <style>.embed-container { position: relative; height: 480px; overflow: hidden; max-width: 100%; } .embed-container iframe, .embed-container object, .embed-container embed { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }</style><div class='embed-container'><iframe src='video/index.html?w=854&h=480&a=0&r=0&c=1' frameborder='0'></iframe></div>
    </div> 
</div>    
    

  <div class="parallax-container valign-wrapper">
    <div class="section no-pad-bot">
      <div class="container">
        <div class="row center">
          <h5 class="header col s12 light">Pardna.com - rotating savings and credit for the digital age</h5>
        </div>
      </div>
    </div>
    <div class="parallax"><img src="img/desktop-image-large.jpg" alt="Pardna.com Desktop"></div>
  </div>


<div class="row white world">
      <div class="container">
        <div class="col l6 s12 world__image">
          <img src="img/world.svg" />

        </div>
          
        <div class="col l6 s12">
          <h4>The World's Trusted Way To Save And Lend Money</h4>
          <p class="left-align light">
              Pardna.com is based on one of the oldest financial systems in history; <strong>rotating savngs and credit</strong>. This system is used the world over and is know by many names, but the basic operation is the same. 

A group of individuals come together to make repeated contributions and withdrawals to and from a common fund. 

Here at Pardna.com you can conduct your group's saving and lending and borrowing on a secure platform that makes the whole process easy! </p>      
          </div>
        </div>
</div>    
    
    

<!-- FOOTER
------------------------------------------------------------------------->
    <?php include 'inc/_footer.php';?>


<!-- JAVASCRIPT
------------------------------------------------------------------------->
    <?php include 'inc/_javascript.php';?>
    <?php include 'inc/_video.php';?>
    <?php include_once("inc/_analyticstracking.php") ?>  
    
    <script>
         var options = [
          {selector: '#play-video-button', offset: 400, callback: function(el) {
              Materialize.fadeInImage($(el));
              $( "#play-video-button" ).fadeIn( 3000, function(){
                  $(this).delay(4000).fadeOut('slow');
              } );
            } }        
          ];
          Materialize.scrollFire(options);
    </script>

  </body>
</html>
