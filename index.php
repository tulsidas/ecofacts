<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>ecofacts</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="a small app to learn about energy consumption, climate change and what you can do about it">
    <meta name="author" content="andres">

    <script type="text/javascript" src="jquery-1.7.1.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>

    <!-- Le styles -->
    <link href="bootstrap/css/bootstrap.css" rel="stylesheet">
    <style type="text/css">
      body {
        padding-top: 80px;
        padding-bottom: 40px;
      }
    </style>
    <link rel="stylesheet" href="TangleKit/TangleKit.css" type="text/css">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
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
          <a class="brand" href="">ecofacts</a>
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a>Home</a></li>
              <li><a href="about.html">About</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
      <div class="hero-unit">
        <h1>Hello, world!</h1>
        <p>&nbsp;</p>
        <p>Welcome to <strong>ecofacts</strong>, a small app to learn about energy consumption, climate change and what you can do about it.</p>
        <p>&nbsp;</p>
        <p>This is a reactive site! On the following pages, look for links <span class="TKMultiToggle">like this one</span> and try to click them or drag them!</p>
        <p>&nbsp;</p>
        <p>Start by choosing the country you live in (or any country you like, really)</p>
        <p>&nbsp;</p>
         <div class="span5 offset3">
            <form class="form-horizontal control-group" id="countryForm" action="co2.php" method="POST">
               <select id="countrySelect" name="country">
                  <option value="AW">Aruba</option>
                  <option value="AF">Afghanistan</option>
                  <option value="AO">Angola</option>
                  <option value="AL">Albania</option>
                  <option value="AD">Andorra</option>
                  <option value="AE">United Arab Emirates</option>
                  <option value="AR">Argentina</option>
                  <option value="AM">Armenia</option>
                  <option value="AS">American Samoa</option>
                  <option value="AG">Antigua and Barbuda</option>
                  <option value="AU">Australia</option>
                  <option value="AT">Austria</option>
                  <option value="AZ">Azerbaijan</option>
                  <option value="BI">Burundi</option>
                  <option value="BE">Belgium</option>
                  <option value="BJ">Benin</option>
                  <option value="BF">Burkina Faso</option>
                  <option value="BD">Bangladesh</option>
                  <option value="BG">Bulgaria</option>
                  <option value="BH">Bahrain</option>
                  <option value="BS">Bahamas, The</option>
                  <option value="BA">Bosnia and Herzegovina</option>
                  <option value="BY">Belarus</option>
                  <option value="BZ">Belize</option>
                  <option value="BM">Bermuda</option>
                  <option value="BO">Bolivia</option>
                  <option value="BR">Brazil</option>
                  <option value="BB">Barbados</option>
                  <option value="BN">Brunei Darussalam</option>
                  <option value="BT">Bhutan</option>
                  <option value="BW">Botswana</option>
                  <option value="CF">Central African Republic</option>
                  <option value="CA">Canada</option>
                  <option value="CH">Switzerland</option>
                  <option value="JG">Channel Islands</option>
                  <option value="CL">Chile</option>
                  <option value="CN">China</option>
                  <option value="CI">Cote d'Ivoire</option>
                  <option value="CM">Cameroon</option>
                  <option value="CD">Congo, Dem. Rep.</option>
                  <option value="CG">Congo, Rep.</option>
                  <option value="CO">Colombia</option>
                  <option value="KM">Comoros</option>
                  <option value="CV">Cape Verde</option>
                  <option value="CR">Costa Rica</option>
                  <option value="CU">Cuba</option>
                  <option value="CW">Curacao</option>
                  <option value="KY">Cayman Islands</option>
                  <option value="CY">Cyprus</option>
                  <option value="CZ">Czech Republic</option>
                  <option value="DE">Germany</option>
                  <option value="DJ">Djibouti</option>
                  <option value="DM">Dominica</option>
                  <option value="DK">Denmark</option>
                  <option value="DO">Dominican Republic</option>
                  <option value="DZ">Algeria</option>
                  <option value="EC">Ecuador</option>
                  <option value="EG">Egypt, Arab Rep.</option>
                  <option value="ER">Eritrea</option>
                  <option value="ES">Spain</option>
                  <option value="EE">Estonia</option>
                  <option value="ET">Ethiopia</option>
                  <option value="FI">Finland</option>
                  <option value="FJ">Fiji</option>
                  <option value="FR">France</option>
                  <option value="FO">Faeroe Islands</option>
                  <option value="FM">Micronesia, Fed. Sts.</option>
                  <option value="GA">Gabon</option>
                  <option value="GB">United Kingdom</option>
                  <option value="GE">Georgia</option>
                  <option value="GH">Ghana</option>
                  <option value="GI">Gibraltar</option>
                  <option value="GN">Guinea</option>
                  <option value="GM">Gambia, The</option>
                  <option value="GW">Guinea-Bissau</option>
                  <option value="GQ">Equatorial Guinea</option>
                  <option value="GR">Greece</option>
                  <option value="GD">Grenada</option>
                  <option value="GL">Greenland</option>
                  <option value="GT">Guatemala</option>
                  <option value="GU">Guam</option>
                  <option value="GY">Guyana</option>
                  <option value="HK">Hong Kong SAR, China</option>
                  <option value="HN">Honduras</option>
                  <option value="HR">Croatia</option>
                  <option value="HT">Haiti</option>
                  <option value="HU">Hungary</option>
                  <option value="ID">Indonesia</option>
                  <option value="IM">Isle of Man</option>
                  <option value="IN">India</option>
                  <option value="IE">Ireland</option>
                  <option value="IR">Iran, Islamic Rep.</option>
                  <option value="IQ">Iraq</option>
                  <option value="IS">Iceland</option>
                  <option value="IL">Israel</option>
                  <option value="IT">Italy</option>
                  <option value="JM">Jamaica</option>
                  <option value="JO">Jordan</option>
                  <option value="JP">Japan</option>
                  <option value="KZ">Kazakhstan</option>
                  <option value="KE">Kenya</option>
                  <option value="KG">Kyrgyz Republic</option>
                  <option value="KH">Cambodia</option>
                  <option value="KI">Kiribati</option>
                  <option value="KN">St. Kitts and Nevis</option>
                  <option value="KR">Korea, Rep.</option>
                  <option value="KV">Kosovo</option>
                  <option value="KW">Kuwait</option>
                  <option value="LA">Lao PDR</option>
                  <option value="LB">Lebanon</option>
                  <option value="LR">Liberia</option>
                  <option value="LY">Libya</option>
                  <option value="LC">St. Lucia</option>
                  <option value="LI">Liechtenstein</option>
                  <option value="LK">Sri Lanka</option>
                  <option value="LS">Lesotho</option>
                  <option value="LT">Lithuania</option>
                  <option value="LU">Luxembourg</option>
                  <option value="LV">Latvia</option>
                  <option value="MO">Macao SAR, China</option>
                  <option value="MF">St. Martin (French part)</option>
                  <option value="MA">Morocco</option>
                  <option value="MC">Monaco</option>
                  <option value="MD">Moldova</option>
                  <option value="MG">Madagascar</option>
                  <option value="MV">Maldives</option>
                  <option value="MX">Mexico</option>
                  <option value="MH">Marshall Islands</option>
                  <option value="MK">Macedonia, FYR</option>
                  <option value="ML">Mali</option>
                  <option value="MT">Malta</option>
                  <option value="MM">Myanmar</option>
                  <option value="ME">Montenegro</option>
                  <option value="MN">Mongolia</option>
                  <option value="MP">Northern Mariana Islands</option>
                  <option value="MZ">Mozambique</option>
                  <option value="MR">Mauritania</option>
                  <option value="MU">Mauritius</option>
                  <option value="MW">Malawi</option>
                  <option value="MY">Malaysia</option>
                  <option value="YT">Mayotte</option>
                  <option value="NA">Namibia</option>
                  <option value="NC">New Caledonia</option>
                  <option value="NE">Niger</option>
                  <option value="NG">Nigeria</option>
                  <option value="NI">Nicaragua</option>
                  <option value="NL">Netherlands</option>
                  <option value="NO">Norway</option>
                  <option value="NP">Nepal</option>
                  <option value="NZ">New Zealand</option>
                  <option value="OM">Oman</option>
                  <option value="PK">Pakistan</option>
                  <option value="PA">Panama</option>
                  <option value="PE">Peru</option>
                  <option value="PH">Philippines</option>
                  <option value="PW">Palau</option>
                  <option value="PG">Papua New Guinea</option>
                  <option value="PL">Poland</option>
                  <option value="PR">Puerto Rico</option>
                  <option value="KP">Korea, Dem. Rep.</option>
                  <option value="PT">Portugal</option>
                  <option value="PY">Paraguay</option>
                  <option value="PS">West Bank and Gaza</option>
                  <option value="PF">French Polynesia</option>
                  <option value="QA">Qatar</option>
                  <option value="RO">Romania</option>
                  <option value="RU">Russian Federation</option>
                  <option value="RW">Rwanda</option>
                  <option value="SA">Saudi Arabia</option>
                  <option value="SD">Sudan</option>
                  <option value="SN">Senegal</option>
                  <option value="SG">Singapore</option>
                  <option value="SB">Solomon Islands</option>
                  <option value="SL">Sierra Leone</option>
                  <option value="SV">El Salvador</option>
                  <option value="SM">San Marino</option>
                  <option value="SO">Somalia</option>
                  <option value="RS">Serbia</option>
                  <option value="SS">South Sudan</option>
                  <option value="ST">Sao Tome and Principe</option>
                  <option value="SR">Suriname</option>
                  <option value="SK">Slovak Republic</option>
                  <option value="SI">Slovenia</option>
                  <option value="SE">Sweden</option>
                  <option value="SZ">Swaziland</option>
                  <option value="SX">Sint Maarten (Dutch part)</option>
                  <option value="SC">Seychelles</option>
                  <option value="SY">Syrian Arab Republic</option>
                  <option value="TC">Turks and Caicos Islands</option>
                  <option value="TD">Chad</option>
                  <option value="TG">Togo</option>
                  <option value="TH">Thailand</option>
                  <option value="TJ">Tajikistan</option>
                  <option value="TM">Turkmenistan</option>
                  <option value="TL">Timor-Leste</option>
                  <option value="TO">Tonga</option>
                  <option value="TT">Trinidad and Tobago</option>
                  <option value="TN">Tunisia</option>
                  <option value="TR">Turkey</option>
                  <option value="TV">Tuvalu</option>
                  <option value="TZ">Tanzania</option>
                  <option value="UG">Uganda</option>
                  <option value="UA">Ukraine</option>
                  <option value="UY">Uruguay</option>
                  <option value="US">United States</option>
                  <option value="UZ">Uzbekistan</option>
                  <option value="VC">St. Vincent and the Grenadines</option>
                  <option value="VE">Venezuela, RB</option>
                  <option value="VI">Virgin Islands (U.S.)</option>
                  <option value="VN">Vietnam</option>
                  <option value="VU">Vanuatu</option>
                  <option value="WS">Samoa</option>
                  <option value="YE">Yemen, Rep.</option>
                  <option value="ZA">South Africa</option>
                  <option value="ZM">Zambia</option>
                  <option value="ZW">Zimbabwe</option>               
               </select>
               <button type="submit" class="btn ">Start <i class="icon-ok"></i></button>
            </form>
         </div>
      </div>

      <hr>

    </div> <!-- /container -->
  </body>
</html>

