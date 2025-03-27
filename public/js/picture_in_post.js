document.addEventListener("DOMContentLoaded", function () {
    const images = document.querySelectorAll(".post-image");
    const overlay = document.createElement("div");
    overlay.id = "image-overlay";
    overlay.innerHTML = '<span class="close-overlay">&times;</span><img>';
    document.body.appendChild(overlay);

    const overlayImage = overlay.querySelector("img");
    const closeOverlay = overlay.querySelector(".close-overlay");

    function disableScroll() {
        document.body.style.overflow = "hidden"; // Désactive le scroll
    }

    function enableScroll() {
        document.body.style.overflow = ""; // Réactive le scroll
    }

    images.forEach(img => {
        img.addEventListener("click", function () {
            overlayImage.src = img.src;
            overlay.style.display = "flex";
            disableScroll();
        });
    });

    closeOverlay.addEventListener("click", function () {
        overlay.style.display = "none";
        enableScroll();
    });

    overlay.addEventListener("click", function (e) {
        if (e.target === overlay) {
            overlay.style.display = "none";
            enableScroll();
        }
    });
});

function redirectToPost(event, url) {
    // Vérifie si l'élément cliqué est un bouton, un lien, un champ de commentaire ou son label ou son image
    const isButtonOrLink = event.target.closest('button') || event.target.closest('a');
    const isCommentFieldOrLabel = event.target.closest('label') || event.target.closest('textarea');
    const isImage = event.target.closest('img');

    // Si l'élément cliqué est un bouton, un lien, un champ de commentaire ou un label, on arrête l'événement
    if (isButtonOrLink || isCommentFieldOrLabel || isImage) {
        event.stopPropagation(); // Empêche l'événement de remonter et de déclencher la redirection
        return;
    }

    // Sinon, on redirige vers l'URL du post
    window.location.href = url;
}
