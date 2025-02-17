import { createRouter, createWebHistory } from 'vue-router'
import Courses from '../dashboard/Courses/Courses.vue'
import CourseVideo from '../dashboard/Courses/CourseVideo.vue'
import Exams from '../dashboard/Exams/Exams.vue'
import ExamDetails from '../dashboard/Exams/ExamDetails.vue'
import Certificates from '../dashboard/Certificates/Certificates.vue'
import CertificateDetails from '../dashboard/Certificates/CertificateDetails.vue'

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
    },
    {
        path: '/dashboard/certificates',
        name: 'certificates',
        component: Certificates
    },
    {
        path: '/dashboard/certificate',
        name: 'Certificates',
        component: Certificates
    },
    {
        path: '/dashboard/certificate/:id',
        name: 'CertificateDetails',
        component: CertificateDetails
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router
