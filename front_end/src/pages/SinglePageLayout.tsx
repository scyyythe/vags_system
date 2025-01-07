import { Navbar } from '../components/Navbar';
import { Hero } from '../components/Hero';
import { About } from './About';
import { Gallery } from './Gallery';
import { useState, useEffect, useRef } from 'react';
import { useLocation } from 'react-router-dom';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faFacebook, faGithub, faInstagram, faTwitter } from '@fortawesome/free-brands-svg-icons';

export function SinglePageLayout() {
  const location = useLocation();
  const [currentSection, setCurrentSection] = useState(location.hash || '#home');

  const homeRef = useRef<HTMLDivElement>(null);
  const aboutRef = useRef<HTMLDivElement>(null);
  const galleryRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    setCurrentSection(location.hash || '#home');
  }, [location.hash]);

  const handleSectionChange = (section: string) => {
    setCurrentSection(section);
    if (section === '#home') homeRef.current?.scrollIntoView({ behavior: 'smooth' });
    if (section === '#about') aboutRef.current?.scrollIntoView({ behavior: 'smooth' });
    if (section === '#gallery') galleryRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  return (
    <main className="bg-black">
      <Navbar currentSection={currentSection} onSectionChange={handleSectionChange} />
      <section id="home" ref={homeRef} className="min-h-screen">
        <Hero />
      </section>
      <section id="about" ref={aboutRef} className="min-h-screen py-20">
        <About />
      </section>
      <section id="gallery" ref={galleryRef} className="min-h-screen py-15">
        <Gallery />
      </section>

      <footer className="bg-black py-10 text-center text-gray-200">
        <div className="flex justify-center space-x-10 mb-6">
          <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" className="mx-4">
            <FontAwesomeIcon icon={faFacebook} className="text-3xl hover:text-red-700 " />
          </a>
          <a href="https://github.com" target="_blank" rel="noopener noreferrer">
            <FontAwesomeIcon icon={faGithub} className="text-3xl hover:text-red-700 " />
          </a>
          <a href="https://instagram.com" target="_blank" rel="noopener noreferrer">
            <FontAwesomeIcon icon={faInstagram} className="text-3xl hover:text-red-700 " />
          </a>
          <a href="https://twitter.com" target="_blank" rel="noopener noreferrer">
            <FontAwesomeIcon icon={faTwitter} className="text-3xl hover:text-red-700 " />
          </a>
        </div>
        <p>Copyright &copy; 2024. Worxist. All Rights Reserved.</p>
      </footer>
    </main>
  );
}
