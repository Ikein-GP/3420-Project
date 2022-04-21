let createForm = document.getElementById('create');
let fname = document.getElementById('fname');
let fnameError = fname.nextElementSibling;
let lname = document.getElementById('lname');
let lnameError = lname.nextElementSibling;
let username = document.getElementById('username');
let usernameError = username.nextElementSibling;
let pass = document.getElementById('password');
let passError = pass.nextElementSibling;
let newpass = document.getElementById('passwordNew');
let newpassre = document.getElementById('passwordNewRe')
let newpassreError = newpassre.nextElementSibling;

createForm.addEventListener("submit", (ev)=> 
{
    valid = true;
    if (fname.value == "")
    {
        valid = false;
        fnameError.classList.remove("hidden");
    }
    else
    {
        fnameError.classList.add("hidden");
    }

    if (lname.value == "")
    {
        valid = false;
        lnameError.classList.remove("hidden");
    }
    else
    {
        lnameError.classList.add("hidden");
    }

    if (username.value == "")
    {
        valid = false;
        usernameError.classList.remove("hidden");
    }
    else
    {
        usernameError.classList.add("hidden");
    }

    if(newpass.value != "" && pass.value == "")
    {
        valid = false;
        passError.classList.remove("hidden");
    }
    else
    {
        passError.classList.add("hidden");
    }

    if (newpassre.value != newpass.value)
    {
        valid = false;
        newpassreError.classList.remove("hidden");
    }
    else
    {
        newpassreError.classList.add("hidden");
    }

    if (valid == false)
    {
        ev.preventDefault();
    }
});