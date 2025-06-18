import { Head, Link } from '@inertiajs/react';
import { useEffect, useState, useRef } from 'react';

const SpaceBackground = () => {
  const containerRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const container = containerRef.current;
    if (!container) return;

    const stars = Array.from({ length: 200 }).map(() => {
      const star = document.createElement('div');

      const size = Math.random() * 3;
      const duration = 10 + Math.random() * 20;
      const delay = Math.random() * 5;
      const left = Math.random() * 100;
      const top = Math.random() * 100;
      const distance = 50 + Math.random() * 100;
      const angle = Math.random() * Math.PI * 2;

      star.className = 'star animate-glow-stars';
      star.style.setProperty('--star-size', `${size}px`);
      star.style.setProperty('--left', `${left}%`);
      star.style.setProperty('--top', `${top}%`);
      star.style.setProperty('--duration', `${duration}s`);
      star.style.setProperty('--delay', `${delay}s`);
      star.style.setProperty('--move-x', `${Math.cos(angle) * distance}px`);
      star.style.setProperty('--move-y', `${Math.sin(angle) * distance}px`);
      star.style.setProperty('--opacity', `${0.3 + Math.random() * 0.7}`);

      container.appendChild(star);
      return star;
    });

    return () => stars.forEach((star) => star.remove());
  }, []);

  return (
    <div ref={containerRef} className="fixed inset-0 z-0 overflow-hidden">
      <div className="absolute inset-0 bg-gradient-to-b from-black to-gray-900" />
    </div>
  );
};

export default function Home() {
  const fullText = 'Welcome to SolvingSphere';
  const [currentIndex, setCurrentIndex] = useState(0);
  const cursorRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    const handleMouseMove = (e: MouseEvent) => {
      if (cursorRef.current) {
        cursorRef.current.style.left = `${e.clientX}px`;
        cursorRef.current.style.top = `${e.clientY}px`;
      }
    };

    window.addEventListener('mousemove', handleMouseMove);
    return () => window.removeEventListener('mousemove', handleMouseMove);
  }, []);

  useEffect(() => {
    if (currentIndex < fullText.length) {
      const timeout = setTimeout(() => {
        setCurrentIndex(currentIndex + 1);
      }, 120);
      return () => clearTimeout(timeout);
    }
  }, [currentIndex]);

  const getColoredText = () => {
    const parts = [
      { text: 'Welcome to ', className: 'text-gray-300' },
      { text: 'Solving', className: 'text-white' },
      { text: 'Sphere', className: 'text-blue-500' },
    ];

    let typed = '';
    return parts.map((part, index) => {
      const sliceLen = Math.min(part.text.length, currentIndex - typed.length);
      typed += part.text.slice(0, sliceLen);
      return (
        <span key={index} className={part.className}>
          {part.text.slice(0, sliceLen)}
        </span>
      );
    });
  };

  const isDone = currentIndex === fullText.length;

  return (
    <>
      <Head title="Welcome" />

      <div ref={cursorRef} className="flashlight" />

      <div className="min-h-screen w-full flex items-center justify-center text-center relative overflow-hidden">
        <SpaceBackground />

        <div className="z-10 p-4">
          <h1 className="text-4xl md:text-5xl font-bold mb-4 relative">
            {getColoredText()}
            {!isDone && <span className="border-r-2 border-blue-500 animate-pulse ml-1"></span>}
          </h1>

          {isDone && (
            <>
              <p className="text-lg text-gray-200 mb-8 max-w-xl mx-auto animate-fadeIn">
                Innovate, Connect, and Grow — Join the platform that empowers problem solvers worldwide.
              </p>
              <div className="flex gap-4 justify-center animate-fadeIn delay-1000">
                <Link
                  href="/login"
                  className="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition"
                >
                  Login
                </Link>
                <Link
                  href="/register"
                  className="px-6 py-2 border border-blue-600 text-blue-600 rounded-md hover:bg-blue-100 transition"
                >
                  Register
                </Link>
              </div>
            </>
          )}
        </div>
      </div>
    </>
  );
}
