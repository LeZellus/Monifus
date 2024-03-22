document.addEventListener('DOMContentLoaded', function () {
    // Fonction pour nettoyer et extraire la valeur numérique
    const getNumericValue = (value) => {
        return Number(value.replace(/[^0-9.-]+/g, ""));
    };

    // Fonction pour convertir la date du format 'd-m-Y' en objet Date
    const convertToDate = (dateStr) => {
        const [day, month, year] = dateStr.split('-').map(part => parseInt(part, 10));
        return new Date(year, month - 1, day);
    };

    // Fonction pour extraire la valeur en fonction du type
    const getCellValue = (tr, idx, type) => {
        const cellValue = tr.children[idx].innerText || tr.children[idx].textContent;
        switch (type) {
            case 'number':
            case 'percent':
                return getNumericValue(cellValue);
            case 'date':
                return convertToDate(cellValue);
            default:
                return cellValue;
        }
    };

    // Comparer pour le tri
    const comparer = (idx, type, asc) => (a, b) => {
        if (asc === null) return 0; // Pas de tri

        const v1 = getCellValue(asc ? a : b, idx, type);
        const v2 = getCellValue(asc ? b : a, idx, type);

        if (v1 instanceof Date && v2 instanceof Date) {
            return v1 - v2;
        } else if (typeof v1 === 'number' && typeof v2 === 'number') {
            return v1 - v2;
        } else {
            return v1.toString().localeCompare(v2.toString());
        }
    };

    // Fonction pour mettre à jour la couleur de l'icône
    const updateIconColor = (th, asc) => {
        // Supprimer les couleurs précédentes
        const allIcons = document.querySelectorAll('.triable-icon');
        allIcons.forEach(icon => {
            icon.classList.remove('text-green-400', 'text-red-400');
            icon.classList.add('text-gray-300', 'hover:text-gray-400', 'dark:text-gray-600', 'dark:hover:text-gray-500');
        });

        if (asc === null) {
            // Réinitialiser la couleur de tous les th
            document.querySelectorAll('th.triable svg').forEach(icon => {
                icon.classList.remove('text-red-400', 'text-green-400');
                icon.classList.add('text-gray-300', 'hover:text-gray-400', 'dark:text-gray-600', 'dark:hover:text-gray-500');
            });
            return;
        }

        // Obtenir l'icône du th actuel
        const icon = th.querySelector('svg');
        // Ajouter la nouvelle couleur
        icon.classList.remove('text-gray-300', 'hover:text-gray-400', 'dark:text-gray-600', 'dark:hover:text-gray-500');
        icon.classList.add(asc ? 'text-green-400' : 'text-red-400');
    };

    // Sauvegarder l'ordre initial des lignes pour chaque tableau
    const initialRows = new Map();
    document.querySelectorAll('table').forEach(table => {
        initialRows.set(table, Array.from(table.querySelectorAll('tbody tr')));
    });

    // Ajout de l'écouteur sur chaque th triable
    document.querySelectorAll('th.triable').forEach(th => {
        // Initialiser un compteur de clics pour chaque en-tête
        th.clickCount = 0;

        th.addEventListener('click', function () {
            const table = th.closest('table');
            const tbody = table.querySelector('tbody');
            const idx = Array.from(th.parentNode.children).indexOf(th);
            const type = th.getAttribute('data-type');

            // Incrémenter le compteur et déterminer l'action en fonction du nombre de clics
            th.clickCount = (th.clickCount + 1) % 3; // 0: état initial, 1: tri descendant, 2: tri ascendant

            if (th.clickCount === 0) {
                // Troisième clic: retour à l'état initial
                initialRows.get(table).forEach(tr => tbody.appendChild(tr));
            } else {
                // Premier et deuxième clics: trier
                const asc = th.clickCount === 2;
                Array.from(tbody.querySelectorAll('tr'))
                    .sort(comparer(idx, type, asc))
                    .forEach(tr => tbody.appendChild(tr));
            }

            updateIconColor(th, th.clickCount === 2 ? true : th.clickCount === 1 ? false : null);
        });
    });
});
