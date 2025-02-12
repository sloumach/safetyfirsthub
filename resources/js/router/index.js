import { createRouter, createWebHistory } from 'vue-router'
import Courses from '../dashboard/Courses/Courses.vue'
import CourseVideo from '../dashboard/Courses/CourseVideo.vue'

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
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
