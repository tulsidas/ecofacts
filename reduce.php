<?php
   session_start();
   
   if (!isset($_SESSION['country'])) {
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
         var country_iso_name = "<?= $_SESSION['country'] ?>";
         var costPerkw = sessvars.costPerkw;

         getCountryData(country_iso_name, function(countryData) {
            // set up tangle
            var element = document.getElementById("tangle");
            
            // set constants
            var PEOPLE_PER_HOUSE = 3;
            var CFL_RATIO = 0.29;
            var KG_OF_CO2_PER_LITER_OF_GAS = 2.38;
            var POUND_OF_CO2_PER_GALLON_OF_GAS = 19;

            var CAR_WEIGHT = 1.7;
            var BUS_WEIGHT = 7;
            var TRUCK_WEIGHT = 15;
            var TRAIN_WEIGHT = 60;
            var PLANE_WEIGHT = 80;

            // set variables
            var houses = countryData.population / PEOPLE_PER_HOUSE;
            
            // update dom with variables
            $('.country_name').text(countryData.name);
            $('.PEOPLE_PER_HOUSE').text(PEOPLE_PER_HOUSE);
            $('.KG_OF_CO2_PER_LITER_OF_GAS').text(KG_OF_CO2_PER_LITER_OF_GAS);
            $('.POUND_OF_CO2_PER_GALLON_OF_GAS').text(POUND_OF_CO2_PER_GALLON_OF_GAS);
            
            var g_per_kwh = {
               coal: 950,
               gas: 600,
               oil: 900,
               renew: 25,
               nuclear: 100,
               hydro: 10
            }
            var tangle = new Tangle(element, {
                initialize: function () {
                     // turn off lights
                    this.percent_house = 0.5;
                    this.cant_lights = 1;
                    this.cant_watts = 60;
                    
                     // leave car at home
                    this.amount_cars = 1; // millions
                    this.consumption = 10; // km/l
                    this.consumption2 = 20; // km/l
                    this.kilometers_per_day = 5; // km
                    this.ec_kilometers_per_day = 50; // km
                    
                    // green power
                    this.amount_coal_plants = 50;
                    this.production_coal_plant = 300;
                },
                update: function () {
                     // turn off lights
                    this.tol_saved_wh = houses * this.percent_house * this.cant_lights * this.cant_watts * 2920; // 8 hours/day/year 
                    this.tol_saved_co2 = this.tol_saved_wh * g_per_kwh.coal / (1000 * 1e6); // 950 g / kWh to ton
                    this.tol_saved_coal_power_plants = this.tol_saved_wh / (1.2e12); // An average plant produces 1.2 TWh / year.
                    
                    this.tol_co2_car = this.tol_saved_co2 / CAR_WEIGHT;
                    this.tol_co2_bus = this.tol_saved_co2 / BUS_WEIGHT;
                    this.tol_co2_truck = this.tol_saved_co2 / TRUCK_WEIGHT;
                    this.tol_co2_train = this.tol_saved_co2 / TRAIN_WEIGHT;
                    this.tol_co2_airplane = this.tol_saved_co2 / PLANE_WEIGHT;

                    // use CFL
                    this.cfl_watt_equivalent = this.cant_watts * CFL_RATIO;
                    this.cfl_saved_wh = houses * this.percent_house * this.cant_lights * this.cant_watts * 2920 * CFL_RATIO; // 8 hours/day/year 
                    this.cfl_saved_co2 = this.cfl_saved_wh * g_per_kwh.coal / (1000 * 1e6); // 950 g / kWh to ton
                    this.cfl_saved_coal_power_plants = this.cfl_saved_wh / (1.2e12); // An average plant produces 1.2 TWh / year.
                    
                    this.cfl_co2_car = this.cfl_saved_co2 / CAR_WEIGHT;
                    this.cfl_co2_bus = this.cfl_saved_co2 / BUS_WEIGHT;
                    this.cfl_co2_truck = this.cfl_saved_co2 / TRUCK_WEIGHT;
                    this.cfl_co2_train = this.cfl_saved_co2 / TRAIN_WEIGHT;
                    this.cfl_co2_airplane = this.cfl_saved_co2 / PLANE_WEIGHT;

                     // leave car at home
                    this.ltc_saved_liters_per_day = this.amount_cars * 1e6 * this.kilometers_per_day / this.consumption;
                    this.ltc_saved_co2_per_year = this.ltc_saved_liters_per_day * 365 * KG_OF_CO2_PER_LITER_OF_GAS / 1000; // kg to ton

                    this.ltc_co2_car = this.ltc_saved_co2_per_year / CAR_WEIGHT;
                    this.ltc_co2_bus = this.ltc_saved_co2_per_year / BUS_WEIGHT;
                    this.ltc_co2_truck = this.ltc_saved_co2_per_year / TRUCK_WEIGHT;
                    this.ltc_co2_train = this.ltc_saved_co2_per_year / TRAIN_WEIGHT;
                    this.ltc_co2_airplane = this.ltc_saved_co2_per_year / PLANE_WEIGHT;

                    // a more efficient car
                    if (this.consumption > this.consumption2) {
                        tangle.setValue("consumption", this.consumption2);
                    }

                    var km = this.amount_cars * 1e6 * this.ec_kilometers_per_day;
                    this.ec_saved_liters_per_day = (km / this.consumption) - (km / this.consumption2);
                    this.ec_saved_co2_per_year = this.ec_saved_liters_per_day * 365 * KG_OF_CO2_PER_LITER_OF_GAS / 1000; // kg to ton
                    
                    this.ec_co2_car = this.ec_saved_co2_per_year / CAR_WEIGHT;
                    this.ec_co2_bus = this.ec_saved_co2_per_year / BUS_WEIGHT;
                    this.ec_co2_truck = this.ec_saved_co2_per_year / TRUCK_WEIGHT;
                    this.ec_co2_train = this.ec_saved_co2_per_year / TRAIN_WEIGHT;
                    this.ec_co2_airplane = this.ec_saved_co2_per_year / PLANE_WEIGHT;
                    
                    // green power
                    var kw = this.amount_coal_plants * this.production_coal_plant * 1000;
                    this.gp_saved_co2_per_hour = ((kw * g_per_kwh.coal) - (kw * g_per_kwh.hydro)) / 1000000; // g to ton
                    this.gp_saved_co2_per_year = this.gp_saved_co2_per_hour * 365 * 16;
                    
                    this.gp_co2_car = this.gp_saved_co2_per_year / CAR_WEIGHT;
                    this.gp_co2_bus = this.gp_saved_co2_per_year / BUS_WEIGHT;
                    this.gp_co2_truck = this.gp_saved_co2_per_year / TRUCK_WEIGHT;
                    this.gp_co2_train = this.gp_saved_co2_per_year / TRAIN_WEIGHT;
                    this.gp_co2_airplane = this.gp_saved_co2_per_year / PLANE_WEIGHT;
                }
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
	<div id="container" class="container">
	   <div class="well">
         <div class="row">
            <div class="offset1 span11">
               <h1>What can <em>you</em> do about it?</h1>
               <p>
                  <br/>
                  <em>You</em> can help reduce the emmisions of CO<sub>2</sub>.
                  <br/>
                  Take a look at some options below and the impact it would have on the environment if people would take them.
               </p>
            </div>
         </div> <!-- end row -->
         <hr/>
         <div class="row">
            <div class="offset1 span10">
               <!-- nav pills -->
               <ul id="tab" class="nav nav-tabs">
                  <li class="dropdown active">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">home<b class="caret"></b></a>
                     <ul class="dropdown-menu">
                        <li><a href="#turn_off_lights" data-toggle="tab">Turn off lights</a></li>
                        <li><a href="#use_cfl_lights" data-toggle="tab">Use CFL lights</a></li>
                     </ul>
                  </li>
                  <li class="dropdown">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">travel<b class="caret"></b></a>
                     <ul class="dropdown-menu">
                        <li><a href="#leave_car" data-toggle="tab">Leave the car at home</a></li>
                        <li><a href="#efficient_car" data-toggle="tab">Use a more efficient car</a></li>
                     </ul>
                  </li>
                  <li class="dropdown">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown">country<b class="caret"></b></a>
                     <ul class="dropdown-menu">
                        <li><a href="#green_power" data-toggle="tab">Use green power plants</a></li>
                     </ul>
                  </li>
               </ul>

               <!-- tangle -->
               <span id="tangle">
               <div class="tab-content">
                  <div class="tab-pane fade active in" id="turn_off_lights">
                     <p>
                        If <span data-var="percent_house" class="TKAdjustableNumber" 
                           data-min="0" data-max="1" data-format="percent" data-step="0.1"></span> 
                        of the houses in
                        <span class="country_name"></span>
                        turned off 
                        <span data-var="cant_lights" class="TKAdjustableNumber" 
                           data-min="0" data-max="6"></span> 
                        lights of
                        <span data-var="cant_watts" class="TKAdjustableNumber" 
                           data-min="20" data-max="150" data-step="10"></span> watts each, that would save
                        <span data-var="tol_saved_wh" data-format="metric Wh" class="badge badge-info"></span> per year
                        which is the output of
                        <span data-var="tol_saved_coal_power_plants" data-format="%.2f" class="badge badge-info"></span>
                        coal power plants
                        which means 
                        <span data-var="tol_saved_co2" data-format="metric_word tons" class="badge badge-info"></span>
                        of CO<sub>2</sub> less on the atmosphere, per year
                     </p>
                     <p>
                        <b> <span data-var="tol_saved_co2" data-format="metric_word tons"></span> </b>
                        of CO<sub>2</sub> equals the weight of:
                        <div style="text-align: center;">
                        <table cellspacing="30" cellpadding="10">
                           <tr>
                              <td> <img src="img/glyphicons_005_car.png"> </td>
                              <td>
                                 <span data-var="tol_co2_car" data-format="metric_word cars" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_031_bus.png"> </td>
                              <td>
                                 <span data-var="tol_co2_bus" data-format="metric_word buses" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_058_truck.png"> </td>
                              <td>
                                 <span data-var="tol_co2_truck" data-format="metric_word trucks" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_014_train.png"> </td>
                              <td>
                                 <span data-var="tol_co2_train" data-format="metric_word trains" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_038_airplane.png"> </td>
                              <td>
                                 <span data-var="tol_co2_airplane" data-format="metric_word airplanes" class="badge"></span>
                              </td>
                           </tr>
                        </table>
                        </div>
                     </p>
                     <p>
                        <small>Assuming: <span class="PEOPLE_PER_HOUSE"></span> people per house and the lights are on 8 hours per day </small>
                     </p>
                  </div>
                  <div class="tab-pane fade" id="use_cfl_lights">
                     <p>
                        If <span data-var="percent_house" class="TKAdjustableNumber" 
                           data-min="0" data-max="1" data-format="percent" data-step="0.1"></span> 
                        of the houses in
                        <span class="country_name"></span>
                        replaced 
                        <span data-var="cant_lights" class="TKAdjustableNumber" 
                           data-min="0" data-max="6"></span> 
                        incandescent lights of
                        <span data-var="cant_watts" class="TKAdjustableNumber" 
                           data-min="20" data-max="150" data-step="10"></span> watts each with their 
                        <a href="http://en.wikipedia.org/wiki/Compact_fluorescent_lamp">CFL</a> 
                        <span data-var="cfl_watt_equivalent" data-format="%.0f W" class="badge badge-info"></span>
                        equivalent, that would save
                        <span data-var="cfl_saved_wh" data-format="metric Wh" class="badge badge-info"></span> per year 
                        which is the output of
                        <span data-var="cfl_saved_coal_power_plants" data-format="%.2f" class="badge badge-info"></span>
                        coal power plants which means 
                        <span data-var="cfl_saved_co2" data-format="metric_word tons" class="badge badge-info"></span>
                        of CO<sub>2</sub> less on the atmosphere, per year
                        <br/>
                     </p>
                     <p>
                        <b> <span data-var="cfl_saved_co2" data-format="metric_word tons"></span> </b>
                        of CO<sub>2</sub> equals the weight of:
                        <div style="text-align: center;">
                        <table cellspacing="30" cellpadding="10">
                           <tr>
                              <td> <img src="img/glyphicons_005_car.png"> </td>
                              <td>
                                 <span data-var="cfl_co2_car" data-format="metric_word cars" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_031_bus.png"> </td>
                              <td>
                                 <span data-var="cfl_co2_bus" data-format="metric_word buses" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_058_truck.png"> </td>
                              <td>
                                 <span data-var="cfl_co2_truck" data-format="metric_word trucks" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_014_train.png"> </td>
                              <td>
                                 <span data-var="cfl_co2_train" data-format="metric_word trains" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_038_airplane.png"> </td>
                              <td>
                                 <span data-var="cfl_co2_airplane" data-format="metric_word airplanes" class="badge"></span>
                              </td>
                           </tr>
                        </table>
                        </div>
                     </p>
                     <p>
                        <small>Assuming: <span class="PEOPLE_PER_HOUSE"></span> people per house and the lights are on 8 hours per day </small>
                     </p>
                  </div>
                  <div class="tab-pane fade" id="leave_car">
                     <p>
                        If <span data-var="amount_cars" class="TKAdjustableNumber" 
                           data-min="0.1" data-max="5" data-step="0.1" data-format="%.1f"></span> 
                        million people in
                        <span class="country_name"></span>
                        walked or used a bicycle 
                        <span data-var="kilometers_per_day" class="TKAdjustableNumber" 
                           data-min="1" data-max="30"></span> 
                        kilometers every day insted of using a car that consumes an average of
                        <span data-var="consumption" class="TKAdjustableNumber" 
                           data-min="1" data-max="40"></span> 
                        kilometers per liter, that would mean that
                        <span data-var="ltc_saved_liters_per_day" data-format="metric_word liters" class="badge badge-info"></span>
                        less of gas would be used every single day, which in turn means saving
                        <span data-var="ltc_saved_co2_per_year" data-format="metric_word tons" class="badge badge-info"></span>
                        of CO<sub>2</sub> to be released into the atmosphere, every year.
                        <br/>
                     </p>
                     <p>
                        <b> <span data-var="ltc_saved_co2_per_year" data-format="metric_word tons"></span> </b>
                        of CO<sub>2</sub> equals the weight of:
                        <div style="text-align: center;">
                        <table cellspacing="30" cellpadding="10">
                           <tr>
                              <td> <img src="img/glyphicons_005_car.png"> </td>
                              <td>
                                 <span data-var="ltc_co2_car" data-format="metric_word cars" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_031_bus.png"> </td>
                              <td>
                                 <span data-var="ltc_co2_bus" data-format="metric_word buses" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_058_truck.png"> </td>
                              <td>
                                 <span data-var="ltc_co2_truck" data-format="metric_word trucks" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_014_train.png"> </td>
                              <td>
                                 <span data-var="ltc_co2_train" data-format="metric_word trains" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_038_airplane.png"> </td>
                              <td>
                                 <span data-var="ltc_co2_airplane" data-format="metric_word airplanes" class="badge"></span>
                              </td>
                           </tr>
                        </table>
                        </div>
                     </p>
                     <p>
                        <small>Assuming: <span class="KG_OF_CO2_PER_LITER_OF_GAS"></span> kg of CO<sub>2</sub> per liter of gas, 
                           or <span class="POUND_OF_CO2_PER_GALLON_OF_GAS"></span> pounds of CO<sub>2</sub> per gallon of gas</small>
                     </p>
                  </div>
                  <div class="tab-pane fade" id="efficient_car">
                     <p>
                        If <span data-var="amount_cars" class="TKAdjustableNumber" 
                           data-min="0.1" data-max="5" data-step="0.1" data-format="%.1f"></span> 
                        million people in
                        <span class="country_name"></span>
                        changed his 
                        <span data-var="consumption" class="TKAdjustableNumber" 
                           data-min="1" data-max="40"></span> 
                        kilometers per liter car to a more efficient
                        <span data-var="consumption2" class="TKAdjustableNumber" 
                           data-min="1" data-max="40"></span> 
                        kilomteres per liter one, to travel an average of 
                        <span data-var="ec_kilometers_per_day" class="TKAdjustableNumber" 
                           data-min="10" data-max="150" data-step="10"></span> kilometers per day
                        , that would mean that
                        <span data-var="ec_saved_liters_per_day" data-format="metric_word liters" class="badge badge-info"></span>
                        less of gas are would be used every single day, which in turn means saving
                        <span data-var="ec_saved_co2_per_year" data-format="metric_word tons" class="badge badge-info"></span>
                        of CO<sub>2</sub> to be released into the atmosphere, every year.
                        <br/>
                     </p>
                     <p>
                        <b> <span data-var="ec_saved_co2_per_year" data-format="metric_word tons"></span> </b>
                        of CO<sub>2</sub> equals the weight of:
                        <div style="text-align: center;">
                        <table cellspacing="30" cellpadding="10">
                           <tr>
                              <td> <img src="img/glyphicons_005_car.png"> </td>
                              <td>
                                 <span data-var="ec_co2_car" data-format="metric_word cars" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_031_bus.png"> </td>
                              <td>
                                 <span data-var="ec_co2_bus" data-format="metric_word buses" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_058_truck.png"> </td>
                              <td>
                                 <span data-var="ec_co2_truck" data-format="metric_word trucks" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_014_train.png"> </td>
                              <td>
                                 <span data-var="ec_co2_train" data-format="metric_word trains" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_038_airplane.png"> </td>
                              <td>
                                 <span data-var="ec_co2_airplane" data-format="metric_word airplanes" class="badge"></span>
                              </td>
                           </tr>
                        </table>
                        </div>
                     </p>
                     <p>
                        <small>Assuming: <span class="KG_OF_CO2_PER_LITER_OF_GAS"></span> kg of CO<sub>2</sub> per liter of gas, 
                           or <span class="POUND_OF_CO2_PER_GALLON_OF_GAS"></span> pounds of CO<sub>2</sub> per gallon of gas</small>
                     </p>
                  </div>
                  <div class="tab-pane fade" id="green_power">
                     <p>
                        If <span data-var="amount_coal_plants" class="TKAdjustableNumber" 
                           data-min="0" data-max="300" data-step="50"></span> 
                        coal plants in
                        <span class="country_name"></span>,
                        where each one produces
                        <span data-var="production_coal_plant" class="TKAdjustableNumber" 
                           data-min="100" data-max="800" data-step="50"></span> 
                        mega watts, are replaced with their equivalent hydroelectrical plants, that would reduce 
                        <span data-var="gp_saved_co2_per_year" data-format="metric_word tons" class="badge badge-info"></span>
                         per year.
                        <br/>
                     </p>
                     <p>
                        <b> <span data-var="gp_saved_co2_per_year" data-format="metric_word tons"></span> </b>
                        of CO<sub>2</sub> equals the weight of:
                        <div style="text-align: center;">
                        <table cellspacing="30" cellpadding="10">
                           <tr>
                              <td> <img src="img/glyphicons_005_car.png"> </td>
                              <td>
                                 <span data-var="gp_co2_car" data-format="metric_word cars" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_031_bus.png"> </td>
                              <td>
                                 <span data-var="gp_co2_bus" data-format="metric_word buses" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_058_truck.png"> </td>
                              <td>
                                 <span data-var="gp_co2_truck" data-format="metric_word trucks" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_014_train.png"> </td>
                              <td>
                                 <span data-var="gp_co2_train" data-format="metric_word trains" class="badge"></span> or
                              </td>
                           </tr>
                           <tr>
                              <td> <img src="img/glyphicons_038_airplane.png"> </td>
                              <td>
                                 <span data-var="gp_co2_airplane" data-format="metric_word airplanes" class="badge"></span>
                              </td>
                           </tr>
                        </table>
                        </div>
                     </p>
                     <p>
                        <small>Assuming: the power plants are operative 16 hours per day, every day of the year</small>
                     </p>
                  </div>
               </div>
               </span>
               <!-- end tangle -->
            </div>
         </div> <!-- end row -->
      </div> <!-- end well -->
      <!--
      <div class="row">
         <div class="offset5 span2">
            <a href="co2.php" class="btn btn-large btn-primary"><i class="icon-arrow-left icon-white"></i> prev</a>
         </div>
         <div class="span2">
            <a href="electric.php" class="btn btn-large btn-primary">next <i class="icon-arrow-right icon-white"></i></a>
         </div>
      </div>
      -->
      <div class="row">
         <div class="span12">
            <p class="pull-right"><small>Data provided by <a href="http://data.worldbank.org/">The World Bank</a></small></p>
         </div>
      </div>
	</div>
</body>
</html>
