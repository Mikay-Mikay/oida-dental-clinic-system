document.addEventListener('DOMContentLoaded', () => {
    const modal      = document.getElementById('serviceModal');
    const closeBtn   = modal.querySelector('.close');
    const bookBtn    = modal.querySelector('.modal-book-btn');
    const items      = document.querySelectorAll('.service-item');
    
  
    // simple descriptions map (expand as needed)
    const descriptions = {
      'Dental Check-ups & Consultation': 'Regular examinations of the teeth and oral cavity to assess overall dental health and detect potential issues. This service includes a detailed consultation and the development of a personalized treatment plan.',
      'Teeth Cleaning':                   'A professional cleaning procedure designed to remove plaque and tartar buildup from the teeth. This service helps prevent tooth decay and gum disease.',
      'Tooth Extraction':                 'The removal of a damaged or decayed tooth that cannot be restored through other dental procedures. This service is performed with careful attention to patient comfort and safety.',
      'Dental Fillings/Dental Bonding':   'Dental fillings restore decayed teeth by removing damage and filling cavities with durable, tooth-colored resin, while dental bonding repairs chips, cracks, and gaps by sculpting and curing a cosmetic resin directly onto the tooth’s surface.',
      'Gum Treatment and Gingivectomy':   'Treatments focused on managing and treating gum diseases, such as scaling and root planing, which help remove plaque and tartar from beneath the gum line to prevent further periodontal issues.',
      'Teeth Whitening':                  'A professional treatment designed to brighten your smile by removing stains and discoloration, resulting in a noticeably whiter smile.',
      'Dental Veneers':                   'Custom-made, thin porcelain shells that are bonded to the front of your teeth to enhance their appearance by improving shape, color, and overall alignment.',
      'Metal Braces/Ceramic':             'A Traditional stainless steel braces used for effective teeth alignment. For Ceramic Braces, it’s  more aesthetic alternative to metal braces, offering a less noticeable appearance.',
      'Clear Aligners/Retainers':         'Clear Invisalign is a discreet and removable aligner used to straighten teeth comfortably without metal braces. It’s clear, custom-made, and easy to wear. A Retainers is a custom-made devices as well to maintain teeth alignment after orthodontic treatment.',
      'Dental Crown':                     'Custom caps placed over damaged teeth to restore strength and appearance.',
      'Dental Bridges':                   'Fixed prosthetic devices used to replace one or more missing teeth by anchoring to adjacent teeth.',
      'Dentures (Partial & Full)':        'Removable replacements for missing teeth; partial dentures replace several teeth while full dentures replace all teeth in an arch.',
      'Dental Implants':                  'Surgically placed fixtures that serve as permanent replacements for missing teeth.',
      'Fluoride Treatment':               'Application of fluoride to strengthen children’s teeth and help prevent cavities.',
      'Dental Sealants':                  'Protective coatings applied to the chewing surfaces of back teeth to prevent decay.',
      'Kids’ Braces & Orthodontic Care':  'Early orthodontic interventions designed to correct developing alignment issues in children.',
      'Wisdom Tooth Extraction (Odontectomy)': ' Removal of impacted or problematic wisdom teeth.',
      'Root Canal Treatment':             'Treatment for infected or inflamed tooth pulp aimed at saving the tooth.',
      'TMJ Treatment':                    'Therapeutic interventions to manage jaw pain and temporomandibular joint disorders.',
      'Intraoral X-ray':                  'Detailed images of individual teeth and adjacent structures, ideal for detecting cavities, bone loss, and other issues.',
      'Panoramic X-ray / Full Mouth X-ray':'Is a quick and painless dental imaging technique that captures a full view of your mouth in a single image, including your teeth, jaw, and surrounding structures. It helps dentists detect issues that may not be visible during a regular check-up, such as impacted teeth, bone abnormalities, or infections.',
      'Lateral Cephalometric X-ray':      'Side profile images used primarily for orthodontic evaluations, helping assess skeletal relationships and treatment planning.',
      'Periapical X-ray / Single Tooth X-ray':'It focuses on a specific tooth and its surrounding bone. It provides a detailed image from the crown to the root, helping the dentist diagnose problems like tooth decay, abscesses, or root infections.',
      'TMJ Transcranial X-ray':           'A specialized imaging technique used to examine the temporomandibular joint (TMJ), which connects the jaw to the skull. It helps detect joint disorders, alignment issues, and other abnormalities that may cause jaw pain or movement problems.',
    };
  
    // open modal when clicking a service
    items.forEach(item => {
      item.addEventListener('click', () => {
        const title = item.querySelector('p').textContent;
        modal.querySelector('#modalTitle').textContent = title;
        modal.querySelector('#modalImage').src = item.querySelector('img').src;
        modal.querySelector('#modalDescription').textContent =
          descriptions[title] || '';
        modal.style.display = 'block';
      });
    });
  
    // close modal
    closeBtn.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', e => {
      if (e.target === modal) modal.style.display = 'none';
    });
  
    // Book Now inside modal
    bookBtn.addEventListener('click', () => {
      if (!isLoggedIn) {
        alert('Please login to book an appointment.');
        return window.location.href = 'login.php';
      }
      window.location.href = 'bookings.php';
    });
  });
