import { useState } from 'react';
import { Menu, X } from 'lucide-react';
import { Link } from './Link';
import { Logo } from './Logo';
import { useLocation } from 'react-router-dom';

interface NavbarProps {
  currentSection: string;
  onSectionChange: (section: string) => void;
}

export function Navbar({ currentSection, onSectionChange }: NavbarProps) {
  const location = useLocation();
  const [isOpen, setIsOpen] = useState(false);

  const isSectionActive = (hash: string) => currentSection === hash || location.hash === hash;

  return (
    <nav className="relative left-[-240px] top-3 py-10 z-50">
      <div className="max-w-7xl mx-auto flex items-center gap-[160px]">
        <Logo onSectionChange={onSectionChange} />

        {/* Desktop Menu */}
        <div className="hidden md:flex items-center gap-[110px] mt-6">
          <Link href="#home" isActive={isSectionActive('#home')} onClick={() => onSectionChange('#home')}>HOME</Link>
          <Link href="#about" isActive={isSectionActive('#about')} onClick={() => onSectionChange('#about')}>ABOUT</Link>
          <Link href="#gallery" isActive={isSectionActive('#gallery')} onClick={() => onSectionChange('#gallery')}>GALLERY</Link>
          <Link href="/login" isActive={location.pathname === '/login'}>LOGIN</Link>
        </div>

        {/* Mobile Menu Button */}
        <button
          className="md:hidden p-1 rounded-full hover:bg-opacity-10 hover:bg-gray-500 transition-colors"
          onClick={() => setIsOpen(!isOpen)}
        >
          {isOpen ? <X size={24} /> : <Menu size={24} />}
        </button>
      </div>

      {/* Mobile Menu */}
      {isOpen && (
        <div className="md:hidden absolute top-full left-0 right-0 bg-black border-t border-gray-800">
          <div className="flex flex-col p-2 gap-5">
            <Link href="#home" isActive={isSectionActive('#home')} onClick={() => onSectionChange('#home')}>HOME</Link>
            <Link href="#about" isActive={isSectionActive('#about')} onClick={() => onSectionChange('#about')}>ABOUT</Link>
            <Link href="#gallery" isActive={isSectionActive('#gallery')} onClick={() => onSectionChange('#gallery')}>GALLERY</Link>
            <Link href="/login" isActive={location.pathname === '/login'}>LOGIN</Link>
          </div>
        </div>
      )}
    </nav>
  );
}
