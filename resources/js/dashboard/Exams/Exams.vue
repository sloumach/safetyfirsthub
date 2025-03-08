<template>
  <div class="exams-dashboard exams-section">

    <div class="exams-header">
      <h2>Available Exams</h2>
      <p class="subtitle">Test your knowledge and earn certificates</p>
    </div>

    <div class="exams-grid">
      <div class="exam-card" v-for="course in paginatedCourses" :key="course.id">
        <div class="exam-card-inner">
          <div class="exam-image">
            <img
              :src="course.cover || 'https://placehold.co/600x400/003366/ffffff?text=Course'"
              :alt="course.name"
              @error="handleImageError"
            />
            <div class="exam-badge">
              <i class='bx bx-video'></i>
              {{ course.total_videos }} videos required
            </div>
          </div>

          <div class="exam-content">
            <h3>{{ course.name }}</h3>
            <p>{{ course.description }}</p>

            <div class="exam-info">
              <div class="info-item">
                <i class='bx bx-user'></i>
                <span>{{ course.students || 0 }} students</span>
              </div>
              <div class="info-item">
                <i class='bx bx-time'></i>
                <span>60 minutes</span>
              </div>
            </div>

            <button
              @click="handleButtonClick(course)"
              :class="['exam-button', { 'certificate-button': course.examcheck }]"
            >
              <i class='bx bx-trophy'></i>
              {{ getButtonText(course) }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modern Pagination -->
    <div class="modern-pagination" v-if="totalPages > 1">
      <button
        class="nav-btn prev"
        @click="changePage(currentPage - 1)"
        :disabled="currentPage === 1"
      >
        <i class='bx bx-chevron-left'></i>
      </button>

      <div class="page-numbers">
        <button
          v-for="page in displayedPages"
          :key="page"
          @click="changePage(page)"
          :class="['page-btn', { active: currentPage === page }]"
        >
          {{ page }}
        </button>
      </div>

      <button
        class="nav-btn next"
        @click="changePage(currentPage + 1)"
        :disabled="currentPage === totalPages"
      >
        <i class='bx bx-chevron-right'></i>
      </button>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
  name: 'Exams',
  setup() {
    const router = useRouter()
    const courses = ref([])
    const route = useRoute()
    const currentPage = ref(1)
    const itemsPerPage = ref(4)

    const handleImageError = (e) => {
      e.target.src = 'https://placehold.co/600x400/003366/ffffff?text=Course'
    }

    const fetchCourses = async () => {
      try {
        const response = await axios.get('/api/courses')
        courses.value = [...response.data]
      } catch (error) {
        console.error('Error fetching courses:', error)
      }
    }

    const checkCourseCompletion = async (courseId) => {
      try {
        const response = await axios.get(`/video/progress/course/${courseId}`)
        return response.data.completed
      } catch (error) {
        console.error("Error checking course completion:", error)
        return false
      }
    }

    const handleButtonClick = async (course) => {
      if (course.examcheck) {
        window.location.href = '/dashboard/certificate'
      } else {
        const isCompleted = await checkCourseCompletion(course.id)

        if (isCompleted) {
          router.push(`/dashboard/exams/${course.id}`)
        } else {
          await Swal.fire({
            title: 'Access Denied',
            text: 'You need to complete all sections of this course before taking the exam.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK'
          })
        }
      }
    }

    const getButtonText = (course) => {
      return course.examcheck ? 'Get Your Certificate' : 'Take Exam'
    }

    watch(courses, (newValue) => {
      console.log('Courses changed:', {
        length: newValue.length,
        totalPages: totalPages.value,
        currentPage: currentPage.value
      })
    })

    const totalPages = computed(() => {
      return Math.ceil(courses.value.length / itemsPerPage.value)
    })

    const shouldShowPagination = computed(() => {
      return courses.value.length > itemsPerPage.value
    })

    const paginatedCourses = computed(() => {
      const start = (currentPage.value - 1) * itemsPerPage.value
      const end = start + itemsPerPage.value
      return courses.value.slice(start, end)
    })

    const displayedPages = computed(() => {
      const pages = []
      const maxVisiblePages = 5
      let start = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2))
      let end = Math.min(totalPages.value, start + maxVisiblePages - 1)

      if (end - start + 1 < maxVisiblePages) {
        start = Math.max(1, end - maxVisiblePages + 1)
      }

      for (let i = start; i <= end; i++) {
        pages.push(i)
      }
      return pages
    })

    const changePage = (page) => {
      if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page
      }
    }

    onMounted(() => {
      fetchCourses()
    })

    return {
      courses,
      handleButtonClick,
      handleImageError,
      getButtonText,
      currentPage,
      totalPages,
      paginatedCourses,
      changePage,
      displayedPages,
      shouldShowPagination,
      itemsPerPage
    }
  }
}
</script>


<style scoped>
.exams-dashboard {
    padding: 2rem !important;
    min-height: 100vh !important;
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

.subtitle {
    color: #4f566b !important;
    font-size: 1.1rem !important;
}

.exams-grid {
    display: grid !important;
    grid-template-columns: repeat(4, 1fr) !important;
    gap: 1.5rem !important;
    padding: 1rem !important;
    max-width: 1400px !important;
    margin: 0 auto !important;
}

.exam-card {
    background: #ffffff !important;
    border-radius: 20px !important;
    overflow: hidden !important;
    transition: all 0.3s ease !important;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05) !important;
    width: 100% !important;
    margin: 0 auto !important;
}

.exam-card:hover {
    transform: translateY(-8px) !important;
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
}

.exam-card-inner {
    height: 100% !important;
    display: flex !important;
    flex-direction: column !important;
}

.exam-image {
    position: relative !important;
    padding-top: 60% !important;
    overflow: hidden !important;
}

.exam-image img {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
    transition: transform 0.5s ease !important;
}

.exam-card:hover .exam-image img {
    transform: scale(1.1) !important;
}
.exams-section {
    padding-top: 100px !important;
    padding-bottom: 100px !important;
}
.exam-badge {
    position: absolute !important;
    top: 1rem !important;
    right: 1rem !important;
    background: rgba(255, 255, 255, 0.95) !important;
    padding: 0.5rem 1rem !important;
    border-radius: 30px !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    font-size: 0.9rem !important;
    color: #1a1f36 !important;
    backdrop-filter: blur(4px) !important;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1) !important;
}

.exam-content {
    padding: 1.2rem !important;
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
}

.exam-content h3 {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
    color: #1a1f36 !important;
    margin-bottom: 0.75rem !important;
}

.exam-content p {
    color: #4f566b !important;
    font-size: 0.9rem !important;
    line-height: 1.6 !important;
    margin-bottom: 1rem !important;
    flex: 1 !important;
}

.exam-info {
    display: flex !important;
    justify-content: space-between !important;
    margin-bottom: 1.5rem !important;
}

.info-item {
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    color: #4f566b !important;
    font-size: 0.9rem !important;
}

.exam-button {
    width: 100% !important;
    padding: 1rem !important;
    border: none !important;
    border-radius: 12px !important;
    background: linear-gradient(45deg, #FF8A00, #ff6b6b) !important;
    color: white !important;
    font-weight: 600 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    gap: 0.75rem !important;
    transition: all 0.3s ease !important;
    cursor: pointer !important;
}

.exam-button:hover {
    transform: translateY(-2px) !important;
    box-shadow: 0 8px 16px rgba(255, 138, 0, 0.2) !important;
}

.certificate-button {
    background: linear-gradient(45deg, #00b09b, #96c93d) !important;
}

.certificate-button:hover {
    box-shadow: 0 8px 16px rgba(0, 176, 155, 0.2) !important;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .exams-grid {
        grid-template-columns: repeat(3, 1fr) !important;
    }
}

@media (max-width: 992px) {
    .exams-grid {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

@media (max-width: 576px) {
    .exams-grid {
        grid-template-columns: 1fr !important;
    }
}

@media (max-width: 768px) {
    .exams-dashboard {
        padding: 1rem !important;
    }

    .exams-header h2 {
        font-size: 2rem !important;
    }

    .exams-grid {
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }

    .exam-content {
        padding: 1.25rem !important;
    }
}

@media (max-width: 480px) {
    .exams-header h2 {
        font-size: 1.75rem !important;
    }

    .subtitle {
        font-size: 1rem !important;
    }

    .exam-badge {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.8rem !important;
    }
}

/* Modern Pagination Styling */
.modern-pagination {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  margin-top: 50px;
  padding: 20px 0;
}

.page-numbers {
  display: flex;
  gap: 8px;
}

.page-btn, .nav-btn {
  background: #ffffff;
  border: none;
  padding: 8px 16px;
  min-width: 40px;
  height: 40px;
  border-radius: 8px;
  font-weight: 500;
  color: #666;
  cursor: pointer;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.page-btn:hover:not(.active):not(:disabled) {
  background: #f0f0f0;
  transform: translateY(-2px);
}

.page-btn.active {
  background: #FF8A00;
  color: white;
  font-weight: 600;
  transform: scale(1.05);
  box-shadow: 0 4px 12px rgba(255, 138, 0, 0.2);
}

.nav-btn {
  font-size: 20px;
  padding: 8px 12px;
}

.nav-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  background: #f5f5f5;
}

.nav-btn:not(:disabled):hover {
  background: #f0f0f0;
  transform: translateY(-2px);
}

/* Responsive adjustments */
@media (max-width: 480px) {
  .modern-pagination {
    gap: 6px;
  }

  .page-btn, .nav-btn {
    min-width: 36px;
    height: 36px;
    padding: 6px 12px;
    font-size: 14px;
  }
}
</style>
