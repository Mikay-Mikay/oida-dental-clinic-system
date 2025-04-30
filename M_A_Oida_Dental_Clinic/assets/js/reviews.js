// assets/js/reviews.js
document.addEventListener('DOMContentLoaded', () => {
  // Globals from PHP
  const isLoggedIn       = window.isLoggedIn;
  const currentUserName  = window.currentUserName || '';

  // DOM elements
  const filterToggle     = document.getElementById('filterToggle');
  const filterDropdown   = document.getElementById('filterDropdown');
  const applyFilter      = document.getElementById('applyFilter');
  const reviewsContainer = document.getElementById('reviewsContainer');
  const addBtn           = document.getElementById('addReviewBtn');
  const modal            = document.getElementById('reviewModal');
  const closeBtn         = modal.querySelector('.close');
  const submitBtn        = document.getElementById('submitReview');
  const serviceSel       = document.getElementById('serviceType');
  const stars            = Array.from(document.querySelectorAll('.star-rating .star'));
  const reviewText       = document.getElementById('reviewText');
  const wordCount        = document.getElementById('wordCount');
  const anonToggle       = document.getElementById('anonToggle');
  const modalUser        = document.getElementById('modalUsername');

  let allReviews = [];
  let selectedRating = 0;

  // --- Filter dropdown handlers ---
  filterToggle.addEventListener('click', e => {
    e.stopPropagation();
    filterDropdown.classList.toggle('show');
  });
  document.addEventListener('click', e => {
    if (!filterDropdown.contains(e.target) && e.target !== filterToggle) {
      filterDropdown.classList.remove('show');
    }
  });
  applyFilter.addEventListener('click', () => {
    const timeVal   = document.querySelector('input[name="time"]:checked').value;
    const ratingVal = document.querySelector('input[name="rating"]:checked').value;
    let filtered = allReviews.slice();

    // filter rating
    if (ratingVal !== 'all') {
      filtered = filtered.filter(r => String(r.rating) === ratingVal);
    }

    // filter time
    if (timeVal !== 'all-time') {
      const now = Date.now(), day = 1000 * 60 * 60 * 24;
      let cutoff;
      switch(timeVal) {
        case 'past-few-days':   cutoff = now - day * 7;    break;
        case 'past-few-weeks':  cutoff = now - day * 30;   break;
        case 'past-few-months': cutoff = now - day * 180;  break;
        case 'past-few-years':  cutoff = now - day * 365*2;break;
      }
      filtered = filtered.filter(r => {
        const d = new Date(r.date_raw).getTime();
        return d >= cutoff && d <= now;
      });
    }

    renderReviews(filtered);
    filterDropdown.classList.remove('show');
  });

  // --- Modal open/close ---
  addBtn.addEventListener('click', () => {
    if (!isLoggedIn) {
      alert('Please log in to add a review.');
      return window.location.href = 'login.php';
    }
    modal.style.display = 'block';
    modalUser.textContent = currentUserName;
    reviewText.value = '';
    wordCount.textContent = '0 / 500 words';
    anonToggle.checked = false;
    selectedRating = 0;
    resetStars();
    serviceSel.selectedIndex = 0;
  });
  closeBtn.addEventListener('click', () => modal.style.display = 'none');
  modal.addEventListener('click', e => {
    if (e.target === modal) modal.style.display = 'none';
  });

  // --- Star rating ---
  stars.forEach(s => {
    const val = +s.dataset.value;
    s.addEventListener('click', () => { selectedRating = val; updateStars(); });
    s.addEventListener('mouseover', () => highlightStars(val));
    s.addEventListener('mouseout', resetStars);
  });
  function updateStars() {
    stars.forEach(s => s.classList.toggle('active', +s.dataset.value <= selectedRating));
  }
  function highlightStars(n) {
    stars.forEach(s => s.classList.toggle('hover', +s.dataset.value <= n));
  }
  function resetStars() {
    stars.forEach(s => s.classList.remove('hover'));
    updateStars();
  }

  // --- Word count ---
  reviewText.addEventListener('input', () => {
    const cnt = reviewText.value.trim().split(/\s+/).filter(w => w).length;
    wordCount.textContent = `${Math.min(cnt, 500)} / 500 words`;
  });

  // --- Anonymous toggle ---
  anonToggle.addEventListener('change', () => {
    modalUser.textContent = anonToggle.checked ? 'Anonymous' : currentUserName;
  });

  // --- Submit review ---
  submitBtn.addEventListener('click', () => {
    const name    = anonToggle.checked ? 'Anonymous' : currentUserName;
    const service = serviceSel.value;
    const text    = reviewText.value.trim();

    if (!service || !selectedRating || !text) {
      return alert('Please select a service, rating, and enter feedback.');
    }

    fetch('reviews.php', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({
        name,
        rating: selectedRating,
        text,
        services: [ service ],
        anon: anonToggle.checked
      })
    })
    .then(r => r.json())
    .then(json => {
      if (json.success) {
        modal.style.display = 'none';
        loadReviews();
      } else {
        alert('Submit failed: ' + (json.error || 'Unknown error'));
      }
    })
    .catch(() => alert('Network error submitting review.'));
  });

  // --- Fetch & render reviews ---
  function loadReviews() {
    fetch('reviews.php?api=1')
      .then(r => r.json())
      .then(data => {
        allReviews = data;
        renderReviews(allReviews);
      })
      .catch(() => {
        reviewsContainer.innerHTML =
          '<p class="error-message">Failed to load reviews.</p>';
      });
  }

  function renderReviews(list) {
    if (!list.length) {
      reviewsContainer.innerHTML = '<p class="no-reviews">No reviews found.</p>';
      return;
    }
    reviewsContainer.innerHTML = list.map(r => {
      const starsHtml = '★'.repeat(r.rating) + '☆'.repeat(5 - r.rating);
      const svc       = (r.services||[]).join(', ');
      return `
        <div class="review-item">
          <div class="review-header">
            <img src="${r.profile_picture}" class="review-avatar" alt>
            <div class="reviewer-name">${r.name}</div>
            <div class="review-rating">${starsHtml}</div>
          </div>
          <div class="review-content">${r.text}</div>
          <div class="review-service">${svc}</div>
          <div class="review-date">${r.date_display}</div>
        </div>`;
    }).join('');
  }

  // initial load
  loadReviews();
});
