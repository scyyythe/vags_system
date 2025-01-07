import React from 'react';

interface ModalProps {
  message: string;
  onClose: () => void;
}

export const Modal: React.FC<ModalProps> = ({ message, onClose }) => {
  return (
    <div className="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
      <div className="relative bg-white p-6 rounded-lg w-80">
        {/* Close Button (X Icon) */}
        <span 
          className="absolute top-2 right-2 text-2xl cursor-pointer" 
          onClick={onClose}
        >
          &times;
        </span>
        <div className="text-center">
          <p>{message}</p>
        </div>
      </div>
    </div>
  );
};
