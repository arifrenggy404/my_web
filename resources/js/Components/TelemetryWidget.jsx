import React, { useEffect, useRef, useState } from 'react';

export default function TelemetryWidget() {
    const canvasRef = useRef(null);
    const [ip, setIp] = useState('127.0.0.1');
    const [node, setNode] = useState('UNKNOWN_NODE');
    const [battery, setBattery] = useState({ level: 100, charging: true, supported: false });
    const [cpuLoad, setCpuLoad] = useState('1.00%');

    // Animasi putaran radar
    useEffect(() => {
        const canvas = canvasRef.current;
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let animationFrameId;
        let angle = 0;

        const resize = () => {
            canvas.width = 45;
            canvas.height = 45;
        };
        resize();

        const draw = () => {
            const cx = canvas.width / 2;
            const cy = canvas.height / 2;
            const radius = Math.min(cx, cy) - 2;

            ctx.clearRect(0, 0, canvas.width, canvas.height);

            const style = getComputedStyle(document.documentElement);
            const primary = style.getPropertyValue('--color-terminal-primary').trim() || '#00f0ff';
            const accent = style.getPropertyValue('--color-terminal-accent').trim() || '#ff007f';

            // Menggambar lingkaran konsentris radar
            ctx.strokeStyle = primary;
            ctx.lineWidth = 0.5;
            ctx.globalAlpha = 0.2;
            ctx.beginPath();
            ctx.arc(cx, cy, radius, 0, Math.PI * 2);
            ctx.stroke();

            ctx.beginPath();
            ctx.arc(cx, cy, radius / 2, 0, Math.PI * 2);
            ctx.stroke();

            // Menggambar garis silang radar
            ctx.beginPath();
            ctx.moveTo(cx - radius, cy);
            ctx.lineTo(cx + radius, cy);
            ctx.moveTo(cx, cy - radius);
            ctx.lineTo(cx, cy + radius);
            ctx.stroke();

            // Menggambar sapuan garis radar
            ctx.globalAlpha = 0.6;
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            const targetX = cx + radius * Math.cos(angle);
            const targetY = cy + radius * Math.sin(angle);
            ctx.lineTo(targetX, targetY);
            ctx.stroke();

            // Menggambar beberapa titik target radar berkedip (menggunakan warna aksen)
            ctx.globalAlpha = 0.8;
            ctx.fillStyle = accent;
            const dot1X = cx + (radius * 0.7) * Math.cos(0.5);
            const dot1Y = cy + (radius * 0.7) * Math.sin(0.5);
            ctx.beginPath();
            ctx.arc(dot1X, dot1Y, 1.5, 0, Math.PI * 2);
            ctx.fill();

            const dot2X = cx + (radius * 0.4) * Math.cos(3.2);
            const dot2Y = cy + (radius * 0.4) * Math.sin(3.2);
            ctx.beginPath();
            ctx.arc(dot2X, dot2Y, 1.5, 0, Math.PI * 2);
            ctx.fill();

            angle += 0.03;
            if (angle > Math.PI * 2) angle = 0;

            ctx.globalAlpha = 1.0;
            animationFrameId = requestAnimationFrame(draw);
        };

        draw();

        return () => {
            cancelAnimationFrame(animationFrameId);
        };
    }, []);

    // Efek pembacaan data sensor
    useEffect(() => {
        // Fetch IP dari Laravel API
        fetch('/api/ip')
            .then(res => res.json())
            .then(data => {
                if (data.ip) setIp(data.ip);
            })
            .catch(() => {});

        // Parsing User Agent sederhana
        const ua = navigator.userAgent;
        let os = 'UNKNOWN_OS';
        let browser = 'UNKNOWN_BROWSER';

        if (ua.indexOf('Win') !== -1) os = 'WIN';
        else if (ua.indexOf('Mac') !== -1) os = 'MAC';
        else if (ua.indexOf('Linux') !== -1) os = 'LINUX';
        else if (ua.indexOf('Android') !== -1) os = 'ANDR';
        else if (ua.indexOf('like Mac') !== -1) os = 'IOS';

        if (ua.indexOf('Firefox') !== -1) browser = 'FF';
        else if (ua.indexOf('Chrome') !== -1) browser = 'CHROME';
        else if (ua.indexOf('Safari') !== -1) browser = 'SAFARI';
        else if (ua.indexOf('Edge') !== -1) browser = 'EDGE';
        else if (ua.indexOf('Opera') !== -1) browser = 'OPERA';

        setNode(`${os}_${browser}`);

        // Membaca Battery Status API
        if (navigator.getBattery) {
            navigator.getBattery().then(batt => {
                const updateBattery = () => {
                    setBattery({
                        level: Math.round(batt.level * 100),
                        charging: batt.charging,
                        supported: true
                    });
                };
                updateBattery();
                batt.addEventListener('levelchange', updateBattery);
                batt.addEventListener('chargingchange', updateBattery);
            });
        }

        // Simulasi fluktuasi beban CPU
        const interval = setInterval(() => {
            const load = (Math.random() * 3.5 + 1.0).toFixed(2);
            setCpuLoad(`${load}%`);
        }, 1500);

        return () => clearInterval(interval);
    }, []);

    const renderBatteryBar = () => {
        if (!battery.supported) return '[UNAVAILABLE]';
        const blocks = Math.round(battery.level / 20);
        const bar = '='.repeat(blocks) + ' '.repeat(5 - blocks);
        return `[${bar}] ${battery.level}% ${battery.charging ? '⚡' : ''}`;
    };

    return (
        <div className="flex flex-row items-center gap-4 p-3 border border-terminal-primary/20 bg-terminal-muted/5 font-mono text-[10px] w-full text-gray-400 select-none">
            <div className="relative flex-shrink-0 flex items-center justify-center border border-terminal-primary/30 p-1 bg-black">
                <canvas ref={canvasRef} className="w-[45px] h-[45px]" />
                <div className="absolute inset-0 bg-gradient-to-b from-terminal-primary/5 via-transparent to-terminal-primary/5 pointer-events-none" />
            </div>
            <div className="grid grid-cols-2 gap-x-4 gap-y-1 flex-1 min-w-0">
                <div className="truncate"><span className="text-terminal-primary">IP_UPLINK:</span> {ip}</div>
                <div className="truncate"><span className="text-terminal-primary">NODE:</span> {node}</div>
                <div className="col-span-2 truncate"><span className="text-terminal-primary">CORE_BATT:</span> {renderBatteryBar()}</div>
                <div className="col-span-2 truncate"><span className="text-terminal-primary">SYS_LOAD:</span> {cpuLoad}</div>
            </div>
        </div>
    );
}
