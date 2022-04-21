let loginForm = document.getElementById('login');
let username = document.getElementById('username');
let error = document.getElementById('error');
let password = document.getElementById('password');

loginForm.addEventListener("submit", (ev)=> 
{
    let valid = true;
    if(username.value == "")
    {
        valid = false;
    }
    if(password.value == "")
    {
        valid = false;
    }
    if(valid == false)
    {
        error.classList.remove("hidden");
        ev.preventDefault();
    }
    else
    {
        error.classList.add("hidden");
    }
});