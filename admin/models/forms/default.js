window.addEvent('domready', function() {
	document.formvalidator.setHandler('start_date',
		function (value) {
			regex=/^[^0-9]+$/;
			return regex.start_date(value);
	});
});
window.addEvent('domready', function(){
	document.formvalidator.setHandler('end_date',
		function(value){
			regex=/^[^a-zA-Z]+$/;
			return regex.en_date(value);
	});
})