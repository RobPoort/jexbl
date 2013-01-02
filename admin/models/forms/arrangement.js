window.addEvent('domready', function() {
	document.formvalidator.setHandler('name',
		function (value) {
			regex=/^[^0-9]+$/;
			return regex.test(value);
	});
});
window.addEvent('domready', function(){
	document.formvalidator.setHandler('price',
		function(value){
			regex=/^[^a-zA-Z]+$/;
			return regex.test(value);
	});
})