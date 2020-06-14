let lastCheck = new Date();
let delay = 1000;

document.getElementById('blade-style-error').click(function() {
	console.log('CLICKED');
});

function httpGet(url) {
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.open('GET', url, false);
	xmlHttp.send(null);
	return xmlHttp;
}

function findStyleElement(id) {
	const styleElements = document.querySelectorAll('style');
	for (let i = 0; i < styleElements.length; i++) {
		let element = styleElements[i];
		if (element.getAttribute('style:id') == id) {
			return element;
		}
	}
}

function createStyleElement(id, style) {
	const head = document.querySelector('head');
	head.innerHTML += `<style style:id="${id}">${style}</style>`;
}

function removeStyleElement(id) {
	findStyleElement(id).remove();
}

function updateChanges(id, newStyle) {
	let element = findStyleElement(id);
	if (!element) {
		createStyleElement(id, newStyle);
		return;
	}
	element.innerHTML = newStyle;
}

function handleResponse(response) {
	try {
		changes = JSON.parse(response.responseText);
	} catch (e) {
		console.log(e);
		return;
	}

	for (let id in changes.updated) {
		updateChanges(id, changes.updated[id]);
	}
	for (let key in changes.removed) {
		removeStyleElement(changes.removed[key]);
	}
}

function shwoError(response) {
	let element = document.getElementById('blade-style-error');
	let wrapper = document.querySelector('#blade-style-error div');
	wrapper.innerHTML = `<iframe src="watch-styles/${lastCheck.getTime()}"></iframe>`;
	element.style.display = 'block';
}

let errorMessage = '';

function checkTime() {
	let response = httpGet(`watch-styles/${lastCheck.getTime()}`);
	console.log(response.status);
	if (response.status == 200) {
		this.handleResponse(response);
		errorMessage = '';
		document.getElementById('blade-style-error').style.display = 'none';
	} else if (response.status == 500) {
		console.log(
			errorMessage.length != response.responseText.length,
			errorMessage.length,
			response.responseText.length
		);
		if (errorMessage.length != response.responseText.length) {
			this.shwoError(response);
		}
		errorMessage = response.responseText;
	}

	lastCheck = new Date();
	setTimeout(function() {
		checkTime();
	}, delay);
}

checkTime();
