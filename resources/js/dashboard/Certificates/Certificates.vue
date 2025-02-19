<template>
  <div class="courses-container certificates-section">
    <h2 class="text-2xl font-bold mb-4 text-center">Your Certificates</h2>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
    </div>

    <!-- Empty State -->
    <div v-else-if="certificateCourses.length === 0" class="text-center py-5">
      <p class="text-muted">No certificates available at the moment.</p>
    </div>

    <!-- Certificates Grid -->
    <div v-else class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
      <div class="col" v-for="course in certificateCourses" :key="course.id">
        <div class="card h-100 course-card">
          <div class="course-image position-relative">
            <img 
              :src="course.cover || 'https://placehold.co/600x400/003366/ffffff?text=Course'" 
              :alt="course.name"
              class="card-img-top"
              @error="handleImageError"
            />
            <div class="course-overlay d-flex align-items-center justify-content-center">
              <span class="badge bg-success">Certificate Available</span>
            </div>
          </div>
          <div class="card-body text-center">
            <h5 class="card-title fw-bold">{{ course.name }}</h5>
            <p class="card-text text-muted">{{ course.description }}</p>
          </div>
          <div class="card-footer bg-white border-0 d-flex flex-column align-items-center">
            <button @click="viewCertificate(course.exam_id)" class="btn custom-btn w-75 mt-3">
              View Certificate
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
        courses.value = [...response.data]
      } catch (error) {
        console.error('Error fetching courses:', error)
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

.courses-container {
  padding: 30px;
  background-color: transparent;
}

.course-card {
  border: none;
  border-radius: 15px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  background-color: white;
}

.course-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.course-image {
  position: relative;
  height: 220px;
  overflow: hidden;
  border-top-left-radius: 15px;
  border-top-right-radius: 15px;
}

.course-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.course-card:hover .course-image img {
  transform: scale(1.08);
}

.course-overlay {
  position: absolute;
  top: 10px;
  right: 10px;
}

.custom-btn {
    border-radius: 30px;
    padding: 0.6rem 1.5rem;
    background-color: var(--main-color);
    border: 2px solid var(--main-color);
    color: white;
    transition: all 0.3s ease;
}

.custom-btn:hover {
    background-color: white;
    color: var(--main-color);
    border: 2px solid var(--main-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.spinner-border {
  width: 3rem;
  height: 3rem;
}
</style> 