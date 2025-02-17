<template>
    <div class="courses-container courses-section">
        <h2 class="text-2xl font-bold mb-4 text-center">Explore Our Courses</h2>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <div class="col" v-for="course in courses" :key="course.id">
                <div class="card h-100 course-card">
                    <div class="course-image position-relative">
                        <!-- Display course cover image -->
                        <img 
                            :src="course.cover || 'https://placehold.co/600x400/003366/ffffff?text=Course'" 
                            :alt="course.name"
                            class="card-img-top"
                            @error="handleImageError"
                        />
                        <div class="course-overlay d-flex align-items-center justify-content-center">
                            <span class="badge duration-badge">{{ course.total_videos }} videos</span>
                        </div>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">{{ course.name }}</h5>
                        <p class="card-text text-muted">{{ course.description }}</p>
                    </div>
                    <div class="card-footer bg-white border-0 d-flex flex-column align-items-center">
                        <span class="text-muted"><i class="fas fa-users"></i> {{ course.students || 0 }} students</span>
                        <button @click="navigateToVideo(course.id)" class="btn custom-btn w-75 mt-3">
                            Start Learning
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

export default {
    name: 'Courses',
    setup() {
        const router = useRouter()
        const courses = ref([])

        const handleImageError = (e) => {
            e.target.src = 'https://placehold.co/600x400/003366/ffffff?text=Course'
        }

        const fetchCourses = async () => {
            try {
                const response = await axios.get('/api/courses')
                courses.value = response.data
            } catch (error) {
                // console.error('Error details:', {
                //     message: error.message,
                //     response: error.response,
                //     status: error.response?.status,
                //     data: error.response?.data
                // })
            }
        }

        const navigateToVideo = (courseId) => {
            router.push(`/dashboard/courses/${courseId}/video`)
        }

        onMounted(() => {
            fetchCourses()
        })

        return {
            courses,
            navigateToVideo,
            handleImageError
        }
    }
}
</script>


<style scoped>
.courses-container {
    padding: 30px;
    background-color: #f8f9fa;
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
    background: rgba(0, 0, 0, 0.6);
    border-radius: 5px;
    padding: 5px 10px;
}

.duration-badge {
    font-size: 0.85rem;
    background-color: black;
    color: white;
    padding: 5px 10px;
    border-radius: 5px;
}

.card-title {
    font-size: 1.3rem;
    font-weight: 700;
    margin-bottom: 0.75rem;
}

.card-text {
    font-size: 0.95rem;
    line-height: 1.5;
    color: #6c757d;
}

.card-footer span {
    font-size: 0.9rem;
    color: #6c757d;
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

.courses-section {
    padding-top: 100px !important;
    padding-bottom: 100px !important;
}

@media (max-width: 576px) {
    .courses-section {
        padding-top: 105px !important;
        padding-bottom: 100px !important;
    }
}
</style>
