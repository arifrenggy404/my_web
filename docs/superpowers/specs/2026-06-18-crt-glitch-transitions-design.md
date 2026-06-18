# CRT Glitch Transitions Design Specification

## Goal
Implement a retro CRT monitor glitch transition effect and loading overlay during Inertia.js route changes to enhance the cyberpunk aesthetic and smooth page navigations.

## Requirements
1. **Inertia.js Navigation Hooks**: Capture `start` and `finish` events to toggle transition states.
2. **Visual Overlay**: Full-screen modal overlay with a CRT screen flicker effect, scanlines, and a dynamic ASCII decryption loading bar.
3. **Optimized Timing**: The transition should last between 300ms to 400ms. A short setTimeout on finish will guarantee the visual effect is visible without causing excessive load delay.
4. **CSS Glitch Animations**: Add keyframe animations for scanline movement, screen flicker, and color distortion inside `app.css`.

## Architecture

### Styling Modifications
- Modify: `resources/css/app.css` to add `@keyframes flicker`, `@keyframes scanline`, and `.crt-transition-overlay` classes.

### Layout Integration
- Modify: `resources/js/Layouts/ArsipLayout.jsx` to manage the transition state, display the glitch overlay, and animate the loading bar.
