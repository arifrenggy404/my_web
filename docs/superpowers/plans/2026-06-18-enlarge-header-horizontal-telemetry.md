# Centering ASCII Header & Horizontal Telemetry Row Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Enlarge and center the ASCII header "ARIF RENGGY" and align the telemetry info along with the active session status horizontally on the header bottom border.

**Architecture:** 
- The ASCII header component will be enlarged (`text-xs md:text-sm`) and centered via `text-center mx-auto w-fit`.
- The telemetry widget will be expanded to also render the active session indicator and the system version in a clean, horizontal, two-column flex layout.
- The main layout header will be simplified to stack these two rows vertically, positioning the telemetry widget directly above the header divider.

**Tech Stack:** React, Tailwind CSS 4, Inertia.js.

## Global Constraints

- Keep telemetry widget text size at `text-[9px]` or similar compact styling.
- Retain the pipe separator (`|`) between telemetry items.
- Maintain persistent responsive desktop view (`width=800` viewport configuration).

---

### Task 1: Enlarge and Center the ASCII Header Component

**Files:**
- Modify: `resources/js/Components/AsciiHeader.jsx`

**Interfaces:**
- Produces: `<AsciiHeader />` with updated styling.

- [ ] **Step 1: Modify AsciiHeader.jsx styles**

Change the classes on the `<pre>` tag to center the text, set width to fit, and set the text size to `text-xs md:text-sm`.

```jsx
import React from 'react';

const ASCII_ART = `
  ___  ____  ___ _____   ____  ____ _   _  ____  ______   __
 / _ \\|  _ \\|_ _|  ___| |  _ \\| ___| \\ | |/ ___|/ ___\\ \\ / /
| |_| | |_) || || |_    | |_) |  _| |  \\| | |  _| |  _ \\ V / 
|  _  |  _ < | ||  _|   |  _ <| |___| |\\  | |_| | |_| | | |  
|_| |_|_| \\_\\___|_|     |_| \\_\\_____|_| \\_|\\____|\\____| |_|  
`;

export default function AsciiHeader() {
    return (
        <pre className="text-xs md:text-sm text-center leading-[1] text-terminal-primary font-mono select-none opacity-80 hover:opacity-100 transition-opacity mx-auto w-fit">
            {ASCII_ART}
        </pre>
    );
}
```

- [ ] **Step 2: Commit Task 1**

Run:
```bash
git add resources/js/Components/AsciiHeader.jsx
git commit -m "style: center and enlarge ASCII art title in AsciiHeader"
```

---

### Task 2: Refactor TelemetryWidget to Include Session and Version Info

**Files:**
- Modify: `resources/js/Components/TelemetryWidget.jsx`

**Interfaces:**
- Produces: `<TelemetryWidget />` rendering horizontal telemetry data plus session status and version.

- [ ] **Step 1: Update TelemetryWidget.jsx to use new layout**

Replace the returned layout with a flex row that is split into two sections: left (IP, Node, Battery, CPU Load) and right (Terminal Session Status, Version).

```jsx
import React, { useEffect, useState } from 'react';

export default function TelemetryWidget() {
    const [ip, setIp] = useState('127.0.0.1');
    const [node, setNode] = useState('UNKNOWN_NODE');
    const [battery, setBattery] = useState({ level: 100, charging: true, supported: false });
    const [cpuLoad, setCpuLoad] = useState('1.00%');

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
        <div className="flex flex-row flex-wrap items-center justify-between w-full font-mono text-[9px] text-gray-500 select-none pb-2">
            <div className="flex flex-row flex-wrap items-center gap-x-3 gap-y-1">
                <div><span className="text-terminal-primary">IP:</span> {ip}</div>
                <div className="text-gray-700">|</div>
                <div><span className="text-terminal-primary">NODE:</span> {node}</div>
                <div className="text-gray-700">|</div>
                <div><span className="text-terminal-primary">BATT:</span> {renderBatteryBar()}</div>
                <div className="text-gray-700">|</div>
                <div><span className="text-terminal-primary">LOAD:</span> {cpuLoad}</div>
            </div>
            <div className="flex flex-row flex-wrap items-center gap-x-3 gap-y-1">
                <div className="flex items-center gap-1.5 uppercase text-terminal-primary">
                    <span className="inline-block w-1.5 h-1.5 bg-terminal-primary rounded-full animate-pulse"></span>
                    SESSION: ACTIVE
                </div>
                <div className="text-gray-700">|</div>
                <div><span className="text-terminal-primary">VER:</span> v4.0.1</div>
            </div>
        </div>
    );
}
```

- [ ] **Step 2: Commit Task 2**

Run:
```bash
git add resources/js/Components/TelemetryWidget.jsx
git commit -m "feat: include terminal session and node version in TelemetryWidget"
```

---

### Task 3: Restructure Header Layout in ArsipLayout.jsx

**Files:**
- Modify: `resources/js/Layouts/ArsipLayout.jsx:118-133`

**Interfaces:**
- Consumes: `<AsciiHeader />`, `<TelemetryWidget />`

- [ ] **Step 1: Simplify and restructure the layout header**

Locate lines 118-133 in `ArsipLayout.jsx` and replace the existing header block. The header container will use a vertical stack: centered ASCII art on top, and the full-width `<TelemetryWidget />` at the bottom right above the header border.

```jsx
            <header className="max-w-7xl mx-auto border-b border-terminal/30 pb-1 mb-8 flex flex-col items-center gap-4 relative z-10">
                <h1 className="sr-only">Arif Renggy - Portofolio Developer Laravel & React</h1>
                
                {/* Row 1: ASCII Art (Centered) */}
                <div className="w-full flex justify-center py-2">
                    <AsciiHeader />
                </div>
                
                {/* Row 2: Telemetry Widget sits directly on/above the header bottom border */}
                <TelemetryWidget />
            </header>
```

- [ ] **Step 2: Commit Task 3**

Run:
```bash
git add resources/js/Layouts/ArsipLayout.jsx
git commit -m "style: center ascii header and layout telemetry horizontally on divider"
```

---

### Task 4: Compilation and Verification

- [ ] **Step 1: Compile assets using Vite**

Run: `npm run build`
Expected: Production build compiles successfully with no compilation errors.

- [ ] **Step 2: Run test suite**

Run: `vendor/bin/phpunit`
Expected: All tests pass.
