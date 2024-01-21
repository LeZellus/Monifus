document.addEventListener('DOMContentLoaded', function() {
    const monitorsContainer = document.getElementById('monitorsContainer');
    monitorsContainer.addEventListener('click', handleMonitorContainerClick);

    document.querySelectorAll('.loadMonitorsBtn').forEach(button => {
        button.addEventListener('click', loadMonitors);
    });
});

function handleMonitorContainerClick(event) {
    if (event.target.matches('td[data-type]')) {
        enterEditMode(event.target);
    } else if (event.target.matches('.deleteMonitorBtn')) {
        const monitorId = event.target.getAttribute('data-monitor-id');
        confirmAndDeleteMonitor(monitorId);
    }
}

async function loadMonitors() {
    const resourceId = this.dataset.resourceId;
    try {
        document.getElementById('monitorsContainer').innerHTML = await fetchHTML('/monitor/' + resourceId);
    } catch (error) {
        console.error('Erreur de chargement des moniteurs:', error);
    }
}

function enterEditMode(td) {
    let originalValue = td.textContent;
    let input = createInputField(originalValue);
    attachInputEvents(input, td);

    td.textContent = '';
    td.appendChild(input);
    input.focus();
}

function createInputField(originalValue) {
    let input = document.createElement('input');
    input.type = 'text';
    input.value = originalValue;
    input.classList.add("appearance-none", "border-0", "outline-none",
        "p-2", "bg-white", "rounded", "text-gray-700");
    return input;
}

function attachInputEvents(input, td) {
    input.onblur = () => handleInputBlur(input, td);
    input.onkeydown = (e) => handleInputKeyDown(e, input);
}

function handleInputBlur(input, td) {
    const monitorId = td.parentElement.getAttribute('data-monitor-id');
    const type = td.getAttribute('data-type');
    const value = input.value;

    updateMonitor(monitorId, type, value);
    td.textContent = value;
}

function handleInputKeyDown(event, input) {
    if (event.key === 'Enter') {
        input.blur();
    }
}

async function updateMonitor(monitorId, type, value) {
    let formData = new FormData();
    formData.append('type', type);
    formData.append('value', value);

    try {
        const response = await fetch('/monitor/edit/' + monitorId, {
            method: 'POST',
            body: formData
        });
        const data = await response.json();
        if (!data.success) {
            // Gérer l'échec de la mise à jour ici
        }
    } catch (error) {
        console.error('Erreur lors de la mise à jour du moniteur:', error);
    }
}

function confirmAndDeleteMonitor(monitorId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer ce moniteur ?")) {
        deleteMonitor(monitorId);
    }
}

async function deleteMonitor(monitorId) {
    try {
        const response = await fetch('/monitor/delete/' + monitorId, { method: 'DELETE' });
        const data = await response.json();
        if (data.success) {
            document.getElementById('monitor-' + monitorId).remove();

            if (data.monitorCount === 0) {
                document.getElementById('resource-container-' + data.resourceId).remove();
                document.getElementById('monitorsContainer').remove();
            }
        } else {
            alert('Erreur de suppression: ' + data.message);
        }
    } catch (error) {
        console.error('Erreur AJAX :', error);
    }
}

async function fetchHTML(url, options = {}) {
    const response = await fetch(url, options);
    if (!response.ok) throw new Error('Réseau ou réponse serveur invalide.');
    return response.text();
}
