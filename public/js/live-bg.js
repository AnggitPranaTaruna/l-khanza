document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('bg-canvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d');
    let particles = [];
    let animationFrameId;
    let width, height;
    
    // Mouse interaction states
    const mouse = {
        x: null,
        y: null,
        radius: 120, // Interaction radius
        active: false
    };

    // Color configurations from theme
    let colors = {
        primary: '#0ea5e9',
        secondary: '#6366f1',
        textSec: '#94a3b8'
    };

    function updateColors() {
        const styles = getComputedStyle(document.documentElement);
        const primary = styles.getPropertyValue('--primary').trim();
        const secondary = styles.getPropertyValue('--secondary').trim();
        const textSec = styles.getPropertyValue('--text-secondary').trim();
        
        if (primary) colors.primary = primary;
        if (secondary) colors.secondary = secondary;
        if (textSec) colors.textSec = textSec;
    }

    updateColors();

    // Observe theme change
    const observer = new MutationObserver(() => {
        updateColors();
    });
    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['data-theme']
    });

    // Particle Class
    class Particle {
        constructor() {
            this.reset(true);
        }

        reset(isInitial = false) {
            this.x = Math.random() * width;
            // Spawn particles at edges if they are recycled during motion, else random
            this.y = isInitial ? Math.random() * height : height + 5;
            this.size = Math.random() * 2 + 1; // 1px to 3px
            // Gentle upward drift, plus minor horizontal sway
            this.speedX = (Math.random() - 0.5) * 0.15;
            this.speedY = -(Math.random() * 0.2 + 0.1); // float up
            this.colorType = Math.random() > 0.4 ? 'primary' : 'secondary';
            this.alpha = Math.random() * 0.35 + 0.1;
        }

        update() {
            this.x += this.speedX;
            this.y += this.speedY;

            // Recycling out-of-screen particles
            if (this.x < -10 || this.x > width + 10 || this.y < -10) {
                this.reset(false);
            }

            // Mouse interaction - gentle repulsion
            if (mouse.active && mouse.x !== null && mouse.y !== null) {
                const dx = mouse.x - this.x;
                const dy = mouse.y - this.y;
                const distance = Math.hypot(dx, dy);
                if (distance < mouse.radius) {
                    const force = (mouse.radius - distance) / mouse.radius;
                    // Push particles away from the mouse
                    this.x -= (dx / distance) * force * 0.8;
                    this.y -= (dy / distance) * force * 0.8;
                }
            }
        }

        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = this.colorType === 'primary' ? colors.primary : colors.secondary;
            ctx.globalAlpha = this.alpha;
            ctx.fill();
        }
    }

    function init() {
        const isReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        
        if (isReduced) {
            particles = [];
            return;
        }

        resize();
        particles = [];
        
        // Density calculation (fewer particles on smaller screens/mobiles)
        const area = width * height;
        const particleCount = Math.min(Math.floor(area / 20000), 70);

        for (let i = 0; i < particleCount; i++) {
            particles.push(new Particle());
        }
    }

    function resize() {
        const dpr = window.devicePixelRatio || 1;
        width = window.innerWidth;
        height = window.innerHeight;
        canvas.width = width * dpr;
        canvas.height = height * dpr;
        canvas.style.width = width + 'px';
        canvas.style.height = height + 'px';
        ctx.scale(dpr, dpr);
    }

    let resizeTimeout;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            resize();
            init();
        }, 150);
    });

    // Track mouse coordinates
    window.addEventListener('mousemove', (e) => {
        mouse.x = e.clientX;
        mouse.y = e.clientY;
        mouse.active = true;
    });

    window.addEventListener('mouseleave', () => {
        mouse.x = null;
        mouse.y = null;
        mouse.active = false;
    });

    // Mobile touch coordinates
    window.addEventListener('touchmove', (e) => {
        if (e.touches.length > 0) {
            mouse.x = e.touches[0].clientX;
            mouse.y = e.touches[0].clientY;
            mouse.active = true;
        }
    }, { passive: true });

    window.addEventListener('touchend', () => {
        mouse.x = null;
        mouse.y = null;
        mouse.active = false;
    });

    function drawLines() {
        const limit = 130;
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const distance = Math.hypot(dx, dy);

                if (distance < limit) {
                    const alpha = (limit - distance) / limit * 0.07;
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    
                    // Simple path styling
                    ctx.strokeStyle = colors.primary;
                    ctx.lineWidth = 0.5;
                    ctx.globalAlpha = alpha;
                    ctx.stroke();
                }
            }
        }
    }

    function animate() {
        ctx.clearRect(0, 0, width, height);
        
        // Gentle mesh connections
        drawLines();

        // Update and draw floating nodes
        particles.forEach(particle => {
            particle.update();
            particle.draw();
        });

        ctx.globalAlpha = 1;
        animationFrameId = requestAnimationFrame(animate);
    }

    // Start background simulation
    init();
    animate();

    // Pause rendering when tab is out of view
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            cancelAnimationFrame(animationFrameId);
        } else {
            animate();
        }
    });
});
