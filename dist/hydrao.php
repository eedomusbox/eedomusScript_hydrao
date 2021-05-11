<!DOCTYPE html>
<html><head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="viewport" content="width=device-width,user-scalable=no">
        <style>
<?
// style par défaut eedomus
// vous pouvez ajouter vos propres styles dans la balise <style>
sdk_get_widget_default_style ();
?>
</style>
        <style>
            .wrapper {
                display: flex;
                flex-direction: column;
                touch-action: auto;
                overflow: hidden;
                position: relative;
                border-radius: 14px;
                border-style: groove;
                margin-top: 4px;
                height: 270px;
            }
        </style>
		
        <body onLoad="document.getElementById('mySelect').options[0].selected = 'selected';" style="text-align: center; overflow = hidden;touch-action: auto;">
		<div class="wrapper">
    		<div id="result"></div>
            <div id="msg" style="font-size:largest;">
    		<!-- you can set whatever style you want on this -->
    		...
			Loading, please wait...
    		</div>
    		<div id="body" style="display:none">
    			<p>Mes
    			<select id="mySelect" onchange="mySelectF()">
    				<option value="10" selected='selected' >10</option>
    				<option value="100" >100</option>
    				<option value="500">500</option>
    			</select>
    			dernières douches</p>
    		</div>
            <p id="result2" style="margin-top: 0px;margin-bottom: 0px;></p>
    		
    		<div style="display: none;">
    			<select id="myDouche" onchange="myDouche()">
    			</select>
    		</div>
		</div>
 <script type="text/javascript">
       setTimeout(function(){
          document.getElementById('msg').style.display="none";
         document.getElementById('body').style.display="inline";
      },1200);
	function str_pad_left(string,pad,length) {
		return (new Array(length+1).join(pad)+string).slice(-length);
	}

	var myHeaders = new Headers();
	myHeaders.append("Content-Type", "application/json");

// Contante de connection
	const urlParams = new URLSearchParams(window.location.search);
	const email = urlParams.get('email');
	const pwd = urlParams.get('pwd');
	const apikey = urlParams.get('apikey');

	var raw = JSON.stringify({
	  "email": email,
	  "password": pwd 
	});

	var requestOptions = {
	  method: 'POST',
	  headers: myHeaders,
	  body: raw,
	  redirect: 'follow'
	};

// Recherche du token de session
	fetch("https://api.hydrao.com/sessions", requestOptions)
	  .then(response => response.text())
	  .then( response => {
			//var resultValue = document.getElementById("result"); 
		//Récupération du Json
			var params = JSON.parse(response);
	    //Token
			accessToken   = params['access_token'];
		
	    // Récupération des valeurs de statistique
			var myHeaders2 = new Headers();
			myHeaders2.append("x-api-key", apikey); //"2AjNiWEYfq2cFvrJPv4hr2hSxI00aZAo8sCmmDbR");
			myHeaders2.append("Authorization", "Bearer " + accessToken );
			myHeaders.append("Content-Type", "application/json");

			var requestOptions2 = {
			  method: 'GET',
			  headers: myHeaders2,
			  redirect: 'follow'
			};

			fetch("https://api.hydrao.com/user-stats", requestOptions2)
			  .then(response2 => response2.text())
			  .then( response2 => { 
					var resultValue = document.getElementById("result");
					var userStat =  JSON.parse(response2);
					var time = userStat['average_duration']['value'];
					var minutes = Math.floor(time / 60);
					var seconds = time - minutes * 60;
					var finalTime = str_pad_left(minutes,'',2)+':'+str_pad_left(seconds,'0',2);
					var colorDuration = "green";
					var arrowDuration = '&#8595;'
					if (userStat['average_duration']['trend'] > 0) {
						colorDuration = "red";
					    arrowDuration = '&#8593;';
					} 
					var colorVolume = "green";
					var arrowVolume = '&#8595;'
					if (userStat['average_volume']['trend'] > 0) {
						colorVolume = "red";
						arrowVolume  = '&#8593;';
					} 
var vartxt =  "";
						vartxt +=  '<table align="center">';
						vartxt +=      "<tr>";
						vartxt +=          "<td>";
						vartxt +=              "<table>";
						vartxt +=                  "<tr>";
						vartxt +=                      "<td style='text-align:center; vertical-align:middle'>" + '<font size="+3">' + userStat['average_volume']['value'] + "</font></td>";
						vartxt +=                      "<td>";
						vartxt +=                          "<table>";
						vartxt +=                              "<tr>";
						vartxt +=                                  "<td>" + '<font color="' + colorVolume + '">' + arrowVolume + Math.abs( ( userStat['average_volume']['trend'] * 100).toFixed(1) ) + "%" + "</font></td>";
						vartxt +=                             " </tr>";
						vartxt +=                              "<tr>";
						vartxt +=                                  "<td>LITRES</td>";
						vartxt +=                              "</tr>";
						vartxt +=                          "</table>";
						vartxt +=                      "</td>";
						vartxt +=                  "</tr>";
						vartxt +=                  "<tr>";
						vartxt +=                      "<td style='text-align:center; vertical-align:middle'; colspan=" + '"2"' + ">Conso moyenne</td>";
						vartxt +=                  "</tr>";
						vartxt +=              "</table>";
						vartxt +=          "<td style='border-left-style:dotted; border-width: 1px '>";
						vartxt +=              "<table>";
						vartxt +=                  "<tr>";
						vartxt +=                      "<td style='text-align:center; vertical-align:middle'>" + '<font size="+3">' + finalTime + "</font></td>";
						vartxt +=                      "<td>";
						vartxt +=                          "<table>";
						vartxt +=                              "<tr>";
						vartxt +=                                  '<td><font color="'+ colorDuration + '">' + arrowDuration + Math.abs( (( userStat['average_duration']['trend']) * 100).toFixed(1)) + "%" + "</font></td>";
						vartxt +=                              "</tr>";
						vartxt +=                              "<tr>";
						vartxt +=                                  "<td>MIN</td>";
						vartxt +=                              "</tr>";
						vartxt +=                          "</table>";
						vartxt +=                      "</td>";
						vartxt +=                  "</tr>";
						vartxt +=                  "<tr>";
						vartxt +=                      "<td style='text-align:center; vertical-align:middle'; colspan=" + '"2"' + ">Durée moyenne</td>";
						vartxt +=                  "</tr>";
						vartxt +=              "</table>";
						vartxt +=          "<td>";
						vartxt +=     "</tr>";
						vartxt +=  "</table>";

					//Affichage
					resultValue.innerHTML = vartxt;
									  
					vartxtab = new Array();
					var mesdonnees;
					for(var i in userStat['savings'])
					{  	mesdonnees = '';
						mesdonnees +=  '<table align="center">';
						mesdonnees +=      "<tr>";
						mesdonnees +=          "<td>";
						
						mesdonnees +=  "<table>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          "<td width='30px'>";
						mesdonnees +=          '<font size="+2">'+userStat['savings'][i]['saved_money']+'</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=          "<td>";
						mesdonnees +=          "&#8364;";
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          '<td colspan="2">';
						mesdonnees +=          '<font size="-1">Economies</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=  "</table>";
						
						mesdonnees +=          "</td>";

						mesdonnees +=          "<td>";
						var volEauSave = userStat['savings'][i]['saved_volume'];
						var volEauSaveTxt = 'LITRES';
						if ( volEauSave > '99' ) {
						 volEauSave = ( volEauSave / 1000 ).toFixed(1);
						 volEauSaveTxt = 'M³';
						}
						mesdonnees +=  "<table>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          "<td width='30px'>";
						mesdonnees +=          '<font size="+2">'+ volEauSave +'</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=          "<td>";
						mesdonnees +=          volEauSaveTxt;
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          '<td colspan="2">';
						mesdonnees +=          '<font size="-1">Eco. Eau</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=  "</table>";
						
						mesdonnees +=          "</td>";
						
						mesdonnees +=          "<td>";
						
						mesdonnees +=  "<table>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          "<td width='30px'>";
						mesdonnees +=          '<font size="+2">'+ ( userStat['savings'][i]['saved_energy'] ).toFixed(0) +'</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=          "<td>";
						mesdonnees +=          "KWH";
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          '<td colspan="2">';
						mesdonnees +=          '<font size="-1">Eco Energie</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=  "</table>";
						
						mesdonnees +=          "</td>";
						
						mesdonnees +=      "</tr>";
						
						mesdonnees +=      "<tr>";
						mesdonnees +=          "<td>";
						
						mesdonnees +=  "<table>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          "<td width='30px'>";
						mesdonnees +=          '<font size="+2">'+userStat['savings'][i]['consumed_money']+'</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=          "<td>";
						mesdonnees +=          "\u20ac";
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          '<td colspan="2">';
						mesdonnees +=          '<font size="-1">Dépensés</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=  "</table>";
						
						mesdonnees +=          "</td>";

						mesdonnees +=          "<td>";
						
						var volEauConsumed = userStat['savings'][i]['consumed_volume'];
						var volEauConsumedTxt = 'LITRES';
						if ( volEauConsumed > '99' ) {
						 volEauConsumed = ( volEauConsumed / 1000 ).toFixed(1);
						 volEauConsumedTxt = 'M³';
						}
						
						mesdonnees +=  "<table>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          "<td width='30px'>";
						mesdonnees +=          '<font size="+2">'+ volEauConsumed +'</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=          "<td>";
						mesdonnees +=          volEauConsumedTxt;
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          '<td colspan="2">';
						mesdonnees +=          '<font size="-1">Conso Eau</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=  "</table>";
						
						mesdonnees +=          "</td>";
						
						mesdonnees +=          "<td>";
						
						mesdonnees +=  "<table>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          "<td width='30px'>";
						mesdonnees +=          '<font size="+2">'+ ( userStat['savings'][i]['consumed_energy'] ).toFixed(0)  +'</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=          '<td style="text-align: left;">';
						mesdonnees +=          "KWH";
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=      "<tr>";
						mesdonnees +=          '<td colspan="2">';
						mesdonnees +=          '<font size="-1">Conso Energie</font>';
						mesdonnees +=          "</td>";
						mesdonnees +=      "</tr>";
						mesdonnees +=  "</table>";
						
						mesdonnees +=          "</td>";
						
						mesdonnees +=      "</tr>";
						
						mesdonnees +=  "</table>";
		
						vartxtab[userStat['savings'][i]['nb_showers']] = mesdonnees;
					} //Fin de FOR
					
					
					var result2Display = document.getElementById("result2");
					result2Display.innerHTML = vartxtab['10'];
					}
				)

			} 
			)
	 // .then(result => console.log(result))
	  .catch(error => console.log('error', error));
  
mySelectF = function () {
			var mySelectAreaValue = document.getElementById("mySelect").value;
		    var result2Area = document.getElementById("result2");
			
			result2Area.innerHTML = vartxtab[mySelectAreaValue]; 
		}
			
	
 </script> 
 
    </body>
</html>
