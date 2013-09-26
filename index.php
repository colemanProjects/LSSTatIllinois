<? xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
     <meta name="keywords" content="IIPImage Ajax Internet Imaging Protocol IIP Zooming Streaming High Resolution Mootools"/>
  <!-- header for lsst.cs.illinois.edu-->
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="-1" />
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="author" content="" />
    <link rel="stylesheet" type="text/css" href="style.css" media="screen" />

  <!-- header for mooviewer-->
  <link rel="stylesheet" type="text/css" media="all" href="iipmooviewer-1.1/css/iip.css" />
  <link rel="shortcut icon" href="iipmooviewer-1.1/images/iip-favicon.png" />
  <title>IIPMooViewer 1.1 :: IIPImage High Resolution Ajax Image Streaming Viewer</title>
  <script type="text/javascript" src="mootools_double_pinned_slider_with_clipped_gutter_image_v2.2/mootools12_all_p.js"></script>
  <script type="text/javascript" src="iipmooviewer-1.1/javascript/mootools-1.2-more-compressed.js"></script>
  <script type="text/javascript" src="iipmooviewer-1.1/javascript/iipmooviewer-1.1.js"></script>

  <script type="text/javascript">

    // The iipsrv server path (/fcgi-bin/iipsrv.fcgi by default)
    var server = '/fcgi-bin/iipsrv.fcgi';

    // The *full* image path on the server. This path does *not* need to be in the web
    // server root directory. On Windows, use Unix style forward slash paths without
    // the "c:" prefix
    // var images = '/srv/www/html/butterfly.tiff';
    var images = '/srv/www/html/uploads/cfImage.tiff';
    // var images = '~/lsst/displayFiles/cfImage.tiff';

    // Copyright or information message
    var credit = '&copy; copyright or information message';

    // Create our viewer object - note: must assign this to the 'iip' variable.
    // See documentation for more details of options
    iip = new IIP( "targetframe", {
    image: images,
    server: server,
    credit: credit,
    zoom: 2,
    render: 'random',
    showNavButtons: true
    });
  </script>





  <title>SaoImage Ds9 Web</title>
  <!--<script src="jquery-ui-1.10.2.custom/js/jquery-1.9.1.js"></script> -->
  <script src="d3/d3.v3.min.js"></script> 
</head>
      <body>
          <div id="wrapper">
  <?php include('includes/header.php'); ?>
  <?php include('includes/nav.php'); ?>
  <div id="content">
    <?php include('includes/menuBar.php'); ?>
    <?php include('includes/dataDisplay.php'); ?>
    <?php include('includes/centralFrame.php');?>
  </div> 
  <?php include('includes/sidebar.php'); ?>
  <?php include('includes/footer.php'); ?>
          </div> <!-- End #wrapper -->
      </body>
</html>
