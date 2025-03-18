import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';

const showSecurityAlert = async (message) => {
    await Swal.fire({
        title: 'Security Alert',
        text: message,
        icon: 'error',
        confirmButtonColor: '#FF8A00',
        customClass: {
            container: 'security-alert-container',
            popup: 'security-alert-popup',
            title: 'security-alert-title',
            content: 'security-alert-content',
            confirmButton: 'security-alert-button'
        }
    });
};

const PreventSecurity = (() => {
    let isExamSessionActive = true;
    let isCourseVideoSessionActive = true;
    let isQuizSessionActive = true;
    let router = null;
    let detectDevToolsInterval = null;
    let securityCallback = null;
    let currentContent = null;
    let videoRef = null;

    const forceStopVideo = () => {
        if (videoRef && videoRef.value) {
            try {
                const video = videoRef.value;
                video.pause();
                video.currentTime = 0;
                video.src = '';
                video.load();
                video.controls = false;
            } catch (error) {
                console.error('Error stopping video:', error);
            }
        }
    };

    const setSecurityCallback = (callback) => {
        console.log('Setting security callback');
        securityCallback = callback;
    };
    
    const triggerSecurityEvent = (reason) => {
        if (securityCallback) {
            securityCallback(reason);
        }
    };

    const handleBlur = async () => {
        if (isExamSessionActive) {
            triggerSecurityEvent('raison');
            isExamSessionActive = false;
            router.push('/dashboard/exams');
        } else if (isQuizSessionActive) {
            triggerSecurityEvent('quiz_security_breach');
        }
    };

    const handleVisibilityChange = async () => {
        if (document.hidden) {
            if (isExamSessionActive) {
                triggerSecurityEvent('raison');
                isExamSessionActive = false;
                router.push('/dashboard/exams');
            } else if (isQuizSessionActive) {
                triggerSecurityEvent('quiz_security_breach');
            }
        }
    };

    const disableRightClick = (event) => {
        event.preventDefault();
    };

    const handleKeydown = async (e) => {
        if (isExamSessionActive) {
            triggerSecurityEvent('raison');
            const blockedKeys = ['F12', 'F5', 'r'];
            const blockedCombos = [
                e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key)
            ];

            if (blockedKeys.includes(e.key) || blockedCombos.includes(true)) {
                triggerSecurityEvent('raison');
                e.preventDefault();
                isExamSessionActive = false;
                router.push('/dashboard/exams');
            }
        } else if (isQuizSessionActive) {
            const blockedKeys = ['F12', 'F5', 'r'];
            const blockedCombos = [
                e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key)
            ];

            if (blockedKeys.includes(e.key) || blockedCombos.includes(true)) {
                e.preventDefault();
                triggerSecurityEvent('quiz_security_breach');
                router.push('/dashboard/courses');
            }
        }
    };

    const handlePopstate = async () => {
        if (isExamSessionActive) {
            triggerSecurityEvent('raison');
            window.history.pushState(null, '', window.location.href);
            router.push('/dashboard/exams');
        } else if (isQuizSessionActive) {
            triggerSecurityEvent('quiz_security_breach');
        }
    };

    const handleSecurityViolation = async (router) => {
        console.log('Security violation detected');
        
        if (securityCallback) {
            console.log('Calling security callback');
            await securityCallback('video_security_breach');
        }

        // Stop video and navigate only after callback
        forceStopVideo();
        isCourseVideoSessionActive = false;
    };

    const handleBlurVideo = async (router) => {
        if (isCourseVideoSessionActive) {
            console.log('Blur detected');
            await handleSecurityViolation(router);
        }else if (isQuizSessionActive) {
            triggerSecurityEvent('quiz_security_breach');
            router.push("/dashboard/courses");
        }
    };

    const handleVisibilityChangeVideo = async (router) => {
        if (document.hidden && isCourseVideoSessionActive) {
            console.log('Visibility change detected');
            await handleSecurityViolation(router);
        }else if (isQuizSessionActive) {
            triggerSecurityEvent('quiz_security_breach');
            router.push("/dashboard/courses");
        }
    };

    const handleKeydownVideo = async (e, router) => {
        if (isCourseVideoSessionActive && ((e.ctrlKey && e.key === 'r') || e.key === 'F5')) {
            e.preventDefault();
            router.push("/dashboard/courses");
        }else if (isQuizSessionActive) {
            const blockedKeys = ['F12', 'F5', 'r'];
            const blockedCombos = [
                e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key)
            ];

            if (blockedKeys.includes(e.key) || blockedCombos.includes(true)) {
                e.preventDefault();
                triggerSecurityEvent('quiz_security_breach');
                router.push("/dashboard/courses");
            }
        }
    };

    const handlePopstateVideo = async (router, currentContent, isCompleted) => {
        // Simply navigate back when popstate occurs
        if (isCourseVideoSessionActive) {
            router.push("/dashboard/courses");
        }else if (isQuizSessionActive) {
            triggerSecurityEvent('quiz_security_breach');
            router.push("/dashboard/courses");
        }
    };

    const handleBeforeUnload = (e, currentContent, isCompleted) => {
        if (isCourseVideoSessionActive && 
            currentContent?.type === 'video' && 
            !isCompleted) {
            e.preventDefault();
            e.returnValue = '';
        }
    };

    const init = (appRouter, isQuiz = false) => {
        router = appRouter;
        isQuizSessionActive = isQuiz;
        
        window.addEventListener('blur', handleBlur);
        document.addEventListener('visibilitychange', handleVisibilityChange);
        document.addEventListener('contextmenu', disableRightClick);
        window.addEventListener('keydown', handleKeydown);
        window.addEventListener('popstate', handlePopstate);
        window.history.pushState(null, '', window.location.href);
    };

    const initVideo = (appRouter, videoPlayerRef, contentRef, completedRef, isQuiz = false) => {
        router = appRouter;
        videoRef = videoPlayerRef;
        currentContent = contentRef;
        isCourseVideoSessionActive = !isQuiz; // Set to false if it's a quiz
        isQuizSessionActive = isQuiz; // Set to true if it's a quiz

        console.log('Initializing security for:', isQuiz ? 'quiz' : 'video');

        window.addEventListener('blur', () => handleBlurVideo(appRouter));
        document.addEventListener('visibilitychange', () => handleVisibilityChangeVideo(appRouter));
        document.addEventListener('contextmenu', disableRightClick);
        window.addEventListener('keydown', (e) => handleKeydownVideo(e, appRouter));
        window.addEventListener('popstate', () => handlePopstateVideo(appRouter, contentRef.value, completedRef.value));
        window.addEventListener('beforeunload', (e) => handleBeforeUnload(e, contentRef.value, completedRef.value));
    };

    const cleanup = () => {
        window.removeEventListener('blur', handleBlur);
        document.removeEventListener('visibilitychange', handleVisibilityChange);
        document.removeEventListener('contextmenu', disableRightClick);
        window.removeEventListener('keydown', handleKeydown);
        window.removeEventListener('popstate', handlePopstate);
        clearInterval(detectDevToolsInterval);
        if (isExamSessionActive) {
            router.push('/dashboard/exams');
        }
        isQuizSessionActive = false;
    };

    const cleanupVideo = () => {
        forceStopVideo();
        window.removeEventListener('blur', handleBlurVideo);
        document.removeEventListener('visibilitychange', handleVisibilityChangeVideo);
        document.removeEventListener('contextmenu', disableRightClick);
        window.removeEventListener('keydown', handleKeydownVideo);
        window.removeEventListener('popstate', handlePopstateVideo);
        window.removeEventListener('beforeunload', handleBeforeUnload);
        isCourseVideoSessionActive = false;
        videoRef = null;
    };

    return { 
        init, 
        cleanup, 
        setSecurityCallback,
        initVideo,
        cleanupVideo
    };
})();

export default PreventSecurity;
