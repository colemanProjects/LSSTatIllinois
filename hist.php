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
        <script type="text/javascript" src="http://mootools.net/download/get/mootools-core-1.4.5-full-compat.js"></script>
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
    </head>
    <body onLoad="javascript:preloader()">
        <ul id = "wholePageList">
            <li id = "graphics">
                <canvas id="secondCanvas" width="1600" height="1200" style="display:none; border:1px solid #d3d3d3;">your browser does not support the HTML5 canvas tag.</canvas>
                <img id="myImage" src="uploads/centralFrameImage.jpg" width="1600" height="1200" style="display:none"/>
                
            <!--histogram helper functions-->
            <script>
				//change of base function for log
				function log10(val)
				{
					return Math.log(val)/Math.LN10;
				}

                //returns the four color channels, red, green, blue, and alpha
                function splitImgByColorChannel(imgData){
                    
                    var colorChannels = new Array(4);
                    var arrLength = imgData.data.length;
                    var channelLength = Math.round(arrLength/4);
                    var red   = new Uint8ClampedArray(channelLength);
                    var green = new Uint8ClampedArray(channelLength);
                    var blue  = new Uint8ClampedArray(channelLength);
                    var alpha = new Uint8ClampedArray(channelLength);
                    for(var i = 0; i<arrLength; i+=4){
                        red[i] = imgData.data[i];
                        green[i] = imgData.data[i+1];
                        blue[i] = imgData.data[i+2];
                        alpha[i] = imgData.data[i+3];
                    }
                    colorChannels[0] = red;
                    colorChannels[1] = green;
                    colorChannels[2] = blue;
                    colorChannels[3] = alpha;
                    return colorChannels;
                }

                //function that displays histogram for a given color channel
                function displayHistogram(channelName, channel){
                    
                    // convert array of values to a map, and do out of bounds check
                    var values = d3.range(channel.length).map(function(x){//grab pixel data from the input img. imgData
                                                            if(channel[x] > 255) 
                                                                {
                                                                alert("imgData out of bounds at value: " +channel[x]); 
                                                                return 255;
                                                                }
                                                            else
                                                            {
                                                                return channel[x];
                                                            }});
					//margin calculations
                    var margin = {top: 10, right: 30, bottom: 30, left: 40},
                        width = 300,
                        height = 340 - margin.top - margin.bottom;
			   		var histogramTopMargin = 10;

					//create x  scale based on bin count
                    var bins = 256;
                    var x = d3.scale.linear()
                        .domain([0, bins -1])
                        .range([0, width]);

                    // Generate a histogram array by counting frequencies from the map of pixel values
                    var data = d3.layout.histogram()
                        .bins(x.ticks(255))
                        (values);

					//get largest frequency count and largest possible log for log scale
					var maxHistBucket = d3.max(data, function(d) { return d.y; });
					var largestLog = Math.pow(10,Math.ceil(log10(maxHistBucket)));

					//create y scale based on the max value in the pixel map
                    var y = d3.scale.log()
                        .clamp(true)
                        .domain([0.01, d3.max(data, function(d) { return d.y; })])
                        .rangeRound([height, 0])
                        .nice();
                    
					//create x and y axis formats
                    var xAxis = d3.svg.axis()
                        .scale(x)
                        .tickSize(0,5,1)
                        .tickValues([0,50,100,150,200,255])
                        .orient("bottom");
                    var yAxis = d3.svg.axis()
                        .scale(y)
                        .tickSize(10,5,1)
                        .tickValues([1,10,100,1000,10000,largestLog])
                        .orient("left");

													/* Display*/


			   //create div for both histogram and add it to the page
               var svgName = channelName + "Histogram";
               var histogramDiv =  document.createElement("div");
               histogramDiv.id = svgName;
               histogramDiv.className = "histogram";
               histogramDiv.style.width = "100%";
               histogramDiv.style.height = height;
               histogramDiv.style.background = "#eee";
               histogramDiv.style.marginTop = "10px";
               document.body.appendChild(histogramDiv);

			   //add the histogram to the div 
               var svg = d3.select(histogramDiv).append("svg")
                    .attr("width", "100%" )
                    .attr("id", svgName + "svg")
                    .attr("height", height + margin.top + margin.bottom)
                    .append("g");

               //create slider knobs to go in the div and on  top of the histogram
               var lowknob =  document.createElement("div");
               lowknob.id = channelName + "lowknob";
               lowknob.className = "lowknob";
               lowknob.style.height = (height + histogramTopMargin) + "px";
               lowknob.style.marginLeft = (margin['left']) + "px";

               var highknob =  document.createElement("div");
               highknob.id = channelName + "highknob";
               highknob.className = "highknob";
               highknob.style.height = (height + histogramTopMargin) + "px";
               highknob.style.marginLeft = (margin['left']) + "px";

			   //add the knobs to the histogram div
               document.getElementById(histogramDiv.id).appendChild(lowknob);
               document.getElementById(histogramDiv.id).appendChild(highknob);
                
                    // create slider oject for lowknob
                    new Slider($(svgName + "svg"),$(lowknob.id), {
                        range: [0, 255],
                        initialStep: 14,
                        wheel: true,
                        snap: false,
                        steps: 256,
                        onChange: function(step) {
                        $(lowknob.id).set('html',step);
                      }
                  });

                    // create slider oject for highknob
                    new Slider($(svgName + "svg"),$(highknob.id), {
                        range: [255, 0],
                        initialStep: 0,
                        wheel: true,
                        snap: false,
                        steps: 256,
                        onChange: function(step) {
                        $(highknob.id).set('html',step);
                      }
                  });

                    // translate,name, and arrang all bars for the histogram
                    var bar = svg.selectAll(".bar")
                        .data(data)
                        .enter().append("g")
                        .attr("class", "bar")
                        .attr("transform", function(d) { return "translate(" + (x(d.x) + margin['left']) + "," + (y(d.y)+ histogramTopMargin) + ")"; });
                    
                    // assign rectangles to each bar
                    bar.append("rect")
                        .attr("x", 1)
                        .attr("fill", channelName)
                        .attr("width", x(data[0].dx) - 1)
                        .attr("height", function(d) { return height - y(d.y); });

					//append the x and y axes to the histogram
                    svg.append("g")
                        .attr("class", "x axis")
                        .attr("transform", "translate(" + margin['left'] + "," + (height + margin['top'])  +  ")")
                        .call(xAxis);
                    svg.append("g")
                        .attr("class", "axis")
                        .attr("transform", "translate(" + margin['left'] + ","+ margin['top'] +")")
                        .call(yAxis);

					//make the text small for axis text
                    d3.selectAll("axisElement")
                        .classed("small-font", true);
                }
            </script>

            <!--histogram -->
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
            </li>
        </ul>
    </body>
</html>
