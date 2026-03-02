document.addEventListener('DOMContentLoaded', () => {
    // Highlight the active navigation link
    const currentPath = window.location.pathname.split('/').pop() || 'index.html';
    const navLinks = document.querySelectorAll('nav ul li a');

    navLinks.forEach(link => {
        const linkPath = link.getAttribute('href');
        if (linkPath === currentPath) {
            link.classList.add('active');
        }
    });

    // Smooth scrolling for navigation links (now mostly for future internal anchors)
    document.querySelectorAll('nav a').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            // Check if the link is an internal anchor
            const href = this.getAttribute('href');
            if (href.startsWith('#')) {
                e.preventDefault();

                document.querySelector(href).scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
    /*

    // Simple Contact Form Submission (Client-side simulation)
    const contactForm = document.getElementById('contactForm');
    const formMessage = document.getElementById('form-message');

    if (contactForm) {
        contactForm.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent actual form submission

            // In a real application, you would send this data to a server
            // using fetch() or XMLHttpRequest.
            console.log('Form submitted!');
            console.log('Name:', document.getElementById('name').value);
            console.log('Email:', document.getElementById('email').value);
            console.log('Subject:', document.getElementById('subject').value);
            console.log('Message:', document.getElementById('message').value);

            // Simulate a successful submission
            formMessage.textContent = 'Thank you for your message! We will get back to you soon.';
            formMessage.classList.remove('error');
            formMessage.classList.add('success');
            formMessage.style.display = 'block';

            contactForm.reset(); // Clear the form

            // Hide message after a few seconds
            setTimeout(() => {
                formMessage.style.display = 'none';
            }, 5000);
*/
            // You could also simulate an error:
            /*
            formMessage.textContent = 'There was an error sending your message. Please try again.';
            formMessage.classList.remove('success');
            formMessage.classList.add('error');
            formMessage.style.display = 'block';
            */
        });


    // Optional: Play/Pause video on hover for sections (e.g., vision/mission)
    document.querySelectorAll('video').forEach(video => {
        video.addEventListener('mouseenter', () => {
            video.play();
        });
        video.addEventListener('mouseleave', () => {
            video.pause();
        });
    });