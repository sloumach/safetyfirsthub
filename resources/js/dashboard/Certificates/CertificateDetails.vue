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

        <div class="qr-section">
          <!-- <qrcode-vue
            :value="qrValue"
            :size="200"
            level="H"
          /> -->
          <p class="qr-text">Scan to verify certificate</p>
        </div>

        <button @click="downloadCertificate" class="download-btn">
          Download Certificate
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import QrcodeVue from 'qrcode.vue'
import { ref, computed } from 'vue'

export default {
  name: 'CertificateDetails',
  components: {
    QrcodeVue
  },
  setup() {
    const currentDate = computed(() => {
      return new Date().toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      })
    })

    // const qrValue = computed(() => {
    //   // This URL would typically point to your certificate verification endpoint
    //   return `https://yourwebsite.com/verify-certificate/${window.location.pathname}`
    // })

    const downloadCertificate = () => {
      // For now, we'll create a simple text file
      const element = document.createElement('a')
      const certificateText = `
        Certificate of Completion
        
        This is to certify that John Doe has successfully completed the course.
        
        Date: ${currentDate.value}
        
        Certificate ID: ${window.location.pathname.split('/').pop()}
      `
      const file = new Blob([certificateText], {type: 'text/plain'})
      element.href = URL.createObjectURL(file)
      element.download = `certificate-${Date.now()}.txt`
      document.body.appendChild(element)
      element.click()
      document.body.removeChild(element)
    }

    return {
      currentDate,
      // qrValue,
      downloadCertificate
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
  border: 2px solid #ffd700;
  position: relative;
}

.certificate-title {
  font-size: 2.5rem;
  color: var(--main-color);
  margin-bottom: 2rem;
  font-weight: bold;
}

.certificate-body {
  margin: 2rem 0;
}

.student-name {
  font-size: 2rem;
  color: #333;
  margin: 1rem 0;
}

.course-name {
  font-size: 1.5rem;
  color: #666;
  margin: 1rem 0;
}

.qr-section {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin: 2rem 0;
}

.qr-text {
  margin-top: 1rem;
  color: #666;
}

.download-btn {
  background-color: var(--main-color);
  color: white;
  padding: 12px 24px;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  font-size: 1.1rem;
  transition: all 0.3s ease;
  margin-top: 2rem;
}

.download-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.recipient, .achievement, .date {
  color: #666;
  margin: 0.5rem 0;
}

@media (max-width: 768px) {
  .certificate-card {
    padding: 20px;
  }

  .certificate-title {
    font-size: 2rem;
  }

  .student-name {
    font-size: 1.5rem;
  }

  .course-name {
    font-size: 1.2rem;
  }
}
</style> 