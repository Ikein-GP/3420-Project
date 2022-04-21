let wishlistForm = document.getElementById('addwishlist');
let title = document.getElementById('title');
let titleError = title.nextElementSibling;
let desc = document.getElementById('description');
let descError = desc.nextElementSibling;
let pass = document.getElementById('password');
let passError = pass.nextElementSibling;
let expiry = document.getElementById('expiry');
let expiryError = expiry.nextElementSibling;

wishlistForm.addEventListener("submit", (ev)=> 
{
    console.log(expiry.value);
    let valid = true;
    if (title.value == "")
    {
        valid = false;
        titleError.classList.remove("hidden");
    }
    else
    {
        titleError.classList.add("hidden");
    }

    if (desc.value == "")
    {
        valid = false;
        descError.classList.remove("hidden");
    }
    else
    {
        descError.classList.add("hidden");
    }

    if (pass.value == "")
    {
        valid = false;
        passError.classList.remove("hidden");
    }
    else
    {
        passError.classList.add("hidden");
    }

    if (expiry.value == "")
    {
        valid = false;
        expiryError.classList.remove("hidden");
    }
    else
    {
        expiryError.classList.add("hidden");
    }

    if (valid == false)
    {
        ev.preventDefault();
    }
});