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
    let isExamSessionActive = false;
    let isCourseVideoSessionActive = false;
    let isQuizSessionActive = false;
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
        console.log('Blur event triggered - Current states:', {
            isExamSessionActive,
            isQuizSessionActive
        });

        if (isExamSessionActive) {
            console.log('Exam security breach detected!');
            if (securityCallback) {
                await securityCallback('raison');
            }
            isExamSessionActive = false;
            window.location.replace('/dashboard/exams');
            return;
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
        if (securityCallback) {
            await securityCallback('video_security_breach');
        }

        // Stop video and navigate only after callback
        forceStopVideo();
        isCourseVideoSessionActive = false;
    };

    const handleBlurVideo = async (router) => {
        if (isCourseVideoSessionActive) {
            await handleSecurityViolation(router);
        } else if (isQuizSessionActive) {
            triggerSecurityEvent('quiz_security_breach');
            router.push("/dashboard/courses");
        }
    };

    const handleVisibilityChangeVideo = async (router) => {
        if (document.hidden && isCourseVideoSessionActive) {
            await handleSecurityViolation(router);
        } else if (isQuizSessionActive) {
            triggerSecurityEvent('quiz_security_breach');
            router.push("/dashboard/courses");
        }
    };

    const handleKeydownVideo = async (e, router) => {
        if (isCourseVideoSessionActive) {
            // Block Ctrl+R, F5, and Ctrl+C
            if ((e.ctrlKey && (e.key === 'r' || e.key === 'c')) || e.key === 'F5') {
                e.preventDefault();
                if (e.key === 'r' || e.key === 'F5') {
                    router.push("/dashboard/courses");
                }
            }
        } else if (isQuizSessionActive) {
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
        } else if (isQuizSessionActive) {
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
        console.log('Initializing security...');
        
        // First remove any existing listeners
        removeAllListeners();
        
        // Then set the state
        isExamSessionActive = true;
        router = appRouter;
        isQuizSessionActive = isQuiz;

        console.log('After init - States:', {
            isExamSessionActive,
            isQuizSessionActive,
            hasRouter: !!router
        });

        // Add fresh event listeners
        addAllListeners();
        
        window.history.pushState(null, '', window.location.href);
    };

    const addAllListeners = () => {
        console.log('Adding all listeners...');
        window.addEventListener('blur', handleBlur);
        document.addEventListener('visibilitychange', handleVisibilityChange);
        document.addEventListener('contextmenu', disableRightClick);
        window.addEventListener('keydown', handleKeydown);
        window.addEventListener('popstate', handlePopstate);
    };

    const removeAllListeners = () => {
        console.log('Removing all listeners...');
        window.removeEventListener('blur', handleBlur);
        document.removeEventListener('visibilitychange', handleVisibilityChange);
        document.removeEventListener('contextmenu', disableRightClick);
        window.removeEventListener('keydown', handleKeydown);
        window.removeEventListener('popstate', handlePopstate);
    };

    const initVideo = (appRouter, videoPlayerRef, contentRef, completedRef, isQuiz = false) => {
        router = appRouter;
        videoRef = videoPlayerRef;
        currentContent = contentRef;
        isCourseVideoSessionActive = !isQuiz; // Set to false if it's a quiz
        isQuizSessionActive = isQuiz; // Set to true if it's a quiz

        window.addEventListener('blur', () => handleBlurVideo(appRouter));
        document.addEventListener('visibilitychange', () => handleVisibilityChangeVideo(appRouter));
        document.addEventListener('contextmenu', disableRightClick);
        window.addEventListener('keydown', (e) => handleKeydownVideo(e, appRouter));
        window.addEventListener('popstate', () => handlePopstateVideo(appRouter, contentRef.value, completedRef.value));
        window.addEventListener('beforeunload', (e) => handleBeforeUnload(e, contentRef.value, completedRef.value));
    };

    const cleanup = () => {
        console.log('Cleaning up security...');
        removeAllListeners();
        isExamSessionActive = false;
        isQuizSessionActive = false;
        router = null;
        securityCallback = null;
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
