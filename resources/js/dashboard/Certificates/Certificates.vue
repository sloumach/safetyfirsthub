<template>
  <div class="certificates-dashboard certificates-section">
    <div class="celebration-background" v-if="certificateCourses.length > 0">
      <div class="firework"></div>
      <div class="firework"></div>
      <div class="firework"></div>
      <div class="firework"></div>
      <div class="firework"></div>
    </div>

    <div class="exams-header">
      <h1>🎉 Your Achievement Gallery 🎉</h1>
      <p class="subtitle">Showcase your completed certifications</p>
    
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="loading-state">
      <div class="loader"></div>
    </div>

    <!-- Empty State -->
    <div v-else-if="certificateCourses.length === 0" class="empty-state">
      <i class='bx bx-award empty-icon'></i>
      <p>Complete courses and exams to earn your certificates</p>
    </div>

    <!-- Certificates Grid -->
    <div v-else class="certificates-grid">
      <div class="certificate-card" v-for="course in certificateCourses" :key="course.id">
        <div class="certificate-ribbon"></div>
        <div class="certificate-inner">
          <div class="certificate-header">
            <div class="certificate-seal">
              <i class='bx bxs-medal'></i>
            </div>
            <div class="certificate-title">
              <span class="certified-text">CERTIFIED</span>
              <h3>{{ course.name }}</h3>
            </div>
          </div>

          <div class="certificate-body">
            <div class="certificate-image">
              <img 
                :src="course.cover || 'https://placehold.co/600x400/003366/ffffff?text=Course'" 
                :alt="course.name"
                @error="handleImageError"
              />
              <div class="image-overlay"></div>
            </div>

            <div class="certificate-details">
              <p>{{ course.description }}</p>
              <div class="achievement-info">
                <div class="info-item">
                  <i class='bx bx-calendar-check'></i>
                  <span>Completed</span>
                </div>
                <div class="info-item">
                  <i class='bx bx-trophy'></i>
                  <span>Excellence</span>
                </div>
              </div>
            </div>

            <button @click="viewCertificate(course.exam_id)" class="view-btn">
              <span>View Certificate</span>
              <i class='bx bx-right-arrow-alt'></i>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
  name: 'Certificates',
  setup() {
    const router = useRouter()
    const courses = ref([])
    const loading = ref(true)

    const certificateCourses = computed(() => {
      return courses.value.filter(course => course.examcheck === true)
    })

    const handleImageError = (e) => {
      e.target.src = 'https://placehold.co/600x400/003366/ffffff?text=Course'
    }

    const fetchCourses = async () => {
      try {
        loading.value = true
        const response = await axios.get('/api/courses')
        
        if (response.data) {
          courses.value = [...response.data]
        } else {
          console.error('No course data received')
          courses.value = []
        }

      } catch (error) {
        console.error('Error fetching courses:', error)
        courses.value = []
      } finally {
        loading.value = false
      }
    }

    const viewCertificate = (exam_id) => {
      router.push(`/dashboard/certificate/${exam_id}`)
    }

    onMounted(() => {
      fetchCourses()

    })

    return {
      certificateCourses,
      loading,
      handleImageError,
      viewCertificate
    }
  }
}
</script>

<style scoped>
.certificates-section {
    padding-top: 100px !important;
    padding-bottom: 100px !important;
}

.certificates-header {
    text-align: center !important;
    margin-bottom: 3rem !important;
    position: relative !important;
    z-index: 2 !important;
    padding: 2rem !important;
}

.certificates-header h2 {
    font-size: 2.5rem !important;
    font-weight: 800 !important;
    background: linear-gradient(45deg, #1a1f36, #FF8A00) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
    margin-bottom: 0.5rem !important;
}

.subtitle {
    color: #4f566b !important;
    font-size: 1.1rem !important;
}

/* Loading State */
.loading-state {
    display: flex !important;
    justify-content: center !important;
    align-items: center !important;
    min-height: 200px !important;
}
.exams-header {
    text-align: center !important;
    margin-bottom: 3rem !important;
}

.exams-header h2 {
    font-size: 2.5rem !important;
    font-weight: 700 !important;
    color: #1a1f36 !important;
    margin-bottom: 0.5rem !important;
}
.loader {
    width: 48px !important;
    height: 48px !important;
    border: 5px solid #f3f3f3 !important;
    border-top: 5px solid #FF8A00 !important;
    border-radius: 50% !important;
    animation: spin 1s linear infinite !important;
}

/* Empty State */
.empty-state {
    text-align: center !important;
    padding: 3rem !important;
    color: #4f566b !important;
}

.empty-icon {
    font-size: 4rem !important;
    color: #c3c8d5 !important;
    margin-bottom: 1rem !important;
}

/* Certificates Grid */
.certificates-grid {
    display: grid !important;
    grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)) !important;
    gap: 2.5rem !important;
    padding: 1.5rem !important;
}

.certificate-card {
    position: relative !important;
    background: #ffffff !important;
    border-radius: 20px !important;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
    overflow: hidden !important;
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) !important;
}

.certificate-ribbon {
    position: absolute !important;
    top: -4px !important;
    right: 30px !important;
    width: 30px !important;
    height: 60px !important;
    background: linear-gradient(45deg, #FFD700, #FFA500) !important;
    border-radius: 0 0 5px 5px !important;
    box-shadow: 0 4px 12px rgba(255, 215, 0, 0.3) !important;
}

.certificate-ribbon::before {
    content: '' !important;
    position: absolute !important;
    top: 0 !important;
    left: -15px !important;
    width: 30px !important;
    height: 30px !important;
    background: linear-gradient(45deg, #FFD700, #FFA500) !important;
    transform: rotate(45deg) !important;
    border-radius: 5px !important;
}

.certificate-inner {
    padding: 2rem !important;
}

.certificate-header {
    display: flex !important;
    align-items: center !important;
    gap: 1.5rem !important;
    margin-bottom: 2rem !important;
}

.certificate-seal {
    width: 60px !important;
    height: 60px !important;
    background: linear-gradient(45deg, #FF8A00, #FF8A00) !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    box-shadow: 0 4px 15px rgba(0, 82, 204, 0.2) !important;
}

.certificate-seal i {
    font-size: 2rem !important;
    color: white !important;
}

.certificate-title {
    flex: 1 !important;
}

.certified-text {
    display: block !important;
    font-size: 0.85rem !important;
    font-weight: 600 !important;
    color: #FF8A00 !important;
    margin-bottom: 0.5rem !important;
    letter-spacing: 2px !important;
}

.certificate-title h3 {
    font-size: 1.4rem !important;
    font-weight: 700 !important;
    color: #1a1f36 !important;
    line-height: 1.3 !important;
}

.certificate-body {
    position: relative !important;
}

.certificate-image {
    position: relative !important;
    border-radius: 15px !important;
    overflow: hidden !important;
    margin-bottom: 1.5rem !important;
}

.certificate-image img {
    width: 100% !important;
    height: 200px !important;
    object-fit: cover !important;
    transition: transform 0.5s ease !important;
}

.image-overlay {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.2) 100%) !important;
}

.certificate-details {
    margin-bottom: 1.5rem !important;
}

.certificate-details p {
    color: #4f566b !important;
    line-height: 1.6 !important;
    margin-bottom: 1.5rem !important;
}

.achievement-info {
    display: flex !important;
    gap: 2rem !important;
    margin-bottom: 1.5rem !important;
}

.info-item {
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    color: #1a1f36 !important;
    font-size: 0.9rem !important;
}

.info-item i {
    color: #FF8A00 !important;
    font-size: 1.2rem !important;
}

.view-btn {
    width: 100% !important;
    padding: 1rem !important;
    border: none !important;
    border-radius: 12px !important;
    background: linear-gradient(45deg, #FF8A00, #FF8A00) !important;
    color: white !important;
    font-weight: 600 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.75rem !important;
    transition: all 0.3s ease !important;
    cursor: pointer !important;
    font-size: 1rem !important;
}

.view-btn:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 20px rgba(0, 82, 204, 0.25) !important;
}

.view-btn i {
    transition: transform 0.3s ease !important;
}

.view-btn:hover i {
    transform: translateX(5px) !important;
}

.certificate-card:hover {
    transform: translateY(-10px) !important;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
}

.certificate-card:hover .certificate-image img {
    transform: scale(1.1) !important;
}

@keyframes spin {
    0% { transform: rotate(0deg) !important; }
    100% { transform: rotate(360deg) !important; }
}

/* Responsive Design */
@media (max-width: 768px) {
    .certificates-dashboard {
        padding: 2rem !important;
    }

    .certificates-header h2 {
        font-size: 2rem !important;
    }

    .certificates-grid {
        grid-template-columns: 1fr !important;
        gap: 2rem !important;
        padding: 1rem !important;
    }
    .exams-header h2 {
        font-size: 2rem !important;
    }

    .certificate-inner {
        padding: 1.5rem !important;
    }

    .certificate-header {
        gap: 1rem !important;
    }

    .certificate-title h3 {
        font-size: 1.2rem !important;
    }
}

@media (max-width: 480px) {
    .certificates-header h2 {
        font-size: 1.75rem !important;
    }
    .exams-header h2 {
        font-size: 1.75rem !important;
    }
    .subtitle {
        font-size: 1rem !important;
    }

    .achievement-badge {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.8rem !important;
    }
}

.celebration-background {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    overflow: hidden !important;
    z-index: 1 !important;
    pointer-events: none !important;
}

.celebration-text {
    margin-top: 1rem !important;
    font-size: 1.2rem !important;
    color: #0052cc !important;
    font-weight: 600 !important;
    animation: bounce 2s infinite !important;
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0) !important;
    }
    50% {
        transform: translateY(-10px) !important;
    }
}

.firework {
    position: absolute !important;
    pointer-events: none !important;
}

.firework::before, .firework::after {
    content: '' !important;
    position: absolute !important;
    width: 5px !important;
    height: 5px !important;
    border-radius: 50% !important;
    box-shadow: -120px -218.66667px #0052cc, 248px -16.66667px #00ff84,
        190px 16.33333px #002bff, -113px -308.66667px #ff009d,
        -109px -287.66667px #ffb300, -50px -313.66667px #ff006e,
        226px -31.66667px #ff4000, 180px -351.66667px #ff00d0,
        -12px -338.66667px #00f6ff, 220px -388.66667px #99ff00,
        -69px -27.66667px #ff0400, -111px -339.66667px #6200ff,
        155px -237.66667px #00ddff, -152px -380.66667px #00ffd0,
        -50px -37.66667px #00ffdd, -95px -175.66667px #a6ff00,
        -88px 10.33333px #0d00ff, 112px -309.66667px #005eff,
        69px -415.66667px #ff00a6, 168px -100.66667px #ff004c,
        -244px 24.33333px #ff6600, 97px -325.66667px #ff0066,
        -211px -182.66667px #00ffa2, 236px -126.66667px #b700ff,
        140px -196.66667px #9000ff, 125px -175.66667px #00bbff,
        118px -381.66667px #ff002f, 144px -111.66667px #ffae00,
        36px -78.66667px #f600ff, -63px -196.66667px #c800ff,
        -218px -227.66667px #d4ff00, -134px -377.66667px #ea00ff,
        -36px -412.66667px #ff00d4, 209px -106.66667px #00fff2,
        91px -278.66667px #000dff, -22px -191.66667px #9dff00,
        139px -392.66667px #a6ff00, 56px -2.66667px #0099ff,
        -156px -276.66667px #ea00ff, -163px -233.66667px #00fffb,
        -238px -346.66667px #00ff73, 62px -363.66667px #0088ff,
        244px -170.66667px #0062ff, 224px -142.66667px #b300ff,
        141px -208.66667px #9000ff, 211px -285.66667px #ff6600,
        181px -128.66667px #1e00ff, 90px -123.66667px #c800ff,
        189px 70.33333px #00ffc8, -18px -383.66667px #00ff33,
        100px -6.66667px #ff008c !important;
    animation: 1s bang ease-out infinite backwards,
        1s gravity ease-in infinite backwards,
        5s position linear infinite backwards !important;
}

.firework::after {
    animation-delay: 1.25s, 1.25s, 1.25s !important;
    animation-duration: 1.25s, 1.25s, 6.25s !important;
}

@keyframes bang {
    to {
        box-shadow: -120px -218.66667px transparent, 248px -16.66667px transparent,
            190px 16.33333px transparent, -113px -308.66667px transparent,
            -109px -287.66667px transparent, -50px -313.66667px transparent,
            226px -31.66667px transparent, 180px -351.66667px transparent,
            -12px -338.66667px transparent, 220px -388.66667px transparent,
            -69px -27.66667px transparent, -111px -339.66667px transparent,
            155px -237.66667px transparent, -152px -380.66667px transparent,
            -50px -37.66667px transparent, -95px -175.66667px transparent,
            -88px 10.33333px transparent, 112px -309.66667px transparent,
            69px -415.66667px transparent, 168px -100.66667px transparent,
            -244px 24.33333px transparent, 97px -325.66667px transparent,
            -211px -182.66667px transparent, 236px -126.66667px transparent,
            140px -196.66667px transparent, 125px -175.66667px transparent,
            118px -381.66667px transparent, 144px -111.66667px transparent,
            36px -78.66667px transparent, -63px -196.66667px transparent,
            -218px -227.66667px transparent, -134px -377.66667px transparent,
            -36px -412.66667px transparent, 209px -106.66667px transparent,
            91px -278.66667px transparent, -22px -191.66667px transparent,
            139px -392.66667px transparent, 56px -2.66667px transparent,
            -156px -276.66667px transparent, -163px -233.66667px transparent,
            -238px -346.66667px transparent, 62px -363.66667px transparent,
            244px -170.66667px transparent, 224px -142.66667px transparent,
            141px -208.66667px transparent, 211px -285.66667px transparent,
            181px -128.66667px transparent, 90px -123.66667px transparent,
            189px 70.33333px transparent, -18px -383.66667px transparent,
            100px -6.66667px transparent !important;
    }
}

@keyframes gravity {
    to {
        transform: translateY(200px) !important;
        opacity: 0 !important;
    }
}

@keyframes position {
    0%, 19.9% {
        margin-top: 10% !important;
        margin-left: 40% !important;
    }
    20%, 39.9% {
        margin-top: 40% !important;
        margin-left: 30% !important;
    }
    40%, 59.9% {
        margin-top: 20% !important;
        margin-left: 70% !important;
    }
    60%, 79.9% {
        margin-top: 30% !important;
        margin-left: 20% !important;
    }
    80%, 99.9% {
        margin-top: 30% !important;
        margin-left: 80% !important;
    }
}
</style> 