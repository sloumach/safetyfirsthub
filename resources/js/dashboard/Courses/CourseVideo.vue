<template>
    <div class="course-video-container">
        <div class="container py-3"> <!-- Reduced padding -->
            <div class="row">
                <div class="col-12">
                    <nav v-if="course" aria-label="breadcrumb">
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item">
            <router-link to="/dashboard/courses">Certified Courses</router-link>
        </li>
        <li class="breadcrumb-item">
            {{ course.category }}
        </li>
        <li class="breadcrumb-item active" aria-current="page">
            {{ course.name || course.title }}
        </li>
    </ol>
</nav>

                </div>
            </div>

            <div class="row">
                <!-- Sidebar Column -->
                <div class="col-lg-3 col-md-4">
                    <div class="course-sidebar">
                        <h4 class="sidebar-title d-flex justify-content-between align-items-center"
                            @click="toggleMobileSidebar">
                            Course Content
                            <i class="fas d-md-none"
                               :class="isSidebarOpen ? 'fa-chevron-up' : 'fa-chevron-down'">
                            </i>
                        </h4>

                        <!-- Course Sections - Add mobile collapse -->
                        <div class="course-sections" :class="{ 'mobile-collapsed': !isSidebarOpen }">
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
                                        @click="selectContent({
                                                type: 'text',
                                                title: slide.title,
                                                content: parseSlideContent(slide.content),
                                                file: slide.file
                                            })">
                                            <i class="fas fa-file-alt"></i>
                                            {{ slide.title }}
                                        </div>
                                    </div>
                                    <!-- 🔹 Affichage des vidéos -->
                                    <div v-if="section.videos.length > 0">
                                        <div v-for="(video, index) in section.videos" :key="video.id"
                                            class="section-item" :class="{ 'locked': !canAccessVideo(section, index) }"
                                            @click="handleVideoClick(section, video, index)" style="cursor: pointer;">
                                            <i class="fas" style="color: #FF8A00;"
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
                                    <!-- Update the quiz item in sidebar -->
                                    <div v-if="section.quiz" class="section-item quiz-item" :class="{
                                        'completed': section.quiz.is_passed,
                                        'locked': !areAllVideosCompleted(section)
                                    }" @click="handleQuizClick(section)">
                                        <div class="quiz-item-content">
                                            <div class="quiz-info">
                                                <i class="fas" :class="getQuizIcon(section)"></i>
                                                Section Quiz
                                            </div>
                                            <div v-if="section.quiz.is_passed" class="quiz-score-badge">
                                                Score: {{ section.quiz.score }}%
                                            </div>
                                            <span v-if="!areAllVideosCompleted(section)" class="lock-icon">
                                                <i class="fas fa-lock"></i>
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
                        <div v-if="currentContent.type === 'video'" class="documentation-container">
                            <div class="doc-container">
                                <div class="doc-header">
                                    <h2 class="doc-title">{{ currentContent.title }}</h2>
                                    <div class="doc-breadcrumb">
                                        <i class="fas fa-play-circle"></i>
                                        <span>Video</span>
                                    </div>
                                </div>

                                <div class="doc-content video-wrapper">
                                    <div class="video-container">
                                        <!-- Simple watermark -->
                                        <div class="video-watermark"> safetyfirsthub.com</div>

                                        <video
                                            ref="videoPlayer"
                                            class="video-player"
                                            controls
                                            controlsList="nodownload"
                                            @timeupdate="updateProgress(currentContent.section_id, currentContent.video_id, $event)"
                                            @ended="markAsCompleted(currentContent.section_id, currentContent.video_id)"
                                            @seeking="preventSeeking">
                                            <source :src="videoUrl" type="video/mp4" />
                                            Your browser does not support the video tag.
                                        </video>
                                    </div>

                                    <div class="video-messages">
                                        <div v-if="isCompleted" class="video-status-message success">
                                            <span>You've completed this video! Now you can take the exam.</span>
                                        </div>
                                        <div v-else class="video-status-message warning">
                                            <i class="fas fa-clock"></i>
                                            <span>You must watch the entire video before taking the exam.</span>
                                        </div>
                                    </div>
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

                                <!-- <div class="doc-content" v-html="currentContent.content"></div> -->
                                <div class="doc-content" v-html="sanitizeContent(currentContent.content)"></div>
                                <div v-if="currentContent.file" class="doc-attachment">
                                    <!-- Show image if it's an image file -->
                                    <div v-if="isImageFile(currentContent.file)" class="doc-image">
                                        <img :src="getImageUrl(currentContent.file)" :alt="currentContent.title" class="img-fluid mt-3">
                                    </div>
                                    <!-- Show PDF download/view option if it's a PDF file -->
                                    <div v-else-if="isPdfFile(currentContent.file)" class="doc-pdf">
                                        <a :href="getPdfUrl(currentContent.file)" target="_blank" class="pdf-link">
                                            <div class="pdf-container">
                                                <i class="fas fa-file-pdf"></i>
                                                <span>View PDF Document</span>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Quiz Content -->
                        <div v-if="currentContent.type === 'quiz'" class="quiz-container">
                            <SectionQuiz :quiz="currentContent.quiz" :sectionId="currentContent.section_id"
                                @quizCompleted="handleQuizCompletion" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, onBeforeUnmount, watch, nextTick, computed } from "vue";
import { useRoute, useRouter } from "vue-router";
import axios from "axios";
import Swal from "sweetalert2";
import PreventSecurity from '@/dashboard/Security/security.js';
import SectionQuiz from './SectionQuiz.vue';
import throttle from 'lodash/throttle';

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
const userEmail = ref('achraftawes@gmail.com'); // Replace with actual user email from your auth system
const isVideoSessionActive = ref(true);
const lastValidTime = ref(0);
const isForwardAttempted = ref(false);
const hasStartedPlaying = ref(false);
const currentSection = ref(null);
const currentVideo = ref(null);
const isSidebarOpen = ref(true);

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
        console.log("Sections:", sections.value);
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
                    content: JSON.parse(firstSlide.content),
                    file: firstSlide.file
                });
            }
        }
    } catch (error) {
        console.error("Erreur lors du chargement des sections :", error);
    }
};

// 📌 Suivi de la progression
const updateProgress = throttle(async (sectionId, videoId, event) => {
    if (!videoId) return;
    const currentTime = Math.floor(event.target.currentTime);
    const totalDuration = Math.floor(event.target.duration);

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
}, 10000); // Mise à jour toutes les 5 secondes

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
        return;
    }

    try {
        await axios.post(`/video/progress/complete`, {
            video_id: video_id,
            section_id: section_id
        });

        isCompleted.value = true;

        // Update video completion status in the section
        if (currentSection.value) {
            const videoIndex = currentSection.value.videos.findIndex(v => v.id === video_id);
            if (videoIndex !== -1) {
                currentSection.value.videos[videoIndex].is_completed = true;
                currentVideo.value = currentSection.value.videos[videoIndex];
            }
        }

        // Update section progress
        const section = sections.value.find(sec => sec.id === section_id);
        if (section) {
            const allVideosCompleted = section.videos.every(video => video.is_completed);
            if (allVideosCompleted) {
                sectionProgress.value[section_id] = true;
            }
        }

        // Check if this is the last section and last video
        const isLastSection = currentSection.value.id === sections.value[sections.value.length - 1].id;
        const allVideosCompleted = currentSection.value.videos.every(video => video.is_completed);

        // If it's the last section, all videos are completed, and there's no quiz
        if (isLastSection && allVideosCompleted && !currentSection.value.quiz) {
            await Swal.fire({
                title: 'Congratulations! 🎉',
                text: 'You have completed all sections! You will now be redirected to the final exam.',
                icon: 'success',
                showCancelButton: true,
                confirmButtonColor: '#FF8A00',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bx bx-trophy"></i> Take Exam',
                cancelButtonText: 'Later'
            }).then((result) => {
                if (result.isConfirmed) {
                    router.push('/dashboard/exams');
                }
            });
        } else {
            // Show regular completion message
            await Swal.fire({
                title: 'Video Completed! 🎉',
                text: 'You can now proceed.',
                icon: 'success',
                confirmButtonColor: '#FF8A00'
            });
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
    currentContent.value = {
        ...content,
        content: sanitizeContent(content.content)
    };
    showPreview.value = false;

    // If it's a slide, ensure proper scroll into view
    if (content.type === 'text') {
        nextTick(() => {
            const slideElement = document.querySelector(`[data-slide-title="${content.title}"]`);
            if (slideElement) {
                slideElement.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    if (content.type === 'quiz') {
        currentSection.value = sections.value.find(s => s.id === content.section_id);
        // Make sure quiz data is properly set in currentContent
        currentContent.value = {
            type: 'quiz',
            quiz: content.quiz,
            section_id: content.section_id
        };
    }
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

    currentSection.value = section;
    currentVideo.value = video;
    isCompleted.value = video.is_completed;
    fetchVideo(video.id);
};

// Modify canAccessSection function
const canAccessSection = (sectionIndex) => {
    // First section is always accessible
    if (sectionIndex === 0) return true;

    // Check if previous section exists
    const previousSection = sections.value[sectionIndex - 1];
    if (!previousSection) return false;

    // Check if all videos in previous section are completed
    const allVideosCompleted = previousSection.videos.every(video => video.is_completed);

    // Check if quiz exists and has been attempted
    const quizCompleted = previousSection.quiz ? previousSection.quiz.is_attempted : true;

    // Section is accessible only if all videos AND quiz are completed
    return allVideosCompleted && quizCompleted;
};

// Update handleSectionClick to show appropriate message
const handleSectionClick = (section, sectionIndex) => {
    if (!canAccessSection(sectionIndex)) {
        const previousSection = sections.value[sectionIndex - 1];
        let message = 'You need to complete the previous section first.';

        if (previousSection) {
            if (!previousSection.videos.every(video => video.is_completed)) {
                message = 'Please complete all videos in the previous section.';
            } else if (previousSection.quiz && !previousSection.quiz.is_attempted) {
                message = 'Please complete the quiz in the previous section.';
            }
        }

        Swal.fire({
            title: 'Section Locked',
            text: message,
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

// Add this method to handle quiz clicks
const handleQuizClick = (section) => {
    // Check if all videos are completed
    if (!areAllVideosCompleted(section)) {
        Swal.fire({
            title: 'Quiz Locked',
            text: 'Please complete all videos in this section before taking the quiz.',
            icon: 'warning',
            confirmButtonColor: '#FF8A00'
        });
        return;
    }

    // If quiz is already passed
    if (section.quiz.is_passed) {
        Swal.fire({
            title: 'Quiz Already Completed',
            html: `
                <div class="quiz-success-message">
                    <i class="fas fa-trophy" style="color: #FFD700; font-size: 48px; margin-bottom: 16px;"></i>
                    <p>You've already passed this quiz!</p>
                    <p>Your score: ${section.quiz.score}%</p>
                </div>
            `,
            icon: 'success',
            confirmButtonColor: '#FF8A00'
        });
        return;
    }

    // If all conditions met, show quiz
    selectContent({
        type: 'quiz',
        quiz: section.quiz,
        section_id: section.id
    });
};

// Update handleQuizCompletion method
const handleQuizCompletion = async ({ passed, score }) => {
    if (passed) {
        // Immediately update the current section's quiz status
        if (currentSection.value?.quiz) {
            currentSection.value.quiz.is_attempted = true;
            currentSection.value.quiz.is_passed = true;
            currentSection.value.quiz.score = score;
        }

        // Check if this is the last section
        const isLastSection = currentSection.value.id === sections.value[sections.value.length - 1].id;

        if (isLastSection) {
            // Show success message and redirect
            await Swal.fire({
                title: 'Congratulations! 🎉',
                text: 'You have completed all sections! You will now be redirected to the final exam.',
                icon: 'success',
                confirmButtonColor: '#FF8A00'
            });

            // Redirect to exam page
            router.push('/dashboard/exams');
        } else {
            // Show regular success message for non-final sections
            await Swal.fire({
                title: 'Congratulations! 🎉',
                text: `Your score: ${score}%. You can now proceed to the next section!`,
                icon: 'success',
                confirmButtonColor: '#FF8A00'
            });

            // Find and unlock next section if exists
            const currentSectionIndex = sections.value.findIndex(
                s => s.id === currentSection.value.id
            );
            if (currentSectionIndex < sections.value.length - 1) {
                openSections.value[sections.value[currentSectionIndex + 1].id] = true;
            }
        }

        // Force UI update by triggering reactivity
        sections.value = [...sections.value];

        // Reset current content to show updated quiz status
        if (!isLastSection) {
            selectContent({
                type: 'text',
                title: currentSection.value.slides[0]?.title || '',
                content: currentSection.value.slides[0]?.content || '',
                file: currentSection.value.slides[0]?.file || ''
            });
        }
    } else {
        // Reset video completion status for the section
        if (currentSection.value) {
            currentSection.value.videos.forEach(video => {
                video.is_completed = false;
            });
        }

        // Reset content without showing alert
        if (currentSection.value?.slides?.length > 0) {
            selectContent({
                type: 'text',
                title: currentSection.value.slides[0].title,
                content: currentSection.value.slides[0].content,
                file: currentSection.value.slides[0].file
            });
        }

        // Force UI update
        sections.value = [...sections.value];
    }
};

// Add these methods
const areAllVideosCompleted = (section) => {
    return section.videos.every(video => video.is_completed);
};

const getQuizIcon = (section) => {
    if (section.quiz.is_passed) return 'fa-check-circle';
    if (!areAllVideosCompleted(section)) return 'fa-lock';
    return 'fa-question-circle';
};

// Add these helper functions
const parseSlideContent = (content) => {
    try {
        // First remove the extra quotes and unescape the content
        const unescapedContent = JSON.parse(content);
        return unescapedContent;
    } catch (e) {
        console.error('Error parsing slide content:', e);
        return content; // Return original content if parsing fails
    }
};

const sanitizeContent = (content) => {
    if (!content) return '';
    try {
        // If content is already an object/parsed, return as is
        if (typeof content === 'object') {
            return content;
        }
        // If it's a string that needs parsing
        return parseSlideContent(content);
    } catch (e) {
        console.error('Error sanitizing content:', e);
        return content;
    }
};

const getImageUrl = (filePath) => {
    if (!filePath) return null;
    return `/storage/${filePath}`;
};

// Add these helper functions
const isImageFile = (filePath) => {
    const imageExtensions = ['.jpg', '.jpeg', '.png', '.gif', '.webp'];
    return imageExtensions.some(ext => filePath.toLowerCase().endsWith(ext));
};

const isPdfFile = (filePath) => {
    return filePath.toLowerCase().endsWith('.pdf');
};

const getPdfUrl = (filePath) => {
    if (!filePath) return null;
    return `/storage/${filePath}`;
};

// Inside the <script setup> section, after the imports
const showInitialWarning = () => {
    return Swal.fire({
        title: 'Important Notice!',
        html: `
            <div class="security-warning">
                <p><i class="fas fa-exclamation-triangle" style="color: #FF8A00; font-size: 48px; margin-bottom: 20px;"></i></p>
                <p>To ensure proper course progress tracking, please note:</p>
                <ul style="text-align: left; margin-top: 15px;">
                    <li>❌ Do not switch between browser tabs</li>
                    <li>❌ Do not close the window/tab</li>
                    <li>❌ Do not minimize the browser</li>
                    <li>❌ Do not use browser navigation (back/forward)</li>
                </ul>
                <p style="margin-top: 15px; color: #FF8A00;">Violating these rules will reset your progress!</p>
            </div>
        `,
        icon: 'warning',
        confirmButtonText: 'I Understand',
        confirmButtonColor: '#FF8A00',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showCancelButton: false
    });
};
// 🎯 Montage du composant
onMounted(() => {
    showInitialWarning().then(() => {
        // Proceed with initialization after warning is acknowledged
        fetchCourse();
        fetchSections();

        PreventSecurity.setSecurityCallback(reportSecurityBreach);

        PreventSecurity.initVideo(
            router,
            videoPlayer.value,
            currentContent,
            isCompleted
        );

        // Check localStorage for saved state, default to true if not set
        const savedState = localStorage.getItem('courseSidebarOpen');
        isSidebarOpen.value = savedState === null ? true : savedState === 'true';
    });
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

// Add a watch for isCompleted
watch(isCompleted, (newValue) => {
    if (newValue && currentSection.value?.quiz) {

    }
});

const toggleMobileSidebar = () => {
    if (window.innerWidth <= 768) {
        isSidebarOpen.value = !isSidebarOpen.value;
        // Save state to localStorage
        localStorage.setItem('courseSidebarOpen', isSidebarOpen.value);
    }
};
</script>



<style scoped>
.video-container {
    position: relative !important;
    width: 100% !important;
    max-width: 100% !important;
    margin: 0 auto !important;
}

.video-player {
    width: 100% !important;
    max-height: 400px !important;
    border-radius: 8px !important;
    background: #000 !important;
    aspect-ratio: 9/16 !important;
    object-fit: contain !important;
}

.video-watermark {
    position: absolute !important;
    bottom: 50% !important;
    left: 50% !important;
    transform: translateX(-50%) rotate(-25deg) !important;
    color: rgba(255, 255, 255, 0.2) !important;
    font-size: 20px !important;
    z-index: 10 !important;
    pointer-events: none !important;
    user-select: none !important;
    white-space: nowrap !important;
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1) !important;
}

@media (max-width: 768px) {
    .video-player {
        max-height: 350px !important;
    }

    .video-watermark {
        font-size: 11px !important;
        bottom: 45% !important;
    }
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
    color: #FF8A00 !important; /* Orange color to match theme */
    font-size: 18px !important;
    font-weight: 600 !important;
    margin-bottom: 15px !important;
    padding-bottom: 10px !important;
    border-bottom: 2px solid #FF8A00 !important; /* Orange underline */
}

/* Icon color for mobile toggle */
.sidebar-title i {
    color: #FF8A00 !important;
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
    display: flex !important;
    align-items: center !important;
    padding: 10px 15px !important;
    margin: 5px 0 !important;
    gap: 10px !important;
    cursor: pointer !important;
    border-radius: 6px !important;
    transition: background-color 0.2s ease !important;
}

.section-item i {
    width: 20px !important; /* Fixed width for icons */
    text-align: center !important; /* Center the icon */
    font-size: 14px !important;
}

/* Quiz item specific alignment */
.section-item.quiz-item {
    padding-left: 15px !important;
}

.quiz-item-content {
    display: flex !important;
    align-items: center !important;
    justify-content: space-between !important;
    width: 100% !important;
}

.quiz-info {
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
}

/* Ensure all icons are aligned */
.fa-file-alt,
.fa-play-circle,
.fa-lock,
.fa-check-circle {
    width: 20px !important;
    text-align: center !important;
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

/* Add these styles */
.quiz-section {
    margin-top: 2rem;
    padding: 1rem;
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.quiz-item {
    padding: 12px 15px;
    margin: 8px 0;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.quiz-item.completed {
    background-color: #f0f9f0 !important;
    border: 1px solid #e0e0e0;
}

.quiz-item-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.quiz-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.quiz-info i {
    font-size: 16px;
}

.quiz-item.completed i {
    color: #4CAF50;
}

.quiz-score-badge {
    background-color: #4CAF50;
    color: white;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
}

/* When hovering over the quiz item */
.quiz-item:hover {
    background-color: #f5f5f5;
    cursor: pointer;
}

.quiz-item.completed:hover {
    background-color: #e8f5e8 !important;
}

.quiz-success-message {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
}

.quiz-success-message p {
    margin: 8px 0;
    font-size: 16px;
}

/* Add these styles */
.quiz-item.locked {
    opacity: 0.7;
    background-color: #f5f5f5;
    cursor: not-allowed;
}

.quiz-item.locked .quiz-info {
    color: #666;
}

.lock-icon {
    color: #666;
    margin-left: 10px;
}

.quiz-item.locked:hover {
    background-color: #f5f5f5;
}

@media (max-width: 768px) {
    .sidebar-title {
        padding: 15px !important;
        margin-bottom: 0 !important;
    }

    .course-sections {
        transition: max-height 0.3s ease-in-out !important;
        overflow: hidden !important;
    }

    .course-sections.mobile-collapsed {
        max-height: 0 !important;
    }

    .course-sections:not(.mobile-collapsed) {
        max-height: 80vh !important;
        overflow-y: auto !important;
        /* Add this to ensure content is visible initially */
        opacity: 1 !important;
        visibility: visible !important;
    }
}

/* Hide chevron on desktop */
@media (min-width: 769px) {
    .d-md-none {
        display: none !important;
    }
    .watermark {
        font-size: 11px !important;
        bottom: 70% !important;
        left: 49% !important;
    }
}

/* Update these styles */
.video-wrapper {
    background: white !important;
    border-radius: 8px !important;
    padding: 20px !important;
    position: relative !important;
    max-width: 100% !important; /* Add max-width */
    margin: 0 auto !important; /* Center the container */
}

.video-player {
    width: 100% !important;
    max-height: 400px !important; /* Control maximum height */
    border-radius: 8px !important;
    background: #000 !important;
    margin-bottom: 20px !important;
    aspect-ratio: 9/16 !important; /* Maintain vertical video aspect ratio */
    object-fit: contain !important; /* Maintain aspect ratio without stretching */
}

.video-messages {
    margin-top: 20px !important;
    padding: 15px !important;
    border-radius: 8px !important;
}

.video-status-message {
    padding: 15px !important;
    border-radius: 8px !important;
    margin-bottom: 10px !important;
    display: flex !important;
    align-items: center !important;
    gap: 10px !important;
}

.video-status-message.success {
    background-color: #e8f5e9 !important;
    color: #2e7d32 !important;
}

.video-status-message.warning {
    background-color: #fff3e0 !important;
    color: #ef6c00 !important;
}

.video-status-message i {
    font-size: 20px !important;
}

.preview-image {
    width: 100% !important;
    border-radius: 8px !important;
}

.play-button {
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    background: rgba(255, 138, 0, 0.9) !important;
    border: none !important;
    color: white !important;
    font-size: 24px !important;
    width: 60px !important;
    height: 60px !important;
    border-radius: 50% !important;
    cursor: pointer !important;
    transition: all 0.3s ease !important;
}

.play-button:hover {
    background: #FF8A00 !important;
    transform: translate(-50%, -50%) scale(1.1) !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .video-wrapper {
        padding: 10px !important;
        max-width: 100% !important;
    }

    .video-player {
        max-height: 201px  !important;
    }
}

/* Section header chevron icons */
.fas.fa-chevron-up,
.fas.fa-chevron-down,
.fas.fa-play-circle,
.fas.fa-lock{
    color: #FF8A00 !important; /* Orange */
}

/* File icon */
.fas.fa-file-alt {
    color: #FF8A00 !important; /* Orange */
}

/* Section titles */
h5 {
    color: #2c3e50 !important; /* Dark blue/gray */
    font-weight: 600 !important;
}

/* Quiz icon */
.fas.fa-question-circle {
    color: #FF8A00 !important; /* Orange */
}

/* Quiz info text */
.quiz-info {
    color: #666666 !important; /* Gray */
}

/* Quiz item when completed */
.quiz-item.completed .fas.fa-question-circle {
    color: #4CAF50 !important; /* Green */
}

/* Quiz item when locked */
.quiz-item.locked .fas.fa-question-circle {
    color: #999999 !important; /* Gray */
}

.doc-attachment {
    margin-top: 20px;
    width: 100%;
}

.doc-image {
    width: 100%;
    text-align: center;
}

.doc-image img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.doc-pdf {
    width: 100%;
    padding: 20px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.pdf-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.pdf-container {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    padding: 20px;
    background-color: white;
    border-radius: 6px;
    transition: all 0.3s ease;
}

.pdf-container:hover {
    background-color: #FF8A00;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.pdf-container i {
    font-size: 24px;
    color: #FF8A00;
}

.pdf-container:hover i {
    color: white;
}

.pdf-container span {
    font-size: 16px;
    font-weight: 500;
}

@media (max-width: 768px) {
    .doc-image img {
        height: 200px;
    }

    .pdf-container {
        padding: 15px;
    }

    .pdf-container i {
        font-size: 20px;
    }

    .pdf-container span {
        font-size: 14px;
    }
}
</style>
