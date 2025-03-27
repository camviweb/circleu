$(document).ready(function () {
    // Liste des catégories et buts sélectionnés
    let selectedCategories = [];
    let selectedPurposes = [];

    // Fonction pour filtrer les posts selon les critères sélectionnés
    function filterPosts() {
        $(".post-card").each(function () {
            let postCategory = $(this).data("category");
            let postPurpose = $(this).data("purpose");

            // Vérifie si le post appartient à au moins une catégorie et à au moins un but sélectionnés
            let categoryMatch = selectedCategories.length === 0 || selectedCategories.includes(postCategory);
            let purposeMatch = selectedPurposes.length === 0 || selectedPurposes.includes(postPurpose);

            if (categoryMatch && purposeMatch) {
                $(this).show();  // Afficher le post si les critères correspondent
            } else {
                $(this).hide();  // Sinon, cacher le post
            }
        });
    }

    // Filtre pour les catégories
    $(".category-filter").on("click", function () {
        let category = $(this).data("category");

        if (category === "") {
            // Si "Tous catégories" est cliqué, réinitialiser les catégories sélectionnées
            selectedCategories = [];
        } else {
            // Ajouter ou retirer la catégorie de la liste des catégories sélectionnées
            if (selectedCategories.includes(category)) {
                selectedCategories = selectedCategories.filter(item => item !== category);  // Retirer la catégorie
            } else {
                selectedCategories.push(category);  // Ajouter la catégorie
            }
        }

        // Appliquer le filtrage
        filterPosts();

        // Mettre à jour l'apparence des boutons
        updateButtonStates("category");
    });

    // Filtre pour les buts
    $(".purpose-filter").on("click", function () {
        let purpose = $(this).data("purpose");

        if (purpose === "") {
            // Si "Tous buts" est cliqué, réinitialiser les buts sélectionnés
            selectedPurposes = [];
        } else {
            // Ajouter ou retirer le but de la liste des buts sélectionnés
            if (selectedPurposes.includes(purpose)) {
                selectedPurposes = selectedPurposes.filter(item => item !== purpose);  // Retirer le but
            } else {
                selectedPurposes.push(purpose);  // Ajouter le but
            }
        }

        // Appliquer le filtrage
        filterPosts();

        // Mettre à jour l'apparence des boutons
        updateButtonStates("purpose");
    });

    // Mettre à jour les boutons pour refléter l'état des filtres sélectionnés
    function updateButtonStates(type) {
        if (type === "category") {
            $(".category-filter").each(function () {
                let category = $(this).data("category");
                if (category === "" && selectedCategories.length === 0) {
                    $(this).addClass("btn-primary").removeClass("btn-light");
                } else if (selectedCategories.includes(category)) {
                    $(this).addClass("btn-primary").removeClass("btn-light");
                } else {
                    $(this).addClass("btn-light").removeClass("btn-primary");
                }
            });
        }

        if (type === "purpose") {
            $(".purpose-filter").each(function () {
                let purpose = $(this).data("purpose");
                if (purpose === "" && selectedPurposes.length === 0) {
                    $(this).addClass("btn-primary").removeClass("btn-light");
                } else if (selectedPurposes.includes(purpose)) {
                    $(this).addClass("btn-primary").removeClass("btn-light");
                } else {
                    $(this).addClass("btn-light").removeClass("btn-primary");
                }
            });
        }
    }
});
