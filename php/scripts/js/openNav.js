function openNav(){
    var menu = document.getElementById("menuContainer");
    var path=document.getElementById("menuImage").src;
    path=path.replace("hamburger_icon.png","");
    path=path.replace("close_icon.png","");
    var closeIcon=path+("close_icon.png");
    var hamburgerIcon=path+("hamburger_icon.png");

    if (menu.className !== "openMenu") {
        document.getElementById("menuImage").src=closeIcon;
        menu.classList.add("openMenu");
    } else {
        menu.classList.remove("openMenu");
        document.getElementById("menuImage").src=hamburgerIcon;
    }
}