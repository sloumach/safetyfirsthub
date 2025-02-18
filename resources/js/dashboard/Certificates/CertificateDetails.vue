<template>
  <div class="certificate-details-container">
    <div class="certificate-card">
      <div class="certificate-content">
        <h1 class="certificate-title">Certificate of Completion</h1>
        
        <div class="certificate-body">
          <p class="recipient">This is to certify that</p>
          <h2 class="student-name">John Doe</h2>
          <p class="achievement">has successfully completed the course</p>
          <h3 class="course-name">Course Title</h3>
          <p class="date">{{ currentDate }}</p>
        </div>

        <!-- QR Code Section -->
        <div v-if="loading" class="qr-section">
          <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
        </div>

        <div v-else-if="qrCodeImage" class="qr-section">
          <img :src="qrCodeImage" alt="QR Code" class="qr-code-image" />
          <p class="qr-text">Scan to view certificate</p>
        </div>

        <button @click="generateCertificate" class="btn custom-btn mt-4">
          Générer le Certificat
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, computed } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import { useRoute } from 'vue-router'

export default {
  name: 'CertificateDetails',
  setup() {
    const route = useRoute() 
    const loading = ref(false)
    const qrCodeImage = ref(null)
    const certificateUrl = ref(null)

    const currentDate = computed(() => {
      return new Date().toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    })

    const generateCertificate = async () => {
      try {
        loading.value = true
        
        // Show loading state
        Swal.fire({
          title: 'Generating Certificate',
          text: 'Please wait...',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading()
          }
        })
        const examUserId = route.params.id
        const response = await axios.post(`/certificates/generate/112`)
        qrCodeImage.value = response.data.certificate.qr_code
        certificateUrl.value = response.data.certificate.url

        // Show success message
        Swal.fire({
          icon: 'success',
          title: 'Success!',
          text: 'Certificate generated successfully',
          confirmButtonColor: 'var(--main-color)'
        })

      } catch (error) {
        console.error('Error generating certificate:', error)
        
        // Show error message
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: 'Failed to generate certificate',
          confirmButtonColor: 'var(--main-color)'
        })
      } finally {
        loading.value = false
      }
    }

    return {
      loading,
      qrCodeImage,
      currentDate,
      generateCertificate
    }
  }
}
</script>

<style scoped>
.certificate-details-container {
  padding: 50px 20px;
  background-color: #f8f9fa;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
}

.certificate-card {
  background-color: white;
  padding: 40px;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  max-width: 800px;
  width: 100%;
}

.certificate-content {
  text-align: center;
  padding: 20px;
  border: 2px solid gold;
  position: relative;
}

.qr-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 2rem 0;
}

.qr-code-image {
  width: 200px;
  height: 200px;
  margin-bottom: 1rem;
}

.qr-text {
  margin-top: 1rem;
  color: #666;
}

.custom-btn {
  background-color: var(--main-color);
  color: white;
  padding: 12px 24px;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  font-size: 1.1rem;
  transition: all 0.3s ease;
}

.custom-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  background-color: white;
  color: var(--main-color);
  border: 2px solid var(--main-color);
}

/* Add other existing styles... */
</style>