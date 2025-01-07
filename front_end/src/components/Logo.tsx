import { Link } from 'react-router-dom';

interface LogoProps {
  onSectionChange?: (section: string) => void;
}

export function Logo({ onSectionChange }: LogoProps) {
  return (
    <div className="flex items-center">
      <Link to="/" onClick={() => onSectionChange?.('/')}>
        <img
          src="/2.png"
          alt="Logo"
          className="h-[60px] md:h-[85px] w-auto transition-transform duration-300 ease-in-out transform hover:scale-105"
        />
      </Link>
    </div>
  );
}
