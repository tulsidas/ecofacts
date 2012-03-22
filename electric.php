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
   <script type='text/javascript' src='sessvars.js'></script>
   
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
            $('#pb').css('width', "7%");

            // country
            $.getJSON('f.php?c='+country_iso_name+'&i=EG.ELC.COAL.ZS', function(coal_json) {
            var coal = toObjArray(coal_json);
            $('#pb').css('width', "14%");

            $.getJSON('f.php?c='+country_iso_name+'&i=EG.ELC.HYRO.ZS', function(hydro_json) {
            var hydro = toObjArray(hydro_json);
            $('#pb').css('width', "21%");

            $.getJSON('f.php?c='+country_iso_name+'&i=EG.ELC.NGAS.ZS', function(gas_json) {
            var gas = toObjArray(gas_json);
            $('#pb').css('width', "28%");

            $.getJSON('f.php?c='+country_iso_name+'&i=EG.ELC.NUCL.ZS', function(nuclear_json) {
            var nuclear = toObjArray(nuclear_json);
            $('#pb').css('width', "35%");

            $.getJSON('f.php?c='+country_iso_name+'&i=EG.ELC.PETR.ZS', function(oil_json) {
            var oil = toObjArray(oil_json);
            $('#pb').css('width', "42%");

            $.getJSON('f.php?c='+country_iso_name+'&i=EG.ELC.RNWX.ZS', function(renew_json) {
            var renew = toObjArray(renew_json);
            $('#pb').css('width', "49%");

            // world
            $.getJSON('f.php?c=1W&i=EG.ELC.COAL.ZS', function(coal_world_json) {
            var coalWorld = toObjArray(coal_world_json);
            $('#pb').css('width', "56%");

            $.getJSON('f.php?c=1W&i=EG.ELC.HYRO.ZS', function(hydro_world_json) {
            var hydroWorld = toObjArray(hydro_world_json);
            $('#pb').css('width', "63%");

            $.getJSON('f.php?c=1W&i=EG.ELC.NGAS.ZS', function(gas_world_json) {
            var gasWorld = toObjArray(gas_world_json);
            $('#pb').css('width', "70%");

            $.getJSON('f.php?c=1W&i=EG.ELC.NUCL.ZS', function(nuclear_world_json) {
            var nuclearWorld = toObjArray(nuclear_world_json);
            $('#pb').css('width', "77%");

            $.getJSON('f.php?c=1W&i=EG.ELC.PETR.ZS', function(oil_world_json) {
            var oilWorld = toObjArray(oil_world_json);
            $('#pb').css('width', "84%");

            $.getJSON('f.php?c=1W&i=EG.ELC.RNWX.ZS', function(renew_world_json) {
            var renewWorld = toObjArray(renew_world_json);
            $('#pb').css('width', "91%");

            // end JSON, start José
               var years = _.union(
                  _.pluck(coal, 'date'),
                  _.pluck(hydro, 'date'),
                  _.pluck(gas, 'date'),
                  _.pluck(nuclear, 'date'),
                  _.pluck(oil, 'date'),
                  _.pluck(renew, 'date')
               );
               
               
               var dataSize = _.max([coal.length, hydro.length, gas.length, nuclear.length, oil.length, renew.length]);
               if (dataSize == 0) {
                  $(document.body).append('Sorry, no data exists for ' + countryData.name + '<br>Want to try <a href="index.php">another country</a>?');
                  return;
               }

               function costPerkw(costs, allValues) {
                  var values = _.map(allValues, function(list) {
                     return _.last(list).value / 100;
                  });

                  var costoPorkwh = 0;
                  for (var i=0; i < values.length; i++) {
                     costoPorkwh += costs[i] * values[i];
                  }
                  
                  return costoPorkwh;
               }
               
               
               // coal, gas, oil, renew, nuclear, hydro
               var costs = [950, 600, 900, 25, 100, 10];
                  
               var costPerkwCountry = costPerkw(costs, [coal, gas, oil, renew, nuclear, hydro]);
               var costPerkwWorld = costPerkw(costs, [coalWorld, gasWorld, oilWorld, renewWorld, nuclearWorld, hydroWorld]);
               
               sessvars.costPerkw = costPerkwCountry;
                     
               chart = new Highcharts.Chart({
		            chart: {
			            renderTo: 'graph_container',
			            plotBackgroundColor: null,
			            plotBorderWidth: null,
			            plotShadow: false,
			            width: 940 
		            },
		            title: {
			            text: 'Electricity production from different sources for ' + countryData.name
		            },
		            tooltip: {
			            formatter: function() {
				            return '<b>'+ this.point.name +'</b>: ' + Highcharts.numberFormat(this.percentage, 2) + ' %';
			            }
		            },
		            plotOptions: {
			            pie: {
				            allowPointSelect: false,
				            dataLabels: {
					            enabled: true,
					            color: '#000000',
					            connectorColor: '#000000',
					            formatter: function() {
						            return '<b>'+ this.point.name +'</b>: ' + Highcharts.numberFormat(this.percentage, 2) + ' %';
					            }
				            }
			            }
		            },
		            series: [{
			            type: 'pie',
			            data: [
				            { name: 'Coal', sliced: true, y: _.last(coal).value },
				            { name: 'Hydro', sliced: true, y: _.last(hydro).value },
				            { name: 'Gas', sliced: true, y: _.last(gas).value },
				            { name: 'Nuclear', sliced: true, y: _.last(nuclear).value },
				            { name: 'Oil', sliced: true, y: _.last(oil).value },
				            { name: 'Renewable', sliced: true, y: _.last(renew).value }
			            ]
		            }]
	            }, function() {
                  $('#pb_div').remove();
               });
               
               // set variables
               $('.country_name').text(countryData.name);
               $('.region_name').text(countryData.regionName);
               $('.costPerkwCountry').text(Highcharts.numberFormat(costPerkwCountry, 0));
               $('.costPerkwWorld').text(Highcharts.numberFormat(costPerkwWorld, 0));
               
               var percentWorld = percentage_to_text(costPerkwCountry / costPerkwWorld);
               $('.country_world_avg').text(percentWorld.text).addClass(percentWorld.badgeClass);

               $("#container").show();
               
            // closing hell
            }); }); });
            }); }); });
            }); }); });
            }); }); });
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
         <div class="offset1 span11">
            <p>Based on the different electricity production sources, in
               <span class="country_name"></span>
               the production of <strong>1 kWh</strong> outputs
               <strong><span class="costPerkwCountry"></span>
               grams of CO<sub>2</sub></strong>.
            </p>
            <p>
               That is about 
               <span class="country_world_avg badge"></span> 
               the average of the entire world.
               ( <strong><span class="costPerkwWorld"></span> grams of CO<sub>2</sub> / kWh </strong> )
            </p>
         </div>
      </div> <!-- end row -->
      <div class="row">
         <div class="offset5 span2">
            <a href="kwh.php" class="btn btn-large btn-primary"><i class="icon-arrow-left icon-white"></i> prev</a>
         </div>
         <div class="span2">
            <a href="reduce.php" class="btn btn-large btn-primary">next <i class="icon-arrow-right icon-white"></i></a>
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
