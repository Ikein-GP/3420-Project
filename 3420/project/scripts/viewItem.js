let viewButton = document.getElementById("view");
let itemID = document.getElementById("item");
itemID = itemID.value;

viewButton.addEventListener("click", (ev)=> 
{
    modalWindow = window.open("Project_viewitem.php?itemID=" + itemID, "ModalPopUp", "width=640," + "height=480");
    modalWindow.focus();
    ev.preventDefault();
});