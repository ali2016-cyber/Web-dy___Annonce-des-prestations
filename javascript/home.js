// Attendre que le DOM soit entièrement chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', () => {
    // Récupération des éléments du DOM
    const searchInput = document.getElementById('search-input'); // Champ de recherche texte
    const categoryFilter = document.getElementById('category-filter'); // Sélecteur de catégorie
    const wilayaFilter = document.getElementById('wilaya-filter'); // Sélecteur de Wilaya
    const servicesGrid = document.querySelector('.services-grid'); // Conteneur des cartes de service
    const cards = document.querySelectorAll('.service-card'); // Toutes les cartes de service individuelles

    /**
     * Fonction principale de filtrage des services
     * Appliquée à chaque changement dans les filtres (recherche, catégorie, wilaya)
     */
    function filterServices() {
        // Récupération et normalisation des valeurs de filtre
        const searchTerm = searchInput.value.toLowerCase().trim();
        const categoryValue = categoryFilter.value;
        const wilayaValue = wilayaFilter.value;

        let hasVisibleCards = false; // Indicateur pour savoir si au moins une carte est affichée

        // Itération sur chaque carte de service pour vérifier si elle correspond aux critères
        cards.forEach(card => {
            // Récupération les métadonnées de la carte via les attributs data-*
            const cardCategory = card.dataset.category;
            const cardWilaya = card.dataset.wilaya;

            // Récupération du contenu textuel de la carte pour la recherche
            // (Comprend le titre, la description, le prix, et le nom du prestataire)
            const cardText = card.textContent.toLowerCase();

            // Vérification des correspondances
            const matchesSearch = cardText.includes(searchTerm); // Le texte contient-il le terme recherché ?

            // La catégorie correspond si le filtre est vide (tout voir) ou identique à la catégorie de la carte
            const matchesCategory = categoryValue === '' || cardCategory === categoryValue;

            // Idem pour la Wilaya
            const matchesWilaya = wilayaValue === '' || cardWilaya === wilayaValue;

            // Si tous les critères sont remplis, on affiche la carte
            if (matchesSearch && matchesCategory && matchesWilaya) {
                card.style.display = 'flex'; // 'flex' pour maintenir la mise en page de la carte
                hasVisibleCards = true;
            } else {
                card.style.display = 'none'; // Sinon, on la masque
            }
        });

        // Gestion du message "Aucun résultat"
        let noResultsMsg = document.getElementById('no-results-msg');

        if (!hasVisibleCards) {
            // S'il n'y a aucune carte visible et que le message n'existe pas encore, on le crée
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('p');
                noResultsMsg.id = 'no-results-msg';
                // Style pour que le message prenne toute la largeur de la grille
                noResultsMsg.style.gridColumn = '1/-1';
                noResultsMsg.style.textAlign = 'center';
                noResultsMsg.textContent = 'لا توجد خدمات مطابقة للبحث حالياً.'; // Message en arabe
                servicesGrid.appendChild(noResultsMsg);
            }
            noResultsMsg.style.display = 'block';
        } else {
            // S'il y a des résultats, on cache le message d'erreur s'il existe
            if (noResultsMsg) {
                noResultsMsg.style.display = 'none';
            }
        }
    }

    // Ajout des écouteurs d'événements pour le filtrage en temps réel
    searchInput.addEventListener('input', filterServices); // Au fur et à mesure de la frappe
    categoryFilter.addEventListener('change', filterServices); // Au changement de sélection
    wilayaFilter.addEventListener('change', filterServices); // Au changement de sélection
});
