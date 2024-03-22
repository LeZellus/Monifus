function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}

function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for(let i=0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

document.addEventListener('DOMContentLoaded', function() {

    try {
        const popup = document.getElementById("popup");
        const closeButtons = document.querySelectorAll(".popup-enter-close");

        if(!popup) {
            console.log("Element popup non trouvé");
            return;
        }

        // Ouvrir le popup si le cookie n'existe pas
        function openPopup() {
            console.log("Cookie value:", getCookie("popupAccepted"));
            if(getCookie("popupAccepted") !== "true") {

                popup.classList.remove("hidden");
                popup.classList.add('flex');
            }
        }

        // Fermer le popup et créer un cookie
        function closePopup() {
            setCookie("popupAccepted", "true", 7); // Le cookie expire après 7 jours
            popup.classList.add("hidden");
            popup.classList.remove("flex");
        }

        closeButtons.forEach(function(button) {
            button.addEventListener("click", closePopup);
        });

        openPopup();

    } catch (error) {
        console.error("Une erreur est survenu:", error);
    }
});


