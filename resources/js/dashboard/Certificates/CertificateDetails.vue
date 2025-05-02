<template>
    <div class="certificate-details-container">
        <!-- Loading State -->
        <div v-if="loading" class="loading-state">
            <div class="spinner"></div>
            <p>Generating your certificate...</p>
        </div>

        <!-- Error State -->
        <div v-else-if="error" class="error-state">
            <div class="error-icon">❌</div>
            <p class="error-message">{{ error }}</p>
            <p class="redirect-message">Redirecting to certificates page...</p>
        </div>

        <!-- Certificate Preview -->
        <div style="width: 100%;" v-else>
            <CertificatePreview
                :candidateName="`${firstname} ${lastname}`"
                :programName="courseName"
                :creditHours="'40'"
                :completionDate="currentDate"
                :qrCode="qrCodeImage"
                :certNum="`${cert_num}`"
            />
            <div class="download-button-container">
                <button @click="downloadCertificate" class="download-btn">
                    <i class='bx bx-download'></i>
                    Download Certificate
                </button>
            </div>
        </div>
    </div>
  </template>

  <script>
  import { ref, computed, onMounted } from "vue";
  import axios from "axios";
  import { useRoute, useRouter } from "vue-router";
  import CertificatePreview from './CertificatePreview.vue';
  import html2canvas from 'html2canvas';
  import jsPDF from 'jspdf';

  export default {
    name: "CertificateDetails",
    components: {
      CertificatePreview
    },
    setup() {
        const route = useRoute();
        const router = useRouter();
        const loading = ref(true);
        const qrCodeImage = ref(null);
        const firstname = ref("");
        const cert_num = ref("");
        const lastname = ref("");
        const courseName = ref("");
        const error = ref(null);

        const currentDate = computed(() => {
            return new Date().toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
            });
        });

        const generateCertificate = async () => {
            loading.value = true;
            error.value = null;

            try {
                const examId = route.params.id;
                const response = await axios.get(`/certificates/generate/${examId}`);

                // Update the data
                qrCodeImage.value = response.data.certificate.qr_code.startsWith('data:')
                  ? response.data.certificate.qr_code
                  : `data:image/svg+xml;base64,${response.data.certificate.qr_code}`;
                firstname.value = response.data.user_firstname;
                cert_num.value = response.data.cert_num;
                lastname.value = response.data.user_lastname;
                courseName.value = response.data.course_name;

                loading.value = false;
            } catch (e) {
                console.error('Certificate generation error:', e.response?.data);
                loading.value = false;

                if (e.response?.status === 404) {
                    error.value = "Exam record not found. Please ensure you have completed this exam.";
                } else if (e.response?.status === 403) {
                    // Check if we have passed courses data
                    const passedCourses = e.response?.data?.['Passed courses'] || [];
                    const examId = route.params.id;
                    const currentExam = passedCourses.find(course => course.exam_id === parseInt(examId));

                    if (currentExam && currentExam.score >= 70) {
                        // If we found the course and score is passing, retry generation
                        try {
                            const retryResponse = await axios.post(`/certificates/generate/${examId}`, {
                                score: currentExam.score,
                                course_id: currentExam.id,
                                completed_at: currentExam.completed_at
                            });

                            // Update the data
                            qrCodeImage.value = retryResponse.data.certificate.qr_code.startsWith('data:')
                              ? retryResponse.data.certificate.qr_code
                              : `data:image/svg+xml;base64,${retryResponse.data.certificate.qr_code}`;
                            firstname.value = retryResponse.data.user_firstname;
                            lastname.value = retryResponse.data.user_lastname;
                            courseName.value = retryResponse.data.course_name;
                            cert_num.value = retryResponse.data.cert_num;

                            loading.value = false;
                            return;
                        } catch (retryError) {
                            console.error('Retry error:', retryError);
                            error.value = "Failed to generate certificate. Please try again or contact support.";
                        }
                    } else {
                        error.value = e.response.data.error || "You are not authorized to view this certificate.";
                    }
                } else {
                    error.value = "Failed to generate certificate. Please try again later.";
                }

                setTimeout(() => {
                    router.push('/dashboard/certificates');
                }, 3000);
            }
        };

        const downloadCertificate = async () => {
            const certificateElement = document.querySelector('.certificate-frame');

            try {
                // Show loading state
                loading.value = true;

                // Capture the certificate as an image
                const canvas = await html2canvas(certificateElement, {
                    scale: 2, // Higher quality
                    useCORS: true, // Enable loading of external images
                    logging: false
                });

                // Calculate dimensions for A4 size
                const imgWidth = 210; // A4 width in mm
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                // Create PDF
                const pdf = new jsPDF('p', 'mm', 'a4');
                pdf.addImage(canvas.toDataURL('image/jpeg', 1.0), 'JPEG', 0, 0, imgWidth, imgHeight);

                // Download the PDF
                pdf.save(`${firstname.value}_${lastname.value}_certificate.pdf`);
            } catch (error) {
                console.error('Error generating PDF:', error);
                error.value = "Failed to generate PDF. Please try again.";
            } finally {
                loading.value = false;
            }
        };

        onMounted(() => {
            generateCertificate();
        });

        return {
            loading,
            qrCodeImage,
            currentDate,
            firstname,
            lastname,
            courseName,
            error,
            cert_num,
            downloadCertificate
        };
    },
  };
  </script>

  <style scoped>
  .certificate-details-container {
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 20px;
      background-color: #f5f5f5;
  }

  .certificate-card {
      background: #fff;
      width: 800px;
      min-height: 600px;
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
      position: relative;
      margin: 20px auto;
  }

  .certificate-content {
      border: 2px solid #FF8A00;
      padding: 40px;
      position: relative;
      height: 100%;
      display: flex;
      flex-direction: column;
      align-items: center;
      background: linear-gradient(to bottom, rgba(255, 138, 0, 0.05), transparent);
  }

  .certificate-title {
      font-size: 36px;
      color: #FF8A00;
      text-align: center;
      margin-bottom: 40px;
      font-weight: bold;
      text-transform: uppercase;
      letter-spacing: 2px;
  }

  .certificate-body {
      text-align: center;
      margin-bottom: 40px;
      flex-grow: 1;
  }

  .recipient {
      font-size: 18px;
      color: #666;
      margin-bottom: 15px;
  }

  .student-name {
      font-size: 32px;
      color: #333;
      margin: 20px 0;
      font-weight: bold;
      text-transform: capitalize;
  }

  .achievement {
      font-size: 18px;
      color: #666;
      margin: 15px 0;
  }

  .course-name {
      font-size: 24px;
      color: #FF8A00;
      margin: 20px 0;
      font-weight: bold;
  }

  .date {
      font-size: 18px;
      color: #666;
      margin-top: 30px;
  }

  .qr-section {
      margin-top: 30px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 10px;
  }

  .qr-code-image {
      width: 120px;
      height: 120px;
      object-fit: contain;
      padding: 10px;
      background: white;
      border: 1px solid #eee;
      border-radius: 8px;
  }

  .qr-text {
      font-size: 14px;
      color: #666;
  }

  /* Loading State */
  .loading-state {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 400px;
  }

  .spinner {
      border: 4px solid rgba(255, 138, 0, 0.1);
      border-top: 4px solid #FF8A00;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      animation: spin 1s linear infinite;
      margin-bottom: 20px;
  }

  @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
  }

  /* Error State */
  .error-state {
      text-align: center;
      padding: 40px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
  }

  .error-icon {
      font-size: 48px;
      margin-bottom: 20px;
  }

  .error-message {
      color: #dc3545;
      font-size: 18px;
      margin-bottom: 15px;
  }

  .redirect-message {
      color: #666;
      font-size: 14px;
  }

  /* Responsive Design */
  @media (max-width: 900px) {
      .certificate-card {
          width: 100%;
          padding: 20px;
          margin-top: 34px;
      }

      .certificate-content {
          padding: 20px;
      }

      .certificate-title {
          font-size: 28px;
      }

      .student-name {
          font-size: 26px;
      }

      .course-name {
          font-size: 20px;
      }
  }

  @media (max-width: 480px) {
      .certificate-details-container {
          padding: 10px;
      }

      .certificate-title {
          font-size: 24px;
      }

      .student-name {
          font-size: 22px;
      }

      .recipient,
      .achievement,
      .date {
          font-size: 16px;
      }

      .course-name {
          font-size: 18px;
      }
  }

  .download-button-container {
      display: flex;
      justify-content: center;
      margin-top: 2rem;
      margin-bottom: 2rem;
  }

  .download-btn {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      padding: 1rem 2rem;
      background: linear-gradient(45deg, #FF8A00, #FF8A00);
      color: white;
      border: none;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 1rem;
  }

  .download-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 15px rgba(255, 138, 0, 0.3);
  }

  .download-btn i {
      font-size: 1.2rem;
  }
  </style>
