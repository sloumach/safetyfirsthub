<template>
    <div class="course-video-container">
        <div class="container py-4">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><router-link to="/dashboard/courses">Courses</router-link></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ course?.title }}</li>
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
                                <source :src="course?.videoUrl" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title">{{ course?.title }}</h2>
                            <p class="card-text">{{ course?.description }}</p>
                            <div class="course-meta">
                                <span class="badge bg-primary me-2">{{ course?.duration }}</span>
                                <span class="text-muted"><i class="fas fa-users"></i> {{ course?.students }} students</span>
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

export default {
    name: 'CourseVideo',
    setup() {
        const route = useRoute()
        const course = ref(null)
        const videoPlayer = ref(null)

        onMounted(() => {
            // In a real application, you would fetch the course data from an API
            // This is just mock data for demonstration
            course.value = {
                id: parseInt(route.params.id),
                title: 'Introduction to Programming',
                description: 'Learn the fundamentals of programming in this introductory course.',
                duration: '2h 30min',
                videoUrl: 'https://www.w3schools.com/html/mov_bbb.mp4',
                students: 1234
            }
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
</style> 