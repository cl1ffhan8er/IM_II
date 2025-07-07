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
        container.innerHTML = '<p>No locations selected yet.</p>';
    } else {
        selectedLocations.forEach((loc, index) => {
            const div = document.createElement('div');
            div.className = 'selected-location-item';
            
            const customIndicator = loc.isCustom ? ' <span class="custom-tag">(Custom)</span>' : '';
            
            div.innerHTML = `
                <span><b>${loc.name}</b>${customIndicator} - ${loc.address}</span>
                <button type="button" class="remove-btn" onclick="removeLocation(${index})">Remove</button>
            `;
            container.appendChild(div);
        });
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
