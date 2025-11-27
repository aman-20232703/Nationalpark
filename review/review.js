const reviews = [
  {
    name: "Aman Kumar",
    date: "2025-07-20",
    rating: 5,
    text: "An unforgettable experience at the park! The wildlife was abundant, and the guides were incredibly knowledgeable. Highly recommend for any nature enthusiast.",
    likes: 12,
    dislikes: 2,
    avatar: "https://i.pravatar.cc/40?img=54",
    park: "Kaziranga",
  },
  {
    name: "Ashish Gupta",
    date: "2025-06-20",
    rating: 4,
    text: "The park was beautiful, but the trails could be better marked. Overall, a good experience, but there's room for improvement.",
    likes: 8,
    dislikes: 3,
    avatar: "https://i.pravatar.cc/40?img=13",
    park: "Jim Corbett",
  },
  {
    name: "Sahil kumar",
    date: "2025-05-23",
    rating: 5,
    text: "Absolutely breathtaking! The park exceeded all expectations. The staff was friendly, and the scenery was stunning. A must-visit!",
    likes: 15,
    dislikes: 1,
    avatar: "https://i.pravatar.cc/40?img=62",
    park: "Kaziranga",
  },
  {
    name: "js singh",
    date: "2023-07-15",
    rating: 4,
    text: "all good vibes as well as outstanding.really it provide us a virtual national park view",
    likes: 9,
    dislikes: 2,
    avatar: "https://i.pravatar.cc/40?img=24",
    park: "Hemis",
  },
  {
    name: "misha roy",
    date: "2023-08-02",
    rating: 3,
    text: "although good but it can be more ellobrative. need some exctra space for the wilds also could be better pathway for the visitors ",
    likes: 14,
    dislikes: 3,
    avatar: "https://i.pravatar.cc/40?img=37",
    park: "Kanha",
  },
];

const searchBar = document.getElementById("searchBar");
const filterPark = document.getElementById("filterPark");
const filterRating = document.getElementById("filterRating");
const sortDate = document.getElementById("sortDate");
const reviewsContainer = document.getElementById("reviewsContainer");

function renderReviews(list) {
  reviewsContainer.innerHTML = "";
  list.forEach((r, index) => {
    const review = document.createElement("div");
    review.classList.add("review-card");
    review.innerHTML = `
      <div class="review-header">
        <img src="${r.avatar}" alt="${r.name}">
        <div>
          <strong>${r.name}</strong><br>
          <small>${new Date(r.date).toLocaleDateString()}</small>
        </div>
      </div>
      <div class="review-rating">${"★".repeat(r.rating)}${"☆".repeat(
      5 - r.rating
    )}</div>
      <p>${r.text}</p>
      <div class="review-footer">
        <span onclick="likeReview(${index})">👍 ${r.likes}</span>
        <span onclick="dislikeReview(${index})">👎 ${r.dislikes}</span>
      </div>
    `;
    reviewsContainer.appendChild(review);
  });
}

function applyFilters() {
  let filtered = [...reviews];

  const searchTerm = searchBar.value.toLowerCase();
  if (searchTerm) {
    filtered = filtered.filter(
      (r) =>
        r.text.toLowerCase().includes(searchTerm) ||
        r.name.toLowerCase().includes(searchTerm) ||
        r.park.toLowerCase().includes(searchTerm)
    );
  }

  if (filterPark.value) {
    filtered = filtered.filter((r) => r.park === filterPark.value);
  }

  if (filterRating.value) {
    filtered = filtered.filter(
      (r) => r.rating === parseInt(filterRating.value)
    );
  }

  if (sortDate.value === "newest") {
    filtered.sort((a, b) => new Date(b.date) - new Date(a.date));
  } else if (sortDate.value === "oldest") {
    filtered.sort((a, b) => new Date(a.date) - new Date(b.date));
  }

  renderReviews(filtered);
}

function likeReview(index) {
  reviews[index].likes++;
  applyFilters();
}

function dislikeReview(index) {
  reviews[index].dislikes++;
  applyFilters();
}

// Event listeners
searchBar.addEventListener("input", applyFilters);
filterPark.addEventListener("change", applyFilters);
filterRating.addEventListener("change", applyFilters);
sortDate.addEventListener("change", applyFilters);

// Initial render
renderReviews(reviews);
