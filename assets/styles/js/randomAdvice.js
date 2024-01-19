function showAdvice() {
    console.log("coucou");
    fetch('/advice')
        .then(response => response.text())
        .then(conseil => {
            document.getElementById('adviceText').innerText = conseil;
            var popup = document.getElementById('advicePopup');
            popup.style.display = 'block';
            popup.style.right = "-100%"; // Affichez la popup


            // Déclenchez l'animation de glissement
            setTimeout(function() {
                popup.style.right = '20px'; // Ajustez en fonction de la marge désirée
            }, 100); // Un délai court pour permettre au navigateur de rafraîchir
        });
}

// Fermer la popup
document.querySelector('.close-btn').addEventListener('click', function() {
    var popup = document.getElementById('advicePopup');

    // Animer la popup hors de l'écran
    popup.style.right = "-100%";
    setTimeout(function() {
        popup.style.display = 'none'; // Cachez la popup après l'animation
    }, 6000); // Ce délai doit correspondre à la durée de l'animation CSS
});


// Appeler `showAdvice` toutes les 10 minutes
setInterval(showAdvice, 600000); // 600000 millisecondes = 10 minutes

