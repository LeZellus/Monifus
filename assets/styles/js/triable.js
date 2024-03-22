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

    // Ajout de l'écouteur sur chaque th triable
    document.querySelectorAll('th.triable').forEach(th => th.addEventListener('click', function () {
        const table = th.closest('table');
        const tbody = table.querySelector('tbody');
        const idx = Array.from(th.parentNode.children).indexOf(th);
        const type = th.getAttribute('data-type');
        Array.from(tbody.querySelectorAll('tr'))
            .sort(comparer(idx, type, this.asc = !this.asc))
            .forEach(tr => tbody.appendChild(tr));
    }));
});