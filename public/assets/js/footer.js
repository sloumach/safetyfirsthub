document.addEventListener('DOMContentLoaded', function() {
    function handleFooterResponsive() {
        const footerTitles = document.querySelectorAll('.footer-title');
        const allContent = document.querySelectorAll('.footer-content');
        
        if (window.innerWidth <= 991) {
            // Mobile view - setup accordion
            allContent.forEach(content => {
                content.style.display = 'none';
            });

            footerTitles.forEach(title => {
                title.addEventListener('click', toggleSection);
            });
        } else {
            // Desktop view - reset everything
            allContent.forEach(content => {
                content.style.display = '';
            });
            
            footerTitles.forEach(title => {
                title.removeEventListener('click', toggleSection);
                const toggle = title.querySelector('.footer-toggle');
                if (toggle) toggle.classList.remove('active');
            });
        }
    }

    function toggleSection() {
        const content = this.nextElementSibling;
        const toggle = this.querySelector('.footer-toggle');
        const isOpen = content.style.display === 'block';

        // Close all sections first
        document.querySelectorAll('.footer-content').forEach(c => {
            c.style.display = 'none';
        });
        document.querySelectorAll('.footer-toggle').forEach(t => {
            t.classList.remove('active');
        });

        // If clicked section wasn't open, open it
        if (!isOpen) {
            content.style.display = 'block';
            toggle.classList.add('active');
        }
    }

    // Initial setup
    handleFooterResponsive();

    // Handle resize
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(handleFooterResponsive, 250);
    });
}); 