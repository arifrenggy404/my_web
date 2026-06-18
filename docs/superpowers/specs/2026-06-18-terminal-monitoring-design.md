# Specification: Terminal Monitoring System

**Date:** 2026-06-18
**Status:** Approved
**Topic:** Real-time Connection Monitor & System Logs

## 1. Overview
Implementing a high-fidelity "hacker terminal" monitoring interface for the portfolio. This includes a real-time status bar at the top of the viewport and a reactive notification system for system logs.

## 2. Architecture & Components

### 2.1 `TerminalStatusBar.jsx`
*   **Position:** Fixed at the top of the screen (`top-0`, `z-50`).
*   **Layout:** Three-zone flexbox layout.
    *   **Zone 1 (Left - Temporal):** 
        *   Digital Clock: `[ HH:mm:ss ]` (updates every second).
        *   Session Timer: `SESSION: HH:mm:ss` (persisted via `sessionStorage`).
    *   **Zone 2 (Center - Security):**
        *   Static/Pulse Text: `// SEC_STATUS: ENCRYPTED // FW: ACTIVE`.
        *   Visual Style: Neon Cyan text with a subtle opacity pulse animation.
    *   **Zone 3 (Right - Network):**
        *   Latency Display: `PING: [VALUE]ms`.
        *   Logic: Values fluctuate between 20ms and 60ms every 3 seconds to simulate network activity.
        *   Indicator: Small LED-style dot (Green < 50ms, Yellow 50-100ms, Red > 100ms).

### 2.2 `TerminalNotifications.jsx`
*   **Position:** Fixed top-right, below the status bar.
*   **Type:** Toast-style notifications stacked vertically.
*   **Animation:**
    *   Entrance: Slide-in from right with a 0.2s glitch/flicker effect (Framer Motion).
    *   Exit: Fade out and slide up.
*   **Data Flow:**
    *   Initial Load: `[SYS] KERNEL_LOADED`, `[SEC] HANDSHAKE_SUCCESS`.
    *   Navigation (Inertia events): `[INFO] FETCHING_NODE: [PATH]`, `[SYS] DOM_UPDATED`.
    *   Auto-dismiss: 4000ms.

## 3. Visual Design (Cyberpunk/Terminal)
*   **Typography:** Fira Code (Monospaced).
*   **Colors:**
    *   Background: `#050505` (Deep Black).
    *   Primary: `#00f0ff` (Neon Cyan).
    *   Secondary: `#ffffff` (White).
    *   Muted: `#1a1a1a` (Dark Grey).
*   **Aesthetics:** 1px borders, scanning line overlays, and micro-animations.

## 4. Technical Implementation Details
*   **State Management:** React `useState` and `useEffect` within `ArsipLayout`.
*   **Persistence:** `sessionStorage` for the `sessionStartTime` to ensure the timer doesn't reset on page refresh/navigation.
*   **Navigation Hooks:** Utilize Inertia.js `router.on('start')` and `router.on('finish')` to trigger terminal logs.

## 5. Success Criteria
*   [ ] Status bar is visible on all pages using `ArsipLayout`.
*   [ ] Latency values update dynamically without manual refresh.
*   [ ] Notifications trigger correctly upon page navigation.
*   [ ] Responsive design (Status bar collapses or hides non-critical info on mobile).
