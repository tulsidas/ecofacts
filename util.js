/**
 * Function : dump()
 * Arguments: The data - array,hash(associative array),object
 *    The level - OPTIONAL
 * Returns  : The textual representation of the array.
 * This function was inspired by the print_r function of PHP.
 * This will accept some data as the argument and return a
 * text that will be a more readable version of the
 * array/hash/object that is given.
 * Docs: http://www.openjs.com/scripts/others/dump_function_php_print_r.php
 */
function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}

function toObjArray(data) {
   var ret = [];

   var total = data[0].total;
   for (var i = 0; i < total; i++) {
      var date = data[1][i].date;
      var value = data[1][i].value;

      if (value != null) {
         var o = new Object();
         o.date = date;
         o.value = value != null ? parseFloat(value) : null;
         ret.push(o);
      }
   }

   ret.sort(function (o, p) { return o.date - p.date; });
   
   return ret;
}

function getCountryData(country, callback) {
   $.getJSON('f.php?c='+country, function(data) {
      $.getJSON('f.php?c='+country+'&i=SP.POP.TOTL', function(pop_data) {
         var popData = toObjArray(pop_data);
      
         var ret = new Object();
         ret.name = data[1][0].name;
         ret.regionId = data[1][0].region.id;
         ret.regionName = data[1][0].region.value;
         
         // filter population null values, and get the latest one
         var popInfo = _.last(_.filter(popData, function(o) { return o.value != null; }));
         
         ret.population = popInfo.value;
         ret.populationDate = popInfo.date;

         
         if (ret.regionName.indexOf('(') > 0) {
            ret.regionName = ret.regionName.substring(0, ret.regionName.indexOf('('));
         }

         callback(ret);
      });
   });
}

function average(arr) {
	return sum(arr) / arr.length;
}

function sum(arr) {
	return _.reduce(arr, function(memo, num) {
		return memo + num;
	}, 0);
}

/**
 * return { text: xxx, badgeClass: badge-x }
 */
function percentage_to_text(p) {
   var int = Math.floor(p);
   var mantisa = p - int;
   
   if (p < 1.125) {
      var q;
      var bc = "success";
      if (mantisa < 0.125) {
         // round
         q = "less than one quarter of";
      }
      else if (mantisa < 0.375) {
         // 1/4
         q = "one quarter of";
      }
      else if (mantisa < 0.625) {
         // half
         q = "half of";
      }
      else if (mantisa < 0.875) {
         // 3/4
         q = "three quarters of";
      }
      else {
         q = "the same as";
         bc = "warning";
      }
      return { text: q, badgeClass: "badge-"+bc};
   }
   else {
      return { text: Highcharts.numberFormat(Math.round(p*10)/10, 2) + " times more than", badgeClass: p >= 1.5 ? "badge-error" : "badge-warning" };
   }
}
