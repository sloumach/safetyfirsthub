<template>
    <div class="course-video-container">
        <div class="container py-3"> <!-- Reduced padding -->
            <div class="row">
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-2"> <!-- Reduced margin -->
                            <li class="breadcrumb-item">
                                <router-link to="/dashboard/courses">Certified Courses</router-link>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ course?.name || course?.title }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <!-- Sidebar Column -->
                <div class="col-lg-3 col-md-4">
                    <div class="course-sidebar">
                        <h4 class="sidebar-title">Course Content</h4>
                        
                        <!-- Course Sections -->
                        <div class="course-sections">
                            <!-- Section 1 -->
                            <div class="section">
                                <div class="section-header" @click="toggleSection(1)">
                                    <h5>
                                        <i class="fas" :class="{'fa-chevron-down': !openSections[1], 'fa-chevron-up': openSections[1]}"></i>
                                        Section 1
                                        <span class="section-status" :class="{ 'completed': sectionProgress[1] }">
                                            <i class="fas" :class="sectionProgress[1] ? 'fa-check-circle' : 'fa-lock'"></i>
                                        </span>
                                    </h5>
                                </div>
                                <div class="section-content" :class="{ 'show': openSections[1] }">
                                    <div class="section-item" @click="selectContent('doc1')">
                                        <i class="fas fa-file-alt"></i>
                                        Documentation
                                    </div>
                                    <div class="section-item" @click="selectContent('video1')">
                                        <i class="fas fa-play-circle"></i>
                                        Video
                                    </div>
                                </div>
                            </div>

                            <!-- Section 2 -->
                            <div class="section" :class="{ 'locked': !sectionProgress[1] }">
                                <div class="section-header" @click="attemptToggleSection(2)">
                                    <h5>
                                        <i class="fas" :class="{'fa-chevron-down': !openSections[2], 'fa-chevron-up': openSections[2]}"></i>
                                        Section 2
                                        <span class="section-status" :class="{ 'completed': sectionProgress[2] }">
                                            <i class="fas" :class="sectionProgress[2] ? 'fa-check-circle' : 'fa-lock'"></i>
                                        </span>
                                    </h5>
                                </div>
                                <div class="section-content" :class="{ 'show': openSections[2] }">
                                    <div class="section-item" @click="attemptSelectContent('doc2')">
                                        <i class="fas fa-file-alt"></i>
                                        Documentation
                                    </div>
                                    <div class="section-item" @click="attemptSelectContent('video2')">
                                        <i class="fas fa-play-circle"></i>
                                        Video
                                    </div>
                                </div>
                            </div>

                          
                        </div>
                    </div>
                </div>

                <!-- Main Content Column -->
                <div class="col-lg-9 col-md-8">
                    <div class="card">
                        <!-- Video Content -->
                        <div v-if="currentContent.type === 'video'" class="video-content">
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
                                <video 
                                    ref="videoPlayer" 
                                    class="w-100" 
                                    controls 
                                    v-show="!showPreview" 
                                    @play="hidePreview"
                                    @timeupdate="updateProgress"
                                    @ended="markAsCompleted"
                                >
                                    <source :src="videoUrl" type="video/mp4" />
                                    Your browser does not support the video tag.
                                </video>
                            </div>

                            <!-- Move messages outside video-container but inside video-content -->
                            <div class="video-messages">
                                <div v-if="isCompleted" class="video-status-message success">
                                    <i class="fas fa-check-circle"></i>
                                    <span>You have completed the course video! You can now take the exam.</span>
                                </div>
                                <div v-else class="video-status-message warning">
                                    <i class="fas fa-clock"></i>
                                    <span>You need to watch the entire video before taking the exam.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Documentation Content -->
                        <div v-if="currentContent.type === 'doc'" class="documentation-container p-4">
                            <div class="doc-container">
                                <div class="doc-breadcrumb">Documentation</div>
                                
                                <div class="doc-content">
                                    <h1>Documentation</h1>
                                    <h2>Safety Training Module</h2>
                                    
                                    <p class="doc-intro">Thank you for enrolling in our safety training course.</p>
                                    
                                    <div class="doc-info">
                                        <div class="info-row">Version: 1.0</div>
                                        <div class="info-row">Created: 15 March, 2024</div>
                                        <div class="info-row">Author: Safety FirstHUB</div>
                                        <div class="info-row">Last Update: 20 March, 2024</div>
                                    </div>

                                    <div class="notice-box">
                                        <div class="notice-icon">ⓘ</div>
                                        <p>If you have any questions that are beyond the scope of this documentation, please feel free to email our support team.</p>
                                    </div>

                                    <section class="doc-section">
                                        <h2>Installation</h2>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam laoreet convallis quam, fermentum molestie lectus laoreet id.</p>
                                    </section>

                                    <section class="doc-section">
                                        <h2>Getting Started</h2>
                                        <p>Nulla facilisi. Aenean ipsum sem, tincidunt ut ex sed, pretium vestibulum sem.</p>
                                    </section>
                                </div>
                            </div>
                        </div>

                        <!-- Card body only shows for video content -->
                        <div v-if="currentContent.type === 'video'" class="card-body p-3">
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
import Swal from 'sweetalert2';

const videoUrl = ref("");
const route = useRoute();
const router = useRouter(); // Vue Router instance
const showPreview = ref(true);
const videoPlayer = ref(null);
const previewImage = ref("");
const course = ref(null);
const watermarkText = ref("safetyfirsthub.com");
const isFullScreen = ref(false);
const isCompleted = ref(false);
const watchedSegments = ref(new Set());
let totalDuration = 0;

// Add new refs for sidebar functionality
const openSections = ref({
    1: true,  // Section 1 open by default
    2: false,
    3: false
});

// Add new refs for section progress
const sectionProgress = ref({
    1: false,
    2: false,
    3: false
});

// Simplify the currentContent ref
const currentContent = ref({
    type: 'video',
    title: ''
});

// Disable right-click
const disableRightClick = (event) => {
    event.preventDefault();
};

// Handle visibility change
const handleVisibilityChange = async () => {
    if (document.hidden) {
        // Do nothing when tab is hidden
    } else {
        await Swal.fire({
            title: 'Session Ended',
            text: 'Your video session has ended due to switching tabs.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
        });
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

// Update window event listeners
window.addEventListener("popstate", async () => {
    if (route.path !== '/dashboard/courses') {
        await Swal.fire({
            title: 'Navigation Detected',
            text: 'Your video session has ended due to navigation.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
        });
        router.push("/dashboard/courses");
    }
});

// Move the blur event listener to onMounted
onMounted(() => {
    // Add blur event listener
    const handleBlur = async () => {
        if (route.path.includes('/video')) {  // Only show alert if we're on the video page
            await Swal.fire({
                title: 'Window Unfocused',
                text: 'Your video session has ended due to switching applications.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
            });
            router.push("/dashboard/courses");
        }
    };
    window.addEventListener("blur", handleBlur);

    // Add other existing event listeners
    document.addEventListener("fullscreenchange", onFullScreenChange);
    document.addEventListener("webkitfullscreenchange", onFullScreenChange);
    document.addEventListener("mozfullscreenchange", onFullScreenChange);
    document.addEventListener("msfullscreenchange", onFullScreenChange);
    document.addEventListener("contextmenu", disableRightClick);
    document.addEventListener("keydown", blockDevTools);
    document.addEventListener("visibilitychange", handleVisibilityChange);

    // Handle refresh attempts (Ctrl+R or F5)
    window.addEventListener('keydown', async (e) => {
        if ((e.ctrlKey && e.key === 'r') || e.key === 'F5') {
            e.preventDefault();
            const result = await Swal.fire({
                title: 'Refresh Attempted',
                text: 'Refreshing will end your video session. Do you want to continue?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, refresh',
                cancelButtonText: 'Cancel'
            });

            if (result.isConfirmed) {
                // Use navigator.sendBeacon for cleanup before refresh
                if (route.params.id) {
                    navigator.sendBeacon(`/api/course/${route.params.id}/end-session`);
                }
                window.location.reload();
            }
        }
    });

    // Add a simple unload handler without preventing default behavior
    window.addEventListener('unload', () => {
        if (route.params.id) {
            navigator.sendBeacon(`/api/course/${route.params.id}/end-session`);
        }
    });

    fetchCourse();
    fetchVideo();

    // Store handleBlur function for cleanup
    window._handleBlur = handleBlur;

    // Add progress check
    
    // Automatically select doc1 and open section 1
    selectContent('doc1');
    openSections.value[1] = true;
});

onBeforeUnmount(async () => {
    try {
        // Only mark as incomplete if we haven't completed the video
        if (!isCompleted.value && videoPlayer.value) {
            await markAsIncomplete();
        }
    } catch (error) {
        console.error('Error during cleanup:', error);
    } finally {
        // Remove event listeners
        if (window._handleBlur) {
            window.removeEventListener("blur", window._handleBlur);
        }
        
        document.removeEventListener("fullscreenchange", onFullScreenChange);
        document.removeEventListener("webkitfullscreenchange", onFullScreenChange);
        document.removeEventListener("mozfullscreenchange", onFullScreenChange);
        document.removeEventListener("msfullscreenchange", onFullScreenChange);
        document.removeEventListener("visibilitychange", handleVisibilityChange);
        document.removeEventListener("contextmenu", disableRightClick);
        document.removeEventListener("keydown", blockDevTools);

        // Redirect to Courses page when leaving this page
        router.push("/dashboard/courses");
    }
});

const fetchVideo = async () => {
    try {
        const courseId = route.params.id;
        // Fixed URL template string
        videoUrl.value = `/api/courses/${courseId}`;
    } catch (error) {
        await Swal.fire({
            title: 'Error',
            text: 'Failed to load video. Please try again.',
            icon: 'error',
            confirmButtonColor: '#3085d6',
        });
        router.push("/dashboard/courses");
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
        await Swal.fire({
            title: 'Error',
            text: 'Failed to load course details. Please try again.',
            icon: 'error',
            confirmButtonColor: '#3085d6',
        });
        router.push("/dashboard/courses");
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

const updateProgress = async () => {
    if (!videoPlayer.value) return;

    const currentTime = Math.floor(videoPlayer.value.currentTime);
    totalDuration = Math.floor(videoPlayer.value.duration);

    watchedSegments.value.add(currentTime);

    // Send progress every 10 seconds
    if (currentTime % 10 === 0) {
        try {
            await axios.post(`/video/progress/update`, {
                current_time: currentTime,
                total_duration: totalDuration,
                course_id: route.params.id,
                is_completed: false // Always false unless explicitly completed
            });
        } catch (error) {
            console.error("Error updating progress:", error);
        }
    }
};

const markAsCompleted = async () => {
    if (!videoPlayer.value) return;
    
    const currentTime = Math.floor(videoPlayer.value.currentTime);
    const duration = Math.floor(videoPlayer.value.duration);
    
    // Only mark as completed if we're at the end of the video
    if (currentTime >= duration - 1) { // -1 to account for small timing differences
        try {
            await axios.post(`/video/progress/complete`, {
                current_time: duration,
                total_duration: duration,
                course_id: route.params.id,
            });
            isCompleted.value = true;
        } catch (error) {
            console.error("Error marking video as completed:", error);
        }
    }
    
    // Update section progress based on current content
    const currentSection = parseInt(currentContent.value.title?.match(/\d+/)?.[0] || 1);
    sectionProgress.value[currentSection] = true;
};

const markAsIncomplete = async () => {
    if (!route.params.id) return; // Guard against missing course_id
    
    try {
        const response = await axios.post(`/video/progress/reset`, {
            course_id: route.params.id
        });
       
    } catch (error) {
        console.error("Error marking video as incomplete:", error);
    }
};

// Toggle section visibility
const toggleSection = (sectionId) => {
    openSections.value[sectionId] = !openSections.value[sectionId];
};

// Modified toggle section function
const attemptToggleSection = (sectionId) => {
    if (sectionId === 1) {
        toggleSection(1);
        return;
    }

    // Check if previous section is completed
    if (!sectionProgress.value[sectionId - 1]) {
        Swal.fire({
            title: 'Section Locked',
            text: 'Please complete the previous section first.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
        });
        return;
    }

    toggleSection(sectionId);
};

// Simplify the selectContent function
const selectContent = (contentId) => {
    if (contentId.startsWith('doc')) {
        currentContent.value = {
            type: 'doc',
            title: 'Documentation'
        };
    } else if (contentId.startsWith('video')) {
        currentContent.value = {
            type: 'video',
            title: course.value?.name || course.value?.title
        };
    }
};

// Modified content selection function
const attemptSelectContent = (contentId) => {
    const sectionNumber = parseInt(contentId.replace(/[^\d]/g, ''));
    
    if (!sectionProgress.value[sectionNumber - 1]) {
        Swal.fire({
            title: 'Content Locked',
            text: 'Please complete the previous section first.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
        });
        return;
    }

    selectContent(contentId);
};
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

.video-content {
    background-color: #000;
}

.video-container {
    position: relative;
    padding-top: 56.25%; /* 16:9 Aspect Ratio */
    width: 100%;
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

.video-messages {
    background: white;
    padding: 0 0;
}

.video-status-message {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    border-radius: 6px;
    margin: 0 0;
    font-size: 14px;
    font-weight: 500;
}

.video-status-message i {
    font-size: 18px;
}

.video-status-message.warning {
    background-color: #FEF3C7;
    color: #92400E;
    border: 1px solid #FCD34D;
}

.video-status-message.success {
    background-color: #D1FAE5;
    color: #065F46;
    border: 1px solid #6EE7B7;
}

/* Remove the old message styles */
.message-success,
.message-warning {
    display: none;
}

@media (max-width: 576px) {
    .course-video-container {
        min-height: 48vh;
        padding-top: 105px !important;
        padding-bottom: 100px !important;
    }

}

/* Original sidebar styles */
.course-sidebar {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 20px;
    margin-bottom: 20px;
}

.sidebar-title {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #FF8A00;
}

.section {
    margin-bottom: 15px;
}

.section-header {
    cursor: pointer;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 5px;
}

.section-header h5 {
    margin: 0;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-content {
    display: none;
    padding: 10px;
}

.section-content.show {
    display: block;
}

.section-item {
    padding: 8px 10px;
    margin: 5px 0;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
    border-radius: 5px;
    transition: background-color 0.2s;
}

.section-item:hover {
    background-color: #f8f9fa;
}

.section-item i {
    color: #FF8A00;
}

/* Documentation styles */
.doc-container {
    padding: 2rem;
    background: #fff;
    color: #374151;
    font-family: system-ui, -apple-system, sans-serif;
}

.doc-breadcrumb {
    color: #6B7280;
    font-size: 0.875rem;
    margin-bottom: 2rem;
}

.doc-content {
    max-width: 65rem;
    margin: 0 auto;
}

.doc-content h1 {
    font-size: 2rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 0.5rem;
    letter-spacing: -0.025em;
}

.doc-content h2 {
    font-size: 1.875rem;
    color: #111827;
    margin-bottom: 1.5rem;
    font-weight: 500;
}

.doc-intro {
    font-size: 1rem;
    color: #4B5563;
    line-height: 1.625;
    margin-bottom: 2rem;
}

.doc-info {
    background: #F9FAFB;
    border-radius: 0.5rem;
    padding: 1.25rem;
    margin: 2rem 0;
}

.info-row {
    color: #4B5563;
    font-size: 0.875rem;
    line-height: 1.75;
}

.notice-box {
    display: flex;
    gap: 1rem;
    background: #F3F4F6;
    border-left: 4px solid #3B82F6;
    padding: 1rem 1.25rem;
    margin: 2rem 0;
    border-radius: 0.375rem;
}

.notice-icon {
    color: #3B82F6;
    font-size: 1.25rem;
}

.notice-box p {
    color: #4B5563;
    font-size: 0.875rem;
    line-height: 1.5;
    margin: 0;
}

.doc-section {
    margin: 2.5rem 0;
    padding-bottom: 2.5rem;
    border-bottom: 1px solid #E5E7EB;
}

.doc-section:last-child {
    border-bottom: none;
}

.doc-section h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111827;
    margin-bottom: 1rem;
}

.doc-section p {
    color: #4B5563;
    font-size: 1rem;
    line-height: 1.75;
}

@media (max-width: 768px) {
    .doc-container {
        padding: 1.5rem;
    }

    .doc-content h1 {
        font-size: 1.75rem;
    }

    .doc-content h2 {
        font-size: 1.5rem;
    }
}

.section-status {
    margin-left: auto;
    font-size: 14px;
}

.section-status i {
    color: #ccc;
}

.section-status.completed i {
    color: #4CAF50;
}

.locked {
    opacity: 0.7;
}

.locked .section-header {
    cursor: not-allowed;
    background: #f0f0f0;
}

.locked .section-item {
    cursor: not-allowed;
    color: #999;
}

.locked .section-item i {
    color: #999;
}

.section-header h5 {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

/* Animation for completion */
@keyframes completedPulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.2); }
    100% { transform: scale(1); }
}

.section-status.completed i {
    animation: completedPulse 0.5s ease-in-out;
}
</style>