<div id="sidebar">
	<h3>Get Started</h3>

 	<ul>
    <li>Upload Image</li>
    <li><iframe id = "uploadFrame" src="uploadPage.html" frameborder ="0" scrolling="no" width="100%" height="100px">
    </iframe></li>
    <li><h3>Image Histogram</h3></li>
<!--	<li><h3 id="otherOptions">Other options are on their way!</h3></li> -->
 	</ul>
     <div id = "histogramContainer"><div id = "histogramFrame"> 
 <canvas id="secondCanvas" width="1600" height="1200" style="display:none; border:1px solid #d3d3d3;">your browser does not support the HTML5 canvas tag.</canvas>
 <img id="myImage" src="uploads/centralFrameImage.jpg" width="1600" height="1200" style="display:none"/>
 <script type="text/javascript" src="displayHist.js"></script>


  <script>
    //upon loading the image:
    var myList = document.getElementsByClassName('layer0');
    console.log(myList);
    //read in image
    document.getElementById('myImage').onload=function(){
        var c=document.getElementById("secondCanvas");
        var ctx= c.getContext("2d");
        var img = document.getElementById('myImage');
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
<?php include('lsstSlider.php');?>

</div></div>
 
</div> <!-- end #sidebar -->

