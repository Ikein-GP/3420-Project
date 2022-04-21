let createForm = document.getElementById('create');
let fname = document.getElementById('fname');
let fnameError = fname.nextElementSibling;
let lname = document.getElementById('lname');
let lnameError = lname.nextElementSibling;
let email = document.getElementById('email');
let emailError = email.nextElementSibling;
let username = document.getElementById('username');
let usernameError = username.nextElementSibling;
let pass = document.getElementById('password');
let passError = pass.nextElementSibling;
let repass = document.getElementById('passwordre');
let repassError = repass.nextElementSibling;
let agree = document.getElementById('agree');
let agreeError = agree.nextElementSibling.nextElementSibling;
const emailIsValid = (string) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(string);

createForm.addEventListener("submit", (ev)=>
{
    console.log(lname.value);
    let valid = true;
    if(fname.value == "")
    {
        valid = false;
        fnameError.classList.remove("hidden");
    }
    else
    {
        fnameError.classList.add("hidden");
    }

    if(lname.value == "")
    {
        valid = false;
        lnameError.classList.remove("hidden");
    }
    else
    {
        lnameError.classList.add("hidden");
    }

    if (emailIsValid(email.value))
    {
        emailError.classList.add("hidden");
    }
    else
    {
        valid = false;
        emailError.classList.remove("hidden");
    }

    if(username.value == "")
    {
        valid = false;
        usernameError.classList.remove("hidden");
    }
    else
    {
        usernameError.classList.add("hidden");
    }

    if(pass.value == "")
    {
        valid = false;
        passError.classList.remove("hidden");
    }
    else
    {
        passError.classList.add("hidden");
    }

    if(repass.value != pass.value)
    {
        valid = false;
        repassError.classList.remove("hidden");
    }
    else
    {
        repassError.classList.add("hidden");
    }

    if(!agree.checked)
    {
        valid = false;
        agreeError.classList.remove("hidden");
    }
    else
    {
        agreeError.classList.add("hidden");
    }

    if (valid == false)
    {
        ev.preventDefault();
    }
});