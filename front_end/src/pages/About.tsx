import { Mail } from 'lucide-react'

export function About() {
  return (
    <section className="py-20 bg-black">
      <div className="container mx-auto px-5">
        <div className="grid grid-cols-1 lg:grid-cols-2 gap-20 items-center">
          <div className="relative flex justify-center">
            <img
              src="/about.png"
              alt="Gallery Space"
              className="rounded-2xl shadow-lg w-[90%] h-300"
            />
          </div>
          
          <div>
            <h3 className="text-sm md:text-lg font-bold text-gray-200 mb-8 pb-4 border-b-2 border-white" style={{ letterSpacing: '0.1em' }}>
            ABOUT US.
          </h3>
            <h2 className="text-4xl md:text-5xl font-bold text-white mb-6 pt-6">
              Your <span className="text-red-600">art.</span><br />
              Our <span className="text-red-600">platform.</span>
            </h2>
            <p className="text-gray-200 text-lg md:text-xl mb-9 leading-relaxed text-justify">
              The Worxist serves as a dynamic stage for aspiring artists and art enthusiasts alike. 
              This platform celebrates creativity by connecting emerging talents with those who appreciate 
              the beauty of art. Dive into a world of imagination and expression, where every piece tells 
              a story and every visit inspires new perspectives.
            </p>
            <button className="flex items-center gap-2 bg-transparent border border-red-600 text-white hover:border-white hover:text-red-600 px-6 py-3 rounded-full transition-colors">
              <Mail size={20} />
              Contact Us
            </button>
          </div>
        </div>
      </div>
    </section>
  )
}
