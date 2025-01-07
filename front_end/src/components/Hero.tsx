export function Hero() {
  return (
    <div className="relative min-h-screen flex items-center bg-black">

      <div className="container mx-auto px-2 relative z-10 mt-[-280px]">
        <div className="max-w-3xl">
          <h1 className="text-2xl md:text-3xl font-normal text-white mb-6">
            YOUR VERSION OF
            <span className="block mt-2 text-6xl md:text-8xl font-bold" style={{ letterSpacing: '0.05em' }}>DESIGN</span>
          </h1>
          <p className="text-xl text-gray-200 mb-8" style={{ letterSpacing: '1.2px' }}>
            Explore, appreciate, and be inspired by a diverse range of  <br /> styles and mediums. 
            Step inside and let the art speak to you.
          </p>
          <div className="relative">
            <button className="bg-customRed text-white px-8 py-2.5 rounded-full hover:bg-red-700 transition-colors text-sm md:text-lg">
              Explore More
            </button>
          </div>
        </div>
      </div>

      <div className="relative mt-[-290px]">
        <div className="lg:absolute right-[140px] top-1/2 -translate-y-1/2 w-[50vw] max-w-[750px] h-[50vw] max-h-[750px] bg-red-700 rounded-full z-0">
          <div
            className="absolute inset-0 rounded-full"
            style={{
              background: "radial-gradient(circle, rgba(255,0,0,0.7) 40%, rgba(255,0,0,0.4) 70%, rgba(255,0,0,0) 100%)",
              opacity: 1,
            }}
          ></div>
        </div>
      </div>

      <div className="absolute left-[47%] top-1/2 -translate-y-1/2 w-[50vw] hidden lg:block">
        <div className="relative mt-[-105px]">
          <img
            src="/1.png" 
            alt="Classical Statue"
            className="absolute left-[12%] top-1/2 -translate-y-1/2 w-[95%] h-300 z-10 mix-blend-luminosity -translate-x-8" />
        </div>
      </div>

      <div className="absolute bottom-7 left-[40%] w-60 h-60 bg-customRed rounded-full" />

    </div>
  )
}