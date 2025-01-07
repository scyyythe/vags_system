interface ButtonProps extends React.ButtonHTMLAttributes<HTMLButtonElement> {
  variant?: 'primary' | 'secondary' | 'google' | 'facebook';
  fullWidth?: boolean;
}

export function Button({ 
  children, 
  variant = 'primary', 
  fullWidth = false,
  className = '',
  ...props 
}: ButtonProps) {
  const baseStyles = 'py-3 px-6 rounded-full transition flex items-center justify-center gap-2 font-medium'
  const variantStyles = {
    primary: 'bg-red-600 text-white hover:bg-red-700',
    secondary: 'bg-gray-700 text-white hover:bg-gray-600',
    google: 'bg-red-600 text-gray-900 hover:bg-gray-100',
    facebook: 'bg-gray-500 text-white hover:bg-gray-600'
  }
  const widthStyles = fullWidth ? 'w-full' : ''

  return (
    <button 
      className={`${baseStyles} ${variantStyles[variant]} ${widthStyles} ${className}`}
      {...props}
    >
      {children}
    </button>
  )
}