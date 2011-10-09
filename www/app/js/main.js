var nick    = $("#nickname");
var pass1   = $("#password");
var pass2   = $("#check_password");
var email   = $("#email");

// for email validation
var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;

// blur
nick.blur(validate_nickname);
pass1.blur(validate_password);
pass2.blur(validate_password_match);
email.blur(validate_email);
            
// keyup
nick.keyup(validate_nickname);
pass1.keyup(validate_password);
pass2.keyup(validate_password_match);
email.keyup(validate_email);

function validate_nickname()
{
    if(nick.val().length < 3)
    {  
        $("span.nickname").text("Nickname must be at least 3 characters long.").removeClass('hide');
        nick.addClass("error");
        return false;  
    }
    else
    {
        if ( $("span.nickname").hasClass("hide") == false ) { $("span.nickname").addClass('hide'); }
        if ( $("#nickname").hasClass("error") == true ) { $("#nickname").removeClass("error"); }
        return true;
    }
}

function validate_password()
{
    if (pass1.val().length < 6)
    {
        $("span.password").text("Password must be at least 6 characters long.").removeClass('hide');
        pass1.addClass("error");
        return false;
    }
    else
    {
        if ( $("span.password").hasClass("hide") == false ) { $("span.password").addClass('hide'); }
        if ( $("#password").hasClass("error") == true ) { $("#password").removeClass("error"); }
        return true;
    }
}

function validate_password_match()
{
    if (pass2.val().length < 6)
    {
        $("span.check_password").text("Password must be at least 6 characters long.").removeClass('hide');
        pass2.addClass("error");
        return false;
    }
    else if ( pass1.val() != pass2.val() )
    {
        $("span.check_password").text("The two passwords are not identical.").removeClass('hide');
        pass2.addClass("error");
        return false;  
    }
    else
    {
        if ( $("span.check_password").hasClass("hide") == false ) { $("span.check_password").addClass('hide'); }
        if ( $("#check_password").hasClass("error") == true ) { $("#check_password").removeClass("error"); }
        return true;
    }
}

function validate_email()
{
    if(filter.test(email.val()) == false)
    {
        $("span.email").text("Invalid email address").removeClass('hide');
        email.addClass("error");
        return false;
    }
    else
    {
        if ( $("span.email").hasClass("hide") == false ) { $("span.email").addClass('hide'); }
        if ( $("#email").hasClass("error") == true ) { $("#email").removeClass("error"); }
        return true;
    }
}


$(document).ready(function() { 
	Cufon.replace('.title', { fontFamily: 'Harabara Bold', hover: true });
	
});