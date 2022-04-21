let deleteButton = document.getElementById("delete");

deleteButton.addEventListener("click", (ev)=> 
{
    let status = confirm("Are you sure you want to delete this wishlist?");
    if (!status)
    {
        ev.preventDefault();
        window.location.replace("https://loki.trentu.ca/~gregoryprouty/3420/project/index.php");
    }
});