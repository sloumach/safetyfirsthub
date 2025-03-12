import Swal from 'sweetalert2';
import { useRouter } from 'vue-router';




const ExamSecurity = (() => {
    let isExamSessionActive = true;
    let router = null;
    let detectDevToolsInterval = null;
    let securityCallback = null;

    const setSecurityCallback = (callback) => {
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
        if (isExamSessionActive) {
            triggerSecurityEvent('raison');
            // Swal.fire({
            //     title: 'Action Not Allowed',
            //     text: 'Right-clicking is disabled during the exam.',
            //     icon: 'warning',
            //     timer: 2000,
            //     showConfirmButton: false
            // });
        }
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

    const init = (appRouter) => {
        router = appRouter;
        window.addEventListener('blur', handleBlur);
        document.addEventListener('visibilitychange', handleVisibilityChange);
        document.addEventListener('contextmenu', disableRightClick);
        window.addEventListener('keydown', handleKeydown);
        window.addEventListener('popstate', handlePopstate);
        window.history.pushState(null, '', window.location.href);

        
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

    return { init, cleanup, setSecurityCallback };
})();

export default ExamSecurity;
