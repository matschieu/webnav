function resetField(fieldId) {
	document.getElementById(fieldId).value = "";
	filter(value = "");
}

function filter(value = "") {
	var elements = document.getElementsByClassName("filename");
	console.log(elements);
	for (let element of elements) {
		var folder = element.closest(".folder");
		var file = element.closest(".file");
		var parent = null;

		if (folder != null) {
			parent = folder;
		} else if (file != null) {
			parent = file;
		} else {
			return;
		}

		parent.style.display = !element.innerText.includes(value) ? "none" : "";
	};
}

var treeLoaded = false;

function loadTree() {
	if (!treeLoaded) {
		fetch(location.href.substring(0, location.href.lastIndexOf("/") + 1) + 'tree.php')
			.then(response => response.text())
			.then(html => document.getElementById('folderTreeModalContent').innerHTML = html)
			.then(treeLoaded = true)
			.catch(error => console.error('Error:', error));
	}
}
