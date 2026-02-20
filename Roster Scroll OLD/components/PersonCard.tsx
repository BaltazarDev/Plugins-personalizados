import React from 'react';
import { motion, useTransform, MotionValue } from 'framer-motion';

interface Person {
  id: number;
  name: string;
  img: string;
  x: string;
  y: string;
  speed: number;
  z: number;
}

interface PersonCardProps {
  person: Person;
  progress: MotionValue<number>;
}

const PersonCard: React.FC<PersonCardProps> = ({ person, progress }) => {
  // Las imágenes suben (negativo en Y) mientras bajamos el scroll
  // Cada una tiene un multiplicador 'speed' para que no todas se muevan igual
  const yParallax = useTransform(
    progress, 
    [0, 1], 
    [400 * person.speed, -600 * person.speed]
  );

  return (
    <motion.div
      className="absolute pointer-events-auto"
      style={{ 
        left: person.x,
        top: person.y,
        y: yParallax, 
        zIndex: person.z, 
        width: '18vw',
        minWidth: '200px'
      }}
    >
      <div className="relative group perspective-1000">
        <motion.div 
          className="overflow-hidden rounded-sm bg-zinc-900 shadow-2xl transition-all duration-700 ease-out group-hover:scale-105"
        >
          <img 
            src={person.img} 
            alt={person.name} 
            className="w-full h-auto aspect-[3/4] object-cover grayscale group-hover:grayscale-0 transition-all duration-1000"
          />
        </motion.div>
        
        {/* Etiqueta flotante */}
        <div 
          className={`absolute -bottom-4 -left-4 px-6 py-2 shadow-2xl transform transition-all duration-300 group-hover:-translate-y-2
            ${person.z > 10 ? 'bg-white text-black' : 'bg-zinc-800 text-white'}
            text-[10px] font-black tracking-[0.2em] uppercase`}
        >
          {person.name}
        </div>
      </div>
    </motion.div>
  );
};

export default PersonCard;