function showChild(id) {
	document.getElementById(id).classList.remove('folderClosed');
	document.getElementById(id).classList.add('folderOpened');
	var childs = document.getElementById(id).children;
	for (var i = 0; i < childs.length; i++) {
		childs[i].style.display = 'block';
	}
}