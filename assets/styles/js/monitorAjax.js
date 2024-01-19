document.addEventListener('DOMContentLoaded', function() {
    // Attachez l'écouteur d'événements au conteneur parent qui est présent lors du chargement initial
    document.getElementById('monitorsContainer').addEventListener('click', function(event) {
        // Vérifiez si l'élément cliqué est un 'td' avec l'attribut 'data-type'
        if (event.target && event.target.matches('td[data-type]')) {
            editMode(event.target);
        }
        // Vérifiez si l'élément cliqué est un bouton avec la class .deletemonitorBtn'
        if (event.target && event.target.matches('.deleteMonitorBtn')) {
            const monitorId = event.target.getAttribute('data-monitor-id');
            deleteMonitor(monitorId);
        }
    });
});

/*****************************************************************************************/
/****************************** SHOW MONITOR LINES ***************************************/
/*****************************************************************************************/

document.querySelectorAll('.loadMonitorsBtn').forEach(button => {
    button.addEventListener('click', function() {
        var resourceId = this.dataset.resourceId;
        fetch('/monitor/' + resourceId)
            .then(response => response.text())
            .then(html => {
                document.getElementById('monitorsContainer').innerHTML = html;
            });
    });
});

/*****************************************************************************************/
/****************************** EDIT MONITOR LINE ****************************************/
/*****************************************************************************************/

function editMode(td) {
    let originalValue = td.textContent;
    let input = document.createElement('input');
    input.type = 'text';
    input.value = originalValue;
    input.classList.add("appearance-none", "border-0", "outline-none",
        "p-2", "bg-white", "rounded", "text-gray-700");

    console.log('input')

    input.onblur = function() {
        updateMonitor(td.parentElement.getAttribute('data-monitor-id'), td.getAttribute('data-type'), this.value);
        td.textContent = this.value;
    };

    input.onkeydown = function(e) {
        if (e.key === 'Enter') {
            this.blur();
        }
    };

    td.textContent = '';
    td.appendChild(input);
    input.focus();
}

function updateMonitor(monitorId, type, value) {
    // Envoyer la requête AJAX pour mettre à jour le moniteur
    let formData = new FormData();
    formData.append('type', type);
    formData.append('value', value);

    fetch('/monitor/edit/' + monitorId, {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                // Gérer l'échec de la mise à jour
            }
        });
}

/*****************************************************************************************/
/****************************** DELETE MONITOR LINE **************************************/
/*****************************************************************************************/

function deleteMonitor(monitorId) {
    fetch('/monitor/delete/' + monitorId, {
        method: 'DELETE'
    })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                console.log(data)
                document.getElementById('monitor-' + monitorId).remove();
                if(data.monitorCount === 0) {
                    document.getElementById('resource-container-' + data.resourceId).remove();
                    document.getElementById('monitorsContainer').remove();
                }
            } else {
                alert('Erreur de suppression: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur AJAX :', error);
        });
}