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
                            <div v-for="(_, index) in 2" :key="index" 
                                 class="video-watermark"
                                 :style="{
                                     top: index === 0 ? '30%' : '70%',
                                     left: '50%',
                                     animationDelay: `${index * 2}s`
                                 }">
                                {{ watermarkText }}
                            </div>
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
        const watermarkText = ref('safetyfirsthub.com')

        const getRandomPosition = () => {
            const positions = [
                { top: '25%', left: '25%' },
                { top: '25%', left: '75%' },
                { top: '75%', left: '25%' },
                { top: '75%', left: '75%' },
                { top: '50%', left: '50%' },
            ];
            return positions[Math.floor(Math.random() * positions.length)];
        }

        const watermarkPosition = ref(getRandomPosition());

        const fetchCourse = async () => {
            try {
                const response = await axios.get(`/api/courses/${route.params.id}`)
                const courses = response.data.data || response.data
                let fetchedCourse = Array.isArray(courses) 
                    ? courses.find(c => c.id === parseInt(route.params.id))
                    : courses
                course.value = {
                    id: fetchedCourse.id,
                    name: fetchedCourse.name || fetchedCourse.title,
                    description: fetchedCourse.description || 'No description available',
                    cover: fetchedCourse.cover,
                    total_videos: fetchedCourse.total_videos || 0,
                    students: fetchedCourse.students || 0,
                    videoUrl: fetchedCourse.videoUrl || fetchedCourse.cover,
                    instructor: fetchedCourse.instructor || 'John Doe',
                    price: fetchedCourse.price || 'Free',
                    duration: fetchedCourse.duration || '2h 30min',
                    email: fetchedCourse.email || 'student@example.com'
                }
                
                // Update watermark with course data
                watermarkText.value = `${course.value.email} | safetyfirsthub.com`
                
                console.log('Enhanced course data:', course.value)
            } catch (error) {
                console.error('Error fetching course:', error)
            }
        }

        onMounted(() => {
            fetchCourse()
        })

        return {
            course,
            videoPlayer,
            watermarkText,
            watermarkPosition
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

.video-watermark {
    position: absolute;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: min(3vw, 32px); /* Increased from 2vw/24px to 3vw/32px */
    color: rgba(255, 255, 255, 0.3);
    pointer-events: none;
    user-select: none;
    white-space: nowrap;
    z-index: 1000;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    font-weight: bold;
    animation: fadeInOut 8s infinite;
    width: 90%;
    text-align: center;
}

@keyframes fadeInOut {
    0%, 100% { opacity: 0.2; }
    50% { opacity: 0.4; }
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
