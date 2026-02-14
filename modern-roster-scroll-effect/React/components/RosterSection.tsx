import React, { useRef } from 'react';
import { motion, useScroll, useTransform } from 'framer-motion';
import PersonCard from './PersonCard';

const ROSTER = [
  { id: 1, name: "ALEX RIVERA", img: "https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=600&q=80", x: "8%", y: "15%", speed: 1.6, z: 20 },
  { id: 2, name: "SARA MONTERO", img: "https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=600&q=80", x: "32%", y: "45%", speed: 0.8, z: 5 },
  { id: 3, name: "MARC COSTA", img: "https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=600&q=80", x: "58%", y: "20%", speed: 1.4, z: 20 },
  { id: 4, name: "JULIA SOLER", img: "https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=600&q=80", x: "12%", y: "65%", speed: 1.1, z: 5 },
  { id: 5, name: "DANIEL VELA", img: "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=600&q=80", x: "78%", y: "60%", speed: 2.2, z: 20 },
  { id: 6, name: "ELENA PEÑA", img: "https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=600&q=80", x: "85%", y: "25%", speed: 0.5, z: 5 }
];

const RosterSection: React.FC = () => {
  const containerRef = useRef<HTMLDivElement>(null);
  
  // Captura el progreso del scroll dentro del contenedor de 500vh
  const { scrollYProgress } = useScroll({
    target: containerRef,
    offset: ["start start", "end end"]
  });

  // Movimiento horizontal del texto: De derecha a izquierda
  // Empezamos en un offset positivo para que entre en escena y terminamos muy a la izquierda
  const xMarquee = useTransform(scrollYProgress, [0, 1], ["80%", "-180%"]);

  return (
    <section ref={containerRef} className="relative h-[500vh] bg-black">
      {/* El div sticky es lo que vemos siempre mientras recorremos la altura del padre */}
      <div className="sticky top-0 h-screen w-full flex items-center justify-center overflow-hidden">
        
        {/* TEXTO MARQUESINA (Z-INDEX INTERMEDIO 10) */}
        <motion.div 
          style={{ x: xMarquee, zIndex: 10 }}
          className="absolute whitespace-nowrap pointer-events-none select-none w-full flex items-center"
        >
          <h2 className="text-[42vw] archivo-black leading-none outline-text tracking-tighter opacity-20">
            EL EQUIPO EL EQUIPO EL EQUIPO
          </h2>
        </motion.div>

        {/* CONTENEDOR DE CARTAS */}
        <div className="relative w-full h-full max-w-[1920px] mx-auto pointer-events-none">
          {ROSTER.map((person) => (
            <PersonCard 
              key={person.id} 
              person={person} 
              progress={scrollYProgress} 
            />
          ))}
        </div>

      </div>
    </section>
  );
};

export default RosterSection;