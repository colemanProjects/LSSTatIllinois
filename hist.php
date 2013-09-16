<!Doctype html>
<html>
  <head>
    <style>
      .axis text {
        font-family: sans-serif;
        font-size: 11px;
      }
    </style>
    <title>"Histogram Page"</title>
    <meta type="utf8"\>
    <link rel="stylesheet" type="text/css" href="hist_style.css">
    <script type="text/javascript" src="mootools_double_pinned_slider_with_clipped_gutter_image_v2.2/mootools12_all_p.js"></script>
    <!--<script type="text/javascript" src="http://mootools.net/download/get/mootools-core-1.4.5-full-compat.js"></script>-->
    <script type="text/javascript" src="iipmooviewer-1.1/javascript/mootools-1.2-more-compressed.js"></script>
    <script src="http://d3js.org/d3.v3.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="lsstTestScript.js"></script>
    <script>
        //preload image
        function preloader(){
            myImage = new Image();
            myImage.src = "uploads/centralFrameImage.jpg";
        }
    </script>
    <!--histogram helper functions-->
    <script type="text/javascript" src="displayHist.js"></script>
    </head>
    <body onLoad="javascript:preloader()">

      <canvas id="secondCanvas" width="1600" height="1200" style="display:none; border:1px solid #d3d3d3;">your browser does not support the HTML5 canvas tag.</canvas>
      <img id="myImage" src="uploads/centralFrameImage.jpg" width="1600" height="1200" style="display:none"/>
      <script>
          //upon loading the image:

          //read in image
          document.getElementById("myImage").onload=function(){
              var c=document.getElementById("secondCanvas");
              var ctx= c.getContext("2d");
              var img = document.getElementById("myImage");
              ctx.drawImage(img,0,0);
              var imgData = ctx.getImageData(0,0,c.width,c.height);
              var index = Math.round(100*Math.random());
              
          //get color Channels
          var colorChannels = new Uint8ClampedArray();
          colorChannels = splitImgByColorChannel(imgData);
          var red = colorChannels[0]; 
          var green = colorChannels[1]; 
          var blue = colorChannels[2]; 

          //display histogram for each channel
              displayHistogram("red", red);
              //displayHistogram("green", green);
             // displayHistogram("blue", blue);
          }
      </script>
      <div id="slider">
      <?php include 'lsstSlider.php'; ?>
      </div>
    </body>
</html>
