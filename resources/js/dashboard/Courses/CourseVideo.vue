<template>
    <div class="course-video-container">
        <div class="container py-4">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><router-link to="/dashboard/courses">Courses</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ course?.name || course?.title }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="video-container">
                            <video 
                                ref="videoPlayer"
                                class="w-100"
                                controls
                                autoplay
                            >
                                <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title">{{ course?.name || course?.title }}</h2>
                            <p class="card-text">{{ course?.description }}</p>
                            <div class="course-meta">
                                <span class="badge bg-primary me-2">{{ course?.duration || '2h 30min' }}</span>
                                <span class="text-muted"><i class="fas fa-users"></i> {{ course?.students || 0 }} students</span>
                            </div>
                            <div class="course-instructor">
                                <span class="text-muted">Instructor: {{ course?.instructor || 'Instructor' }}</span>
                            </div>
                            <div class="course-price mt-2">
                                <span class="badge bg-success">{{ course?.price ? `$${course.price}` : 'Free' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import axios from 'axios'

export default {
    name: 'CourseVideo',
    setup() {
        const route = useRoute()
        const course = ref(null)
        const videoPlayer = ref(null)

        const fetchCourse = async () => {
            try {
                const response = await axios.get(`/api/courses/${route.params.id}`)
                const courses = response.data.data || response.data
                course.value = Array.isArray(courses) 
                    ? courses.find(c => c.id === parseInt(route.params.id))
                    : courses
            } catch (error) {
                console.error('Error fetching course:', error)
            }
        }

        onMounted(() => {
            fetchCourse()
        })

        return {
            course,
            videoPlayer
        }
    }
}
</script>

<style scoped>
.course-video-container {
    background-color: #f8f9fa;
    min-height: 100vh;
}

.course-image-container {
    margin-bottom: 20px;
}

.course-image-container img {
    width: 100%;
    height: auto;
    object-fit: cover;
    border-radius: 15px;
}

.video-container {
    background-color: #000;
    position: relative;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
}

.video-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.course-meta {
    margin-top: 1rem;
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.course-instructor,
.course-price {
    margin-top: 1rem;
}
</style>
