import React from 'react';
import RosterSection from './components/RosterSection';
import { motion } from 'framer-motion';

const App: React.FC = () => {
  return (
    <main className="bg-black w-full overflow-hidden">
      {/* Intro Section */}
      <section className="h-screen flex flex-col items-center justify-center relative">
        <div className="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-zinc-800/20 to-black pointer-events-none"></div>
        <motion.div 
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
          transition={{ duration: 1.2, ease: "easeOut" }}
          className="text-center z-10"
        >
          <span className="text-zinc-500 font-bold tracking-[0.5em] uppercase text-[10px] mb-8 block">Inspiración Webflow</span>
          <h1 className="text-8xl md:text-[14rem] font-black tracking-tighter leading-none mb-4 uppercase">
            THE<br />CORE
          </h1>
          <p className="text-zinc-500 uppercase tracking-[0.3em] text-xs font-bold mt-4">Haz scroll para activar el efecto</p>
        </motion.div>
        <div className="absolute bottom-10 w-px h-20 bg-gradient-to-b from-white to-transparent opacity-20"></div>
      </section>

      {/* La sección con el efecto horizontal/vertical */}
      <RosterSection />

      {/* Footer / Outro Section */}
      <section className="min-h-screen bg-white text-black flex items-center justify-center p-20 text-center relative z-50">
        <div className="max-w-4xl">
          <h2 className="text-6xl md:text-9xl font-black tracking-tighter uppercase mb-8 leading-none">
            EFECTO<br /><span className="text-zinc-200">FINALIZADO</span>
          </h2>
          <p className="text-xl md:text-2xl text-zinc-500 font-medium leading-relaxed max-w-2xl mx-auto">
            Este es el final de la sección sticky. Al salir de los 500vh de altura, el scroll recupera su comportamiento natural de WordPress.
          </p>
        </div>
      </section>

      <footer className="py-20 bg-black text-zinc-800 text-center">
        <p className="text-[10px] font-black tracking-[0.8em] uppercase">© 2024 MODERN SCROLL MODULE</p>
      </footer>
    </main>
  );
};

export default App;