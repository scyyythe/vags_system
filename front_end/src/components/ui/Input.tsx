import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faEye, faEyeSlash } from '@fortawesome/free-solid-svg-icons';
import { useState } from 'react';

interface InputProps extends React.InputHTMLAttributes<HTMLInputElement> {
  label: string;
  error?: string;
}

export function Input({ label, id, type, error, ...props }: InputProps) {
  const [passwordVisible, setPasswordVisible] = useState(false);
  const [inputValue, setInputValue] = useState(''); 

  const isPassword = type === 'password';

  const togglePasswordVisibility = () => {
    setPasswordVisible((prev) => !prev);
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setInputValue(e.target.value); 
  };

  return (
    <div className="relative">
      <label htmlFor={id} className="block text-sm font-medium text-black mb-2">
        {label}
      </label>
      <input
        id={id}
        {...props}
        type={isPassword && passwordVisible ? 'text' : type}
        value={inputValue} 
        onChange={handleChange} 
        className={`w-[600px] px-4 py-2 bg-gray-300 rounded-full focus:ring-2 focus:ring-red-600 ${
          error ? 'border-2 border-red-500' : 'border border-gray-300'
        }`}
      />
      {isPassword && inputValue && ( 
        <button
          type="button"
          onClick={togglePasswordVisibility}
          aria-label="Toggle password visibility"
          className="absolute top-[50px] right-[-25px] transform -translate-y-1/2 text-gray-500 hover:text-black"
        >
          <FontAwesomeIcon icon={passwordVisible ? faEyeSlash : faEye} />
        </button>
      )}
      {error && (
        <p className="absolute text-red-500 text-xs mt-1">{error}</p>
      )}
    </div>
  );
}
