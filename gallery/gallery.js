// Parks Gallery JavaScript - Working Version
console.log("Gallery script loaded!");

let allParks = [];
let displayedParks = [];
const parkDataCache = new Map();

const numberOrDefault = (value, fallback = 0) =>
  typeof value === "number" && !Number.isNaN(value) ? value : fallback;

const escapeHtml = (value = "") =>
  String(value)
    .replace(/&/g, "&amp;")
    .replace(/</g, "&lt;")
    .replace(/>/g, "&gt;")
    .replace(/"/g, "&quot;")
    .replace(/'/g, "&#39;");

// Fetch and display parks
async function loadParks() {
  const grid = document.getElementById("parksGrid");

  try {
    grid.innerHTML = '<div class="loading">Loading parks from API...</div>';
    console.log("Fetching from ../parks_api_proxy.php");

    const response = await fetch("../parks_api_proxy.php");
    console.log("Response status:", response.status);

    if (!response.ok) {
      throw new Error("Failed to fetch: " + response.status);
    }

    const data = await response.json();
    console.log("Data received:", data.total, "parks");

    if (!data.success || !data.data) {
      throw new Error("Invalid API response");
    }

    allParks = data.data;
    displayedParks = [...allParks];
    renderParks(displayedParks);
  } catch (error) {
    console.error("Error:", error);
    grid.innerHTML = `<div class="loading" style="color: red;">Error: ${error.message}</div>`;
  }
}

// Render park cards
function renderParks(parks) {
  console.log("Rendering", parks.length, "parks");
  const grid = document.getElementById("parksGrid");

  if (!grid) {
    console.error("Grid not found!");
    return;
  }

  if (parks.length === 0) {
    grid.innerHTML = '<div class="loading">No parks found.</div>';
    return;
  }

  parkDataCache.clear();

  const html = parks
    .map((park, index) => {
      const parkKey = String(
        park?.id || park?.parkCode || park?.code || park?.fullName || index
      );
      parkDataCache.set(parkKey, park);

      const imageUrl =
        park?.images && park.images.length > 0
          ? park.images[0].url
          : "https://via.placeholder.com/800x600?text=National+Park";
      const safeImageUrl = imageUrl.replace(/'/g, "%27");
      const stateLabel = park?.states?.length ? park.states : "Unknown";

      const parkNameSafe = escapeHtml(park?.fullName || "National Park");
      const stateLabelSafe = escapeHtml(stateLabel);
      const parkKeySafe = escapeHtml(parkKey);

      return `
        <div class="park-card" data-park-key="${parkKeySafe}">
          <div class="park-card-image" style="background-image: url('${safeImageUrl}')">
            <div class="park-card-overlay">
              <h3>${parkNameSafe}</h3>
              <p class="park-card-location">📍 ${stateLabelSafe}</p>
            </div>
          </div>
        </div>
      `;
    })
    .join("");

  grid.innerHTML = html;
  attachEvents();
}

// Attach all event handlers
function attachEvents() {
  document.querySelectorAll(".park-card").forEach((card) => {
    card.addEventListener("click", () => {
      const parkKey = card.dataset.parkKey;
      if (!parkKey) {
        console.warn("Missing park key for card");
        return;
      }

      const parkData = parkDataCache.get(parkKey);
      if (!parkData) {
        console.error("Park data not found for key", parkKey);
        return;
      }

      openModal(parkData);
    });
  });
}

// Open modal with park details
function openModal(park) {
  const modal = document.getElementById("modalOverlay");
  const modalContent = document.getElementById("modalContent");
  if (!modal || !modalContent) {
    console.error("Modal elements not found");
    return;
  }

  const heroImage =
    park?.images && park.images.length > 0
      ? park.images[0].url
      : "https://via.placeholder.com/1200x600?text=National+Park";
  const stateLabel = park?.states?.length ? park.states : "Unknown";
  const wildlifeLabel = Array.isArray(park?.wildlife)
    ? park.wildlife.join(", ")
    : park?.wildlife && String(park.wildlife).trim().length > 0
    ? String(park.wildlife)
    : "Not specified";

  const ratingRaw =
    typeof park?.rating === "number" && !Number.isNaN(park.rating)
      ? park.rating
      : null;
  const ratingDisplay =
    ratingRaw !== null ? `${ratingRaw.toFixed(1)}/5` : "Not available";

  const visitsDisplay =
    typeof park?.visits === "number" && !Number.isNaN(park.visits)
      ? Number(park.visits).toLocaleString()
      : "Not available";

  const reviewsDisplay =
    typeof park?.reviews === "number" && !Number.isNaN(park.reviews)
      ? Number(park.reviews).toLocaleString()
      : "Not available";

  const descriptionText =
    park?.description && park.description.trim().length > 0
      ? park.description
      : "Detailed information about this park is currently unavailable.";

  const ticketUrl = `../tickets/tickets.php?park=${encodeURIComponent(
    park?.fullName || ""
  )}&parkId=${park?.id ?? ""}`;

  const parkNameSafe = escapeHtml(park?.fullName || "National Park");
  const stateLabelSafe = escapeHtml(stateLabel);
  const wildlifeLabelSafe = escapeHtml(wildlifeLabel);
  const ratingDisplaySafe = escapeHtml(ratingDisplay);
  const visitsDisplaySafe = escapeHtml(visitsDisplay);
  const reviewsDisplaySafe = escapeHtml(reviewsDisplay);
  const descriptionSafe = escapeHtml(descriptionText);
  const heroImageSafe = escapeHtml(heroImage);
  const ticketUrlSafe = escapeHtml(ticketUrl);

  const content = `
    <div class="modal-header">
      <img src="${heroImageSafe}" alt="${parkNameSafe}" class="modal-image">
      <div class="modal-title-section">
        <h2>${parkNameSafe}</h2>
        <p class="modal-location">📍 ${stateLabelSafe}</p>
      </div>
    </div>
    <div class="modal-body">
      <p class="modal-description">${descriptionSafe}</p>
      <div class="modal-stats">
        <div class="modal-stat-item">
          <div class="stat-icon">🦁</div>
          <div class="stat-label">Wildlife</div>
          <div class="stat-value">${wildlifeLabelSafe}</div>
        </div>
        <div class="modal-stat-item">
          <div class="stat-icon">⭐</div>
          <div class="stat-label">Rating</div>
          <div class="stat-value">${ratingDisplaySafe}</div>
        </div>
        <div class="modal-stat-item">
          <div class="stat-icon">👥</div>
          <div class="stat-label">Visitors</div>
          <div class="stat-value">${visitsDisplaySafe}</div>
        </div>
        <div class="modal-stat-item">
          <div class="stat-icon">💬</div>
          <div class="stat-label">Reviews</div>
          <div class="stat-value">${reviewsDisplaySafe}</div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-book-ticket-modal" data-ticket-url="${ticketUrlSafe}">
        🎫 Book Ticket Now
      </button>
    </div>
  `;

  modalContent.innerHTML = content;
  const ticketBtn = modalContent.querySelector(".btn-book-ticket-modal");
  if (ticketBtn) {
    ticketBtn.addEventListener("click", (event) => {
      const url = event.currentTarget.getAttribute("data-ticket-url");
      if (url) {
        window.location.href = url;
      }
    });
  }

  modal.classList.add("active");
  document.body.style.overflow = "hidden";
}

// Close modal
function closeModal() {
  const modal = document.getElementById("modalOverlay");
  modal.classList.remove("active");
  document.body.style.overflow = "auto";
}

// Initialize modal close handlers
document.addEventListener("DOMContentLoaded", () => {
  const modalClose = document.getElementById("modalClose");
  const modalOverlay = document.getElementById("modalOverlay");

  if (modalClose) {
    modalClose.addEventListener("click", closeModal);
  }

  if (modalOverlay) {
    modalOverlay.addEventListener("click", (e) => {
      if (e.target === modalOverlay) {
        closeModal();
      }
    });
  }

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeModal();
    }
  });
});

// Apply filters
function applyFilters() {
  const search = document.getElementById("searchInput").value.toLowerCase();
  const state = document.getElementById("stateFilter").value;
  const wildlife = document.getElementById("wildlifeFilter").value;

  displayedParks = allParks.filter((park) => {
    const parkName = park?.fullName ? park.fullName.toLowerCase() : "";
    const description = park?.description ? park.description.toLowerCase() : "";

    const statesList = park?.states
      ? park.states.split(",").map((item) => item.trim().toLowerCase())
      : [];

    const wildlifeList = Array.isArray(park?.wildlife)
      ? park.wildlife.map((item) => String(item).toLowerCase())
      : park?.wildlife
      ? String(park.wildlife)
          .split(",")
          .map((item) => item.trim().toLowerCase())
      : [];

    const matchSearch =
      !search || parkName.includes(search) || description.includes(search);

    const matchState =
      !state ||
      statesList.includes(state.toLowerCase()) ||
      (park?.states && park.states.toLowerCase() === state.toLowerCase());

    const matchWildlife =
      !wildlife ||
      wildlifeList.some((item) => item.includes(wildlife.toLowerCase()));

    return matchSearch && matchState && matchWildlife;
  });

  renderParks(displayedParks);
}

// Sort parks
function sortParks(by) {
  const sorted = [...displayedParks];

  if (by === "most-visited") {
    sorted.sort(
      (a, b) => numberOrDefault(b.visits) - numberOrDefault(a.visits)
    );
  } else if (by === "most-reviewed") {
    sorted.sort(
      (a, b) => numberOrDefault(b.reviews) - numberOrDefault(a.reviews)
    );
  } else if (by === "user-rating") {
    sorted.sort(
      (a, b) => numberOrDefault(b.rating) - numberOrDefault(a.rating)
    );
  }

  renderParks(sorted);
}

// Initialize everything when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
  console.log("DOM ready, initializing...");

  // Search
  const searchInput = document.getElementById("searchInput");
  if (searchInput) {
    searchInput.addEventListener("input", applyFilters);
  }

  // Filters
  const stateFilter = document.getElementById("stateFilter");
  const wildlifeFilter = document.getElementById("wildlifeFilter");
  const clearBtn = document.getElementById("clearFilters");

  if (stateFilter) stateFilter.addEventListener("change", applyFilters);
  if (wildlifeFilter) wildlifeFilter.addEventListener("change", applyFilters);

  if (clearBtn) {
    clearBtn.addEventListener("click", () => {
      if (searchInput) searchInput.value = "";
      if (stateFilter) stateFilter.value = "";
      if (wildlifeFilter) wildlifeFilter.value = "";
      applyFilters();
    });
  }

  // Sort buttons
  document.querySelectorAll(".sort-btn").forEach((btn) => {
    btn.addEventListener("click", function () {
      document
        .querySelectorAll(".sort-btn")
        .forEach((b) => b.classList.remove("active"));
      this.classList.add("active");
      sortParks(this.dataset.sort);
    });
  });

  // Load parks
  console.log("Starting park load...");
  loadParks();
});

console.log("Gallery script setup complete!");
