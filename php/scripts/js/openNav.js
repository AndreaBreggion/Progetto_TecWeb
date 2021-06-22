window.onload=function jsAttivo() {
    document.getElementById("openImage").classList.add("jsActive");
    document.getElementById("closeImage").classList.add("jsActive");
    document.getElementById("menuContainer").classList.add("jsActive");
    if (document.getElementById("returnButton")) {
        window.onscroll = function () {
            scrollFunction()
        };
    }
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
        if (document.body.scrollTop < 150 || document.documentElement.scrollTop < 150) {
            //document.getElementById('returnButton').classList.add('Fade');
            document.getElementById('returnButton').style.display = "none";
        } else {
            //document.getElementById('returnButton').classList.remove('Fade');
            document.getElementById('returnButton').style.display = "block";
        }
    }

    function topButton() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

