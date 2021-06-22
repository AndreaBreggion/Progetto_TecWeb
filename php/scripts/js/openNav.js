window.onload=function jsAttivo() {
    document.getElementById("openImage").classList.add("jsActive");
    document.getElementById("closeImage").classList.add("jsActive");
    document.getElementById("menuContainer").classList.add("jsActive");
    window.onscroll = function() {scrollFunction()};
}

function openNav(){
    var menu = document.getElementById("menuContainer");

    if (menu.className !== "jsActive openMenu") {
        menu.classList.add("openMenu");
    } else {
        menu.classList.remove("openMenu");
    }
}

function scrollFunction() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
    //document.getElementById('returnButton').classList.add('Fade');
    document.getElementById('returnButton').style.display = "block";
    } else {
    //document.getElementById('returnButton').classList.remove('Fade');
    document.getElementById('returnButton').style.display = "none";
    }
}

    function topButton() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

