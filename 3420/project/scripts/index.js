let publicButton = document.getElementById("public-view");
let deleteButton = document.getElementById("delete");

publicButton.addEventListener("click", (ev)=> 
{
    navigator.clipboard.writeText(publicButton.value);
    alert("Copied Public View link to clipboard!");
});