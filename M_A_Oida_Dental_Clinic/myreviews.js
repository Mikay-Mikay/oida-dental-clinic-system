document.addEventListener('DOMContentLoaded', () => {
    const toggle         = document.getElementById('filterToggle');
    const dropdown       = document.getElementById('filterDropdown');
    const applyBtn       = document.getElementById('applyFilter');
    const reviewsContainer = document.getElementById('reviewsContainer');
    let allReviews = [];
  
    // toggle dropdown
    toggle.addEventListener('click', e => {
      e.stopPropagation();
      dropdown.classList.toggle('show');
    });
    document.addEventListener('click', e => {
      if (!dropdown.contains(e.target) && e.target !== toggle) {
        dropdown.classList.remove('show');
      }
    });

    // notification dropdown
const notifToggle = document.getElementById('notificationToggle');
const notifWrapper = document.getElementById('notificationWrapper');
notifToggle?.addEventListener('click', e => {
  e.stopPropagation();
  notifWrapper.classList.toggle('show');
});
document.addEventListener('click', e => {
  if (!notifWrapper.contains(e.target) && e.target !== notifToggle) {
    notifWrapper.classList.remove('show');
  }
});

  
    // apply filters
    applyBtn.addEventListener('click', () => {
      const timeVal   = dropdown.querySelector('input[name="time"]:checked').value;
      const ratingVal = dropdown.querySelector('input[name="rating"]:checked').value;
      const now       = Date.now();
  
      let filtered = allReviews.filter(r => {
        // rating
        if (ratingVal!=='all' && String(r.rating)!==ratingVal) return false;
        // time
        if (timeVal!=='all-time') {
          const ms = now - new Date(r.date).getTime();
          switch(timeVal) {
            case 'past-few-days':   if (ms>3*24*60*60*1000)   return false; break;
            case 'past-few-weeks':  if (ms>21*24*60*60*1000)  return false; break;
            case 'past-few-months': if (ms>90*24*60*60*1000)  return false; break;
            case 'past-few-years':  if (ms>365*24*60*60*1000) return false; break;
          }
        }
        return true;
      });
  
      renderReviews(filtered);
      dropdown.classList.remove('show');
    });
  
    // fetch & render only current user's reviews
    function loadMyReviews() {
      fetch('reviews.php?api=1')
        .then(r=>r.json())
        .then(data=>{
          allReviews = data.filter(r=>r.patient_id===window.currentUserId);
          renderReviews(allReviews);
        })
        .catch(_=>{
          reviewsContainer.innerHTML = '<p class="error-message">Failed to load reviews.</p>';
        });
    }
  
    // same render logic as your reviews.js
    function renderReviews(list) {
      if (!list.length) {
        reviewsContainer.innerHTML = '<p class="no-reviews">No reviews found.</p>';
        return;
      }
      reviewsContainer.innerHTML = list.map(r=> {
        const stars = '★'.repeat(r.rating)+'☆'.repeat(5-r.rating);
        const svc   = (r.services||[]).join(', ');
        return `
        <div class="review-item">
          <div class="review-header">
            <img src="${r.profile_picture}" class="review-avatar" alt>
            <div class="reviewer-name">${r.name}</div>
            <div class="review-rating">${stars}</div>
          </div>
          <div class="review-content">${r.text}</div>
          <div class="review-service">${svc}</div>
          <div class="review-date">${r.date_display||new Date(r.date).toLocaleDateString()}</div>
        </div>`;
      }).join('');
    }
  
    loadMyReviews();
  });
  