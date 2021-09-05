// Get the modal
var modal = document.getElementById("myModal");

// Get the menu link that opens the modal
var publishMenuItem = document.querySelector(".publish-link a");
var unpublishMenuItem = document.querySelector(".unpublish-link a");
var publishLink = publishMenuItem.getAttribute("href"); 
var unpublishLink = unpublishMenuItem.getAttribute("href"); 
var destinationLink;

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

publishMenuItem.onclick = function(e) {
  e.preventDefault();
  e.stopPropagation();
  destinationLink = publishLink;
  modal.style.display = "block";
}
unpublishMenuItem.onclick = function(e) {
  e.preventDefault();
  e.stopPropagation();
  destinationLink = unpublishLink;
  modal.style.display = "block";
}

var okButton = document.querySelector("#myModal .ok-button");
var cancelButton = document.querySelector("#myModal .cancel-button");
okButton.onclick = function() {
  window.location = destinationLink;
}
cancelButton.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}