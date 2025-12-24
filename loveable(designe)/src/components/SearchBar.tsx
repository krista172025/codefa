import { Search } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

interface SearchBarProps {
  variant?: "hero" | "compact";
  onSearch?: (query: string) => void;
}

export const SearchBar = ({ variant = "hero", onSearch }: SearchBarProps) => {
  const isHero = variant === "hero";

  return (
    <div
      className={`glass-liquid-strong hover:glass-hover-trust transition-smooth ${
        isHero ? "p-2.5 max-w-4xl w-full" : "p-2 max-w-md w-full"
      }`}
      style={{ borderRadius: "2rem" }}
    >
      <div className="flex items-center gap-2">
        <div className="flex-1 relative">
          <Input
            type="text"
            placeholder="Куда вы собираетесь?"
            className={`border-0 bg-white/30 backdrop-blur-sm focus-visible:ring-0 focus-visible:ring-offset-0 focus-visible:bg-white/50 transition-all duration-300 placeholder:text-foreground/60 focus:shadow-md ${
              isHero ? "text-lg h-14 pl-5" : "h-11 pl-4"
            }`}
            style={{ borderRadius: "1.5rem" }}
          />
        </div>
        <Button
          size={isHero ? "lg" : "default"}
          className={`bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5 hover:scale-105 ${
            isHero ? "h-14 px-8" : "h-11 px-6"
          }`}
          style={{ borderRadius: "1.5rem" }}
        >
          <Search className={isHero ? "h-5 w-5 mr-2" : "h-4 w-4 mr-2"} />
          <span>Найти</span>
        </Button>
      </div>
    </div>
  );
};
