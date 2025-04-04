function uploadProfilePicture() {
    const fileInput = document.getElementById('profile-picture-input');
    const profileImage = document.getElementById('profile-image');
  
    if (fileInput.files && fileInput.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        profileImage.src = e.target.result;
        uploadToServer(e.target.result);
      }
      reader.readAsDataURL(fileInput.files[0]);
    }
  }
  
  function uploadToServer(imageData) {
    fetch('/upload_profile_picture.php', {
      method: 'POST',
      body: JSON.stringify({ image: imageData })
    })
    .then(response => response.json())
    .then(data => {
      console.log('Profile picture uploaded successfully!');
    })
    .catch(error => {
      console.error('Error uploading profile picture:', error);
    });
  }
  