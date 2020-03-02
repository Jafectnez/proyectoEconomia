<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Foodlanding - Bootstrap Landing Template</title>
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta content="" name="keywords">
  <meta content="" name="description">

  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,800|Libre+Baskerville:400italic" rel="stylesheet">

  <!-- Bootstrap CSS File -->
  <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet">

  <!-- Libraries CSS Files -->
  <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet">
  <link href="lib/catslider/catslider.css" rel="stylesheet">

  <!-- Main Stylesheet File -->
  <link href="css/styleLanding.css" rel="stylesheet">

  <!-- =======================================================
    Template Name: FoodLanding
    Template URL: https://templatemag.com/foodlanding-bootstrap-landing-template/
    Author: TemplateMag.com
    License: https://templatemag.com/license/
  ======================================================= -->
</head>

<body data-spy="scroll" data-offset="58" data-target="#navigation">

  <div id="navigation" class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><img src="img/logo.png" width="30"></a>
      </div>
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="active"><a href="#h" class="smoothscroll">Home</a></li>
          <li><a href="#chefs" class="smoothscroll">Chefs</a></li>
          <li><a href="#dishes" class="smoothscroll">Dishes</a></li>
          <li><a href="#contact" class="smoothscroll">Contact</a></li>
        </ul>
      </div>
      <!--/.nav-collapse -->
    </div>
  </div>

  <!-- ********** HEADER SECTION ********** -->
  <div id="h">
    <div class="container">
      <div class="row">
        <div class="col-md-6 col-md-offset-3 welcome">
          <h1>A LANDING PAGE<br/>FOR YOUR RESTAURANT</h1>
          <hr class="aligncenter">
          <p>Enjoy the experience of our restaurant.<br/>Register to know our latest news.</p>
          <div class="col-md-10 col-md-offset-1 centered mt">
            <form role="form" action="register.php" method="post" enctype="plain">
              <input type="email" name="email" class="subscribe-input" placeholder="Enter your e-mail address..." required>
              <button class='btn btn-lg btn-orange subscribe-submit' type="submit">Subscribe</button>
            </form>
          </div>
        </div>
      </div>
      <!--/row -->
    </div>
    <!-- /.container -->
  </div>
  <!--/ H -->

  <!-- ********** WHITE SECTION ********** -->
  <div class="container">
    <div class="row mtb centered">
      <h3>HANDSOME LANDING. AWESOME EXPERIENCES.</h3>
      <hr class="aligncenter mb">

      <div class="col-md-3">
        <i class="ion-ios-wineglass og"></i>
        <h5>TRULY TASTEFUL</h5>
        <p class="text-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
      </div>
      <!--/col-md-3 -->
      <div class="col-md-3">
        <i class="ion-ios-location og"></i>
        <h5>8 CITIES</h5>
        <p class="text-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
      </div>
      <!--/col-md-3 -->
      <div class="col-md-3">
        <i class="ion-ios-calculator og"></i>
        <h5>AFFORDABLE PRICES</h5>
        <p class="text-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
      </div>
      <!--/col-md-3 -->
      <div class="col-md-3">
        <i class="ion-ios-cart og"></i>
        <h5>WE DELIVER</h5>
        <p class="text-p">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt.</p>
      </div>
      <!--/col-md-3 -->

    </div>
    <!--/row -->
  </div>
  <!--/container -->


  <!-- ********** PARALLAX SEPARATOR SECTION ********** -->
  <div class="sep s01" data-stellar-background-ratio="0.5"></div>


  <!-- ********** MEET THE CHEFS SECTION ********** -->
  <div class="container" id="chefs">
    <div class="row mtb">
      <h3 class="centered">MEET OUR AWESOME CHEFS</h3>
      <hr class="aligncenter mb">
      <div class="col-md-2 col-sm-2">
        <div class="tabbable">
          <ul class="nav nav-stacked" id="myTab">
            <li class="active"><a href="#tab1">David Harrison</a></li>
            <li class=""><a href="#tab2">Patrick Dawson</a></li>
          </ul>
          <!-- /tabs -->
        </div>
        <!-- /tabbable -->
      </div>
      <!--/col-md-2 -->

      <div class="col-md-10 col-sm-10">
        <div class="tab-content">

          <div class="tab-pane active" id="tab1">
            <!-- FIRST PROFILE -->
            <div class="row">
              <div class="col-md-8 col-sm-8">
                <h4><b>David Harrison</b></h4>
                <h6>TOP CHEF</h6>
                <p class="rating">
                  <i class="ion-ios7-star"></i>
                  <i class="ion-ios7-star"></i>
                  <i class="ion-ios7-star"></i>
                  <i class="ion-ios7-star"></i>
                  <i class="ion-ios7-star"></i>
                </p>
                <br>
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
              </div>
              <div class="col-md-4 col-sm-4">
                <img class="img-responsive aligncenter" src="img/chef01.png" alt="">
              </div>
            </div>
            <!-- /row -->
          </div>
          <!-- /tab-pane -->

          <div class="tab-pane" id="tab2">
            <!-- FIRST PROFILE -->
            <div class="row">
              <div class="col-md-8 col-sm-8">
                <h4><b>Patrick Dawson</b></h4>
                <h6>MASTER CHEF</h6>
                <p class="rating">
                  <i class="ion-ios7-star"></i>
                  <i class="ion-ios7-star"></i>
                  <i class="ion-ios7-star"></i>
                  <i class="ion-ios7-star"></i>
                  <i class="ion-ios7-star-half"></i>
                </p>
                <br>
                <p>Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                <p>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</p>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
              </div>
              <div class="col-md-4 col-sm-4">
                <img class="img-responsive aligncenter" src="img/chef02.png" alt="">
              </div>
            </div>
            <!-- /row -->
          </div>
          <!-- /tab-pane -->

        </div>
        <!--/tab-content -->
      </div>
      <!--/col-md-10 -->
    </div>
    <!--/row -->
  </div>
  <!--/container -->


  <!-- ********** PARALLAX SEPARATOR SECTION ********** -->
  <div class="sep s02" data-stellar-background-ratio="0.5"></div>

  <!-- ********** MENU SLIDER SECTION ********** -->
  <div class="container" id="dishes">
    <div class="row mtb">
      <h3 class="centered">EXCLUSIVE MENU</h3>
      <hr class="aligncenter mb">
      <p class="text-p centered">Enjoy our meals. You can click each menu to display more information.</p>
      <div class="col-md-10 col-md-offset-1">
        <div id="mi-slider" class="mi-slider">
          <ul>
            <li><a data-toggle="modal" data-target="#myModal"><img src="img/food/1.png" alt="img01"><h4>Salmon</h4></a></li>
            <li><a data-toggle="modal" data-target="#myModal"><img src="img/food/2.png" alt="img02"><h4>Seafood Platter</h4></a></li>
            <li><a data-toggle="modal" data-target="#myModal"><img src="img/food/3.png" alt="img03"><h4>Crawfish</h4></a></li>
            <li><a data-toggle="modal" data-target="#myModal"><img src="img/food/4.png" alt="img04"><h4>Bouillabaisse</h4></a></li>
          </ul>
          <ul>
            <li><a data-toggle="modal" data-target="#myModal"><img src="img/food/5.png" alt="img05"><h4>Steak</h4></a></li>
            <li><a data-toggle="modal" data-target="#myModal"><img src="img/food/6.png" alt="img06"><h4>Ribs &amp; Bread</h4></a></li>
            <li><a data-toggle="modal" data-target="#myModal"><img src="img/food/7.png" alt="img07"><h4>Pork</h4></a></li>
            <li><a data-toggle="modal" data-target="#myModal"><img src="img/food/8.png" alt="img08"><h4>Lasagna</h4></a></li>
          </ul>
          <ul>
            <li><a data-toggle="modal" data-target="#myModal"><img src="img/food/9.png" alt="img09"><h4>Banana Pudding</h4></a></li>
            <li><a data-toggle="modal" data-target="#myModal"><img src="img/food/10.png" alt="img10"><h4>Black Bottom Pie</h4></a></li>
            <li><a data-toggle="modal" data-target="#myModal"><img src="img/food/11.png" alt="img11"><h4>Black Forest</h4></a></li>
          </ul>
          <nav>
            <a href="#">SeaFood</a>
            <a href="#">Meat</a>
            <a href="#">Desserts</a>
          </nav>
        </div>
      </div>
      <!--/col-md-10 -->
    </div>
    <!--/row -->
  </div>
  <!--/container -->

  <!-- MODAL DEMO FOR A MENU ITEM -->
  <!-- Modal -->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
          <h5 class="modal-title" id="myModalLabel">Skillet Poached Eggs</h5>
        </div>
        <div class="modal-body">
          <img class="img-responsive aligncenter" src="img/modalbg.jpg" alt="">
          <h4>Poached Eggs</h4>
          <p class="text-p">It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout.</p>
          <p class="text-p">The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English.</p>
          <h5>Price..........$29</h5>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
    <!--/modal-dialog -->
  </div>
  <!-- /modal -->


  <!-- ********** PARALLAX SEPARATOR SECTION ********** -->
  <div class="sep s03" data-stellar-background-ratio="0.5"></div>


  <!-- ********** PHONE USER SECTION ********** -->
  <div class="container">
    <div class="row mt">
      <div class="col-md-7 col-md-offset-1">
        <h3>RESERVE VIA YOUR PHONE</h3>
        <hr class="mb">
        <p class="text-p">Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.</p>
        <p class="text-p">Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the
          undoubtable source.</p>
        <p class="text-p">Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC.</p>
        <p class="down">
          <i class="ion-social-apple"></i>
          <i class="ion-social-android"></i>
        </p>
      </div>

      <div class="col-md-3">
        <img src="img/phone.png" class="aligncenter" width="240" alt="">
      </div>

    </div>
    <!--/row -->
  </div>
  <!--/container -->

  <!-- ********** FOOTER SECTION ********** -->
  <div id="f">
    <div class="container">
      <div class="row">
        <h3 class="centered">SUBSCRIBE TO OUR NEWSLETTER</h3>
        <hr class="aligncenter mb">
        <p class="text-p centered">We will let you know all our awesome offers.</p>
        <div class="col-md-6 col-md-offset-3 centered">
          <form role="form" action="register.php" method="post" enctype="plain">
            <input type="email" name="email" class="subscribe-input" placeholder="Enter your e-mail address..." required>
            <button class='btn btn-lg btn-orange subscribe-submit' type="submit">Subscribe</button>
          </form>
        </div>
      </div>
      <!--/row -->
    </div>
    <!--/container -->
  </div>

  <div id="contact">
    <div class="container">
      <div class="col-md-8 col-md-offset-2">
        <h3 class="centered">CONTACT US</h3>
        <hr class="aligncenter mb">

        <form class="contact-form php-mail-form" role="form" action="contactform/contactform.php" method="POST">

          <div class="form-group">
            <label for="contact-name">Your Name</label>
            <input type="name" name="name" class="form-control" id="contact-name" placeholder="Your Name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" >
            <div class="validate"></div>
          </div>
          <div class="form-group">
            <label for="contact-email">Your Email</label>
            <input type="email" name="email" class="form-control" id="contact-email" placeholder="Your Email" data-rule="email" data-msg="Please enter a valid email">
            <div class="validate"></div>
          </div>
          <div class="form-group">
            <label for="contact-subject">Subject</label>
            <input type="text" name="subject" class="form-control" id="contact-subject" placeholder="Subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject">
            <div class="validate"></div>
          </div>

          <div class="form-group">
            <label for="contact-message">Your Message</label>
            <textarea class="form-control" name="message" id="contact-message" placeholder="Your Message" rows="5" data-rule="required" data-msg="Please write something for us"></textarea>
            <div class="validate"></div>
          </div>

          <div class="loading"></div>
          <div class="error-message"></div>
          <div class="sent-message">Your message has been sent. Thank you!</div>

          <div class="form-send">
            <button type="submit" class="btn btn-large">Send Message</button>
          </div>

        </form>
      </div>
    </div>
  </div>

  <div id="copyrights">
    <div class="container">
      <p>
        &copy; Copyrights <strong>FoodLanding</strong>. All Rights Reserved
      </p>
      <div class="credits">
        <!--
          You are NOT allowed to delete the credit link to TemplateMag with free version.
          You can delete the credit link only if you bought the pro version.
          Buy the pro version with working PHP/AJAX contact form: https://templatemag.com/foodlanding-bootstrap-landing-template/
          Licensing information: https://templatemag.com/license/
        -->
        Created with FoodLanding template by <a href="https://templatemag.com/">TemplateMag</a>
      </div>
    </div>
  </div>

  <!-- JavaScript Libraries -->
  <script src="lib/jquery/jquery.min.js"></script>
  <script src="lib/bootstrap/js/bootstrap.min.js"></script>
  <script src="lib/php-mail-form/validate.js"></script>
  <script src="lib/easing/easing.min.js"></script>
  <script src="lib/modernizr/modernizr.custom.js"></script>
  <script src="lib/stellar/stellar.min.js"></script>
  <script src="lib/catslider/catslider.js"></script>

  <!-- Template Main Javascript File -->
  <script src="js/landing.js"></script>

</body>
</html>
