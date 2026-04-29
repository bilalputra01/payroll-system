(function () {
    const canvas = document.getElementById("bg-canvas");
    const ctx = canvas.getContext("2d");
    let W, H, particles;

    function resize() {
        W = canvas.width = window.innerWidth;
        H = canvas.height = window.innerHeight;
    }

    function initParticles() {
        particles = Array.from(
            {
                length: 55,
            },
            () => ({
                x: Math.random() * W,
                y: Math.random() * H,
                r: Math.random() * 1.2 + 0.3,
                vx: (Math.random() - 0.5) * 0.18,
                vy: (Math.random() - 0.5) * 0.18,
                a: Math.random() * 0.45 + 0.05,
            }),
        );
    }

    function drawGrid() {
        ctx.strokeStyle = "rgba(201,168,76,0.04)";
        ctx.lineWidth = 1;
        const step = 80;
        for (let x = 0; x < W; x += step) {
            ctx.beginPath();
            ctx.moveTo(x, 0);
            ctx.lineTo(x, H);
            ctx.stroke();
        }
        for (let y = 0; y < H; y += step) {
            ctx.beginPath();
            ctx.moveTo(0, y);
            ctx.lineTo(W, y);
            ctx.stroke();
        }
    }

    function drawOrb(cx, cy, radius, colorStop) {
        const g = ctx.createRadialGradient(cx, cy, 0, cx, cy, radius);
        g.addColorStop(0, colorStop);
        g.addColorStop(1, "transparent");
        ctx.fillStyle = g;
        ctx.beginPath();
        ctx.arc(cx, cy, radius, 0, Math.PI * 2);
        ctx.fill();
    }

    let t = 0;

    function frame() {
        ctx.clearRect(0, 0, W, H);
        drawGrid();

        // Soft ambient orbs
        drawOrb(W * 0.15, H * 0.3, 340, "rgba(201,168,76,0.045)");
        drawOrb(W * 0.85, H * 0.7, 380, "rgba(50,60,140,0.06)");
        drawOrb(W * 0.5, H * 0.5, 280, "rgba(201,168,76,0.02)");

        // Particles
        particles.forEach((p) => {
            p.x += p.vx;
            p.y += p.vy;
            if (p.x < 0) p.x = W;
            if (p.x > W) p.x = 0;
            if (p.y < 0) p.y = H;
            if (p.y > H) p.y = 0;

            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = `rgba(201,168,76,${p.a})`;
            ctx.fill();
        });

        t++;
        requestAnimationFrame(frame);
    }

    resize();
    initParticles();
    frame();
    window.addEventListener("resize", () => {
        resize();
        initParticles();
    });
})();
