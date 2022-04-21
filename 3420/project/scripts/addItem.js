let addItemForm = document.getElementById('itemadd');
let title = document.getElementById("title");
let titleError = title.nextElementSibling;

addItemForm.addEventListener("submit", (ev) => 
{
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

    if (valid == false)
    {
        ev.preventDefault();
    }
});