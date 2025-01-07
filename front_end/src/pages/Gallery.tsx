import React, { useState, useRef } from 'react';
import Slider from 'react-slick';
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import { Link } from 'react-router-dom'

interface ArtworkProps {
  title: string;
  artist: string;
  description?: string;
  image: string;
}

function ArtworkCard({ title, artist, description, image, isActive }: ArtworkProps & { isActive: boolean }) {
  return (
    <div className={`relative group overflow-hidden transition-transform duration-500 ${isActive ? '' : 'scale-90 blur-sm'} rounded-[50px]`}>
      <img
        src={image}
        alt={title}
        className={`w-full ${isActive ? 'h-[600px]' : 'h-[560px] mt-6'} object-cover rounded-[50px]`}
      />
      <div className="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent flex flex-col justify-end p-6 rounded-[50px]">
        <div className={`transition-transform duration-500 transform ${isActive ? 'translate-y-3 group-hover:translate-y-[-50px]' : 'translate-y-[-15px]'}`}>
          <h3 className="text-xl font-bold text-white transition-opacity duration-500">{title}</h3>
          <p className="text-sm text-gray-300 transition-opacity duration-500">by {artist}</p>
        </div>
        {isActive && (
          <div className="transition-transform duration-500 transform translate-y-10 group-hover:translate-y-[-10px] opacity-0 group-hover:opacity-100">
            {description && (
              <p className="text-sm text-gray-400 mt-[-1rem] opacity-0 group-hover:opacity-100 transition-opacity duration-500 mb-5">
                {description}
              </p>
            )}
            <Link to="/login" className="mt-6 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full hover:bg-white/20 transition-opacity duration-400 w-fit opacity-0 group-hover:opacity-100">
              Explore →
            </Link>
          </div>
        )}
      </div>
    </div>
  );
}

export function Gallery() {
  const artworks = [
    { image: "/map.jpg", title: "World Map", artist: "Earthling Pawn", description: "A detailed map of the world." },
    { image: "/a1.jpg", title: "Sunset", artist: "John Doe", description: "A beautiful sunset over the mountains." },
    { image: "/a2.jpg", title: "Forest", artist: "Jane Smith", description: "A serene forest with tall trees." },
    { image: "/a3.jpg", title: "Ocean", artist: "Alice Johnson", description: "Waves crashing on the shore." },
  ];

  const [activeIndex, setActiveIndex] = useState(0);
  const sliderRef = useRef<Slider>(null);

  const handleCardClick = (index: number) => {
    setActiveIndex(index);
    sliderRef.current?.slickGoTo(index);
  };

  const settings = {
    dots: true,
    infinite: true,
    speed: 800,
    slidesToShow: 3,
    slidesToScroll: 1,
    centerMode: true,
    centerPadding: '0',
    arrows: false,
    beforeChange: (current: number, next: number) => setActiveIndex(next),
    responsive: [
      {
        breakpoint: 1024,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 1,
          infinite: true,
          dots: true,
        },
      },
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1,
          initialSlide: 1,
        },
      },
    ],
  };

  return (
    <section className="bg-black py-20">
      <div className="container mx-auto px-6">
        <h2 className="text-4xl font-bold text-red-600 mb-2 text-center">GALLERY</h2>
        <p className="text-gray-200 mb-20 text-center">Explore our collection</p>

        <Slider ref={sliderRef} {...settings}>
          {artworks.slice(0, 4).map((artwork, index) => (
            <div key={index} onClick={() => handleCardClick(index)}>
              <ArtworkCard {...artwork} isActive={index === activeIndex} />
            </div>
          ))}
        </Slider>
      </div>
    </section>
  );
}