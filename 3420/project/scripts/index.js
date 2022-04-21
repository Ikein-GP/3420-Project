document.addEventListener("DOMContentLoaded", () => {
    let publicButtons = document.querySelectorAll('#viewlists li:nth-child(6) > button:nth-child(1)');
    console.log(publicButtons);
    let deleteButton = document.getElementById("delete");
    
    publicButtons.forEach((publicButton) => {
        publicButton.addEventListener("click", (ev) => {
            navigator.clipboard.writeText(publicButton.value);
            alert("Copied Public View link to clipboard!");
        });
    });

});