import { useState } from "react";
import type { MouseEvent as ReactMouseEvent } from "react";
import type { LucideIcon } from "lucide-react";
import { Car, Clock3, Heart, MapPin, Star, Users } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";

export interface ProductCardProps {
  title: string;
  city: string;
  image: string;

  price: number;
  oldPrice?: number;

  groupLabel?: string;
  durationLabel?: string;
  transportLabel?: string;

  rating?: number;
  reviews?: number;

  guideImage?: string;
  guideName?: string;
}

type BadgeItem = {
  key: "group" | "duration" | "transport";
  label: string;
  Icon: LucideIcon;
};

export function ProductCard({
  title,
  city,
  image,
  price,
  oldPrice,
  groupLabel = "Групповая",
  durationLabel = "2:00",
  transportLabel = "Авто",
  rating = 5,
  reviews = 0,
  guideImage,
  guideName = "Гид",
}: ProductCardProps) {
  const [isHovered, setIsHovered] = useState(false);
  const [isFavorite, setIsFavorite] = useState(false);

  const setFollowGlow = (e: ReactMouseEvent<HTMLElement>) => {
    const el = e.currentTarget as HTMLElement;
    const rect = el.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    el.style.setProperty("--mx", `${x}px`);
    el.style.setProperty("--my", `${y}px`);
  };

  const badges: BadgeItem[] = [
    { key: "group", label: groupLabel, Icon: Users },
    { key: "duration", label: durationLabel, Icon: Clock3 },
    { key: "transport", label: transportLabel, Icon: Car },
  ];

  const formatEur = (v: number) =>
    `${v.toLocaleString("ru-RU", {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2,
    })} €`;

  return (
    <article
      className={`relative overflow-hidden bg-card mst-product-outline-hover ${
        isHovered ? "mst-product-outline-hover--active" : ""
      }`}
      style={{ borderRadius: "2rem" }}
      onMouseEnter={() => setIsHovered(true)}
      onMouseLeave={() => setIsHovered(false)}
    >
      <div className="p-4">
        <div
          className="relative overflow-hidden"
          style={{ borderRadius: "1.5rem" }}
        >
          <img
            src={image}
            alt={`Экскурсия: ${title}`}
            loading="lazy"
            className="h-full w-full object-cover"
          />

          {/* Top badges - hover glow that follows cursor (on the badge itself) */}
          <div className="absolute left-3 top-3 z-10 flex flex-wrap gap-2">
            {badges.map(({ key, label, Icon }) => (
              <div
                key={key}
                className="mst-glass-pill mst-follow-glow"
                role="note"
                aria-label={label}
                onMouseMove={setFollowGlow}
              >
                <Icon className="h-3.5 w-3.5" />
                <span className="leading-none">{label}</span>
              </div>
            ))}
          </div>

          {/* Favorite icon - hover glow that follows cursor */}
          <button
            type="button"
            className="absolute right-3 top-3 z-10 mst-glass-icon mst-follow-glow"
            aria-label={
              isFavorite ? "Убрать из избранного" : "Добавить в избранное"
            }
            onMouseMove={setFollowGlow}
            onClick={() => setIsFavorite((v) => !v)}
          >
            <Heart
              className={`h-4 w-4 transition-colors ${
                isFavorite ? "fill-primary text-primary" : "text-foreground"
              }`}
            />
          </button>
        </div>

        <div className="pt-4">
          <h3 className="text-base font-extrabold uppercase tracking-tight text-foreground leading-snug">
            {title}
          </h3>

          <div className="mt-3 grid gap-2">
            <div className="flex items-center gap-2 text-sm text-muted-foreground">
              <MapPin className="h-4 w-4 text-secondary" />
              <span>{city}</span>
            </div>

            <div className="flex items-center gap-2 text-sm">
              <Star className="h-4 w-4 fill-secondary text-secondary" />
              <span className="font-semibold text-foreground">{rating}</span>
              <span className="text-muted-foreground">({reviews})</span>
            </div>

            <div className="flex items-center justify-end gap-3">
              {typeof oldPrice === "number" && (
                <span className="text-sm text-muted-foreground line-through">
                  {formatEur(oldPrice)}
                </span>
              )}
              <span className="text-lg font-extrabold text-foreground">
                {formatEur(price)}
              </span>
            </div>
          </div>

          <div className="mt-4 flex items-center gap-3">
            <Button
              type="button"
              className="h-11 flex-1 rounded-full mst-btn-glow mst-follow-glow"
              onMouseMove={setFollowGlow}
            >
              Забронировать
            </Button>

            <div className="shrink-0">
              <Avatar className="h-11 w-11 ring-2 ring-primary/70">
                <AvatarImage src={guideImage} alt={`Гид: ${guideName}`} />
                <AvatarFallback>{guideName.slice(0, 1)}</AvatarFallback>
              </Avatar>
            </div>
          </div>
        </div>
      </div>
    </article>
  );
}

