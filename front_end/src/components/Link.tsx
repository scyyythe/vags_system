import { Link as RouterLink } from 'react-router-dom';

interface LinkProps {
  href: string;
  children: React.ReactNode;
  isActive: boolean;
  onClick?: () => void;
}

export function Link({ href, children, isActive, onClick }: LinkProps) {
  return (
    <RouterLink
      to={href}
      className={`text-xl font-medium transition-colors relative ${
        isActive
          ? 'text-customRed font-extrabold border-b border-customRed'
          : 'text-white hover:text-customRed'
      }`}
      onClick={onClick}
    >
      {children}
      <span
        className={`absolute bottom-0 left-0 w-full h-0.5 ${
          isActive ? 'bg-customRed' : 'bg-transparent hover:bg-customRed'
        } transition-colors`}
      ></span>
    </RouterLink>
  );
}
