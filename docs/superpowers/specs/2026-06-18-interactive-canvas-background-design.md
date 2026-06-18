# Interactive Canvas Background Design Specification

## Goal
Implement a highly performant and visually stunning interactive canvas background rendering reactive particles that form a neural-network connection effect when close to the mouse cursor.

## Requirements
1. **Performance First**: Limit particle count to 60 to prevent CPU/GPU throttling.
2. **Color Palette**: Use neon cyan (`#00f0ff`) and neon pink (`#ff007f`) with a dark base background.
3. **Responsive**: Re-render/resize canvas dynamically on window size changes.
4. **Reactive**: Draw lines between particles that are close (within 100px) and connect them to the mouse cursor when within a specific hover radius (120px).
5. **No Event Blockers**: Place the canvas in a pointer-events-none layer or structure it in a way that doesn't intercept user clicks or scroll events on page content.

## Architecture

### Component File
- Create: `resources/js/Components/InteractiveBackground.jsx`

### Layout Integration
- Modify: `resources/js/Layouts/ArsipLayout.jsx` to render `<InteractiveBackground />` fixed to the background.

### Styling
- Add custom global styles if needed, but vanilla Canvas drawing will handle colors and lines directly.
