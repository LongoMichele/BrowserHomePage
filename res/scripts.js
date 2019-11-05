/*#######################################################################################################################*/
/*#Clock#################################################################################################################*/
/*#######################################################################################################################*/
function parseNumber(n) {
	if (n < 10) {
		return n = '0' + n;
	}
	else {
		return n;
	}
}

function startClock(separator) {
	var curtime = new Date();
	var hh = parseNumber(curtime.getHours());
	var mm = parseNumber(curtime.getMinutes());
	if (separator == ' ') {
		separator = ':';
	}
	else {
		separator = ' ';
	}
	var clock = hh + separator + mm;

	var dd = curtime.getDate();
	var mm = parseNumber(curtime.getMonth());
	var yyyy = curtime.getFullYear();

	var date = dd + '/' + mm + '/' + yyyy;

	document.getElementById('clock').innerHTML = clock;
	document.getElementById('date').innerHTML = date;

	setTimeout(startClock, 500, separator);
}
/*#######################################################################################################################*/
/*#QuickLinks############################################################################################################*/
/*#######################################################################################################################*/
function showChild(id) {
	document.getElementById(id + '1').classList.remove('folderClosed');
	document.getElementById(id + '1').classList.add('folderOpened');
	var childs = document.getElementById(id + '2').children;
	for (var i = 0; i < childs.length; i++) {
		childs[i].style.display = 'block';
	}
}
/*#######################################################################################################################*/
/*#WeatherForecast#######################################################################################################*/
/*#######################################################################################################################*/
function showForecast() {
	document.getElementById('forecast').style.display = 'block';
}
/*#######################################################################################################################*/
/*#Other#################################################################################################################*/
/*#######################################################################################################################*/
function focus() {
	document.getElementById('searchBar').focus();
}