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

                //display histogram for a given color channel
                function displayHistogram(channelName, channel){
                    
                    // compute the array of values to be placed in histogram bin
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
                    // A formatter for counts.
                    var formatCount = d3.format(",.0f");

                    var margin = {top: 10, right: 30, bottom: 30, left: 30},
                        width = 370 - margin.left - margin.right,
                        height = 140 - margin.top - margin.bottom;

                    var bins = 256;
                    var x = d3.scale.linear()
                        .domain([0, bins -1])
                        .range([0, width]);

                    // Generate a histogram using twenty uniformly-spaced bins.
                    var data = d3.layout.histogram()
                        .bins(x.ticks(100))
                        (values);

                    var y = d3.scale.log()
                        .clamp(true)
                        .domain([0.01, d3.max(data, function(d) { return d.y; })])
                        .range([height, 0])
                        .nice();
                    //window.alert(y(-255));
                    
                    var xAxis = d3.svg.axis()
                        .scale(x)
                        .tickSize(0,5,1)
                        .tickValues([0,Math.round(bins/4),Math.round(bins/2),Math.round(3*bins/4),Math.round(bins)])
                        .orient("bottom");

                    //create text to modify and knob for contrast bar
                 //       document.createElement('<div id="fontSize">Change the value, to change the font size.</div>');
               var svgName = channelName + "Histogram";

               var lowknob =  document.createElement("div");
               var highknob =  document.createElement("div");

               lowknob.id = channelName + "lowknob";
               highknob.id = channelName + "highknob";

               lowknob.className = "lowknob";
               highknob.className = "highknob";

               var knobDiv =  document.createElement("div");
               knobDiv.id = svgName;
               knobDiv.className = "histogram";
               knobDiv.style.width = width - "370px";
               knobDiv.style.height = "140px";
               knobDiv.style.background = "#eee";

               document.body.appendChild(knobDiv);

               //var svg = d3.select("body").append("svg")
               var svg = d3.select(knobDiv).append("svg")
                    .attr("width", width)
                    .attr("id", svgName + "svg")
                    .attr("height", height + margin.top + margin.bottom)
                    .append("g");
                   // .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
               document.getElementById(knobDiv.id).appendChild(lowknob);
               document.getElementById(knobDiv.id).appendChild(highknob);
                
                    // contrast bar
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

                    // contrast bar
                    new Slider($(svgName + "svg"),$(highknob.id), {
                        range: [0, 255],
                        initialStep: 200,
                        wheel: true,
                        snap: false,
                        steps: 256,
                        onChange: function(step) {
                        $(highknob.id).set('html',step);
                      }
                  });

                    var bar = svg.selectAll(".bar")
                        .data(data)
                        .enter().append("g")
                        .attr("class", "bar")
                        .attr("transform", function(d) { return "translate(" + x(d.x) + "," + y(d.y) + ")"; });
                    
                    bar.append("rect")
                        .attr("x", 1)
                        .attr("fill", channelName)
                        .attr("width", x(data[0].dx) - 1)
                        .attr("height", function(d) { return height - y(d.y); });

                 /*   bar.append("text")
                        .attr("dy", ".75em")
                        .attr("y", 6)
                        .attr("x", x(data[0].dx) / 2)
                        .attr("text-anchor", "middle")
                        .text(function(d) { return formatCount(d.y); });
                */
                    svg.append("g")
                        .attr("class", "x axis")
                        .attr("transform", "translate(0," + height + ")")
                        .call(xAxis);

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
                    displayHistogram("green", green);
                    displayHistogram("blue", blue);
                }
            </script>
            </li>
        </ul>
    </body>
</html>
