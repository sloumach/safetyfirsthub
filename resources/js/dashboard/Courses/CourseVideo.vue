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
                            <div v-for="section in sections" :key="section.id" class="section">
                                <!-- Section Header -->
                                <div class="section-header" @click="toggleSection(section.id)">
                                    <h5>
                                        <i class="fas"
                                            :class="{ 'fa-chevron-down': !openSections[section.id], 'fa-chevron-up': openSections[section.id] }"></i>
                                        {{ section.title }}
                                        <span class="section-status"
                                            :class="{ 'completed': sectionProgress[section.id] }">
                                            <i class="fas"
                                                :class="sectionProgress[section.id] ? 'fa-check-circle' : 'fa-lock'"></i>
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
                                        <div v-for="video in section.videos" :key="video.id" class="section-item"
                                            @click="fetchVideo(video.id)">

                                            <i class="fas fa-play-circle"></i>
                                            {{ video.title }}
                                            <span v-if="video.is_completed" class="completed-badge">✔</span>
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

                                <div v-if="showPreview" class="video-overlay" @click="playVideo">
                                    <img v-if="previewImage" :src="previewImage" alt="Preview" class="preview-image" />
                                    <button class="play-button">▶</button>
                                </div>

                                <video ref="videoPlayer" class="w-100" controls v-show="!showPreview"
                                    @play="hidePreview" @timeupdate="updateProgress(currentContent.section_id,currentContent.video_id, $event)"
                                    @ended="markAsCompleted(currentContent.section_id,currentContent.video_id)">
                                    <source :src="videoUrl" type="video/mp4" />
                                    Your browser does not support the video tag.
                                </video>
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
                        <div v-if="currentContent.type === 'text'" class="documentation-container p-4">
                            <div class="doc-container">
                                <div class="doc-breadcrumb">{{ currentContent.title }}</div>

                                <div class="doc-content">
                                    <h3>{{ currentContent.content }}</h3>
                                </div>
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


// 🛠 Désactiver clic droit
const disableRightClick = (event) => event.preventDefault();

// 🔄 Récupérer les détails du cours
const fetchCourse = async () => {
    try {
        const response = await axios.get(`/api/course/${route.params.id}`);
        course.value = response.data;
    } catch (error) {
        Swal.fire({ title: 'Erreur', text: 'Impossible de charger le cours.', icon: 'error' });
        router.push("/dashboard/courses");
    }
};
const fetchVideo = async (videoId) => {
    try {
        console.log("Fetching video for ID:", videoId); // Debugging

        const response = await axios.get(`/api/videos/${videoId}/video-url`);
        console.log("Video URL Response:", response.data.section_id); // Debugging

        if (response.data.video_url) {
            videoUrl.value = response.data.video_url;

            isCompleted.value = false;
            // ✅ Mettre à jour le state pour afficher le lecteur vidéo
            currentContent.value = {
                type: 'video',
                title: 'Lecture de la vidéo',
                videoUrl: response.data.video_url,
                video_id: videoId,
                section_id: response.data.section_id
            };
            console.log("currentContent", currentContent.value.id);
            showPreview.value = false; // 🔥 Masquer la prévisualisation pour afficher le player

            console.log("Player should now be visible");
        } else {
            console.error("No valid video URL received");
        }
    } catch (error) {
        console.error("Erreur lors de la récupération de la vidéo :", error);
        await Swal.fire({
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
        console.log('------------------------------------');

        sections.value = response.data.sections;

        // Initialiser l'ouverture des sections et la progression
        sections.value.forEach((section, index) => {
            openSections.value[section.id] = index === 0; // Ouvrir uniquement la première section
            sectionProgress.value[section.id] = section.videos.every(video => video.is_completed);
        });

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
    console.log("Toggle Section:", sectionId, sectionProgress.value);

    // Always allow first section
    if (sectionId === sections.value[0].id) {
        openSections.value[sectionId] = !openSections.value[sectionId];
        return;
    }

    // Find the previous section
    const sectionIndex = sections.value.findIndex(sec => sec.id === sectionId);
    if (sectionIndex > 0) {
        const previousSection = sections.value[sectionIndex - 1];

        // Check if previous section is completed
        if (sectionProgress.value[previousSection.id]) {
            openSections.value[sectionId] = !openSections.value[sectionId];
        } else {
            Swal.fire({
                title: 'Section Verrouillée',
                text: 'Complétez la section précédente.',
                icon: 'warning'
            });
        }
    }
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

// 🎯 Montage du composant
onMounted(() => {
    fetchCourse();
    fetchSections();

    document.addEventListener("contextmenu", disableRightClick);
});

// 🛑 Nettoyage
onBeforeUnmount(() => {
    document.removeEventListener("contextmenu", disableRightClick);
});
</script>



<style scoped>
.watermark {
    position: absolute;
    /* Position it over the video */
    color: rgba(255, 255, 255, 0.7);
    font-size: 20px;
    font-weight: bold;
    z-index: 9999;
    /* Ensure it's on top of everything */
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
    font-size: 24px;
    /* Increase size for visibility */
    opacity: 0.8;
    /* Increase opacity */
    z-index: 10000;
    /* Ensure it's on top of everything */
    position: fixed;
    /* Stay in a fixed position on the screen */
}

/* Full-screen specific positioning */
.watermark-fullscreen.watermark-top-left {
    top: 5%;
    /* Slightly closer to the top-left corner */
    left: 5%;
}

.watermark-fullscreen.watermark-bottom-right {
    bottom: 5%;
    /* Slightly closer to the bottom-right corner */
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
</style>
