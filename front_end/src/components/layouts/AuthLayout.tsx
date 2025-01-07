import { Logo } from '../Logo';

interface AuthLayoutProps {
  children: React.ReactNode;
  onSectionChange: (section: string) => void;
  statueOnRight?: boolean; 
}

export function AuthLayout({ children, onSectionChange, statueOnRight = true }: AuthLayoutProps) {
  return (
    <div className="min-h-screen grid grid-cols-1 md:grid-cols-2 overflow-hidden relative bg-white">
      {/* Logo */}
      <div className="absolute top-2 left-4 md:left-10 p-4">
        <Logo onSectionChange={onSectionChange} />
        <h1 className="ml-[100px] mt-[-45px] text-xl font-bold text-white">Worxist</h1>
      </div>

      {/* Conditional Placement */}
      {statueOnRight ? (
        <>
          {/* Left Side - Form with Inward Curved Border */}
          <div className="flex items-center justify-center bg-white p-8">
            {children}
          </div>

          {/* Right Side - Statue */}
          <div className="hidden md:flex items-center justify-center bg-black rounded-l-[50px]">
            <div className="relative mt-[55px]">
              <div className="absolute right-[-350px] top-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-customRed rounded-full z-0">
                <div className="absolute inset-0 rounded-full bg-gradient-radial from-customRed to-red-500 opacity-75"></div>
              </div>
            </div>

            <div className="absolute -translate-y-1/2 w-1/2 hidden lg:block">
              <div className="relative top-[130px]">
                <img
                  src="/1.png"
                  alt="Auth Illustration"
                  className="absolute left-[7px] -translate-y-1/2 w-[100%] h-300 z-10 mix-blend-luminosity"
                />
              </div>
            </div>

            <div className="absolute bottom-[865px] right-0 text-right text-white">
              <h1 className="mr-[100px] text-xl font-bold">
                <span className='text-customRed'>W</span>orxist
              </h1>
            </div>
          </div>
        </>
      ) : (
        <>
          {/* Left Side - Statue */}
          <div className="hidden md:flex items-center justify-center bg-black rounded-r-[50px] w-[1050px]">
            <div className="relative mt-[55px]">
              <div className="absolute right-[-350px] top-1/2 -translate-y-1/2 w-[700px] h-[700px] bg-customRed rounded-full z-0">
                <div className="absolute inset-0 rounded-full bg-gradient-radial from-customRed to-red-500 opacity-75"></div>
              </div>
            </div>

            <div className="absolute -translate-y-1/2 w-1/2 hidden lg:block">
              <div className="relative mt-[-380px]">
                <img
                  src="/1.png"
                  alt="Auth Illustration"
                  className="absolute left-[-20px] -translate-y-1/2 w-[100%] h-300 z-10 mix-blend-luminosity -translate-x-8"
                  style={{ transform: 'scaleX(-1)' }} 
                />
              </div>
            </div>
          </div>

          {/* Right Side - Form with Inward Curved Border */}
          <div className="flex items-center justify-center bg-white ml-8">
            {children}
          </div>
        </>
      )}
    </div>
  );
}
