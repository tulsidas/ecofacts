<?php
   session_start();
   
   if (!isset($_SESSION['country'])) {
      header('Location: index.php');
      die();
   }

   $country = $_SESSION['country'];
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
            $.getJSON('f.php?c=1W&i=EG.USE.ELEC.KH.PC', function(world_json) {
               var world = toObjArray(world_json);
               $('#pb').css('width', "50%");
               $.getJSON('f.php?c='+country_iso_name+'&i=EG.USE.ELEC.KH.PC', function(country_json) {
                  var country = toObjArray(country_json);
                  $('#pb').css('width', "75%");
                  $.getJSON('f.php?c='+countryData.regionId+'&i=EG.USE.ELEC.KH.PC', function(region_json) {
                     var region = toObjArray(region_json);
                     $('#pb').css('width', "95%");
                     
                     // calc variables
                     var countryAvg = average(_.pluck(country, 'value')); // kWh
                     var regionAvg = average(_.pluck(region, 'value'));
                     var worldAvg = average(_.pluck(world, 'value'));
                     
                     var light_bulb = countryAvg * 1000 / 100; // 100W
                     var compact_fluorescent_lamp = countryAvg * 1000 / 26; // 26W
                     var air_conditioner = countryAvg * 1000 / 3500; // 3500W
                     var desktop_computer = countryAvg * 1000 / 300;; // 300W
                     var laptop_computer = countryAvg * 1000 / 80; // 80W
                     var microwave = countryAvg * 1000 / 1440; // 1440W
                     
                     var country_world_avg = countryAvg / worldAvg;
                     var country_region_avg = countryAvg / regionAvg;
                     
                     // draw chart
                     chart1 = new Highcharts.Chart({
                        chart: { renderTo: 'graph_container', type: 'line', width: 940 },
                        title: { text: 'Electric power consumption' },
                        tooltip: {
                           formatter: function() {
					               return this.series.name + ", " + this.x + ": <b>" + Highcharts.numberFormat(this.y, 0, ',', ' ') + ' kWh per capita</b>';
				               }
                        },
                        xAxis: { categories: $.map(world, function(e) { return e.date; } ) },
                        yAxis: [ {
                           title: { text: 'kWh per capita' }
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
                     $('.country_average').text(Highcharts.numberFormat(countryAvg, 0));
                     $('.country_average_joule').text(Highcharts.numberFormat(countryAvg * 3600, 0));
                     
                     var percentRegion = percentage_to_text(country_region_avg);
                     var percentWorld = percentage_to_text(country_world_avg);

                     $('.country_region_avg').text(percentRegion.text).addClass(percentRegion.badgeClass);
                     $('.country_world_avg').text(percentWorld.text).addClass(percentWorld.badgeClass);
                     
                     // set up tangle
                     var element = document.getElementById("tangle");

                     var tangle = new Tangle(element, {
                         initialize: function () {
                           this.appliance = 0;
                           this.energy = 0;
                           this.values = [light_bulb, compact_fluorescent_lamp, air_conditioner, desktop_computer, laptop_computer, microwave ];
                         },
                         update: function () {
                           this.appliance_hours = Highcharts.numberFormat(this.values[this.appliance], 0);
                           this.appliance_years = Highcharts.numberFormat(this.values[this.appliance] / 8760, 2);
                         }
                     });

// 500MW una coal plant estandar

                     $("#container").show();
                  });
               });
            });
         });         
      });
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
            <p>
               A large part of the CO<sub>2</sub> emmissions are created by power plants that produce electricity. The more electricity being consumed, the more power plants will need to be active to produce it, hence more CO<sub>2</sub> will be produced as well.
            </p>
            <p>On average, each person from 
               <span class="country_name"></span>
               consumes <strong><span class="country_average"></span>
               kWh per year</strong>.
            </p>
            <p>
               That is <span class="country_region_avg badge"></span> 
               as the average of <span class="region_name"></span>, and about 
               <span class="country_world_avg badge"></span> 
               as the average of the entire world.
            </p>
         </div>
         <div class="span11">
            <p>
               <!-- tangle -->
               <span id="tangle">
                  <span data-var="energy" class="TKMultiToggle TKSwitch">
                     <span><span class="country_average"></span> kWh</span>
                     <span><span class="country_average_joule"></span> kJ</span>
                  </span>
                  is enough to keep a
                  <span data-var="appliance" class="TKMultiToggle TKSwitch">
                     <span>
                        standard <small>(100 W)</small> light bulb 
                     </span>
                     <span>
                        CFL <small>(100 W equivalen)</small> light bulb
                     </span>
                     <span> medium air conditioner </span>
                     <span> desktop computer </span>
                     <span> laptop computer </span>
                     <span> microwave oven </span>
                  </span>
                  on for 
                  <span data-var="appliance_hours"></span> consecutive hours (<span data-var="appliance_years"></span> years)
               </span>
               <!-- end tangle -->
            </p>
         </div>
      </div> <!-- end row -->
      <div class="row">
         <div class="offset5 span2">
            <a href="co2.php" class="btn btn-large btn-primary"><i class="icon-arrow-left icon-white"></i> prev</a>
         </div>
         <div class="span2">
            <a href="electric.php" class="btn btn-large btn-primary">next <i class="icon-arrow-right icon-white"></i></a>
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
