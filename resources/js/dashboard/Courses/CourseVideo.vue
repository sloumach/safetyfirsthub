<template>
    <div class="course-video-container">
        <div class="container py-4">
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard/courses">Courses</router-link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ course?.name || course?.title }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="video-container">
                            <!-- Preview Image & Play Button -->
                            <div v-if="showPreview" class="video-overlay" @click="playVideo">
                                <img v-if="previewImage" :src="previewImage" alt="Preview" class="preview-image" />
                                <button class="play-button">▶</button>
                            </div>

                            <!-- Video Player -->
                            <video
                                ref="videoPlayer"
                                class="w-100"
                                controls
                                v-show="!showPreview"
                                @play="hidePreview"
                            >
                                <source :src="videoUrl" type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>
                        </div>

                        <div class="card-body">
                            <h2 class="card-title">{{ course?.name || course?.title }}</h2>
                            <p class="card-text">{{ course?.description }}</p>
                            <div class="course-meta">
                                <span class="badge bg-primary me-2">
                                    {{ course?.duration || '2h 30min' }}
                                </span>
                                <span class="text-muted">
                                    <i class="fas fa-users"></i> {{ course?.students || 0 }} students
                                </span>
                            </div>
                            <div class="course-instructor">
                                <span class="text-muted">Instructor: {{ course?.instructor || 'Instructor' }}</span>
                            </div>
                            <div class="course-price mt-2">
                                <span class="badge bg-success">
                                    {{ course?.price ? `$${course.price}` : 'Free' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import axios from "axios"; // Ajout de l'import axios

const videoUrl = ref(""); 
const route = useRoute(); 
const showPreview = ref(true);
const videoPlayer = ref(null);
const previewImage = ref("");
const course = ref(null);
const watermarkText = ref('safetyfirsthub.com');

const playVideo = () => {
    showPreview.value = false;
    videoPlayer.value.play();
};

const hidePreview = () => {
    showPreview.value = false;
};

const fetchVideo = async () => {
    try {
        const courseId = route.params.id;
        videoUrl.value = `/api/courses/${courseId}`;
    } catch (error) {
        console.error("Error fetching video URL", error);
    }
};

const fetchCourse = async () => {
    try {
        const response = await axios.get(`/api/course/${route.params.id}`);
        const fetchedCourse = response.data.data || response.data;

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
        };

        previewImage.value = course.value.cover;
        watermarkText.value = `${course.value.email} | safetyfirsthub.com`;
    } catch (error) {
        console.error('Error fetching course:', error);
    }
};

onMounted(() => {
    fetchCourse();
    fetchVideo();
});
</script>

<style scoped>
.course-video-container {
    background-color: #f8f9fa;
    min-height: 100vh;
}

.video-container {
    position: relative;
    background-color: #000;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
}

.video-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.preview-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    z-index: 2;
    cursor: pointer;
}

/* Fix pour le bouton play */
.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4); /* Légère opacité pour améliorer la visibilité */
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3;
    cursor: pointer;
}

.play-button {
    width: 90px;
    height: 90px;
    background: rgba(255, 255, 255, 0.9);
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 4;
    transition: transform 0.2s ease-in-out;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    position: relative;
}

.play-button::before {
    content: '▶';
    font-size: 36px;
    color: black;
    font-weight: bold;
    margin-left: 3px; /* Moves the triangle slightly right to center it */
}

.play-button:hover {
    transform: scale(1.1);
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
