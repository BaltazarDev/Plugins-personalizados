import React, { useRef } from 'react';
import { createRoot } from 'react-dom/client';
import { motion, useScroll, useTransform, MotionValue } from 'framer-motion';

// Datos del equipo con configuraciones de paralaje y capas
const TEAM_DATA = [
  { id: 1, name: "ALEX RIVERA", img: "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=800", x: "10%", y: "15%", speed: 1.8, z: 20 },
  { id: 2, name: "SARA MONTERO", img: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=800", x: "35%", y: "50%", speed: 0.9, z: 5 },
  { id: 3, name: "MARC COSTA", img: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=800", x: "62%", y: "10%", speed: 2.2, z: 20 },
  { id: 4, name: "JULIA SOLER", img: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=800", x: "12%", y: "70%", speed: 1.2, z: 5 },
  { id: 5, name: "DANIEL VELA", img: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=800", x: "78%", y: "65%", speed: 1.5, z: 20 },
  { id: 6, name: "ELENA PEÑA", img: "https://images.unsplash.com/photo-1531123897727-8f129e1688ce?w=800", x: "85%", y: "20%", speed: 0.7, z: 5 }
];

const PersonCard = ({ person, progress }: { person: typeof TEAM_DATA[0], progress: MotionValue<number> }) => {
  // Las imágenes suben (eje Y) a diferentes velocidades mientras el usuario baja el scroll
  const yParallax = useTransform(progress, [0, 1], [400 * person.speed, -800 * person.speed]);
  
  return (
    <motion.div
      className="absolute pointer-events-auto"
      style={{ 
        left: person.x,
        top: person.y,
        y: yParallax, 
        zIndex: person.z,
        width: '18vw',
        minWidth: '220px'
      }}
    >
      <div className="relative group">
        <div className="overflow-hidden rounded-sm bg-zinc-900 border border-white/5 shadow-2xl transition-all duration-500 group-hover:scale-105">
          <img 
            src={person.img} 
            alt={person.name} 
            className="w-full h-auto aspect-[3/4] object-cover grayscale group-hover:grayscale-0 transition-all duration-1000"
          />
        </div>
        <div className={`absolute -bottom-4 -left-4 px-5 py-2 ${person.z > 10 ? 'bg-white text-black' : 'bg-zinc-800 text-white'} text-[9px] font-black tracking-widest uppercase shadow-xl transition-transform duration-300 group-hover:-translate-y-2`}>
          {person.name}
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

  // Movimiento horizontal del texto gigante (de derecha a izquierda)
  const xMarquee = useTransform(scrollYProgress, [0, 1], ["70%", "-180%"]);

  return (
    <div className="bg-black">
      {/* Intro */}
      <section className="h-screen flex flex-col items-center justify-center text-center px-4">
        <motion.div 
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 1 }}
        >
          <h1 className="text-8xl md:text-[14rem] font-black tracking-tighter leading-none uppercase">CORE</h1>
          <p className="text-zinc-500 font-bold tracking-[0.6em] text-[10px] uppercase mt-4">Desliza para ver la magia</p>
        </motion.div>
      </section>

      {/* Sección con efecto horizontal y vertical */}
      <section ref={containerRef} className="relative h-[500vh]">
        <div className="sticky top-0 h-screen w-full flex items-center justify-center overflow-hidden">
          
          {/* Texto de Fondo (Capa Z: 10) */}
          <motion.div 
            style={{ x: xMarquee, zIndex: 10 }}
            className="absolute whitespace-nowrap pointer-events-none select-none"
          >
            <h2 className="text-[42vw] archivo-black stroke-marquee opacity-30">
              EXPERIENCE EXPERIENCE EXPERIENCE
            </h2>
          </motion.div>

          {/* Galería de Cartas (Capas Z: 5 y 20) */}
          <div className="relative w-full h-full max-w-[1920px] mx-auto pointer-events-none">
            {TEAM_DATA.map((person) => (
              <PersonCard key={person.id} person={person} progress={scrollYProgress} />
            ))}
          </div>
        </div>
      </section>

      {/* Sección de Salida */}
      <section className="min-h-screen bg-white text-black flex flex-col items-center justify-center text-center p-12 relative z-50">
        <h2 className="text-7xl md:text-9xl font-black uppercase tracking-tighter mb-4 leading-none">THE END</h2>
        <p className="text-xl text-zinc-400 font-medium max-w-xl">El scroll ahora vuelve a su comportamiento normal fuera de la sección interactiva.</p>
      </section>

      <footer className="py-16 bg-black text-zinc-900 text-center">
        <p className="text-[10px] font-black tracking-[1em] uppercase">© 2024 CORE DIGITAL STUDIO</p>
      </footer>
    </div>
  );
};

const root = createRoot(document.getElementById('root')!);
root.render(<App />);