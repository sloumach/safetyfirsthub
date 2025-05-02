<template>
    <div class="certificate-preview">
      <div class="certificate-frame">
<p>Certificate N° {{ certNum }}</p>
        <div class="certificate-content">
          <h1 class="title">CERTIFICATE OF COMPLETION</h1>

          <div class="certificate-text">
            <p class="certifies">This certifies that</p>
            <h2 class="candidate-name">{{ candidateName || 'Nom du candidat' }}</h2>
            <p class="completion-text">has successfully completed the course</p>
            <h3 class="program-name">({{ programName || 'NOM DU PROGRAMME/FORMATION' }})</h3>

            <div class="certificate-details">
              <div class="detail-item">
                <img src="../../../../public/assets/img/clock.svg" alt="Credit Hours" class="detail-icon">
                <span>Credit Hours: {{ creditHours }}</span>
              </div>
              <div class="detail-item">
                <img src="../../../../public/assets/img/medal.svg" alt="Completion Date" class="detail-icon">
                <span>Completion Date: {{ completionDate }}</span>
              </div>
            </div>
          </div>

          <!-- QR Code Section -->
          <div class="verification-section">
            <div class="qr-placeholder">
              <!-- QR code image with higher z-index -->
              <img v-if="qrCode" :src="qrCode" alt="QR Code" class="qr-code-image">

            </div>
            <p class="verify-text">Scan to Verify</p>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script>
  export default {
    name: 'CertificatePreview',
    props: {
      candidateName: {
        type: String,
        default: ''
      },
        certNum: {
            type: String,
            default: ''
        },
      programName: {
        type: String,
        default: ''
      },
      creditHours: {
        type: String,
        default: ''
      },
      completionDate: {
        type: String,
        default: ''
      },
      qrCode: {
        type: String,
        default: ''
      }
    }
  }
  </script>

  <style scoped>
  .certificate-preview {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
  }

  .certificate-frame {
    position: relative;
    aspect-ratio: 1.414/1; /* A4 ratio */
    background: url('../../../../public/assets/img/background-certificate.png') no-repeat center;
    background-size: 100% 100%;
    padding: 2rem;
  }

  .company-logo {
    width: 180px;
    display: block;
    margin: 20px auto 40px;
  }

  .certificate-content {
    text-align: center;
    position: relative;
    max-width: 800px;
    margin: 0 auto;
    padding-top: 120px; /* Add space for the logo that's in the background */
  }

  .title {
    color: #B8860B; /* Golden color matching the original */
    font-size: 26px;
    font-family: 'Times New Roman', serif;
    font-weight: normal;
    margin: 0;
    letter-spacing: 1px;
    margin-top: 12px;
  }

  .certifies {
    color: #333;
    font-size: 19px;
    margin-bottom: 20px;
  }

  .candidate-name {
      color: #b86b0b;
      font-size: 51px;
      margin: 7px 0;
      font-family: Times New Roman, serif;
      position: relative;
      display: inline-block;
  }

  .candidate-name::after {
      content: "";
      position: absolute;
      bottom: 6px;
      left: 0;
      width: 100%;
      height: 1.5px;
      background: black;
  }

  .completion-text {
      color: #333;
      font-size: 22px;
  }

  .program-name {
      color: #333;
      font-size: 20px;
      font-style: italic;
  }

  .certificate-details {
      display: flex;
      flex-direction: column;
      gap: 18px;
      margin: 35px 0;
      padding-left: 259px;
      text-align: left;
  }

  .detail-item {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #333;
    font-size: 15px;
  }

  .detail-icon {
      width: 24px;
      height: 29px;
      opacity: .7;
  }

  .verification-section {
      position: absolute;
      bottom: -115px;
      right: 11px;
      text-align: center;
  }

  .qr-placeholder {
    width: 80px;
    height: 80px;
    position: relative;
  }

  /* QR code image styling */
  .qr-code-image {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    height: 90%;
    object-fit: contain;
    z-index: 10; /* Ensure QR code appears above corners */
  }

  .qr-corner {
      position: absolute;
      width: 20px;
      height: 20px;
      border: 3px solid red;
      z-index: 5;
  }

  /* Rest of your existing corner styles */
  .top-left {
    top: 0;
    left: 0;
    border-right: none;
    border-bottom: none;
  }

  .top-right {
    top: 0;
    right: 0;
    border-left: none;
    border-bottom: none;
  }

  .bottom-left {
    bottom: 0;
    left: 0;
    border-right: none;
    border-top: none;
  }

  .bottom-right {
    bottom: 0;
    right: 0;
    border-left: none;
    border-top: none;
  }

  .verify-text {
    color: #8B0000;
    font-size: 12px;
    margin-top: 5px;
    font-weight: bold;
  }

  .website {
    position: absolute;
    bottom: 20px;
    left: 0;
    right: 0;
    text-align: center;
    color: #333;
    font-size: 14px;
  }

  /* Add these responsive styles at the end of your CSS */
  @media screen and (max-width: 1200px) {
    .certificate-preview {
      max-width: 900px;
    }

    .program-name {
      font-size: 18px;
      margin: 15px 0;
    }

    .detail-item {
      font-size: 14px;
    }

    .verification-section {
      bottom: -100px;
      right: 10px;
    }
  }

  @media screen and (max-width: 992px) {
    .certificate-preview {
      max-width: 800px;
    }

    .program-name {
      font-size: 16px;
      padding: 0 20px;
    }

    .detail-item {
      font-size: 13px;
    }

    .verification-section {
      bottom: -90px;
      right: 8px;
    }

    .qr-placeholder {
      width: 70px;
      height: 70px;
    }
  }

  @media screen and (max-width: 768px) {
    .certificate-preview {
      max-width: 100%;
      padding: 10px;
    }

    .certificate-frame {
      padding: 1.5rem;
    }

    .program-name {
      font-size: 15px;
      padding: 0 15px;
    }

    .certificate-details {
      padding-left: 0;
      align-items: center;
    }

    .detail-item {
      font-size: 12px;
      justify-content: center;
    }

    .verification-section {
      bottom: -80px;
      right: 5px;
    }

    .qr-placeholder {
      width: 60px;
      height: 60px;
    }

    .verify-text {
      font-size: 10px;
    }
  }

  @media screen and (max-width: 480px) {
    .certificate-frame {
      padding: 1rem;
    }

    .program-name {
      font-size: 14px;
      padding: 0 10px;
    }

    .detail-item {
      font-size: 11px;
    }

    .detail-icon {
      width: 20px;
      height: 25px;
    }

    .verification-section {
      bottom: -70px;
      right: 3px;
    }

    .qr-placeholder {
      width: 50px;
      height: 50px;
    }

    .qr-corner {
      width: 15px;
      height: 15px;
      border-width: 2px;
    }
  }

  /* Print media query for better printing */
  @media print {
    .certificate-preview {
      max-width: none;
      padding: 0;
    }

    .certificate-frame {
      padding: 2rem;
    }

    .program-name {
      font-size: 20px;
    }

    .detail-item {
      font-size: 15px;
    }

    .verification-section {
      bottom: -115px;
      right: 11px;
    }

    .qr-placeholder {
      width: 80px;
      height: 80px;
    }
  }
  </style>
