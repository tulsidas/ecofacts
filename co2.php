<?php
   session_start();

   if (isset($_POST["country"])) {
      $_SESSION["country"] = $_POST["country"];
   }
   else if (!isset($_POST["country"]) && !isset($_SESSION["country"])) {
      header('Location: index.php');
      die();
   }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>ecofacts</title>
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 50px;
        padding-bottom: 40px;
      }
    </style>

   <script type='text/javascript' src='underscore-min.js'></script>
   <script type='text/javascript' src='util.js'></script>
   <script type='text/javascript' src='jquery-1.7.1.min.js'></script>
   <script type='text/javascript' src='bootstrap/js/bootstrap.min.js'></script>
   <script type='text/javascript' src='highcharts.js'></script>
   
    <!-- Tangle -->
    <script type="text/javascript" src="Tangle.js"></script>
    <link rel="stylesheet" href="TangleKit/TangleKit.css" type="text/css">
    <script type="text/javascript" src="TangleKit/mootools.js"></script>
    <script type="text/javascript" src="TangleKit/sprintf.js"></script>
    <script type="text/javascript" src="TangleKit/BVTouchable.js"></script>
    <script type="text/javascript" src="TangleKit/TangleKit.js"></script>

   <script type='text/javascript'>
      $(document).ready(function() {
         var chart1; // globally available

         var country_iso_name = "<?= $_SESSION['country'] ?>";
         
         $("#container").hide();

         getCountryData(country_iso_name, function(countryData) {
            $('#pb').css('width', "25%");
            $.getJSON('f.php?c=1W&i=EN.ATM.CO2E.PC', function(world_json) {
               var world = toObjArray(world_json);
               $('#pb').css('width', "50%");
               $.getJSON('f.php?c='+country_iso_name+'&i=EN.ATM.CO2E.PC', function(country_json) {
                  var country = toObjArray(country_json);
                  $('#pb').css('width', "75%");
                  $.getJSON('f.php?c='+countryData.regionId+'&i=EN.ATM.CO2E.PC', function(region_json) {
                     var region = toObjArray(region_json);
                     $('#pb').css('width', "95%");
                     
                     // calc variables
                     var countryAvg = average(_.pluck(country, 'value'));
                     var regionAvg = average(_.pluck(region, 'value'));
                     var worldAvg = average(_.pluck(world, 'value'));
                     
                     var country_world_avg = countryAvg / worldAvg;
                     var country_region_avg = countryAvg / regionAvg;
                     
                     var countryAvgCar = countryAvg / 1.7;
                     var countryAvgBus = countryAvg / 7;
                     var countryAvgTruck = countryAvg / 15;
                     
                     // draw chart
                     chart1 = new Highcharts.Chart({
                        chart: { renderTo: 'graph_container', type: 'line', width: 940 },
                        title: { text: 'CO2 emmissions' },
                        tooltip: {
                           formatter: function() {
					               return this.series.name + ", " + this.x + ": <b>" + Highcharts.numberFormat(this.y, 2) + ' metric tons per capita</b>';
				               }
                        },
                        xAxis: {  categories: $.map(world, function(e) { return e.date; } ) },
                        yAxis: [ {
                           title: { text: 'metric tons per capita' }
                        } ],
                        series: [
                           {
                              name: countryData.name,
                              data: $.map(country, function(e) { return e.value; } )
                           },
                           {
                              name: countryData.regionName,
                              data: $.map(region, function(e) { return e.value; } )
                           },
                           {
                              name: 'World',
                              data: $.map(world, function(e) { return e.value; } )
                           }
                        ],
                     }, function() {
                        $('#pb_div').remove();
                     });
                     
                     // set variables
                     $('.country_name').text(countryData.name);
                     $('.region_name').text(countryData.regionName);
                     $('.country_population').text(Highcharts.numberFormat(countryData.population, 0));
                     $('.country_population_date').text(countryData.populationDate);
                     $('.country_average').text(Highcharts.numberFormat(countryAvg, 2));
                     $('.country_average_kg').text(Highcharts.numberFormat(countryAvg * 1000, 0));
                     $('.country_average_pound').text(Highcharts.numberFormat(countryAvg * 2204, 0));

                     var percentRegion = percentage_to_text(country_region_avg);
                     var percentWorld = percentage_to_text(country_world_avg);

                     $('.country_region_avg').text(percentRegion.text).addClass(percentRegion.badgeClass);
                     $('.country_world_avg').text(percentWorld.text).addClass(percentWorld.badgeClass);
                     
                     $('.country_average_car').text(Highcharts.numberFormat(countryAvgCar, 2));
                     $('.country_average_truck').text(Highcharts.numberFormat(countryAvgTruck, 2));
                     $('.country_average_bus').text(Highcharts.numberFormat(countryAvgBus, 2));
                     
                     // set up tangle
                     var element = document.getElementById("tangle");

                     var tangle = new Tangle(element, {
                         initialize: function () {
                           this.vehicle = 0;
                           this.co2weight = 0;
                         },
                         update: function () {
                         /*
                           $("#vehicle_container").empty();
                           
                           var t;
                           var imgFile;
                           if (this.vehicle == 0) { // car
                              t = Math.round(countryAvgCar);
                              imgFile = "img/glyphicons_005_car.png";
                           }
                           else if (this.vehicle == 1) { // truck
                              t = Math.round(countryAvgTruck);
                              imgFile = "img/glyphicons_058_truck.png";
                           }
                           else if (this.vehicle == 2) { // bus
                              t = Math.round(countryAvgBus);
                              imgFile = "img/glyphicons_031_bus.png";
                           }
                           
                           // populate vehicles
                           _.times(t, function() {
                              var img = $('<img>');
                              img.attr("src", imgFile);

                              $("#vehicle_container").append(img);
                           });
                        */
                         }
                     });


                     $("#container").show();
                  });
               });
            });
         });
      });
      function setVariables() {
         // variables para todos
      }
   </script>
</head>
<body>
   <!-- top nav bar -->
    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="index.php">ecofacts</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="index.php">Home</a></li>
              <li><a href="about.html">About</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
   <div id="pb_div" class="row">
      <div class="span4 offset4 alert alert-success">
         <center>loading...</center>
         <div class="progress progress-success">
            <div id="pb" class="bar" style="width: 0%;"></div>
         </div>
      </div>
   </div>
   <div id="container" class="container">
      <div id="graph_container"></div>
	   <hr/>
      <div class="row well">
         <div class="span11">
            <p>On average, each person from 
               <span class="country_name"></span>
               produces <strong><span class="country_average"></span>
               metric tons of CO<sub>2</sub> per year</strong>.
            </p>
            <p>
               That is <span class="country_region_avg badge"></span> 
               the average of <span class="region_name"></span>, and about 
               <span class="country_world_avg badge"></span> 
               the average of the entire world.
            </p>
         </div>
         <div class="span11">
            <p>
               <strong>
               <span class="country_average"></span>
               metric tons</strong> of CO<sub>2</sub> equals
               <!-- tangle -->
               <span id="tangle">
                  <span data-var="co2weight" class="TKMultiToggle TKSwitch">
                     <span><span class="country_average_kg"></span> kilograms</span>
                     <span><span class="country_average_pound"></span> pounds</span>
                  </span>
                  which is about the combined weight of
                  <span data-var="vehicle" class="TKMultiToggle TKSwitch">
                     <span><span class="country_average_car"></span> cars</span>
                     <span><span class="country_average_truck"></span> trucks</span>
                     <span><span class="country_average_bus"></span> buses</span>
                  </span>
               </span>
               <!-- end tangle -->
               of CO<sub>2</sub> being released into the atmosphere, and that is <em>per person, every year!</em>
            </p>
         </div>
         <div class="span12" style="text-align: center;" id="vehicle_container">
         </div>
      </div> <!-- end row -->
      <div class="row">
         <div class="offset5 span2">
            <a href="index.php" class="btn btn-large btn-primary"><i class="icon-arrow-left icon-white"></i> prev</a>
         </div>
         <div class="span2">
            <a href="kwh.php" class="btn btn-large btn-primary">next <i class="icon-arrow-right icon-white"></i></a>
         </div>
      </div>
      <div class="row">
         <div class="span12">
            <p class="pull-right"><small>Data provided by <a href="http://data.worldbank.org/">The World Bank</a></small></p>
         </div>
      </div>
   </div>
</body>
</html>
