
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>QR Generator</title>

    <!-- Le HTML5 shim, for IE6-8 support of HTML elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le styles -->
    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css">
    <style type="text/css">
      /* Override some defaults */
      html, body {
        background-color: #eee;
      }
      body {
        padding-top: 40px; /* 40px to make the container go all the way to the bottom of the topbar */
      }
      .container > footer p {
        text-align: center; /* center align it with the container */
      }
      .container {
        width: 820px; /* downsize our container to make the content feel a bit tighter and more cohesive. NOTE: this removes two full columns from the grid, meaning you only go to 14 columns and not 16. */
      }

      /* The white background content wrapper */
      .content {
        background-color: #fff;
        padding: 20px;
        margin: 0 -20px; /* negative indent the amount of the padding to maintain the grid system */
        -webkit-border-radius: 0 0 6px 6px;
           -moz-border-radius: 0 0 6px 6px;
                border-radius: 0 0 6px 6px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.15);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.15);
                box-shadow: 0 1px 2px rgba(0,0,0,.15);
      }

      /* Page header tweaks */
      .page-header {
        background-color: #f5f5f5;
        padding: 20px 20px 10px;
        margin: -20px -20px 20px;
      }

      /* Styles you shouldn't keep as they are for displaying this base example only */
      .content .span10,
      .content .span4 {
        min-height: 500px;
      }
      /* Give a quick and non-cross-browser friendly divider */
      .content .span4 {
        margin-left: 0;
        padding-left: 19px;
        border-left: 1px solid #eee;
      }

      .topbar .btn {
        border: 0;
      }
      

    </style>
    <script src="http://www.google.com/jsapi"></script> 
<script type="text/javascript"> 
	// Load jQuery
		google.load("jquery", "1.6.4");
	// Load jQueryUI
		google.load("jqueryui", "1.8.14");
		
</script> 
<script src="/assets/js/jquery.form.js" type="text/javascript"></script> 
<script src="http://twitter.github.com/bootstrap/1.3.0/bootstrap-modal.js" type="text/javascript"></script>
<script src="http://twitter.github.com/bootstrap/1.3.0/bootstrap-alerts.js" type="text/javascript"></script>
<script src="/assets/js/hc/highcharts.js" type="text/javascript"></script>
<script type="text/javascript">
	
	$(function() { 
	    
	    $('#add_new').modal({
  			keyboard: true,
  			backdrop: true
		});
		
		$(".alert-message").alert('close');
	    
	}); 

</script>
  </head>
  

  <body>
  
	<!-- Modal -->
        <div class="modal hide fade" id="add_new">
            <form action="<?= site_url("create/codes/create") ?>" method="post">
          <div class="modal-header">
            <a href="#" class="close">&times;</a>
            <h3>Create a New QR Code</h3>
          </div>
          <div class="modal-body">
	
				<div class="clearfix">
			        <label for="url">URL</label>
			        <div class="input">
			          <div class="input-prepend">
			            <span class="add-on">http://</span>
			            <input class="large" id="url" name="url" size="16" type="text" />
			          </div>
			        </div>
			      </div><!-- /clearfix -->
			      
			      <div class="clearfix">
			        <label for="notes">Notes</label>
			        <div class="input">
			          <textarea class="xlarge" name="notes" id="notes"></textarea>
			        </div>
			      </div><!-- /clearfix -->
			
          </div>
          <div class="modal-footer">
             <input type="submit" class="btn primary" value="Build QR Code" />
          </div>
          </form>
        </div>

    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="<?= site_url("create/dashboard") ?>">QR Code Generator</a>
          <ul class="nav">
            <li><a href="<?= site_url("create/dashboard") ?>">Dashboard</a></li>
            <li><a href="<?= site_url("create/codes") ?>">Codes</a></li>
          </ul>
          <form class="pull-right"><button data-controls-modal="add_new" data-backdrop="true" data-keyboard="true" class="btn">+ Create a New Code</button></form>
        </div>
      </div>
    </div>

    <div class="container">

      <div class="content">
        <div class="page-header">
          <h1><?= $page_title ?></h1>
        </div>
        <div class="row">
          <div class="span14">
          	
          	<?= $view ?>
          	
          </div>
        </div>
      </div>

      <footer>
        <p></p>
      </footer>

    </div> <!-- /container -->


  </body>
</html>
