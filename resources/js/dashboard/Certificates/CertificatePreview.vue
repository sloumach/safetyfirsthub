<template>
  <div class="certificate-preview">
    <div class="certificate-frame">
      <!-- Logo at top -->
      
      
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
            <!-- Corners -->
            <div class="qr-corner top-left"></div>
            <div class="qr-corner top-right"></div>
            <div class="qr-corner bottom-left"></div>
            <div class="qr-corner bottom-right"></div>
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
    font-size: 28px;
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
  font-size: 19px;
}

.detail-icon {
    width: 24px;
    height: 29px;
    opacity: .7;
}

.verification-section {
    position: absolute;
    bottom: -86px;
    right: 0;
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
</style> 