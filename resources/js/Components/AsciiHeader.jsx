import React from 'react';
import { usePage } from '@inertiajs/react';

export default function AsciiHeader() {
    const { pengaturan } = usePage().props;
    const asciiArt = pengaturan?.ascii_header || `
  ___  ____  ___ _____   ____  ____ _   _  ____  ______   __
 / _ \\|  _ \\|_ _|  ___| |  _ \\| ___| \\ | |/ ___|/ ___\\ \\ / /
| |_| | |_) || || |_    | |_) |  _| |  \\| | |  _| |  _ \\ V / 
|  _  |  _ < | ||  _|   |  _ <| |___| |\\  | |_| | |_| | | |  
|_| |_|_| \\_\\___|_|     |_| \\_\\_____|_| \\_|\\____|\\____| |_|  
`;

    return (
        <pre 
            role="img" 
            aria-label="ASCII Art Header" 
            className="text-xs md:text-sm text-center leading-[1] text-terminal-primary font-mono select-none opacity-80 hover:opacity-100 transition-opacity mx-auto w-fit"
        >
            {asciiArt}
        </pre>
    );
}
