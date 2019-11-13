/*#######################################################################################################################*/
/*#Other#################################################################################################################*/
/*#######################################################################################################################*/
function focus() {
	document.getElementById('searchBar').focus();
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
function manageFolder(id) {
	if (document.getElementById(id + 'Folder').classList.contains('folderClosed')) {
		document.getElementById(id + 'Folder').classList.remove('folderClosed');
		document.getElementById(id + 'Folder').classList.add('folderOpened');
	}
	else {
		if (document.getElementById(id + 'Folder').classList.contains('folderOpened')) {
			document.getElementById(id + 'Folder').classList.remove('folderOpened');
			document.getElementById(id + 'Folder').classList.add('folderClosed');
			var childs = document.getElementById(id).children;
			for (var i = 0; i < childs.length; i++) {
				if (childs[i].classList.contains('folderOpened')) {
					document.getElementById(childs[i].id).classList.remove('folderOpened');
					document.getElementById(childs[i].id).classList.add('folderClosed');
				}
			}
		}
	}
}
/*#######################################################################################################################*/
/*#WeatherForecast#######################################################################################################*/
/*#######################################################################################################################*/
function showForecast() {
	document.getElementById('forecast').style.display = 'block';
}
/*#######################################################################################################################*/
/*#Programmer Quotes#####################################################################################################*/
/*#######################################################################################################################*/
function copyToClipboard() {
	var copyText = document.getElementById('quote');
	copyText.select();
	copyText.setSelectionRange(0, 99999);
	document.execCommand('copy');
	alert('Quote copied.');
}