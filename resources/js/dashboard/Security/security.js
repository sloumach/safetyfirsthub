import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';




const PreventSecurity = (() => {
    let isExamSessionActive = true;
    let isCourseVideoSessionActive = true;
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
            // await Swal.fire({
            //     title: 'Window Unfocused',
            //     text: 'Your exam session has ended due to switching applications.',
            //     icon: 'warning',
            //     confirmButtonColor: '#3085d6',
            //     allowOutsideClick: false,
            //     allowEscapeKey: false,
            // });
            router.push('/dashboard/exams');
        }
    };

    const handleVisibilityChange = async () => {
        if (document.hidden || isExamSessionActive) {
            triggerSecurityEvent('raison');
            isExamSessionActive = false;
            // await Swal.fire({
            //     title: 'Session Ended',
            //     text: 'Your exam session has ended due to switching tabs.',
            //     icon: 'warning',
            //     confirmButtonColor: '#3085d6',
            //     allowOutsideClick: false,
            //     allowEscapeKey: false,
            // });
            router.push('/dashboard/exams');
        }
    };

    const disableRightClick = (event) => {
        event.preventDefault();
    };

    const handleKeydown = async (e) => {
        if (!isExamSessionActive) return;
        triggerSecurityEvent('raison');
        const blockedKeys = ['F12', 'F5', 'r'];
        const blockedCombos = [
            e.ctrlKey && e.shiftKey && ['I', 'J', 'C'].includes(e.key)
        ];

        if (blockedKeys.includes(e.key) || blockedCombos.includes(true)) {
            triggerSecurityEvent('raison');
            e.preventDefault();
            isExamSessionActive = false;
            // await Swal.fire({
            //     title: 'Unauthorized Action',
            //     text: 'This action is not allowed during the exam.',
            //     icon: 'warning',
            //     confirmButtonColor: '#3085d6',
            // });
            router.push('/dashboard/exams');
        }
    };

    const handlePopstate = async () => {
        if (isExamSessionActive) {
            triggerSecurityEvent('raison');
            window.history.pushState(null, '', window.location.href);
            // await Swal.fire({
            //     title: 'Navigation Detected',
            //     text: 'Please use the application navigation.',
            //     icon: 'warning',
            //     confirmButtonColor: '#3085d6',
            // });
            router.push('/dashboard/exams');
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
        }
    };

    const handleVisibilityChangeVideo = async (router) => {
        if (document.hidden && isCourseVideoSessionActive) {
            console.log('Visibility change detected');
            await handleSecurityViolation(router);
        }
    };

    const handleKeydownVideo = async (e, router) => {
        if (isCourseVideoSessionActive && ((e.ctrlKey && e.key === 'r') || e.key === 'F5')) {
            e.preventDefault();
            // await Swal.fire({
            //     title: 'Refresh Attempted',
            //     text: 'Refreshing will end your video session.',
            //     icon: 'warning',
            //     confirmButtonColor: '#3085d6',
            // });
            router.push("/dashboard/courses");
        }
    };

    const handlePopstateVideo = async (router, currentContent, isCompleted) => {
        // Simply navigate back when popstate occurs
        if (isCourseVideoSessionActive) {
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

    const init = (appRouter) => {
        router = appRouter;
        window.addEventListener('blur', handleBlur);
        document.addEventListener('visibilitychange', handleVisibilityChange);
        document.addEventListener('contextmenu', disableRightClick);
        window.addEventListener('keydown', handleKeydown);
        window.addEventListener('popstate', handlePopstate);
        window.history.pushState(null, '', window.location.href);

        
    };

    const initVideo = (appRouter, videoPlayerRef, contentRef, completedRef) => {
        router = appRouter;
        videoRef = videoPlayerRef;
        currentContent = contentRef;
        isCourseVideoSessionActive = true;

        console.log('Initializing video security');

        window.addEventListener('blur', () => handleBlurVideo(appRouter));
        document.addEventListener('visibilitychange', () => handleVisibilityChangeVideo(appRouter));
        document.addEventListener('contextmenu', disableRightClick);
        window.addEventListener('keydown', (e) => handleKeydownVideo(e, appRouter));
        window.addEventListener('popstate', () => handlePopstateVideo(appRouter, contentRef.value, completedRef.value));
        window.addEventListener('beforeunload', (e) => handleBeforeUnload(e, contentRef.value, completedRef.value));
        
        // Remove this line that was causing the issue
        // window.history.pushState(null, '', window.location.href);
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
