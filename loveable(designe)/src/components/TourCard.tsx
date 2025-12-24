import { Heart, Star, MapPin } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { useState, useRef } from "react";
import { NavLink } from "@/components/NavLink";
import { BookingModal } from "@/components/BookingModal";

interface TourCardProps {
  id: string;
  title: string;
  location: string;
  price: number;
  rating: number;
  reviews: number;
  image: string;
  category?: string;
}

export const TourCard = ({
  id,
  title,
  location,
  price,
  rating,
  reviews,
  image,
  category,
}: TourCardProps) => {
  const [isFavorite, setIsFavorite] = useState(false);
  const [isBookingOpen, setIsBookingOpen] = useState(false);
  const [isHovered, setIsHovered] = useState(false);
  const [glowPosition, setGlowPosition] = useState({ x: 0, y: 0 });
  const cardRef = useRef<HTMLDivElement>(null);

  const handleMouseMove = (e: React.MouseEvent<HTMLDivElement>) => {
    if (cardRef.current) {
      const rect = cardRef.current.getBoundingClientRect();
      setGlowPosition({
        x: e.clientX - rect.left,
        y: e.clientY - rect.top,
      });
    }
  };

  return (
    <div 
      ref={cardRef}
      className={`group relative glass-liquid-strong overflow-hidden transition-all duration-500 ${
        isHovered 
          ? 'shadow-[0_0_30px_rgba(255,255,255,0.15),inset_0_0_0_1px_rgba(255,255,255,0.3)]' 
          : ''
      }`} 
      style={{ borderRadius: "1.5rem" }}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
      onMouseMove={handleMouseMove}
    >
      {/* Cursor glow effect */}
      {isHovered && (
        <div
          className="pointer-events-none absolute z-10 transition-opacity duration-300"
          style={{
            left: glowPosition.x - 40,
            top: glowPosition.y - 40,
            width: 80,
            height: 80,
            background: 'radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%)',
            borderRadius: '50%',
          }}
        />
      )}
      
      <NavLink to={`/tour/${id}`} className="block">
        <div className="relative h-64 overflow-hidden">
          <img
            src={image}
            alt={title}
            className="w-full h-full object-cover transition-all duration-700"
          />
          {category && (
            <Badge className="absolute top-4 left-4 glass-strong border-0">
              {category}
            </Badge>
          )}
          <Button
            size="icon"
            variant="ghost"
            className={`absolute top-4 right-4 rounded-full glass-strong transition-all duration-300 ${
              isHovered ? 'shadow-[0_0_15px_rgba(255,255,255,0.2)]' : ''
            }`}
            onClick={(e) => {
              e.preventDefault();
              setIsFavorite(!isFavorite);
            }}
          >
            <Heart
              className={`h-5 w-5 transition-all duration-300 ${
                isFavorite ? "fill-primary text-primary scale-110" : "text-foreground"
              }`}
            />
          </Button>
        </div>
      </NavLink>

      <div className="p-6">
        <NavLink to={`/tour/${id}`} className="block group/link">
          <div className="flex items-start justify-between mb-3">
            <div className="flex-1">
              <h3 className="text-lg font-semibold mb-2 text-foreground group-hover/link:text-primary transition-all duration-300 line-clamp-2">
                {title}
              </h3>
              <div className={`flex items-center gap-1 text-muted-foreground text-sm transition-all duration-300 ${
                isHovered ? '[&>svg]:drop-shadow-[0_0_4px_rgba(255,255,255,0.4)]' : ''
              }`}>
                <MapPin className="h-4 w-4 transition-all duration-300" />
                <span>{location}</span>
              </div>
            </div>
          </div>

          <div className="flex items-center justify-between mb-4">
            <div className={`flex items-center gap-1 transition-all duration-300 ${
              isHovered ? '[&>svg]:drop-shadow-[0_0_4px_rgba(255,255,255,0.4)]' : ''
            }`}>
              <Star className="h-4 w-4 fill-primary text-primary transition-all duration-300" />
              <span className="font-medium text-foreground">{rating}</span>
              <span className="text-muted-foreground text-sm">({reviews})</span>
            </div>
            <div className="text-right">
              <div className="text-sm text-muted-foreground">От</div>
              <div className="text-xl font-bold text-foreground">
                {price.toLocaleString()}₽
              </div>
            </div>
          </div>
        </NavLink>

        <Button
          className="w-full bg-primary hover:bg-primary/90 text-primary-foreground transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5"
          onClick={(e) => {
            e.preventDefault();
            setIsBookingOpen(true);
          }}
        >
          Забронировать
        </Button>
      </div>

      <BookingModal
        open={isBookingOpen}
        onOpenChange={setIsBookingOpen}
        tourTitle={title}
        tourPrice={price}
      />
    </div>
  );
};