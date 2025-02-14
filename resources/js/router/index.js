import { createRouter, createWebHistory } from 'vue-router'
import Courses from '../dashboard/Courses/Courses.vue'
import CourseVideo from '../dashboard/Courses/CourseVideo.vue'
import Exams from '../dashboard/Exams/Exams.vue'
import ExamDetails from '../dashboard/Exams/ExamDetails.vue'

const routes = [
    {
        path: '/dashboard',
        redirect: '/dashboard/courses'
    },
    {
        path: '/dashboard/courses',
        name: 'courses',
        component: Courses
    },
    {
        path: '/dashboard/courses/:id/video',
        name: 'course-video',
        component: CourseVideo
    },
    {
        path: '/dashboard/exams',
        name: 'exams',
        component: Exams
    },
    {
        path: '/dashboard/exams/:id',
        name: 'exam-details',
        component: ExamDetails
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
