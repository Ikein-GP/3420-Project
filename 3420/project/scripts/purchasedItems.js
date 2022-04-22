document.addEventListener("DOMContentLoaded", () => {
    
    let allTDs = document.querySelectorAll('td');
    allTDs.forEach((td) => {
        if (td.innerHTML.trim()=="Purchased") {
            
            td.parentNode.style.backgroundColor = "aliceblue";
        }
    });
});