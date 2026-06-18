# Terminal Monitoring System Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Implement a real-time status bar and a system-log notification pop-up for the cyberpunk portfolio.

**Architecture:** Add specialized React components for the status bar and notifications. Integrate them into `ArsipLayout` to ensure they are present on all pages. Use React hooks for real-time data simulation (latency and clock) and Inertia hooks for navigation-based logging.

**Tech Stack:** React 19, Inertia.js, Framer Motion, Lucide React, Tailwind CSS 4.

---

### Task 1: Create TerminalStatusBar Component

**Files:**
- Create: `resources/js/Components/TerminalStatusBar.jsx`

- [ ] **Step 1: Implement TerminalStatusBar component with real-time clock and simulated ping**

```jsx
import React, { useState, useEffect } from 'react';

export default function TerminalStatusBar() {
    const [time, setTime] = useState(new Date());
    const [sessionTime, setSessionTime] = useState(0);
    const [ping, setPing] = useState(32);

    useEffect(() => {
        // Clock & Session Timer
        const start = sessionStorage.getItem('sessionStartTime') || Date.now();
        if (!sessionStorage.getItem('sessionStartTime')) {
            sessionStorage.setItem('sessionStartTime', start);
        }

        const timer = setInterval(() => {
            setTime(new Date());
            setSessionTime(Math.floor((Date.now() - start) / 1000));
        }, 1000);

        // Ping Simulation
        const pingInterval = setInterval(() => {
            setPing(prev => {
                const change = Math.floor(Math.random() * 11) - 5; // -5 to +5
                return Math.max(15, Math.min(120, prev + change));
            });
        }, 3000);

        return () => {
            clearInterval(timer);
            clearInterval(pingInterval);
        };
    }, []);

    const formatDuration = (seconds) => {
        const h = Math.floor(seconds / 3600).toString().padStart(2, '0');
        const m = Math.floor((seconds % 3600) / 60).toString().padStart(2, '0');
        const s = (seconds % 60).toString().padStart(2, '0');
        return `${h}:${m}:${s}`;
    };

    const getPingColor = (p) => {
        if (p < 50) return 'text-green-500';
        if (p < 100) return 'text-yellow-500';
        return 'text-red-500';
    };

    return (
        <div className="fixed top-0 left-0 right-0 h-8 bg-terminal-bg border-b border-terminal-primary/30 z-[60] flex justify-between items-center px-4 font-mono text-[10px] uppercase tracking-wider select-none">
            {/* Left: Temporal */}
            <div className="flex gap-4">
                <span className="text-terminal-secondary">[ {time.toLocaleTimeString()} ]</span>
                <span className="text-gray-500">SESSION: <span className="text-terminal-primary">{formatDuration(sessionTime)}</span></span>
            </div>

            {/* Center: Security */}
            <div className="hidden md:flex gap-4 items-center animate-pulse opacity-80">
                <span className="text-terminal-primary">// SEC_STATUS: ENCRYPTED</span>
                <span className="text-terminal-primary">// FW: ACTIVE</span>
            </div>

            {/* Right: Network */}
            <div className="flex items-center gap-2">
                <span className="text-gray-500">PING: <span className={getPingColor(ping)}>{ping}ms</span></span>
                <div className={`w-1.5 h-1.5 rounded-full ${ping < 100 ? 'bg-terminal-primary animate-pulse' : 'bg-red-500'}`}></div>
            </div>
        </div>
    );
}
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/Components/TerminalStatusBar.jsx
git commit -m "feat: add TerminalStatusBar component"
```

### Task 2: Create TerminalNotifications Component

**Files:**
- Create: `resources/js/Components/TerminalNotifications.jsx`

- [ ] **Step 1: Implement TerminalNotifications component using Framer Motion**

```jsx
import React from 'react';
import { motion, AnimatePresence } from 'framer-motion';

export default function TerminalNotifications({ logs }) {
    return (
        <div className="fixed top-12 right-4 z-[70] flex flex-col gap-2 pointer-events-none max-w-xs w-full">
            <AnimatePresence mode="popLayout">
                {logs.map((log) => (
                    <motion.div
                        key={log.id}
                        initial={{ opacity: 0, x: 50, filter: 'blur(10px)' }}
                        animate={{ opacity: 1, x: 0, filter: 'blur(0px)' }}
                        exit={{ opacity: 0, scale: 0.95, filter: 'blur(5px)' }}
                        transition={{ type: 'spring', stiffness: 500, damping: 30 }}
                        className="bg-terminal-bg/90 border-r-2 border-terminal-primary border border-terminal-muted p-3 backdrop-blur-md shadow-lg shadow-black"
                    >
                        <div className="flex items-start gap-2">
                            <span className="text-terminal-primary font-bold">{'>'}</span>
                            <div className="flex flex-col">
                                <span className="text-[9px] text-gray-500 leading-none mb-1">[{log.timestamp}] {log.type}</span>
                                <span className="text-[11px] font-mono text-terminal-secondary leading-tight uppercase tracking-tighter">
                                    {log.message}
                                </span>
                            </div>
                        </div>
                    </motion.div>
                ))}
            </AnimatePresence>
        </div>
    );
}
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/Components/TerminalNotifications.jsx
git commit -m "feat: add TerminalNotifications component"
```

### Task 3: Integrate and Implement Logic in ArsipLayout

**Files:**
- Modify: `resources/js/Layouts/ArsipLayout.jsx`

- [ ] **Step 1: Integrate components and add logging logic**

```jsx
import React, { useState, useEffect } from 'react';
import { Link, router } from '@inertiajs/react';
import { Terminal, Cpu, Layers, Zap, MessageSquare } from 'lucide-react';
import AsciiHeader from '../Components/AsciiHeader';
import SystemLogs from '../Components/SystemLogs';
import TerminalStatusBar from '../Components/TerminalStatusBar';
import TerminalNotifications from '../Components/TerminalNotifications';

export default function ArsipLayout({ children }) {
    const [logs, setLogs] = useState([]);

    const addLog = (message, type = 'SYS') => {
        const id = Math.random().toString(36).substr(2, 9);
        const newLog = {
            id,
            message,
            type,
            timestamp: new Date().toLocaleTimeString()
        };
        setLogs(prev => [...prev, newLog]);
        setTimeout(() => {
            setLogs(prev => prev.filter(l => l.id !== id));
        }, 4000);
    };

    useEffect(() => {
        // Initial Logs
        setTimeout(() => addLog('KERNEL_LOADED_V4.0', 'SYS'), 500);
        setTimeout(() => addLog('SECURE_HANDSHAKE_COMPLETE', 'SEC'), 1200);

        // Inertia Navigation Logs
        const startListener = router.on('start', (event) => {
            addLog(`FETCHING_NODE: ${event.detail.visit.url.pathname}`, 'INFO');
        });

        const finishListener = router.on('finish', () => {
            addLog('DOM_UPDATED_READY', 'SYS');
        });

        return () => {
            startListener();
            finishListener();
        };
    }, []);

    const navItems = [
        { href: '/identitas', label: 'identitas', icon: Cpu },
        { href: '/misi', label: 'misi', icon: Layers },
        { href: '/arsenal', label: 'arsenal', icon: Zap },
        { href: '/jalur-komunikasi', label: 'komunikasi', icon: MessageSquare }
    ];

    return (
        <div className="min-h-screen bg-terminal-bg text-terminal-secondary pt-12 p-4 md:p-8 overflow-hidden relative font-mono selection:bg-terminal-primary selection:text-terminal-bg">
            <TerminalStatusBar />
            <TerminalNotifications logs={logs} />
            
            <div className="absolute inset-0 scanline pointer-events-none opacity-5 z-50"></div>
            
            <header className="max-w-7xl mx-auto border-b border-terminal pb-6 mb-8 flex flex-col md:flex-row justify-between items-end gap-4 relative z-10">
                <AsciiHeader />
                <div className="text-right">
                    <div className="flex items-center justify-end gap-2 text-terminal-primary font-mono text-[10px] uppercase mb-1">
                        <span className="inline-block w-2 h-2 bg-terminal-primary rounded-full animate-pulse"></span>
                        Terminal Session: Active
                    </div>
                    <div className="text-xs text-gray-500 uppercase tracking-widest">
                        Node: arif-renggy-v4.0.1
                    </div>
                </div>
            </header>

            <div className="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-4 gap-8 relative z-10">
                <aside className="space-y-6">
                    <nav className="space-y-1">
                        <div className="text-terminal-primary text-[10px] font-mono mb-2 uppercase tracking-widest opacity-50">Directory</div>
                        {navItems.map((item) => (
                            <Link 
                                key={item.href}
                                href={item.href} 
                                className={`flex items-center gap-3 p-2 border border-transparent hover:border-terminal hover:bg-terminal-primary/5 transition-all group ${window.location.pathname === item.href ? 'text-terminal-primary border-terminal bg-terminal-primary/10' : 'text-gray-400'}`}
                            >
                                <span className="text-terminal-primary opacity-50 group-hover:opacity-100">{'>'}</span>
                                <span className="font-mono text-xs tracking-wider">/home/{item.label}</span>
                            </Link>
                        ))}
                    </nav>
                    
                    <SystemLogs />
                </aside>
                
                <main className="md:col-span-3 border border-terminal p-6 relative bg-terminal-muted/10 backdrop-blur-sm min-h-[500px]">
                    {/* Decorative Terminal Corners */}
                    <div className="absolute top-0 left-0 w-2 h-2 border-t border-l border-terminal-primary"></div>
                    <div className="absolute top-0 right-0 w-2 h-2 border-t border-r border-terminal-primary"></div>
                    <div className="absolute bottom-0 left-0 w-2 h-2 border-b border-l border-terminal-primary"></div>
                    <div className="absolute bottom-0 right-0 w-2 h-2 border-b border-r border-terminal-primary"></div>
                    
                    {children}
                </main>
            </div>
        </div>
    );
}
```

- [ ] **Step 2: Commit**

```bash
git add resources/js/Layouts/ArsipLayout.jsx
git commit -m "feat: integrate TerminalStatusBar and notifications into layout"
```

### Task 4: Final Verification

- [ ] **Step 1: Verify Status Bar**
    - Check if clock updates every second.
    - Check if Ping value changes every 3 seconds.
    - Verify that session timer persists on page refresh.

- [ ] **Step 2: Verify Notifications**
    - Click on different navigation links.
    - Ensure `FETCHING_NODE` log appears for each navigation.
    - Ensure logs disappear after 4 seconds.
    - Verify visual alignment in the top-right corner.

- [ ] **Step 3: Commit**

```bash
git commit --allow-empty -m "chore: final verification of terminal monitoring system"
```
