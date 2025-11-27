// Park data
const parksData = {
  ranthambore: {
    name: "Ranthambore National Park",
    state: "Rajasthan",
    wildlife: "Tigers",
    visits: 15000,
    reviews: 4500,
    rating: 4.8,
  },
  kaziranga: {
    name: "Kaziranga National Park",
    state: "Assam",
    wildlife: "Rhinos",
    visits: 12000,
    reviews: 3800,
    rating: 4.7,
  },
  periyar: {
    name: "Periyar National Park",
    state: "Kerala",
    wildlife: "Elephants",
    visits: 18000,
    reviews: 5200,
    rating: 4.6,
  },
  bandipur: {
    name: "Bandipur National Park",
    state: "Karnataka",
    wildlife: "Tigers",
    visits: 11000,
    reviews: 3200,
    rating: 4.5,
  },
  "jim-corbett": {
    name: "Jim Corbett National Park",
    state: "Uttarakhand",
    wildlife: "Tigers",
    visits: 20000,
    reviews: 6800,
    rating: 4.9,
  },
  kanha: {
    name: "Kanha National Park",
    state: "Madhya Pradesh",
    wildlife: "Tigers",
    visits: 14000,
    reviews: 4100,
    rating: 4.8,
  },
  gir: {
    name: "Gir Forest National Park",
    state: "Gujarat",
    wildlife: "Lions",
    visits: 13000,
    reviews: 3900,
    rating: 4.7,
  },
  sundarbans: {
    name: "Sundarbans National Park",
    state: "West Bengal",
    wildlife: "Tigers",
    visits: 8000,
    reviews: 2400,
    rating: 4.4,
  },
  eravikulam: {
    name: "Eravikulam National Park",
    state: "Kerala",
    wildlife: "Nilgiri Tahr",
    visits: 9000,
    reviews: 2700,
    rating: 4.6,
  },
  "valley-flowers": {
    name: "Nagarhole National Park",
    state: "karnataka",
    wildlife: "Tigers",
    visits: 7000,
    reviews: 2100,
    rating: 4.9,
  },
  hemis: {
    name: "Hemis National Park",
    state: "Ladakh",
    wildlife: "Snow Leopard",
    visits: 5000,
    reviews: 1500,
    rating: 4.8,
  },
  "nanda-devi": {
    name: "Nanda Devi National Park",
    state: "Uttarakhand",
    wildlife: "Snow Leopard",
    visits: 4000,
    reviews: 1200,
    rating: 4.7,
  },
};

// Search functionality
const searchInput = document.getElementById("searchInput");
const parksGrid = document.getElementById("parksGrid");

searchInput.addEventListener("input", function (e) {
  const searchTerm = e.target.value.toLowerCase();
  const parkCards = parksGrid.querySelectorAll(".park-card");

  parkCards.forEach((card) => {
    const parkName = card.querySelector("h3").textContent.toLowerCase();
    if (parkName.includes(searchTerm)) {
      card.style.display = "block";
      card.style.animation = "fadeInUp 0.4s ease forwards";
    } else {
      card.style.display = "none";
    }
  });
});

// Sort functionality
const sortButtons = document.querySelectorAll(".sort-btn");
const parkCards = Array.from(document.querySelectorAll(".park-card"));

sortButtons.forEach((button) => {
  button.addEventListener("click", function () {
    // Update active state
    sortButtons.forEach((btn) => btn.classList.remove("active"));
    this.classList.add("active");

    const sortBy = this.dataset.sort;
    let sortedCards;

    switch (sortBy) {
      case "most-visited":
        sortedCards = parkCards.sort((a, b) => {
          const aData = parksData[a.dataset.park];
          const bData = parksData[b.dataset.park];
          return bData.visits - aData.visits;
        });
        break;
      case "most-reviewed":
        sortedCards = parkCards.sort((a, b) => {
          const aData = parksData[a.dataset.park];
          const bData = parksData[b.dataset.park];
          return bData.reviews - aData.reviews;
        });
        break;
      case "user-rating":
        sortedCards = parkCards.sort((a, b) => {
          const aData = parksData[a.dataset.park];
          const bData = parksData[b.dataset.park];
          return bData.rating - aData.rating;
        });
        break;
    }

    // Re-append sorted cards
    sortedCards.forEach((card) => {
      parksGrid.appendChild(card);
    });

    // Re-animate cards
    sortedCards.forEach((card, index) => {
      card.style.animation = "none";
      setTimeout(() => {
        card.style.animation = `fadeInUp 0.4s ease forwards`;
      }, index * 50);
    });
  });
});

// Park card click events
parkCards.forEach((card) => {
  card.addEventListener("click", function () {
    const parkKey = this.dataset.park;
    const parkData = parksData[parkKey];
    alert(
      `Welcome to ${parkData.name}!\n\nLocation: ${parkData.state}\nFamous for: ${parkData.wildlife}\nRating: ${parkData.rating}/5`
    );
  });
});

// Filter button interactions
document.querySelectorAll(".filter-btn").forEach((btn) => {
  btn.addEventListener("click", function () {
    this.style.backgroundColor = "#27ae60";
    this.style.color = "white";
    this.style.borderColor = "#27ae60";

    setTimeout(() => {
      this.style.backgroundColor = "white";
      this.style.color = "#333";
      this.style.borderColor = "#ecf0f1";
    }, 200);
  });
});
