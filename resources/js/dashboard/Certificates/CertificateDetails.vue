<template>
  <div class="certificate-details-container">
      <div class="certificate-card">
          <div class="certificate-content">
              <h1 class="certificate-title">Certificate of Completion</h1>

              <div class="certificate-body">
                  <p class="recipient">This is to certify that</p>
                  <h2 class="student-name">{{ firstname || "..." }} {{ lastname || "..." }}</h2>
                  <p class="achievement">has successfully completed the course</p>
                  <h3 class="course-name">{{ courseName || "..." }}</h3>
                  <p class="date">{{ currentDate }}</p>
              </div>

              <!-- Loading State -->
              <div v-if="loading" class="qr-section">
                  <div class="spinner-border text-primary" role="status">
                      <span class="visually-hidden">Loading...</span>
                  </div>
              </div>

              <!-- QR Code Display -->
              <div v-else-if="qrCodeImage" class="qr-section">
                  <img :src="qrCodeImage" alt="QR Code" class="qr-code-image" />
                  <p class="qr-text">Scan to verify</p>
              </div>
          </div>
      </div>
  </div>
</template>

<script>
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import Swal from "sweetalert2";
import { useRoute } from "vue-router";

export default {
  name: "CertificateDetails",
  setup() {
      const route = useRoute();
      const loading = ref(true);
      const qrCodeImage = ref(null);
      const firstname = ref("");
      const lastname = ref("");
      const courseName = ref("");

      const currentDate = computed(() => {
          return new Date().toLocaleDateString("en-US", {
              year: "numeric",
              month: "long",
              day: "numeric",
          });
      });

      const generateCertificate = async () => {
          try {
              const examUserId = route.params.id;

              const response = await axios.post(`/certificates/generate/${examUserId}`);
              
              if (response.data) {
                  qrCodeImage.value = `data:image/svg+xml;base64,${response.data.certificate.qr_code}`;
                  firstname.value = response.data.user_firstname || "Unknown";
                  lastname.value = response.data.user_lastname || "Unknown";
                  courseName.value = response.data.course_name || "Unknown Course";
              } else {
                  Swal.fire({
                      icon: "error",
                      title: "Oops...",
                      text: "No certificate data received",
                      confirmButtonColor: "#FF8A00",
                  });
              }

          } catch (error) {
              console.error("Error generating certificate:", error);
              Swal.fire({
                  icon: "error",
                  title: "Oops...",
                  text: error.response?.data?.message || "Failed to generate certificate",
                  confirmButtonColor: "#FF8A00",
              });
          } finally {
              loading.value = false;
          }
      };

      // Automatically generate certificate when component mounts
      onMounted(() => {
          generateCertificate();
      });

      return {
          loading,
          qrCodeImage,
          currentDate,
          firstname,
          lastname,
          courseName
      };
  },
};
</script>

<style scoped>
.certificate-details-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.certificate-card {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
  text-align: center;
  width: 500px;
}

.certificate-title {
  font-size: 24px;
  font-weight: bold;
  margin-bottom: 15px;
}

.certificate-body {
  margin-bottom: 20px;
}

.student-name {
  font-size: 22px;
  font-weight: bold;
  color: #2c3e50;
}

.course-name {
  font-size: 20px;
  font-weight: bold;
  color: #34495e;
}

.qr-section {
  margin-top: 15px;
}

.qr-code-image {
  width: 150px;
  height: 150px;
}

.qr-text {
  font-size: 14px;
  color: #666;
}

.custom-btn {
  background-color: #FF8A00;
  color: white;
  padding: 10px 15px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
  transition: 0.3s;
}

.custom-btn:hover {
  background-color: #1a73e8;
}
</style>
