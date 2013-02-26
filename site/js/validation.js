jQuery.noConflict ();
//global vars
var form = $("#kamperenForm");
var kamp_type = $("#kamp_type");
var verblijf_type = $("#verblijf_type");

var message = $("#message");

function validateKamp_type(){
	//if it's NOT valid
	if(name.val().length < 0){
		name.addClass("error");
		nameInfo.text("You need to select at least 1");
		nameInfo.addClass("error");
		return false;
	}
	//if it's valid
	else{
		name.removeClass("error");
		nameInfo.text("What's your name?");
		nameInfo.removeClass("error");
		return true;
	}
function validateVerblijf_type(){
	//if it's NOT valid
	if(name.val().length < 0){
		name.addClass("error");
		nameInfo.text("You need to select at least 1");
		nameInfo.addClass("error");
		return false;
	}
	//if it's valid
	else{
		name.removeClass("error");
		nameInfo.text("What's your name?");
		nameInfo.removeClass("error");
		return true;
	}
//On Submitting
form.submit(function(){
	if(validateKamp_type() &amp;amp; validateVerblijf_type())
		return true
	else
		return false;
});