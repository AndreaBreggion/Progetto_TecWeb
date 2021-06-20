window.onload=function jsAttivo() {
    document.getElementById("openImage").classList.add("jsActive");
    document.getElementById("closeImage").classList.add("jsActive");
    document.getElementById("menuContainer").classList.add("jsActive");

}

function openNav(){
    var menu = document.getElementById("menuContainer");

    if (menu.className !== "jsActive openMenu") {
        menu.classList.add("openMenu");
    } else {
        menu.classList.remove("openMenu");
    }
}