$(document).ready(function() {

	const elements = document.querySelectorAll('button');
	elements.forEach(el => {
	if (el.textContent.includes("Block disabled")) {
		el.style.display = "none";
	}
	});

});
