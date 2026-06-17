import React from 'react';
import ArsipLayout from '../Layouts/ArsipLayout';
import { Github, Linkedin, MessageSquare, Globe, Mail, ChevronRight } from 'lucide-react';

export default function Kontak() {
    const contacts = [
        { name: 'GitHub', icon: Github, url: 'https://github.com/arifrenggy00', label: '@arifrenggy00', color: 'hover:text-white' },
        { name: 'LinkedIn', icon: Linkedin, url: '#', label: 'Arif Renggy', color: 'hover:text-[#00f0ff]' },
        { name: 'WhatsApp', icon: MessageSquare, url: '#', label: 'MISI_LANGSUNG', color: 'hover:text-[#fee715]' },
        { name: 'Email', icon: Mail, url: 'mailto:arifrenggy404@gmail.com', label: 'arifrenggy404@gmail.com', color: 'hover:text-[#ff007f]' },
        { name: 'Website', icon: Globe, url: 'https://portofolio-cyberpunk-production.up.railway.app', label: 'PORT_PROD_V1', color: 'hover:text-[#00f0ff]' }
    ];

    return (
        <ArsipLayout>
            <div className="space-y-8">
                <div className="flex justify-between items-center border-b border-gray-800 pb-2">
                    <h2 className="text-[#ff007f] font-mono text-lg uppercase tracking-widest underline decoration-wavy text-neon-pink">Saluran Terenkripsi</h2>
                    <span className="text-[10px] text-gray-600 font-mono">SINYAL: KUAT</span>
                </div>

                <div className="grid grid-cols-1 gap-4">
                    {contacts.map(link => (
                        <a 
                            key={link.name}
                            href={link.url} 
                            target="_blank"
                            rel="noopener noreferrer"
                            className="flex items-center justify-between p-5 border border-gray-800 bg-[#1a1a1c]/20 hover:border-[#00f0ff] hover:bg-[#00f0ff]/5 group transition-all relative overflow-hidden"
                        >
                            {/* Efek Hover Background */}
                            <div className="absolute top-0 left-0 w-1 h-full bg-[#00f0ff] scale-y-0 group-hover:scale-y-100 transition-transform origin-top"></div>
                            
                            <div className="flex items-center gap-5 relative z-10">
                                <div className={`p-2 border border-gray-800 bg-black group-hover:border-[#00f0ff]/50 transition-colors ${link.color}`}>
                                    <link.icon size={20} />
                                </div>
                                <div className="space-y-0.5">
                                    <span className="font-mono text-xs text-gray-500 uppercase tracking-tighter group-hover:text-gray-300">HUBUNGKAN_MELALUI</span>
                                    <div className="font-mono text-sm font-black uppercase tracking-tight group-hover:text-[#00f0ff]">{link.name}</div>
                                </div>
                            </div>
                            
                            <div className="flex items-center gap-3 relative z-10">
                                <span className="text-xs text-gray-600 font-mono tracking-tighter group-hover:text-[#fee715]">{link.label}</span>
                                <ChevronRight size={14} className="text-gray-800 group-hover:text-[#00f0ff] transition-colors" />
                            </div>
                        </a>
                    ))}
                </div>
                
                <div className="mt-12 p-6 border border-gray-800 bg-black/40 font-mono relative">
                    <div className="text-[10px] text-[#ff007f] mb-4 uppercase tracking-widest text-neon-pink">// CATATAN_PENGEMBANG:</div>
                    <div className="text-xs text-gray-400 leading-relaxed space-y-2">
                        <p>Pesan yang dikirim melalui saluran ini akan diproses melalui pipeline enkripsi asimetris.</p>
                        <p>Waktu respons rata-rata: <span className="text-[#00f0ff]">12-24 Jam Standar Sektor</span>.</p>
                    </div>
                </div>
            </div>
        </ArsipLayout>
    );
}
