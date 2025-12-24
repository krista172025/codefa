import { LucideIcon } from "lucide-react";
import { NavLink } from "@/components/NavLink";

interface CategoryCardProps {
  title: string;
  description: string;
  icon: LucideIcon;
  href: string;
  image: string;
}

export const CategoryCard = ({
  title,
  description,
  icon: Icon,
  href,
  image,
}: CategoryCardProps) => {
  return (
    <NavLink
      to={href}
      className="group block glass-liquid-strong overflow-hidden hover-lift-gentle hover:glass-hover-trust transition-smooth"
      style={{ borderRadius: "1.5rem" }}
    >
      <div className="relative h-48 overflow-hidden">
        <img
          src={image}
          alt={title}
          className="w-full h-full object-cover transition-smooth group-hover:scale-110"
        />
        <div className="absolute inset-0 bg-gradient-to-t from-foreground/60 to-transparent" />
        <div className="absolute bottom-4 left-4 right-4">
          <div className="flex items-center gap-2 mb-2">
            <div className="w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center shadow-md">
              <Icon className="h-6 w-6 text-secondary-foreground" />
            </div>
          </div>
        </div>
      </div>
      <div className="p-6">
        <h3 className="text-xl font-semibold mb-2 text-foreground group-hover:text-primary transition-smooth">
          {title}
        </h3>
        <p className="text-muted-foreground text-sm">{description}</p>
      </div>
    </NavLink>
  );
};
