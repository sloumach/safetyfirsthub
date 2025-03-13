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
                            <div v-for="(section, sectionIndex) in sections" :key="section.id" class="section">
                                <!-- Section Header -->
                                <div class="section-header" @click="handleSectionClick(section, sectionIndex)"
                                    :class="{ 'locked': !canAccessSection(sectionIndex) }">
                                    <h5>
                                        <i class="fas"
                                            :class="{ 'fa-chevron-down': !openSections[section.id], 'fa-chevron-up': openSections[section.id] }"></i>
                                        {{ section.title }}
                                        <span class="section-status">
                                            <i v-if="!canAccessSection(sectionIndex)" class="fas fa-lock"></i>
                                            <i v-else-if="sectionProgress[section.id]"></i>
                                        </span>
                                    </h5>
                                </div>
                                <!-- Contenu de la Section -->
                                <div class="section-content" :class="{ 'show': openSections[section.id] }">
                                    <!-- 🔹 Affichage des slides (contenu écrit du cours) -->
                                    <div v-if="section.slides.length > 0">
                                        <div v-for="slide in section.slides" :key="slide.id" class="section-item"
                                            @click="selectContent({ type: 'text', title: slide.title, content: slide.content })">
                                            <i class="fas fa-file-alt"></i>
                                            {{ slide.title }}
                                        </div>
                                    </div>
                                    <!-- 🔹 Affichage des vidéos -->
                                    <div v-if="section.videos.length > 0">
                                        <div v-for="(video, index) in section.videos" :key="video.id"
                                            class="section-item" :class="{ 'locked': !canAccessVideo(section, index) }"
                                            @click="handleVideoClick(section, video, index)" style="cursor: pointer;">
                                            <i class="fas"
                                                :class="video.is_completed ? 'fa-check-circle' : 'fa-play-circle'"></i>
                                            {{ video.title }}
                                            <span v-if="!canAccessVideo(section, index)" class="section-status">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <span v-else-if="video.is_completed" class="section-status completed">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                        </div>
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
                        <!-- 🎥 Section Player -->
                        <div v-if="currentContent.type === 'video'" class="video-content">
                            <div class="video-container">
                                <!-- Single watermark with random position -->
                                <div class="watermark" :style="watermarkPosition">
                                    {{ watermarkText }}
                                </div>

                                <div v-if="showPreview" class="video-overlay" @click="playVideo">
                                    <img v-if="previewImage" :src="previewImage" alt="Preview" class="preview-image" />
                                    <button class="play-button">▶</button>
                                </div>

                                <video ref="videoPlayer" class="w-100" controls v-show="!showPreview"
                                    @play="hidePreview"
                                    @timeupdate="updateProgress(currentContent.section_id, currentContent.video_id, $event)"
                                    @ended="markAsCompleted(currentContent.section_id, currentContent.video_id)"
                                    @seeking="preventSeeking" @webkitfullscreenchange="handleFullscreenChange"
                                    @mozfullscreenchange="handleFullscreenChange"
                                    @fullscreenchange="handleFullscreenChange">
                                    <source :src="videoUrl" type="video/mp4" />
                                    Your browser does not support the video tag.
                                </video>
                            </div>

                            <div class="video-messages">
                                <div v-if="isCompleted" class="video-status-message success">

                                    <span>Vous avez terminé cette vidéo ! Vous pouvez maintenant passer l'examen.</span>
                                </div>
                                <div v-else class="video-status-message warning">
                                    <i class="fas fa-clock"></i>
                                    <span>Vous devez regarder toute la vidéo avant de passer l'examen.</span>
                                </div>
                            </div>
                        </div>

                        <!-- Documentation Content -->
                        <div v-if="currentContent.type === 'text'" class="documentation-container">
                            <div class="doc-container">
                                <div class="doc-header">
                                    <h2 class="doc-title">{{ currentContent.title }}</h2>
                                    <div class="doc-breadcrumb">
                                        <i class="fas fa-book-open"></i>
                                        <span>Documentation</span>
                                    </div>
                                </div>

                                <div class="doc-content" v-html="currentContent.content"></div>
                            </div>
                        </div>

                        <!-- Card body only shows for video content -->
                        <!-- <div v-if="currentContent.type === 'video'" class="card-body p-3">
                            <h2 class="card-title mb-2">{{ course?.name || course?.title }}</h2>
                            <p class="card-text small mb-2">{{ course?.description }}</p>

                            <div class="course-meta d-flex align-items-center gap-2 mb-2">
                                <span class="badge bg-primary">
                                    {{ course?.duration || '2h 30min' }}
                                </span>
                                <span class="text-muted small">
                                    <i class="fas fa-users"></i> {{ course?.students || 0 }} students
                                </span>
                            </div>
                            <div class="course-instructor small text-muted mb-2">

                                Instructor: {{ course?.instructor || 'Instructor' }}
                            </div>
                            <div class="course-price">
                                <span class="badge bg-success">
                                    {{ course?.price ? `$${course.price}` : 'Free' }}
                                </span>
                            </div>
                        </div>
                        -->
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
import Swal from "sweetalert2";
import PreventSecurity from '@/dashboard/Security/security.js';

const route = useRoute();
const router = useRouter();
const course = ref(null);
const sections = ref([]); // Récupérer dynamiquement les sections
const videoPlayer = ref(null);
const showPreview = ref(true);
const isCompleted = ref(false);
const currentContent = ref({ type: 'text', title: '', content: '' });
const openSections = ref({}); // Gère l'ouverture des sections
const sectionProgress = ref({}); // Gère la progression par section.
const videoUrl = ref("");
const lastTime = ref(0);
const watermarkText = ref('safetyfirsthub.com');
const watermarkPosition = ref({});
const isVideoSessionActive = ref(true);
const lastValidTime = ref(0);
const isForwardAttempted = ref(false);
const hasStartedPlaying = ref(false);

const preventSeeking = (e) => {
    if (videoPlayer.value) {
        const newTime = videoPlayer.value.currentTime;

        // If video hasn't started playing yet, force it to start from beginning
        if (!hasStartedPlaying.value && newTime > 0) {
            videoPlayer.value.pause();
            videoPlayer.value.currentTime = 0;

            Swal.fire({
                title: 'Action Non Autorisée',
                text: 'Vous devez commencer la vidéo depuis le début.',
                icon: 'warning',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                videoPlayer.value.play();
            });

            return false;
        }

        // Normal forward seeking prevention
        if (newTime > lastValidTime.value + 1) {
            videoPlayer.value.pause();
            videoPlayer.value.currentTime = lastValidTime.value;

            Swal.fire({
                title: 'Action Non Autorisée',
                text: 'Vous ne pouvez pas avancer dans la vidéo.',
                icon: 'warning',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                videoPlayer.value.play();
            });

            e.preventDefault();
            e.stopPropagation();
            return false;
        }

        // Allow seeking backwards after video has started
        if (hasStartedPlaying.value && newTime <= lastValidTime.value) {
            lastValidTime.value = newTime;
        }
    }
};

const getRandomPosition = () => {
    const positions = [
        { top: '41%', left: '17%' },

    ];
    return positions[Math.floor(Math.random() * positions.length)];
};

// 🔄 Récupérer les détails du cours
const fetchCourse = async () => {
    try {
        const response = await axios.get(`/api/course/${route.params.id}`);
        course.value = response.data;

        // Set random position for watermark
        watermarkPosition.value = getRandomPosition();

        // Update watermark text with email
        watermarkText.value = `${course.value.email || 'student@example.com'} | safetyfirsthub.com`;

    } catch (error) {
        console.error('Error fetching course:', error);
        Swal.fire({ title: 'Erreur', text: 'Impossible de charger le cours.', icon: 'error' });
        router.push("/dashboard/courses");
    }
};
const fetchVideo = async (videoId) => {
    try {


        const response = await axios.get(`/api/videos/${videoId}/video-url`);


        if (response.data.video_url) {
            // Force reset the video player state
            if (videoPlayer.value) {
                videoPlayer.value.pause();
                videoPlayer.value.currentTime = 0;
            }

            // Reset all states
            showPreview.value = false;
            videoUrl.value = '';  // Clear first
            await new Promise(resolve => setTimeout(resolve, 50)); // Small delay
            videoUrl.value = response.data.video_url;  // Then set new URL
            isCompleted.value = false;

            // Update current content
            currentContent.value = {
                type: 'video',
                title: 'Lecture de la vidéo',
                videoUrl: response.data.video_url,
                video_id: videoId,
                section_id: response.data.section_id
            };

        }
    } catch (error) {
        console.error("Error fetching video:", error);
        Swal.fire({
            title: 'Erreur',
            text: 'Impossible de charger la vidéo.',
            icon: 'error',
            confirmButtonColor: '#3085d6',
        });
    }
};

// 📌 Récupérer les sections dynamiquement
const fetchSections = async () => {
    try {
        const response = await axios.get(`/courses/${route.params.id}/sections`);
        sections.value = response.data.sections;

        // Initialize sections state
        sections.value.forEach((section, index) => {
            openSections.value[section.id] = index === 0; // Open first section
            sectionProgress.value[section.id] = section.videos.every(video => video.is_completed);
        });

        // Automatically select the first slide of the first section
        if (sections.value.length > 0) {
            const firstSection = sections.value[0];
            if (firstSection.slides && firstSection.slides.length > 0) {
                const firstSlide = firstSection.slides[0];
                selectContent({
                    type: 'text',
                    title: firstSlide.title,
                    content: firstSlide.content
                });
            }
        }
    } catch (error) {
        console.error("Erreur lors du chargement des sections :", error);
    }
};

// 📌 Suivi de la progression
const updateProgress = async (sectionId, videoId, event) => {
    if (!videoId) return;

    const currentTime = Math.floor(event.target.currentTime);
    const totalDuration = Math.floor(event.target.duration);

    // Update lastValidTime only during normal playback
    if (currentTime <= lastValidTime.value + 1) {
        lastValidTime.value = currentTime;
    }



    try {
        await axios.post(`/video/progress/update`, {
            video_id: videoId,
            section_id: sectionId,
            current_time: currentTime,
            total_duration: totalDuration,
            is_completed: false
        });


    } catch (error) {
        console.error("🚨 Erreur lors de la mise à jour de la progression :", error);
    }
};

// ✅ Marquer une vidéo comme complétée
const markAsCompleted = async (section_id, video_id) => {
    if (isForwardAttempted.value) {
        Swal.fire({
            title: 'Erreur',
            text: 'Vous devez regarder la vidéo entièrement sans avancer.',
            icon: 'error'
        });
        return;
    }

    if (!isVideoSessionActive.value) {
        return; // Don't mark as completed if session is inactive
    }

    try {

        await axios.post(`/video/progress/complete`, {
            video_id: video_id,
            section_id: section_id
        });

        isCompleted.value = true;

        // Mettre à jour la progression de la section
        const section = sections.value.find(sec => sec.videos.some(video => video.id === video_id));
        const currentSection = sections.value.find(sec => sec.id === section_id);
        if (currentSection) {
            // Update the videos completion status
            const videoIndex = currentSection.videos.findIndex(v => v.id === video_id);
            if (videoIndex !== -1) {
                currentSection.videos[videoIndex].is_completed = true;
            }

            // Check if all videos in the section are completed
            const allVideosCompleted = currentSection.videos.every(video => video.is_completed);
            if (allVideosCompleted) {
                sectionProgress.value[section_id] = true;

                // Find the next section and make it accessible
                const nextSectionIndex = sections.value.findIndex(sec => sec.id === section_id) + 1;
                if (nextSectionIndex < sections.value.length) {
                    const nextSection = sections.value[nextSectionIndex];
                    sectionProgress.value[nextSection.id] = false; // Reset but make it accessible

                    // Optionally show a notification that next section is unlocked
                    Swal.fire({
                        title: 'Section Déverrouillée',
                        text: 'La section suivante est maintenant accessible !',
                        icon: 'success',
                        timer: 2000
                    });
                }
            }
        }
    } catch (error) {
        console.error("Erreur lors de la complétion de la vidéo :", error);
    }
};

// 🔄 Gérer l'ouverture des sections
const toggleSection = (sectionId) => {
    // Simply toggle the section open/closed state without any checks
    openSections.value[sectionId] = !openSections.value[sectionId];
};

// 🔄 Sélectionner un contenu
const selectContent = (content) => {
    currentContent.value = content;
    showPreview.value = false;

};

// Add these new functions
const canAccessVideo = (section, videoIndex) => {
    // First video is always accessible
    if (videoIndex === 0) return true;

    // Check if previous video is completed
    return section.videos[videoIndex - 1]?.is_completed;
};

const handleVideoClick = (section, video, index) => {
    if (!canAccessVideo(section, index)) {
        Swal.fire({
            title: 'Vidéo verrouillée',
            text: 'Vous devez terminer la vidéo précédente avant de pouvoir accéder à celle-ci.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
        });
        return;
    }

    fetchVideo(video.id);
};

// Add/modify these functions
const canAccessSection = (sectionIndex) => {
    // First section is always accessible
    if (sectionIndex === 0) return true;

    // Check if previous section is completed
    const previousSection = sections.value[sectionIndex - 1];
    return sectionProgress.value[previousSection.id] === true;
};

const handleSectionClick = (section, sectionIndex) => {
    if (!canAccessSection(sectionIndex)) {
        Swal.fire({
            title: 'Section verrouillée',
            text: 'Vous devez terminer la section précédente avant de pouvoir accéder à celle-ci.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
        });
        return;
    }

    toggleSection(section.id);
};

// Add fullscreen handler
const handleFullscreenChange = () => {
    const watermarks = document.querySelectorAll('.watermark');
    const isFullscreen = document.fullscreenElement !== null;

    watermarks.forEach(watermark => {
        if (isFullscreen) {
            watermark.classList.add('watermark-fullscreen');
        } else {
            watermark.classList.remove('watermark-fullscreen');
        }
    });
};

// Reset when changing videos
const handleContentClick = (section, content) => {
    hasStartedPlaying.value = false;
    lastValidTime.value = 0;
    currentContent.value = {
        type: 'video',
        video_id: content.id,
        section_id: section.id
    };
    fetchVideo(content.id);
};

// Update play handler to track video start
const hidePreview = () => {
    showPreview.value = false;
    if (videoPlayer.value && videoPlayer.value.currentTime === 0) {
        hasStartedPlaying.value = true;
        lastValidTime.value = 0;
    }
};

const reportSecurityBreach = async (reason) => {
    console.log('Security breach reported:', reason);
    
    // First redirect
    await router.push("/dashboard/courses");
    
    // Then show alert
    await Swal.fire({
        title: 'Security Alert',
        text: 'Your video session has ended due to security violation.',
        icon: 'warning',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        allowEscapeKey: false,
    });
};

// 🎯 Montage du composant
onMounted(() => {
    fetchCourse();
    fetchSections();
    
    console.log('Setting up security...'); // This should show in console
    
    // Set the security callback first
    PreventSecurity.setSecurityCallback(reportSecurityBreach);
    
    // Then initialize video security
    PreventSecurity.initVideo(
        router,
        videoPlayer.value,
        currentContent,
        isCompleted
    );
});


// 📌 Détection des changements de route
watch(() => route.params.id, async (newId, oldId) => {
    if (newId !== oldId) {
        await fetchCourse();
        await fetchSections();

    }
});

// Add a watch for videoUrl changes
watch(videoUrl, (newUrl) => {

    if (videoPlayer.value) {
        videoPlayer.value.load(); // Force reload the video player
    }
});

// 🛑 Nettoyage
onBeforeUnmount(() => {
    PreventSecurity.cleanupVideo();

    if (videoPlayer.value) {
        videoPlayer.value.removeEventListener('seeking', preventSeeking, true);
        videoPlayer.value.removeEventListener('seeked', preventSeeking, true);
    }
});
</script>



<style scoped>
.watermark {
    position: absolute;
    color: #ffffff80;
    font-size: 28px;
    font-weight: 600;
    z-index: 100;
    pointer-events: none;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, .3);
    background: transparent;
    padding: 6px 12px;
    transform: rotate(-15deg);
    opacity: .4;
    -webkit-user-select: none;
    -moz-user-select: none;
    user-select: none;
    white-space: nowrap;
    font-family: Arial, sans-serif;
    letter-spacing: .5px;
}

/* Fullscreen watermark styles */
.video-container:fullscreen .watermark {
    position: fixed;
    font-size: 20px;
    opacity: 0.5;
}

/* Support for different browser prefixes */
.video-container:-webkit-full-screen .watermark {
    position: fixed;
    font-size: 20px;
    opacity: 0.5;
}

.video-container:-moz-full-screen .watermark {
    position: fixed;
    font-size: 20px;
    opacity: 0.5;
}

.video-container:-ms-fullscreen .watermark {
    position: fixed;
    font-size: 20px;
    opacity: 0.5;
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
    padding-top: 56.25%;
    /* 16:9 Aspect Ratio */
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
    width: 70px;
    /* Smaller play button */
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
    font-size: 28px;
    /* Smaller play icon */
    color: black;
    font-weight: bold;
    margin-left: 3px;
}

.play-button:hover {
    transform: scale(1.1);
}

.course-meta {
    margin-top: 0.5rem;
    /* Reduced margin */
}

.card {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.course-instructor,
.course-price {
    margin-top: 0.5rem;
    /* Reduced margin */
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
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
.documentation-container {
    background: #ffffff;
    min-height: 500px;
}

.doc-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem;
}

.doc-header {
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
}

.doc-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 0.5rem;
}

.doc-breadcrumb {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    font-size: 0.875rem;
}

.doc-breadcrumb i {
    color: #3b82f6;
}

.doc-content {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
    line-height: 1.8;
    color: #374151;
}

/* Typography styles for the content */
.doc-content h1 {
    font-size: 1.875rem;
    font-weight: 700;
    margin: 2rem 0 1rem;
    color: #111827;
}

.doc-content h2 {
    font-size: 1.5rem;
    font-weight: 600;
    margin: 1.5rem 0 1rem;
    color: #1f2937;
}

.doc-content h3 {
    font-size: 1.25rem;
    font-weight: 600;
    margin: 1.25rem 0 0.75rem;
    color: #374151;
}

.doc-content p {
    margin-bottom: 1.25rem;
    font-size: 1rem;
    color: #4b5563;
}

.doc-content ul,
.doc-content ol {
    margin: 1rem 0;
    padding-left: 1.5rem;
}

.doc-content li {
    margin: 0.5rem 0;
}

/* Code blocks */
.doc-content pre {
    background: #f3f4f6;
    padding: 1rem;
    border-radius: 0.5rem;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.doc-content code {
    font-family: 'Fira Code', monospace;
    font-size: 0.875rem;
    background: #f3f4f6;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    color: #dc2626;
}

/* Blockquotes */
.doc-content blockquote {
    border-left: 4px solid #3b82f6;
    padding: 1rem 1.5rem;
    margin: 1.5rem 0;
    background: #f3f4f6;
    color: #4b5563;
    font-style: italic;
}

/* Tables */
.doc-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1.5rem 0;
}

.doc-content th,
.doc-content td {
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
}

.doc-content th {
    background: #f9fafb;
    font-weight: 600;
    text-align: left;
}

/* Info boxes */
.doc-content .info-box {
    background: #dbeafe;
    border-left: 4px solid #3b82f6;
    padding: 1rem;
    margin: 1.5rem 0;
    border-radius: 0.5rem;
}

.doc-content .warning-box {
    background: #fef3c7;
    border-left: 4px solid #d97706;
    padding: 1rem;
    margin: 1.5rem 0;
    border-radius: 0.5rem;
}

/* Links */
.doc-content a {
    color: #3b82f6;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: border-color 0.2s;
}

.doc-content a:hover {
    border-bottom-color: #3b82f6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .doc-container {
        padding: 1rem;
    }

    .doc-title {
        font-size: 1.5rem;
    }

    .doc-content h1 {
        font-size: 1.5rem;
    }

    .doc-content h2 {
        font-size: 1.25rem;
    }

    .doc-content h3 {
        font-size: 1.125rem;
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
    0% {
        transform: scale(1);
    }

    50% {
        transform: scale(1.2);
    }

    100% {
        transform: scale(1);
    }
}

.section-status.completed i {
    animation: completedPulse 0.5s ease-in-out;
}

.section-item.locked {
    opacity: 0.7;
    cursor: not-allowed !important;
    background-color: #f5f5f5;
}

.section-item.locked:hover {
    background-color: #f5f5f5;
}

/* Add these styles */
.section-header.locked {
    opacity: 0.7;
    cursor: not-allowed !important;
    background-color: #f5f5f5;
}

.section-header.locked:hover {
    background-color: #f5f5f5;
}

.section-header.locked h5 {
    color: #999;
}
</style>
