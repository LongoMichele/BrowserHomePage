function showChild(id) {
	var childs = document.getElementById(id).children;
	for (var i = 0; i < childs.length; i++) {
		childs[i].style.display = 'block';
	}
}