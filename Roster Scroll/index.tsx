import React, { useRef } from 'react';
import { createRoot } from 'react-dom/client';
import { motion, useScroll, useTransform, MotionValue } from 'framer-motion';

// Datos de las tarjetas con posiciones y velocidades variadas
const CARDS = [
  { id: 1, name: "CREATIVE DIRECTOR", img: "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=800", x: "12%", y: "20%", speed: 1.8, z: 20 },
  { id: 2, name: "UX STRATEGIST", img: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=800", x: "38%", y: "50%", speed: 0.6, z: 5 },
  { id: 3, name: "LEAD DEVELOPER", img: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=800", x: "62%", y: "15%", speed: 2.2, z: 20 },
  { id: 4, name: "BRAND DESIGNER", img: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=800", x: "15%", y: "70%", speed: 1.1, z: 5 },
  { id: 5, name: "MOTION ARTIST", img: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?q=80&w=800", x: "75%", y: "65%", speed: 1.4, z: 20 },
  { id: 6, name: "CONTENT HEAD", img: "https://images.unsplash.com/photo-1531123897727-8f129e1688ce?q=80&w=800", x: "82%", y: "25%", speed: 0.4, z: 5 }
];

const PersonCard = ({ card, progress }: { card: typeof CARDS[0], progress: MotionValue<number> }) => {
  // Las imágenes suben (Y negativo) mientras el usuario baja el scroll.
  // La velocidad multiplica el desplazamiento para dar profundidad.
  const yTranslate = useTransform(progress, [0, 1], [300 * card.speed, -600 * card.speed]);
  
  return (
    <motion.div
      className="absolute pointer-events-auto"
      style={{ 
        left: card.x,
        top: card.y,
        y: yTranslate, 
        zIndex: card.z,
        width: '18vw',
        minWidth: '220px'
      }}
    >
      <div className="relative group overflow-visible">
        <div className="overflow-hidden rounded-lg bg-zinc-900 shadow-[0_20px_50px_rgba(0,0,0,0.8)] border border-white/5 transition-transform duration-500 group-hover:scale-105">
          <img 
            src={card.img} 
            alt={card.name} 
            className="w-full h-auto aspect-[3/4] object-cover grayscale group-hover:grayscale-0 transition-all duration-700"
          />
        </div>
        <div className={`absolute -bottom-4 -left-4 px-4 py-2 shadow-2xl ${card.z > 10 ? 'bg-white text-black' : 'bg-zinc-800 text-white'} text-[9px] font-black tracking-widest uppercase`}>
          {card.name}
        </div>
      </div>
    </motion.div>
  );
};

const App = () => {
  const containerRef = useRef<HTMLDivElement>(null);
  
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start start", "end end"]
  });

  // Movimiento horizontal del texto: de derecha a izquierda
  const xMarquee = useTransform(scrollYProgress, [0, 1], ["50%", "-150%"]);

  return (
    <main className="bg-black">
      {/* Intro Section */}
      <section className="h-screen flex flex-col items-center justify-center relative overflow-hidden">
        <div className="absolute inset-0 bg-gradient-to-b from-zinc-900/50 to-black pointer-events-none"></div>
        <motion.div 
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          transition={{ duration: 1 }}
          className="z-10 text-center px-6"
        >
          <h1 className="text-7xl md:text-[14rem] font-black tracking-tighter leading-none uppercase mb-4">
            CORE<br />TEAM
          </h1>
          <p className="text-zinc-500 uppercase tracking-[0.4em] text-[10px] font-bold">Desliza para explorar el roster</p>
        </motion.div>
      </section>

      {/* Main Interactive Section */}
      <section ref={containerRef} className="relative h-[500vh]">
        <div className="sticky top-0 h-screen w-full flex items-center justify-center overflow-hidden">
          
          {/* MARQUEE TEXT (CENTERED HORIZONTALLY) */}
          <motion.div 
            style={{ x: xMarquee, zIndex: 10 }}
            className="absolute no-select flex items-center h-full"
          >
            <h2 className="text-[40vw] archivo-black marquee-text opacity-40">
              EXPERIENCE EXPERIENCE EXPERIENCE
            </h2>
          </motion.div>

          {/* CARDS LAYER */}
          <div className="relative w-full h-full max-w-[1920px] mx-auto pointer-events-none">
            {CARDS.map((card) => (
              <PersonCard key={card.id} card={card} progress={scrollYProgress} />
            ))}
          </div>

        </div>
      </section>

      {/* Post-Scroll Section */}
      <section className="min-h-screen bg-white text-black flex flex-col items-center justify-center p-12 text-center relative z-50 shadow-[0_-50px_100px_rgba(0,0,0,0.5)]">
        <div className="max-w-4xl">
          <h2 className="text-6xl md:text-9xl font-black tracking-tighter uppercase mb-8 leading-none">
            READY TO<br /><span className="text-zinc-300">COLLABORATE</span>
          </h2>
          <p className="text-xl md:text-2xl text-zinc-500 font-medium leading-relaxed max-w-2xl mx-auto mb-10">
            Este efecto se desactiva automáticamente al salir del contenedor de scroll infinito, permitiendo que tu web de WordPress continúe su flujo normal.
          </p>
          <div className="w-20 h-1 bg-black mx-auto"></div>
        </div>
      </section>

      <footer className="py-20 bg-zinc-950 text-zinc-800 text-center">
        <p className="text-[10px] font-black tracking-[0.6em] uppercase">© 2024 DIGITAL ARCHITECTURE</p>
      </footer>
    </main>
  );
};

const container = document.getElementById('root');
if (container) {
  const root = createRoot(container);
  root.render(<App />);
}