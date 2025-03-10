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
                                <div class="section-header" 
                                     @click="handleSectionClick(section, sectionIndex)"
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
                                        <div v-for="(video, index) in section.videos" 
                                             :key="video.id" 
                                             class="section-item"
                                             :class="{ 'locked': !canAccessVideo(section, index) }"
                                             @click="handleVideoClick(section, video, index)"
                                             style="cursor: pointer;">
                                            <i class="fas" :class="video.is_completed ? 'fa-check-circle' : 'fa-play-circle'"></i>
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
                                <video
                                    ref="videoPlayer"
                                    class="video-js vjs-default-skin"
                                    controls
                                    preload="auto"
                                    width="100%"
                                    height="auto"
                                    @seeking="preventSeeking"
                                >
                                    <source :src="videoUrl" type="video/mp4" />
                                </video>
                                <!-- Updated watermark implementation -->
                                <div class="watermark-container" :class="{ 'fullscreen': isFullscreen }">
                                    <div v-for="(_, index) in 2" 
                                         :key="index" 
                                         class="video-watermark"
                                         :style="{
                                             top: index === 0 ? '42%' : '61%',
                                             left: '50%',
                                             animationDelay: `${index * 2}s`
                                         }">
                                        {{ course?.email ? `${course.email} | safetyfirsthub.com` : 'safetyfirsthub.com' }}
                                    </div>
                                </div>
                            </div>

                            <div class="video-messages">
                                <div v-if="isCompleted" class="video-status-message success">
                                    <i class="fas fa-check-circle"></i>
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
import { ref, onMounted, onBeforeUnmount, watch, nextTick } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";
import Swal from "sweetalert2";
import videojs from 'video.js';
import 'video.js/dist/video-js.css';


const route = useRoute();
const router = useRouter();
const course = ref(null);
const sections = ref([]); // Récupérer dynamiquement les sections
const videoPlayer = ref(null);
const player = ref(null);
const showPreview = ref(true);
const isCompleted = ref(false);
const currentContent = ref({ type: 'text', title: '', content: '' });
const openSections = ref({}); // Gère l'ouverture des sections
const sectionProgress = ref({}); // Gère la progression par section.
const videoUrl = ref("");
const lastTime = ref(0);
const lastKnownTime = ref(0);
const lastValidTime = ref(0);
const maxAllowedTime = ref(0);
const isFullscreen = ref(false);

// 🛠 Désactiver clic droit
const disableRightClick = (event) => event.preventDefault();

const preventSeeking = () => {
  if (videoPlayer.value) {
    if (videoPlayer.value.currentTime > lastTime.value + 2) {
      videoPlayer.value.currentTime = lastTime.value; // Bloque l'avance rapide
    } else {
      lastTime.value = videoPlayer.value.currentTime; // Autorise le retour arrière
    }
  }
};

// 🔄 Récupérer les détails du cours
const fetchCourse = async () => {
    try {
        const response = await axios.get(`/api/course/${route.params.id}`);
        course.value = {
            ...response.data,
            email: response.data.email || response.data.user?.email // Try to get email from course or user object
        };
    } catch (error) {
        Swal.fire({ title: 'Erreur', text: 'Impossible de charger le cours.', icon: 'error' });
        router.push("/dashboard/courses");
    }
};
const fetchVideo = async (videoId) => {
    try {
        console.log("Attempting to fetch video:", videoId); // Debug log

        const response = await axios.get(`/api/videos/${videoId}/video-url`);
        console.log("Video response:", response.data); // Debug log
        
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

            console.log("Video URL set to:", videoUrl.value); // Debug log
            console.log("Current content updated:", currentContent.value); // Debug log
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
    if (!videoId) {
        console.error("Erreur : videoId ou sectionId est manquant !");
        return;
    }

    const currentTime = Math.floor(event.target.currentTime);
    const totalDuration = Math.floor(event.target.duration);

    console.log(`✅ Progression mise à jour : Video ${videoId} | ${currentTime}/${totalDuration} sec`);

    try {
        const response = await axios.post(`/video/progress/update`, {
            video_id: videoId,
            section_id: sectionId,
            current_time: currentTime,
            total_duration: totalDuration,
            is_completed: false
        });
        console.log("📌:", currentTime);
        // 📌 Vérifier si la vidéo est déjà terminée
        if (response.data.is_completed) {
            console.log("📌 La vidéo est déjà complétée, pas de mise à jour nécessaire.");
            return;
        }

    } catch (error) {
        console.error("🚨 Erreur lors de la mise à jour de la progression :", error);
    }
};



// ✅ Marquer une vidéo comme complétée
const markAsCompleted = async (section_id,video_id) => {
    try {
        console.log("Marquer la vidéo comme complétée :", video_id);
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

// 📌 Détection des changements de route
watch(() => route.params.id, async (newId, oldId) => {
    if (newId !== oldId) {
        await fetchCourse();
        await fetchSections();

    }
});

// Add these security-related functions
const handleVisibilityChange = async () => {
    if (document.hidden && route.path.includes('/video')) {
        await Swal.fire({
            title: 'Session Ended',
            text: 'Your video session has ended due to switching tabs.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
        });
        router.push("/dashboard/courses");
    }
};

const handleBlur = async () => {
    if (route.path.includes('/video')) {
        await Swal.fire({
            title: 'Window Unfocused',
            text: 'Your video session has ended due to switching applications.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
        });
        router.push("/dashboard/courses");
    }
};

// Block browser navigation
window.addEventListener('popstate', async (e) => {
    if (route.path.includes('/video')) {
        e.preventDefault();
        await Swal.fire({
            title: 'Navigation Detected',
            text: 'Your video session has ended due to navigation.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
        });
        router.push("/dashboard/courses");
    }
});

// 🎯 Montage du composant
onMounted(() => {
    fetchCourse();
    fetchSections();

    // Add all security event listeners
    window.addEventListener("blur", handleBlur);
    document.addEventListener("visibilitychange", handleVisibilityChange);
    document.addEventListener("contextmenu", disableRightClick);

    // Block refresh attempts (F5/Ctrl+R)
    window.addEventListener('keydown', async (e) => {
        if (route.path.includes('/video') && ((e.ctrlKey && e.key === 'r') || e.key === 'F5')) {
            e.preventDefault();
            await Swal.fire({
                title: 'Refresh Attempted',
                text: 'Refreshing will end your video session.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
            });
            router.push("/dashboard/courses");
        }
    });

    // Block browser back/forward buttons
    window.history.pushState(null, '', window.location.href);
    window.addEventListener('popstate', async () => {
        if (route.path.includes('/video')) {
            window.history.pushState(null, '', window.location.href);
            await Swal.fire({
                title: 'Navigation Detected',
                text: 'Please use the application navigation.',
                icon: 'warning',
                confirmButtonColor: '#3085d6',
            });
            router.push("/dashboard/courses");
        }
    });

    // Handle page unload/close
    window.addEventListener('beforeunload', (e) => {
        if (route.path.includes('/video')) {
            e.preventDefault();
            e.returnValue = '';
            router.push("/dashboard/courses");
        }
    });

    initializePlayer();
});

// 🛑 Nettoyage
onBeforeUnmount(() => {
    window.removeEventListener("blur", handleBlur);
    document.removeEventListener("visibilitychange", handleVisibilityChange);
    document.removeEventListener("contextmenu", disableRightClick);
    
    // If we're leaving the video page, redirect to courses
    if (route.path.includes('/video')) {
        router.push("/dashboard/courses");
    }

    if (player.value) {
        player.value.dispose();
    }
});

// Add a watch for videoUrl changes
watch(videoUrl, (newUrl) => {
    console.log("Video URL changed to:", newUrl); // Debug log
    if (player.value) {
        player.value.src({ src: newUrl, type: 'video/mp4' });
        player.value.load(); // Force reload the video player
    }
});

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

// Initialize video.js player
const initializePlayer = () => {
    if (videoPlayer.value) {
        player.value = videojs(videoPlayer.value, {
            controls: true,
            fluid: true,
            playbackRates: [0.5, 1, 1.5, 2],
            controlBar: {
                children: [
                    'playToggle',
                    'volumePanel',
                    'currentTimeDisplay',
                    'timeDivider',
                    'durationDisplay',
                    'progressControl',
                    'fullscreenToggle'
                ]
            },
            userActions: {
                hotkeys: false
            }
        });

        // Add fullscreen change detection
        player.value.on('fullscreenchange', () => {
            isFullscreen.value = player.value.isFullscreen();
        });

        // Add backward button
        const controlBar = player.value.controlBar;
        const backwardButton = controlBar.addChild('button', {}, 1);
        backwardButton.el().innerHTML = '<i class="fas fa-backward-step"></i> -3s';
        backwardButton.on('click', () => handleSeek(-3));

        // Add all necessary event listeners
        player.value.on('play', onPlay);
        player.value.on('timeupdate', onTimeUpdate);
        player.value.on('ended', onEnded);
        player.value.on('seeking', preventForwardSeeking);
        player.value.on('seeked', onSeeked);
        player.value.on('loadedmetadata', onLoadedMetadata);
        player.value.on('pause', onPause);

        // Disable progress bar clicks
        const progressControl = player.value.controlBar.progressControl.el();
        progressControl.style.pointerEvents = 'none';

        // Add mousemove listener to prevent dragging
        player.value.on('mousemove', preventDragging);
    }
};

// Handle video metadata loaded
const onLoadedMetadata = () => {
    if (player.value) {
        lastValidTime.value = 0;
        maxAllowedTime.value = 0;
    }
};

// Prevent dragging on progress bar
const preventDragging = (event) => {
    if (player.value) {
        event.preventDefault();
        event.stopPropagation();
    }
};

// Handle seeking attempts
const preventForwardSeeking = () => {
    if (player.value) {
        const currentTime = player.value.currentTime();
        
        // If trying to seek forward beyond maxAllowedTime
        if (currentTime > maxAllowedTime.value) {
            console.log('Preventing forward seek:', currentTime, 'max:', maxAllowedTime.value);
            player.value.currentTime(lastValidTime.value);
            player.value.play(); // Force continue playing
        }
    }
};

// Handle successful seek
const onSeeked = () => {
    if (player.value) {
        const currentTime = player.value.currentTime();
        if (currentTime > maxAllowedTime.value) {
            player.value.currentTime(lastValidTime.value);
        }
    }
};

// Track playback position
const onTimeUpdate = () => {
    if (player.value) {
        const currentTime = player.value.currentTime();
        
        // Only update if time is moving forward naturally
        if (currentTime - lastValidTime.value <= 0.1) { // Small threshold for normal playback
            lastValidTime.value = currentTime;
            maxAllowedTime.value = currentTime;
            
            updateProgress(
                currentContent.value.section_id,
                currentContent.value.video_id,
                { target: { currentTime } }
            );
        } else {
            // Reset to last valid time if jumped forward
            player.value.currentTime(lastValidTime.value);
        }
    }
};

// Handle pause event
const onPause = () => {
    if (player.value) {
        // Ensure we're at a valid position when paused
        const currentTime = player.value.currentTime();
        if (currentTime > maxAllowedTime.value) {
            player.value.currentTime(lastValidTime.value);
        }
    }
};

// Backward seeking only
const handleSeek = (seconds) => {
    if (player.value && seconds < 0) {
        const newTime = Math.max(0, lastValidTime.value + seconds);
        player.value.currentTime(newTime);
        lastValidTime.value = newTime;
        maxAllowedTime.value = newTime;
    }
};

// Event handlers
const onPlay = () => {
    hidePreview();
};

const onEnded = () => {
    markAsCompleted(currentContent.value.section_id, currentContent.value.video_id);
};

const addWatermark = () => {
    if (player.value) {
        const videoContainer = player.value.el();
        
        // Create watermark container
        const watermark = document.createElement('div');
        watermark.className = 'vjs-watermark';
        watermark.innerHTML = `
            <span class="watermark-text">${window.Laravel.user.email} || safetyfirsthub.com</span>
        `;
        
        // Add watermark to the video container
        const videoWrapper = videoContainer.querySelector('.vjs-tech');
        if (videoWrapper) {
            videoWrapper.parentNode.insertBefore(watermark, videoWrapper.nextSibling);
        }
        
        setInterval(() => {
            moveWatermark(watermark);
        }, 5000);
    }
};
</script>



<style scoped>
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
    font-size: min(3vw, 32px);
    color: rgba(255, 255, 255, 0.5); /* Increased opacity */
    pointer-events: none;
    user-select: none;
    white-space: nowrap;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    font-weight: bold;
    animation: fadeInOut 8s infinite;
    width: 100%;
    text-align: center;
    transition: all 0.3s ease;
}

:deep(.vjs-fullscreen) .video-watermark {
    font-size: min(5vw, 64px); /* Larger font in fullscreen */
}

@keyframes fadeInOut {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.6; }
}

.course-video-container {
    background-color: #f8f9fa;
    min-height: 100vh;

}

.video-content {
    background-color: transparent;
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

.doc-content ul, .doc-content ol {
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

/* Import Video.js CSS */
@import 'video.js/dist/video-js.css';

/* Custom styles */
.video-container {
    width: 100%;
    max-width: 1000px;
    margin: 0 auto;
}

/* Custom button styles */
.video-js .vjs-control-bar button {
    background: none;
    border: none;
    color: white;
    cursor: pointer;
}

.video-js .vjs-control-bar button:hover {
    color: #ff8a00;
}

/* Message styles */
.video-messages {
    margin-top: 1rem;
    padding: 1rem;
}

.video-status-message {
    padding: 1rem;
    border-radius: 4px;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.success {
    background-color: #d4edda;
    color: #155724;
}

.warning {
    background-color: #fff3cd;
    color: #856404;
}

/* Custom Video.js theme overrides */
.video-js .vjs-big-play-button {
    background-color: rgba(255, 138, 0, 0.8);
    border-color: #ff8a00;
}

.video-js .vjs-progress-holder .vjs-play-progress {
    background-color: #ff8a00;
}

.video-js .vjs-control-bar {
    background-color: rgba(0, 0, 0, 0.7);
}

/* Add this to ensure proper video container sizing */
.video-js {
    width: 100%;
    height: auto;
    background: transparent; /* Make video.js background transparent */
}

.video-js .vjs-tech {
    position: relative;
    background: transparent; /* Make video element background transparent */
}

/* Disable progress bar interaction */
.video-js .vjs-progress-control {
    pointer-events: none !important;
}

/* Hide the progress bar hover effect */
.video-js .vjs-progress-holder:hover .vjs-play-progress:before,
.video-js .vjs-progress-holder:hover .vjs-time-tooltip {
    display: none !important;
}

/* Optional: Make the progress bar look disabled */
.video-js .vjs-progress-holder {
    opacity: 0.7;
}

/* Completely disable progress bar interaction */
.video-js .vjs-progress-control {
    pointer-events: none !important;
    cursor: default !important;
}

.video-js .vjs-progress-holder {
    pointer-events: none !important;
    cursor: default !important;
}

.video-js .vjs-play-progress {
    pointer-events: none !important;
}

.video-js .vjs-time-tooltip {
    display: none !important;
}

/* Prevent text selection */
.video-js {
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

/* Hide mouse cursor over progress bar */
.video-js .vjs-progress-control:hover {
    cursor: default !important;
}

/* Update watermark styles */
.watermark-container {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    z-index: 9999;
}

.watermark-container.fullscreen {
    position: fixed;
    z-index: 999999;
}

.video-watermark {
    position: absolute;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: min(3vw, 32px);
    color: rgba(255, 255, 255, 0.5); /* Increased opacity */
    pointer-events: none;
    user-select: none;
    white-space: nowrap;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    font-weight: bold;
    animation: fadeInOut 8s infinite;
    width: 100%;
    text-align: center;
    transition: all 0.3s ease;
}

:deep(.vjs-fullscreen) .watermark-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
}

:deep(.vjs-fullscreen) .video-watermark {
    font-size: min(5vw, 64px); /* Larger font in fullscreen */
}

@keyframes fadeInOut {
    0%, 100% { opacity: 0.3; }
    50% { opacity: 0.6; }
}

/* Updated z-index hierarchy */
.video-js {
    position: relative;
    z-index: 1; /* Base layer */
}

.video-js .vjs-tech {
    z-index: 2; /* Video layer */
}

.vjs-watermark {
    position: absolute;
    z-index: 2; /* Lower z-index to stay under alerts */
    pointer-events: none;
    transition: all 0.8s ease;
    padding: 8px 12px;
    background-color: rgba(0, 0, 0, 0.4);
    border-radius: 4px;
    font-family: Arial, sans-serif;
}

.video-js .vjs-control-bar {
    z-index: 3;
}

/* Ensure video player and watermark stay below alerts */
.video-content {
    position: relative;
    z-index: 1; /* Keep video content in a lower layer */
}

/* Remove any !important from watermark z-index */
.vjs-watermark {
    z-index: 2;
}

.watermark-text {
    color: rgba(255, 255, 255, 0.9);
    font-size: 16px;
    font-weight: 500;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
    white-space: nowrap;
    user-select: none;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
}

/* Let alerts and modals handle their own z-index */
.swal2-container,
.modal,
.alert {
    /* These will use their default z-index values which are typically very high */
    position: fixed;
}
</style>
