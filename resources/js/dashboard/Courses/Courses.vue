<template>
    <div class="courses-dashboard exams-section">
        <div class="text-2xl font-bold mb-4 text-center">
            <div class="dashboard-title">
                <h1>My Learning Dashboard</h1>
                <div class="info-tooltip">
                    <i class='bx bx-info-circle'></i>
                    <div class="tooltip-content">
                        <h3>How to Start Learning</h3>
                        <p>1. Select your purchased course from the list below</p>
                        <p>2. Click "Continue Learning" to start your course</p>
                        <p>3. Complete all videos to unlock your exam</p>
                        <div class="tooltip-note">
                            <i class='bx bx-bulb'></i>
                            <span>Tip: Track your progress and earn certificates upon completion!</span>
                        </div>
                    </div>
                </div>
            </div>
            <p class="subtitle">Your Purchased Certifications</p>
            <p class="description" style="color: #666; font-size: 14px; margin-top: 5px;">
                <span style="display: block; margin-top: 10px;">
                    If you want to buy another certified course, 
                    <a href="/courses" style="color: #2d6cdf; font-weight: 600; text-decoration: underline;">CLICK ME!</a>
                </span>
            </p>
        </div>

        <div class="dashboard-content">
          
            <!-- Mobile Toggle -->
            <button class="mobile-toggle" @click="toggleSidebar">
                <i class='bx bx-menu'></i>
                Certified Courses
            </button>

            <!-- Course Cards Grid -->
            <div class="courses-grid">
                <div class="row g-4 justify-content-center">
                    <div class="col-12 col-md-6 col-lg-4 lg8a" v-for="course in paginatedCourses" :key="course.id">
                        <div class="course-card">
                            <div class="course-image">
                                <img 
                                    :src="course.cover || 'https://placehold.co/600x400/003366/ffffff?text=Course'" 
                                    :alt="course.name"
                                    @error="handleImageError"
                                />
                                <div class="course-badge">
                                    <i class='bx bx-play-circle'></i>
                                    {{ course.total_videos }} videos
                                </div>
                            </div>
                            
                            <div class="course-content">
                                <h3>{{ course.name }}</h3>
                                <p>{{ course.short_description }}</p>
                                
                                <div class="course-meta">
                                    <!-- <span>
                                        <i class='bx bx-user'></i>
                                        {{ course.students || 0 }} students
                                    </span> -->
                                    <button @click="navigateToVideo(course.id)" class="btn-continue">
                                        Continue Learning
                                        <i class='bx bx-right-arrow-alt'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modern Pagination -->
                <div class="modern-pagination" v-if="totalPages > 1">
                    <button 
                        class="nav-btn prev" 
                        @click="changePage(currentPage - 1)"
                        :disabled="currentPage === 1"
                    >
                        <i class='bx bx-chevron-left'></i>
                    </button>

                    <div class="page-numbers">
                        <button 
                            v-for="page in displayedPages" 
                            :key="page"
                            @click="changePage(page)"
                            :class="['page-btn', { active: currentPage === page }]"
                        >
                            {{ page }}
                        </button>
                    </div>

                    <button 
                        class="nav-btn next" 
                        @click="changePage(currentPage + 1)"
                        :disabled="currentPage === totalPages"
                    >
                        <i class='bx bx-chevron-right'></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
    name: 'Courses',
    setup() {
        const router = useRouter()
        const courses = ref([])
        const isSidebarOpen = ref(false)
        const selectedCourse = ref(null)
        const currentPage = ref(1)
        const itemsPerPage = ref(3)

        const handleImageError = (e) => {
            e.target.src = 'https://placehold.co/600x400/003366/ffffff?text=Certified Course'
        }

        const fetchCourses = async () => {
            try {
                const response = await axios.get('/api/courses');
                
                if (response.data && response.data.length > 0) {
                    courses.value = response.data;
                    console.log(courses.value);
                } else {
                    courses.value = [];
                    Swal.fire({
                        icon: "info",
                        title: "No Courses Found",
                        text: "You haven't purchased any courses yet.",
                        confirmButtonColor: "#FF8A00",
                    });
                }
            } catch (error) {
                courses.value = [];
                Swal.fire({
                    icon: "error",
                    title: "Error",
                    text: "Failed to load courses. Please try again later.",
                    confirmButtonColor: "#FF8A00",
                });
            }
        }

        const navigateToVideo = (courseId) => {
            router.push(`/dashboard/courses/${courseId}/video`)
        }

        const toggleSidebar = () => {
            isSidebarOpen.value = !isSidebarOpen.value
        }

        const selectCourse = (courseId) => {
            selectedCourse.value = courseId
            navigateToVideo(courseId)
            if (window.innerWidth < 768) {
                isSidebarOpen.value = false
            }
        }

        const totalPages = computed(() => {
            return Math.ceil(courses.value.length / itemsPerPage.value)
        })

        const paginatedCourses = computed(() => {
            const start = (currentPage.value - 1) * itemsPerPage.value
            const end = start + itemsPerPage.value
            return courses.value.slice(start, end)
        })

        const changePage = (page) => {
            if (page >= 1 && page <= totalPages.value) {
                currentPage.value = page
                // Scroll to top of courses section smoothly
                this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' })
            }
        }

        const displayedPages = computed(() => {
            const pages = []
            const maxVisiblePages = 5
            let start = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2))
            let end = Math.min(totalPages.value, start + maxVisiblePages - 1)

            if (end - start + 1 < maxVisiblePages) {
                start = Math.max(1, end - maxVisiblePages + 1)
            }

            for (let i = start; i <= end; i++) {
                pages.push(i)
            }
            return pages
        })

        onMounted(() => {
            fetchCourses()

        })

        return {
            courses,
            navigateToVideo,
            handleImageError,
            isSidebarOpen,
            selectedCourse,
            toggleSidebar,
            selectCourse,
            currentPage,
            totalPages,
            paginatedCourses,
            changePage,
            displayedPages
        }
    }
}
</script>

<style scoped>
.dashboard-layout {
    display: flex !important;
    min-height: 100vh !important;
    position: relative !important;
}

.course-sidebar {
    width: 280px !important;
    background: #ffffff !important;
    border-right: 1px solid #e5e7eb !important;
    height: 100vh !important;
    position: fixed !important;
    left: 0 !important;
    top: 0 !important;
    z-index: 1000 !important;
    transition: transform 0.3s ease !important;
    overflow-y: auto !important;
}

.sidebar-header {
    padding: 1.5rem !important;
    border-bottom: 1px solid #e5e7eb !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
}

.sidebar-header h3 {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
    color: #1a1f36 !important;
    margin: 0 !important;
}

.course-list {
    padding: 1rem 0 !important;
}

.course-item {
    padding: 0.75rem 1.5rem !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    color: #4f566b !important;
}

.course-item:hover {
    background: #f8f9fa !important;
    color: #0052cc !important;
}

.course-item.active {
    background: #e8f0fe !important;
    color: #0052cc !important;
    border-right: 3px solid #0052cc !important;
}

.course-item i {
    font-size: 1.25rem !important;
}

.close-sidebar {
    display: none !important;
    background: none !important;
    border: none !important;
    font-size: 1.5rem !important;
    color: #4f566b !important;
    cursor: pointer !important;
    padding: 0.25rem !important;
}

.sidebar-toggle {
    display: none !important;
    position: fixed !important;
    left: 1rem !important;
    top: 1rem !important;
    z-index: 1001 !important;
    background: #ffffff !important;
    border: 1px solid #e5e7eb !important;
    border-radius: 8px !important;
    padding: 0.5rem !important;
    font-size: 1.25rem !important;
    color: #1a1f36 !important;
    cursor: pointer !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
}

.courses-dashboard {
    margin-left: 98px !important;
    width: calc(100% - 280px) !important;
}

.dashboard-header {
    text-align: center;
    padding: 20px 15px;
    margin-bottom: 30px;
}

.dashboard-title {
    font-size: 2rem;
    font-weight: bold;
    margin-bottom: 10px;
}

.dashboard-subtitle {
    font-size: 1.2rem;
    color: #666;
    margin-bottom: 15px;
}

.purchase-link {
    font-size: 0.9rem;
    color: #666;
}

.purchase-link a {
    color: #2d6cdf;
    font-weight: 600;
    text-decoration: underline;
    transition: color 0.3s ease;
}

.purchase-link a:hover {
    color: #FF8A00;
}

.course-card {
    background: #ffffff !important;
    border-radius: 12px !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    transition: transform 0.2s ease, box-shadow 0.2s ease !important;
    overflow: hidden !important;
    height: 100% !important;
    display: flex !important;
    flex-direction: column !important;
    width: 100%;
}
@media (min-width: 992px) {
    .lg8a {
        flex: 0 0 auto !important;
        width: 30.333333% !important;
    }
}
.course-card:hover {
    transform: translateY(-4px) !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
}

.course-image {
    position: relative !important;
    padding-top: 42.25% !important;  /* 16:9 Aspect Ratio */
    background: #f1f5f9 !important;
}

.course-image img {
    position: absolute !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    object-fit: cover !important;
}

.course-badge {
    position: absolute !important;
    top: 1rem !important;
    right: 1rem !important;
    background: rgba(255, 255, 255, 0.9) !important;
    padding: 0.5rem 0.75rem !important;
    border-radius: 6px !important;
    font-size: 0.875rem !important;
    color: #1a1f36 !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    font-weight: 500 !important;
}

.course-content {
    padding: 1.5rem !important;
    flex: 1 !important;
    display: flex !important;
    flex-direction: column !important;
}

.course-content h3 {
    font-size: 1.125rem ;
    font-weight: 600 ;
    color: #1a1f36 ;
    margin-bottom: 0.75rem;
}

.course-content p {
    color: #4f566b !important;
    font-size: 0.875rem !important;
    line-height: 1.5 !important;
    margin-bottom: 1.5rem !important;
    flex: 1 !important;
}

.course-meta {
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    margin-top: auto !important;
}

.course-meta span {
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    color: #4f566b !important;
    font-size: 0.875rem !important;
}

.btn-continue {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    padding: 0.5rem 1rem !important;
    background-color: #FF8A00 !important;
    color: white !important;
    border: none !important;
    border-radius: 6px !important;
    font-size: 0.875rem !important;
    font-weight: 500 !important;
    transition: background-color 0.2s ease !important;
}

.btn-continue:hover {
    background-color: #FF8A00 !important;
}

.btn-continue i {
    font-size: 1.25rem !important;
    transition: transform 0.2s ease !important;
}

.btn-continue:hover i {
    transform: translateX(4px) !important;
}

@media (max-width: 1200px) {
    .courses-dashboard {
        padding: 1.5rem !important;
    }

    .course-card {
        margin-bottom: 1.5rem !important;
        width: 146%;
    }

    .dashboard-header h2 {
        font-size: 1.5rem !important;
    }
    
}

@media (max-width: 992px) {
    .row {
        margin: 0 -10px !important;
    }

    .col-12, .col-md-6, .col-lg-4 {
        padding: 0 36px !important;
    }

    .course-content {
        padding: 1.25rem !important;
    }

    .course-content h3 {
        font-size: 1rem !important;
    }

    .course-badge {
        font-size: 0.75rem !important;
        padding: 0.4rem 0.6rem !important;
    }
}

@media (max-width: 768px) {
    .course-sidebar {
        transform: translateX(-100%) !important;
    }

    .sidebar-active {
        transform: translateX(0) !important;
    }

    .close-sidebar {
        display: block !important;
    }

    .sidebar-toggle {
        display: block !important;
    }

    .courses-dashboard {
        margin-left: 0 !important;
        width: 100% !important;
        padding-top: 4rem !important;
    }

    .dashboard-header {
        padding: 15px 10px;
    }

    .dashboard-title {
        font-size: 1.5rem;
        margin-bottom: 8px;
        padding-left: 17px;
    }

    .dashboard-subtitle {
        font-size: 1rem;
        margin-bottom: 10px;
    }

    .purchase-link {
        font-size: 0.85rem;
        padding: 0 20px;
        line-height: 1.4;
    }

    .course-card {
        margin-bottom: 1rem !important;
        width: 100%;
    }

    .course-image {
        padding-top: 52% !important; /* Slightly shorter aspect ratio for mobile */
        width: 102%;
    }

    .course-content {
        padding: 1rem !important;
    }

    .course-content p {
        font-size: 0.8rem !important;
        margin-bottom: 1rem !important;
    }

    .course-meta {
        flex-direction: column !important;
        gap: 0.75rem !important;
        align-items: flex-start !important;
    }

    .course-meta span {
        font-size: 0.8rem !important;
    }

    .btn-continue {
        width: 100% !important;
        justify-content: center !important;
        padding: 0.6rem !important;
        font-size: 0.85rem !important;
    }
}
@media (min-width: 992px) {
    .col-lg-4 {
        flex: 0 0 auto;
        width: 45.333333%;
    }
}
@media (max-width: 576px) {
    .courses-dashboard {
        padding: 0.75rem !important;
    }

    .dashboard-header {
        margin-bottom: 1rem !important;
    }

    .dashboard-header h2 {
        font-size: 1.25rem !important;
    }

    .course-badge {
        top: 0.75rem !important;
        right: 0.75rem !important;
        padding: 0.3rem 0.5rem !important;
    }

    .course-content h3 {
        font-size: 0.95rem !important;
    }

    .course-content p {
        font-size: 0.75rem !important;
        line-height: 1.4 !important;
    }

    .btn-continue i {
        font-size: 1rem !important;
    }
}

@media (max-width: 360px) {
    .courses-dashboard {
        padding: 0.5rem !important;
    }

    .dashboard-header h2 {
        font-size: 1.15rem !important;
    }

    .subtitle {
        font-size: 0.8rem !important;
    }

    .course-content {
        padding: 0.75rem !important;
    }

    .course-badge {
        font-size: 0.7rem !important;
    }
}

@media (max-height: 600px) and (orientation: landscape) {
    .courses-dashboard {
        padding: 0.75rem !important;
    }

    .course-image {
        padding-top: 45% !important; /* Shorter images for landscape */
    }

    .course-content {
        padding: 0.75rem !important;
    }

    .course-content p {
        margin-bottom: 0.75rem !important;
    }
}

@media (-webkit-min-device-pixel-ratio: 2), (min-resolution: 192dpi) {
    .course-card {
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08) !important;
    }
}

.exams-section {
    padding-top: 100px !important;
    padding-bottom: 100px !important;
}

.dashboard-content {
    display: flex !important;
    gap: 2rem !important;
    margin-top: 2rem !important;
}

.course-sidebar {
    width: 280px !important;
    flex-shrink: 0 !important;
    background: #ffffff !important;
    border-radius: 12px !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1) !important;
    height: fit-content !important;
    position: sticky !important;
    top: 20px !important;
}

.sidebar-header {
    padding: 1.25rem !important;
    border-bottom: 1px solid #e5e7eb !important;
}

.sidebar-header h3 {
    font-size: 1.1rem !important;
    font-weight: 600 !important;
    color: #1a1f36 !important;
    margin: 0 !important;
}

.course-list {
    padding: 0.75rem 0 !important;
}

.course-item {
    padding: 0.75rem 1.25rem !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.75rem !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    color: #4f566b !important;
}

.course-item:hover {
    background: #f8f9fa !important;
    color: #0052cc !important;
}

.course-item.active {
    background: #e8f0fe !important;
    color: #0052cc !important;
    border-right: 3px solid #0052cc !important;
}

.course-item i {
    font-size: 1.25rem !important;
}

.courses-grid {
    flex: 1 !important;
}

.mobile-toggle, .mobile-close {
    display: none !important;
}

/* Responsive Design */
@media (max-width: 992px) {
    .dashboard-content {
        flex-direction: column !important;
    }

    .course-sidebar {
        width: 100% !important;
        position: relative !important;
        top: 0 !important;
    }
}

@media (max-width: 768px) {
    .course-sidebar {
        position: fixed !important;
        top: 185px !important;
        right: -100% !important;
        height: auto !important;
        width: 280px !important;
        z-index: 1000 !important;
        transition: right 0.3s ease !important;
    }

    .sidebar-active {
        right: 0 !important;
    }

    .mobile-toggle {
        display: flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        padding: 0.75rem 1rem !important;
        background: #ffffff !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 8px !important;
        margin-bottom: 1rem !important;
        font-size: 0.9rem !important;
        color: #1a1f36 !important;
        cursor: pointer !important;
    }

    .mobile-close {
        display: block !important;
        position: absolute !important;
        right: 1rem !important;
        top: 1rem !important;
        background: none !important;
        border: none !important;
        font-size: 1.5rem !important;
        color: #4f566b !important;
        cursor: pointer !important;
    }
}

/* Modern Pagination Styling */
.modern-pagination {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 50px;
    padding: 20px 0;
}

.page-numbers {
    display: flex;
    gap: 8px;
}

.page-btn, .nav-btn {
    background: #ffffff;
    border: none;
    padding: 8px 16px;
    min-width: 40px;
    height: 40px;
    border-radius: 8px;
    font-weight: 500;
    color: #666;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.page-btn:hover:not(.active):not(:disabled) {
    background: #f0f0f0;
    transform: translateY(-2px);
}

.page-btn.active {
    background: #FF8A00;
    color: white;
    font-weight: 600;
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(255, 138, 0, 0.2);
}

.nav-btn {
    font-size: 20px;
    padding: 8px 12px;
}

.nav-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: #f5f5f5;
}

.nav-btn:not(:disabled):hover {
    background: #f0f0f0;
    transform: translateY(-2px);
}

/* Dark mode support */
:deep(.theme-dark) .page-btn,
:deep(.theme-dark) .nav-btn {
    background: #2d2d2d;
    color: #fff;
}

:deep(.theme-dark) .page-btn:hover:not(.active):not(:disabled),
:deep(.theme-dark) .nav-btn:not(:disabled):hover {
    background: #3d3d3d;
}

:deep(.theme-dark) .page-btn.active {
    background: #FF8A00;
}

:deep(.theme-dark) .nav-btn:disabled {
    background: #222;
    color: #666;
}

/* Responsive adjustments */
@media (max-width: 480px) {
    .modern-pagination {
        gap: 6px;
    }

    .page-btn, .nav-btn {
        min-width: 36px;
        height: 36px;
        padding: 6px 12px;
        font-size: 14px;
    }
}

.dashboard-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.info-tooltip {
    position: relative;
    cursor: pointer;
}

.info-tooltip i {
    font-size: 20px;
    color: #666;
    transition: color 0.3s ease;
}

.info-tooltip:hover i {
    color: #FF8A00;
}

.tooltip-content {
    position: absolute;
    top: calc(100% + 10px);
    left: 50%;
    transform: translateX(-50%);
    width: 300px;
    background: white;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
    z-index: 1000;
}

.tooltip-content::before {
    content: '';
    position: absolute;
    top: -8px;
    left: 50%;
    transform: translateX(-50%);
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 8px solid white;
}

.info-tooltip:hover .tooltip-content {
    opacity: 1;
    visibility: visible;
    top: calc(100% + 5px);
}

.tooltip-content h3 {
    color: #FF8A00;
    margin-bottom: 12px;
    font-weight: 600;
}

.tooltip-content p {
    margin-bottom: 8px;
    font-size: 13px;
    color: #555;
}

.tooltip-note {
    margin-top: 12px;
    padding-top: 8px;
    border-top: 1px solid #eee;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12px;
    color: #666;
}

.tooltip-note i {
    color: #FF8A00;
    font-size: 16px;
}

/* Add these responsive styles */
h1 {
    font-size: 2rem !important; /* 32px default */
}

.subtitle {
    font-size: 1.25rem !important; /* 20px default */
}

.description {
    font-size: 0.875rem !important; /* 14px default */
}

/* Responsive breakpoints */
@media (max-width: 768px) {
    h1 {
        font-size: 1.5rem !important; /* 24px for mobile */
    }

    .subtitle {
        font-size: 1rem !important; /* 16px for mobile */
    }

    .description {
        font-size: 0.75rem !important; /* 12px for mobile */
    }
}

@media (max-width: 480px) {
    h1 {
        font-size: 1.25rem !important; /* 20px for smaller mobile */
    }

    .subtitle {
        font-size: 0.875rem !important; /* 14px for smaller mobile */
    }

    .description {
        font-size: 0.75rem !important; /* 12px for smaller mobile */
    }
}

/* Add responsive styles for tooltip */
@media (max-width: 768px) {
    .tooltip-content {
        width: 271px !important;
        left: -246.8px !important;
        transform: none !important; /* Remove the transform since we're using fixed left */
    }

    /* Adjust the arrow position for mobile */
    .tooltip-content::before {
        left: 125px !important; /* Adjust arrow position to match new alignment */
        transform: none !important;
    }
}
</style>
