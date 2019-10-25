let colors;
let oldDate;

function parseNumber(n) {
	if(n < 10) {
		return n = "0" + n;
	}
	else {
		return n;
	}
}

function randomizeColors(s) {
	var color = Math.floor(Math.random() * colors.length);
	s = '<span style="color: ' + colors[color] + '">' + s + '</span>';
	colors.splice(color, 1);

	return s;
}

function updateTime() {
	colors = ['#FF9AA2', '#FFB7B2', '#FFDAC1', '#E2F0CB', '#B5EAD7', '#C7CEEA', '#ACE7FF'];
	var curtime = new Date();
	
	if(oldDate != curtime.getMinutes()) {
		var hh = randomizeColors(parseNumber(curtime.getHours()));
		var mm = randomizeColors(parseNumber(curtime.getMinutes()));
		var clock = hh + randomizeColors(':') + mm;

		var dd = randomizeColors(curtime.getDate());
		var mm = randomizeColors(parseNumber(curtime.getMonth()));
		var yyyy = randomizeColors(curtime.getFullYear());

		var temp = randomizeColors('/');
		var date = randomizeColors(dd + temp + mm + temp + yyyy);

		document.getElementById('clock').innerHTML = clock;
		document.getElementById('date').innerHTML = date;
		oldDate = curtime.getMinutes();
	}

	setTimeout(updateTime, 100);
}