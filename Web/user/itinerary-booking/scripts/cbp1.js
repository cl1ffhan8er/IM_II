const STORAGE_KEY = 'customItineraryLocations';
let selectedLocations = [];

function addLocation(name, address) {
    const isDuplicate = selectedLocations.some(loc => loc.name === name && loc.address === address);
    if (isDuplicate) {
        alert("Location is already added.");
        return;
    }
    selectedLocations.push({ name, address, isCustom: false });
    updateSelectedLocationsDisplay();
}

function addCustomLocation() {
    const nameInput = document.getElementById('custom-name');
    const addressInput = document.getElementById('custom-address');
    const name = nameInput.value.trim();
    const address = addressInput.value.trim();

    if (!name || !address) {
        alert("Please enter both a name and an address for the custom location.");
        return;
    }

    const isDuplicate = selectedLocations.some(loc => loc.name === name && loc.address === address);
    if (isDuplicate) {
        alert("Location is already added.");
        return;
    }

    selectedLocations.push({ name, address, isCustom: true });
    updateSelectedLocationsDisplay();

    nameInput.value = '';
    addressInput.value = '';
}

function removeLocation(index) {
    selectedLocations.splice(index, 1);
    updateSelectedLocationsDisplay();
}

function updateSelectedLocationsDisplay() {
    const container = document.getElementById('selected-locations');
    if (!container) return;

    container.innerHTML = '';

    if (selectedLocations.length === 0) {
        container.innerHTML = '<p class="no-locations-msg">No locations selected yet.</p>';
    } else {
        selectedLocations.forEach((loc, index) => {
            const div = document.createElement('div');
            div.className = 'selected-location-item' + (loc.isCustom ? ' custom-location' : '');
            div.setAttribute('data-index', index);

            const customIndicator = loc.isCustom ? ' <span class="custom-tag"></span>' : '';

            div.innerHTML = `
                <img src="../svg-icons/drag.svg" alt="Drag" class="drag-handle" draggable="true">
                <p class="location-text"><b>${loc.name}</b>${customIndicator} ${loc.address}</p>
                <button type="button" class="remove-btn" onclick="removeLocation(${index})">
                    <img src="../svg-icons/cancel.svg" alt="Remove" class="remove-icon">
                </button>
            `;

            container.appendChild(div);
        });
        setupDragEvents();
    }
    saveToStorage();
}

function findLocation() {
    const filter = document.getElementById('searchLocation').value.toUpperCase();
    const locations = document.querySelectorAll('.location-selector .location');

    locations.forEach(locationDiv => {
        const name = locationDiv.dataset.name.toUpperCase();
        const address = locationDiv.dataset.address.toUpperCase();
        if (name.includes(filter) || address.includes(filter)) {
            locationDiv.style.display = "";
        } else {
            locationDiv.style.display = "none";
        }
    });
}

function saveToStorage() {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(selectedLocations));
}

function logout() {
    localStorage.removeItem(STORAGE_KEY);
    window.location.href = 'clear-booking-session.php?redirect_to=login/logout.php';
}

document.addEventListener('DOMContentLoaded', () => {
    const savedData = localStorage.getItem(STORAGE_KEY);
    if (savedData) {
        try {
            selectedLocations = JSON.parse(savedData);
        } catch (e) {
            console.error("Error parsing saved locations:", e);
            selectedLocations = [];
        }
    }

    updateSelectedLocationsDisplay();

    const bookingForm = document.getElementById('bookingform');
    if (bookingForm) {
        bookingForm.addEventListener('submit', () => {
            const hiddenInput = document.getElementById('hidden-locations');
            hiddenInput.value = JSON.stringify(selectedLocations);
            localStorage.removeItem(STORAGE_KEY);
        });
    }
});

function setupDragEvents() {
    const items = document.querySelectorAll('.selected-location-item');

    let draggedIndex = null;

    items.forEach(item => {
        const handle = item.querySelector('.drag-handle');

        // Allow drag only from handle
        handle.addEventListener('dragstart', (e) => {
            draggedIndex = parseInt(item.dataset.index);
            item.classList.add('dragging');
            e.dataTransfer.effectAllowed = 'move';
        });

        handle.addEventListener('dragend', () => {
            item.classList.remove('dragging');
        });

        item.addEventListener('dragover', (e) => {
            e.preventDefault();
            item.classList.add('drag-over');
        });

        item.addEventListener('dragleave', () => {
            item.classList.remove('drag-over');
        });

        item.addEventListener('drop', () => {
            const dropIndex = parseInt(item.dataset.index);
            if (draggedIndex !== null && draggedIndex !== dropIndex) {
                const moved = selectedLocations.splice(draggedIndex, 1)[0];
                selectedLocations.splice(dropIndex, 0, moved);
                updateSelectedLocationsDisplay();
            }
        });
    });
}
