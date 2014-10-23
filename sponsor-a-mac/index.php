<?php
date_default_timezone_set('Europe/London');

$goal = 3600;
$amount = 120;

// Defined donations here.
$donations = array(
	array(
		'amount' => 120,
		'name' => 'unstyled',
		'url' => 'http://unstyled.com/',
		'img' => 'unstyled_logo.png',
	),
	array(
		'amount' => 150,
		'name' => 'The Higgs Design Co',
		'url' => 'http://higgsdesign.com/',
		'img' => 'higgs_design_co_logo.png',
	),
);

$total = 0;
foreach($donations as $donor) {
	$total += $donor['amount'];
}

$progress_percent = ($total / $goal) *100;

?>

<!DOCTYPE html>
<html lang="">
<head>
    <meta charset="utf-8">
    <title>Sponsor a Mac at The International School, Birmingham</title>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Fund-raising campaign to give students at The International School industry standard software and the necessary resources to embrace creativity.">
    <meta name="author" content="Simon Jobling">

    <!-- Le styles -->
    <link href="docs/assets/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
      section {
      	padding: 30px 0;
      }
      h2 {
      	font-weight: normal;
      	margin-bottom: 0.7em;
      }
      
      .hero-unit {
      	background: url(img/imac_27.jpg) no-repeat 100% 50%;
      	padding: 30px 500px 10px 0;
      }
      
      .thumbnail {
        height: 150px;
        text-align: center;
      }
      .thumbnail h5 {
        margin: 4px 0;
      }
      
      @media (max-width: 480px) {

        .hero-unit {
        	background: none;
        	padding-right: 0;
        }


      }
      
    </style>
    <link href="docs/assets/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

  <script type="text/javascript">
  
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-91583-43']);
    _gaq.push(['_trackPageview']);
  
    (function() {
      var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
      ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
      var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
  
  </script>

</head>
<body>

  <!-- Navbar
    ================================================== -->
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">Sponsor a Mac</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active">
                <a href="#">Overview</a>
              </li>
              <li class="">
                <a href="#problem">The Problem</a>
              </li>
              <li class="">
                <a href="#contribute">We Need You</a>
              </li>
              <li class="">
                <a href="#registration">Registration</a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
	

    <div class="container">

      <!-- Main hero unit for a primary marketing message or call to action -->
      <div class="hero-unit">
			
				<h1>Sponsor a Mac</h1>
				<p>Give children the opportunity of using industry-standard software to become the creative minds of the future.</h2>

        <p><a href="#registration" class="btn btn-primary btn-large">Contribute &pound;<?php echo $amount; ?> towards the campaign and watch your educational investment
grow</a></p>
      </div>

      <div class="row">

        <div class="span8">
        	
        	<section id="problem">
	          <h2>The Problem</h2>
	          <p>In spite of its name, The International School is a normal state comprehensive and not a private school for students from other countries. It was formed by merging two secondary schools which were in challenging circumstances. Like most state schools funding is often a difficult issue. The International School has had a great number of trials in making progress. However, the news is good; achievement is up, expectations are high, and the school has outstanding capacity to improve still further.</p>

						<p>Through the Labour government’s ‘Building Schools for the Future’ programme, the school has had a revamp of it's buildings. However, significant decreases in funding have further disadvantaged the children of the local area, which itself is in the top 5% of areas of socio-economic depravation in the country, by impeding the range of technology and software available to its pupils.</p>

						<p>The International School is ready to take its pupils to the next level; with your help and financial support, this can be achieved incredibly quickly, by broadening the horizons of the pupils and offering them an insight into professions that they would otherwise not be able to access.</p>

						<p>Without investing in future- and cutting-edge technologies, pupils will find it even harder to demonstrate the talents that they hold in the field of creative design. By investing in The International School, you will not only drive change in the local area, but support and encourage the progress of the next generation.</p>
	          
					</section>

          <?php /*
					<section id="solution">
	          <h2>The Solution</h2>
	          <ul>
	          	<li>Local agencies subsidise cost of industry-standard software (<a href="http://www.adobe.com/products/creativesuite/mastercollection.html" rel="nofollow">Adobe CS5.5</a> including Photoshop, Illustrator, InDesign, Fireworks etc).</li>
							<li>With bulk educational license, each Mac license costs approximately <strong>&pound;120</strong></li>
							<li>In return, they're investing in local talent, potential apprenticeships in the future.</li>
							<li>Possible in-house training opportunities at schools</li>
							<li>Platform for agencies to promote new tools with targeted audience (games and social networks)</li>
							<li>Build relationships with local schools/colleges</li>
							<li>Raise profile in a moral way, charitable contributions/donations.</li>
						</ul>
					</section>

					<section id="danny">
						<h2>Meet Danny</h2>
						<img src="http://4.bp.blogspot.com/_XAePxwGya7E/SfBA4mMngkI/AAAAAAAACWc/ScYx-IRB9qo/s400/ChrisEvansShortHairstyles1.jpg" width="240" class="pull-right" />
						<p>Case study of existing talent who would really benefit from the software, someone who has bought his own graphics tablet from his own pocket money to develop his illustration skills.</p>
						<blockquote>
						  <p>If I had access to Illustrator at school, I would be able to improve my skills with my teachers who know how to harness my ability.</p>
						  <small>Danny</small>
						</blockquote>
						
						<small>(For data protection, Danny is a fake name for a real student at International School.)</small>
					</section>
					*/ ?>
					
					<section id="contribute">
						<h2>We Need You</h2>
						<p>The International School are looking for agencies in and around the West Midlands to "Sponsor a Mac"
with the appropriate software to train students. What better way to support your local community than
to invest in the future of the next generation?</p>

            <p>If you're interested in helping children develop their ICT skills and are able to invest in the technological future of tomorrow’s graduates, please contact <strong>Mick Clune</strong> at International School by calling <strong>0121 566 6400</strong> (Mon–Fri 8am–4pm) or <a href="mailto:michael.clune@internationalsch.org.uk">email</a> for further details.</p>
            <p>Every donor will have the option to be included in our ‘Wall of Sponsors’, so that the local community are fully aware of the support you can offer to their young people.</p>

						<div class="alert alert-info">
							<p>We aim to raise just <strong>&pound;<?php echo number_format($goal); ?></strong> by <strong>Friday 27th July 2012</strong> so that our new Mac suite of 30 iMacs can be prepared for the new academic year (2012–13).</p>
							<p>By contributing just &pound;<?php echo $amount; ?>, you are investing in the future of our creative generation.</p>

							<?php 
							$days_left = strtotime('27 July 2012') - time();
							if ($days_left < 0) { 
                $days_left = 0; 
						  } else {
						    $days_left = floor($days_left/60/60/24);
						  }
							if($total>0) : 
							?>
							<p>So far, <strong>we've raised &pound;<?php echo number_format($total); ?></strong> with <strong><?php echo $days_left; ?> days to go</strong>!</p>
							<div class="progress progress-striped active">
                <div class="bar"
                     style="width: <?php echo $progress_percent;?>%;"></div>
              </div>
							<?php endif; ?>

							<p>Help us make this target a reality by <a href="#registration">registering below</a>.</p>
						</div>
												
					</section>
					
					<section id="registration">
            
            <h2>Registration</h2>
            
            <p>Register to join the campaign below. Please specify your interest level, from <var>Donor</var> to <var>Educator</var> or just <var>Progress</var>.</p>

            <form action="http://unstyled.createsend.com/t/r/s/ydqljj/" method="post" id="subForm">

              <label for="name">Full Name</label>
              <input type="text" name="cm-name" id="name" size="25" required />

              <label for="Company">Company</label>
              <input type="text" name="cm-f-fehuh" id="Company" size="25" />

              <label for="ydqljj-ydqljj">Email Address</label>
              <input type="text" name="cm-ydqljj-ydqljj" id="ydqljj-ydqljj" size="25" required />

              <label for="Telephone">Telephone</label>
              <input type="text" name="cm-f-feklr" id="Telephone" size="25" />
              
              <label for="Type">Interested in&hellip;</label>
              <select name="cm-fo-fgujk">
                <option value="2914019">Progress</option>
                <option value="2942480">Training/Workshops</option>
                <option value="2942764">Supplying material (eg. books)</option>
                <option value="2914018">Donating (please specify below)</option>
							</select>

              <label for="Donated">How much are you willing to donate?</label>
              <input type="number" name="cm-f-feujr" id="Donated" />

              <label for="Contribute">If there is anything else you're willing to contribute (workshops, books etc), let us know.</label>
              <textarea name="cm-f-fittth" id="Contribute"></textarea>

              <div class="form-actions">
                <input type="submit" class="btn" value="Register" />
              </div>

            </form>
					</section>

        </div>
        
        <div class="span4">
        
        	<section id="about">

						<h2>About the Campaign</h2>
						
						<img src="img/the_international_school.png" alt="The International School" />
						
						<p>This is a collaboration between <a href="http://isccbonline.net/">International School Birmingham</a> and <a href="http://twitter.com/Si">Simon Jobling</a> – an enthusiastic professional who has worked in the creative industry for over 15 years. Having grown up with computers in a creative mindset, he appreciates the value in harnessing young talent with the appropriate equipment. </p>
						
						<p>When Simon heard about the situation from his wife (Head of Art and Design at the school), he wanted to call on his professional network to help the school, knowing full well there are other enthusiastic creative professionals in the area interested in engaging with young local talent.</p>
						
						<p>View <a href="http://issuu.com/the_international_school/docs/school_prospectus_-_low_res?mode=window&viewMode=singlePage">The International School's new prospectus</a> for more details on the recent redevelopment.</p>

        	</section>

					<section id="wall">
					
						<h2>Wall of Sponsors</h2>
					
						<ul class="thumbnails">
						<?php 
						// Donations
						foreach($donations as $donator) : 
						?>
						  <li class="span2">
						    <a href="<?php echo $donator['url']; ?>" class="thumbnail">
						      <img src="img/sponsors/<?php echo $donator['img']; ?>" alt="<?php echo $donator['name']; ?>">
						      <h5><?php echo $donator['name']; ?></h5>
						    </a>
						  </li>
						 <?php 
						 endforeach; 
						 ?>
						 <?php 
						 // Placeholders
						 if(count($donations)<6) :
               $x=0;
							 while($x<(6 - count($donations))) {
							 	
							?>						 
						  <li class="span2">
						    <a href="#registration" class="thumbnail">
						      <img src="http://placehold.it/160x120" alt="Placeholder">
						      <h5>Your name here</h5>
						    </a>
						  </li>
						  <?php
						  	 $x++;
						  }
						  endif;
						  ?>
						</ul>
					
					</section>



        </div>

      </div>

      <hr>

      <footer>
        <p>Collaboration between <a href="http://isccbonline.net/">The International School, Birmingham</a> and <a href="http://twitter.com/Si">Si Jobling</a></p>
        <p>Created with <a href="http://twitter.github.com/bootstrap/">Twitter Bootstrap</a> and <a href="http://www.campaignmonitor.com/">Campaign Monitor</a></p>
      </footer>

    </div> <!-- /container -->


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="../assets/js/jquery.js"></script>
    <script src="../assets/js/bootstrap-transition.js"></script>
    <script src="../assets/js/bootstrap-alert.js"></script>
    <script src="../assets/js/bootstrap-modal.js"></script>
    <script src="../assets/js/bootstrap-dropdown.js"></script>
    <script src="../assets/js/bootstrap-scrollspy.js"></script>
    <script src="../assets/js/bootstrap-tab.js"></script>
    <script src="../assets/js/bootstrap-tooltip.js"></script>
    <script src="../assets/js/bootstrap-popover.js"></script>
    <script src="../assets/js/bootstrap-button.js"></script>
    <script src="../assets/js/bootstrap-collapse.js"></script>
    <script src="../assets/js/bootstrap-carousel.js"></script>
    <script src="../assets/js/bootstrap-typeahead.js"></script>

    <!-- Social Media -->
    <div id="fb-root"></div>
    <script>
    // Facebook
    (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "//connect.facebook.net/en_GB/all.js#xfbml=1&appId=164681470277157";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    // Twitter
    !function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");
    // Google+
    (function(){
    	var po = document.createElement('script'); po.type='text/javascript'; po.async=true;
    	po.src = 'https://apis.google.com/js/plusone.js';
    	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po,s);
    })();
    </script>

    <script>
        var GoSquared = {"acct":"GSN-461638-M","VisitorName":"Si"};
        (function(w){
            function gs(){
                w._gstc_lt=+(new Date); var d=document;
                var g = d.createElement("script"); g.type = "text/javascript"; g.async = true; g.src = "//d1l6p2sc9645hc.cloudfront.net/tracker.js";
                var s = d.getElementsByTagName("script")[0]; s.parentNode.insertBefore(g, s);
            }
            w.addEventListener?w.addEventListener("load",gs,false):w.attachEvent("onload",gs);
        })(window);
    </script>
    <script>window.gsevents = window.gsevents || [];</script><script>(function() {function waitForTracker(f) {if (!GoSquared.DefaultTracker) {setTimeout(function() {waitForTracker(f);},500);} else {f();}}waitForTracker(function() {var gsevents = window.gsevents || [];for (var ev in gsevents) {gsevents[ev]();}});})();</script>

</body>
</html>