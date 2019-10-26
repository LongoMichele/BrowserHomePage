function parseNumber(n) {
	if(n < 10) {
		return n = "0" + n;
	}
	else {
		return n;
	}
}

function updateTime() {
var curtime = new Date();
	var hh = parseNumber(curtime.getHours());
	var mm = parseNumber(curtime.getMinutes());
	var clock = hh + ':' + mm;

	var dd = curtime.getDate();
	var mm = parseNumber(curtime.getMonth());
	var yyyy = curtime.getFullYear();

	var date = dd + '/' + mm + '/' + yyyy;

	document.getElementById('clock').innerHTML = clock;
	document.getElementById('date').innerHTML = date;

	setTimeout(updateTime, 100);
}