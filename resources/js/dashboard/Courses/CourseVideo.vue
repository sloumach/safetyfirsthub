<template>
    <div class="course-video-container">
        <div class="container py-3"> <!-- Reduced padding -->
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2"> <!-- Reduced margin -->
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
                            <!-- First Watermark (Top-Left) -->
                            <div v-if="!showPreview" :class="['watermark', 'watermark-top-left', { 'watermark-fullscreen': isFullScreen }]">
    {{ watermarkText }}
</div>

<!-- Bottom-Right Watermark -->
<div v-if="!showPreview" :class="['watermark', 'watermark-bottom-right', { 'watermark-fullscreen': isFullScreen }]">
    {{ watermarkText }}
</div>

                            <!-- Preview Image & Play Button -->
                            <div v-if="showPreview" class="video-overlay" @click="playVideo">
                                <img v-if="previewImage" :src="previewImage" alt="Preview" class="preview-image" />
                                <button class="play-button">▶</button>
                            </div>

                            <!-- Video Player -->
                            <video ref="videoPlayer" class="w-100" controls v-show="!showPreview" @play="hidePreview">
                                <source :src="videoUrl" type="video/mp4" />
                                Your browser does not support the video tag.
                            </video>
                        </div>

                        <div class="card-body p-3"> <!-- Reduced padding -->
                            <h2 class="card-title mb-2">{{ course?.name || course?.title }}</h2> <!-- Reduced margin -->
                            <p class="card-text small mb-2">{{ course?.description }}</p> <!-- Smaller text and reduced margin -->
                            <div class="course-meta d-flex align-items-center gap-2 mb-2"> <!-- Flex layout for meta -->
                                <span class="badge bg-primary">
                                    {{ course?.duration || '2h 30min' }}
                                </span>
                                <span class="text-muted small">
                                    <i class="fas fa-users"></i> {{ course?.students || 0 }} students
                                </span>
                            </div>
                            <div class="course-instructor small text-muted mb-2"> <!-- Smaller text and reduced margin -->
                                Instructor: {{ course?.instructor || 'Instructor' }}
                            </div>
                            <div class="course-price">
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
import { ref, onMounted, onBeforeUnmount, watch } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";

const videoUrl = ref("");
const route = useRoute();
const router = useRouter(); // Vue Router instance
const showPreview = ref(true);
const videoPlayer = ref(null);
const previewImage = ref("");
const course = ref(null);
const watermarkText = ref("safetyfirsthub.com");
const isFullScreen = ref(false);

// Disable right-click
const disableRightClick = (event) => {
    event.preventDefault();
};

// Handle visibility change
const handleVisibilityChange = () => {
    if (document.hidden) {
    } else {
        // When user comes back, redirect them
        router.push("/dashboard/courses");
    }
};

// Play video and hide preview
const playVideo = () => {
    showPreview.value = false;
    videoPlayer.value.play();
};

// Hide preview when video starts
const hidePreview = () => {
    showPreview.value = false;
};

// Handle full-screen toggle
const onFullScreenChange = () => {
    isFullScreen.value = !!(
        document.fullscreenElement || 
        document.webkitFullscreenElement || 
        document.mozFullScreenElement || 
        document.msFullscreenElement
    );
};

onMounted(() => {
    document.addEventListener("fullscreenchange", onFullScreenChange);
    document.addEventListener("webkitfullscreenchange", onFullScreenChange);
    document.addEventListener("mozfullscreenchange", onFullScreenChange);
    document.addEventListener("msfullscreenchange", onFullScreenChange);

    // Disable right-click on the page
    document.addEventListener("contextmenu", disableRightClick);
    document.addEventListener("keydown", blockDevTools);
    document.addEventListener("visibilitychange", handleVisibilityChange);

    fetchCourse();
    fetchVideo();
});

onBeforeUnmount(() => {
    document.removeEventListener("fullscreenchange", onFullScreenChange);
    document.removeEventListener("webkitfullscreenchange", onFullScreenChange);
    document.removeEventListener("mozfullscreenchange", onFullScreenChange);
    document.removeEventListener("msfullscreenchange", onFullScreenChange);
    document.removeEventListener("visibilitychange", handleVisibilityChange);
    // Remove right-click prevention when leaving the page
    document.removeEventListener("contextmenu", disableRightClick);
    document.removeEventListener("keydown", blockDevTools);

    // Redirect to Courses page when leaving this page
    router.push("/dashboard/courses");
});

const fetchVideo = async () => {
    try {
        const courseId = route.params.id;
        // Fixed URL template string
        videoUrl.value = `/api/courses/${courseId}`;
    } catch (error) {
        // console.error("Error fetching video URL", error);
    }
};

const fetchCourse = async () => {
    try {
        const response = await axios.get(`/api/course/${route.params.id}`);
        const fetchedCourse = response.data.data || response.data;

        course.value = {
            id: fetchedCourse.id,
            name: fetchedCourse.name || fetchedCourse.title,
            description: fetchedCourse.description || "No description available",
            cover: fetchedCourse.cover,
            total_videos: fetchedCourse.total_videos || 0,
            students: fetchedCourse.students || 0,
            videoUrl: fetchedCourse.videoUrl || fetchedCourse.cover,
            instructor: fetchedCourse.instructor || "John Doe",
            price: fetchedCourse.price || "Free",
            duration: fetchedCourse.duration || "2h 30min",
            email: fetchedCourse.email || "student@example.com",
        };

        previewImage.value = course.value.cover;
        // Correct template string for watermark
        watermarkText.value = `${course.value.email} | safetyfirsthub.com`;
    } catch (error) {
        // console.error("Error fetching course:", error);
    }
};

const blockDevTools = (event) => {
    if (
        event.key === "F12" || 
        (event.ctrlKey && event.shiftKey && event.key === "I") || 
        (event.metaKey && event.altKey && event.key === "I") // For Mac Cmd+Opt+I
    ) {
        event.preventDefault();
    }
};

// Watch for changes in route parameters and refetch data
watch(() => route.params.id, async (newId, oldId) => {
    if (newId !== oldId) {
        await fetchCourse();
        await fetchVideo();
    }
});
</script>


<style scoped>
.watermark {
    position: absolute; /* Position it over the video */
    color: rgba(255, 255, 255, 0.7);
    font-size: 20px;
    font-weight: bold;
    z-index: 9999;  /* Ensure it's on top of everything */
    pointer-events: none;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    background: transparent;
    padding: 8px 16px;
    border-radius: 5px;
    transform: rotate(-20deg);
    opacity: 0.1;
}

/* First Watermark (Top-Left) */
.watermark-top-left {
    top: 22%;
    left: 10%;
}

.watermark-bottom-right {
    bottom: 31%;
    right: 10%;
}

/* Full-screen watermark styling */
.watermark-fullscreen {
    font-size: 24px;  /* Increase size for visibility */
    opacity: 0.8;     /* Increase opacity */
    z-index: 10000;   /* Ensure it's on top of everything */
    position: fixed;  /* Stay in a fixed position on the screen */
}

/* Full-screen specific positioning */
.watermark-fullscreen.watermark-top-left {
    top: 5%;  /* Slightly closer to the top-left corner */
    left: 5%;
}

.watermark-fullscreen.watermark-bottom-right {
    bottom: 5%;  /* Slightly closer to the bottom-right corner */
    right: 5%;
}

.course-video-container {
    background-color: #f8f9fa;
    min-height: 100vh;
}

.video-container {
    position: relative;
    background-color: #000;
    padding-top: 40%; /* Adjusted to make the video container smaller */
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

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 3;
    cursor: pointer;
}

.play-button {
    width: 70px; /* Smaller play button */
    height: 70px;
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
    font-size: 28px; /* Smaller play icon */
    color: black;
    font-weight: bold;
    margin-left: 3px;
}

.play-button:hover {
    transform: scale(1.1);
}

.course-meta {
    margin-top: 0.5rem; /* Reduced margin */
}

.card {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.course-instructor,
.course-price {
    margin-top: 0.5rem; /* Reduced margin */
}
</style>