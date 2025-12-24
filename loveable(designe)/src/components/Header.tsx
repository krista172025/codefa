import { Heart, User, Menu, Search } from "lucide-react";
import { Button } from "@/components/ui/button";
import { NavLink } from "@/components/NavLink";
import { useState } from "react";
import logo from "@/assets/logo.png";

export const Header = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);

  return (
    <header className="fixed top-0 left-0 right-0 z-50 glass-liquid-strong border-b border-white/20">
      <div className="container mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
        {/* Logo */}
        <NavLink to="/" className="flex items-center space-x-3 group">
          <div className="relative">
            <div className="w-12 h-12 rounded-2xl bg-white flex items-center justify-center transition-bounce group-hover:scale-110 group-hover:rotate-3 p-1.5">
              <img src={logo} alt="My Super Tour" className="w-full h-full object-contain" />
            </div>
          </div>
          <div className="hidden sm:block">
            <div className="text-xl font-bold text-foreground leading-tight">
              My Super Tour
            </div>
            <div className="text-xs text-muted-foreground">Честно о путешествиях</div>
          </div>
        </NavLink>

        {/* Desktop Navigation */}
        <nav className="hidden md:flex items-center space-x-1">
          <NavLink
            to="/tours"
            className="px-4 py-2 rounded-lg text-sm font-medium text-foreground transition-all duration-300 hover:bg-accent hover:-translate-y-0.5 hover:shadow-sm"
            activeClassName="bg-accent text-accent-foreground"
          >
            Экскурсии
          </NavLink>
          <NavLink
            to="/transfers"
            className="px-4 py-2 rounded-lg text-sm font-medium text-foreground transition-all duration-300 hover:bg-accent hover:-translate-y-0.5 hover:shadow-sm"
            activeClassName="bg-accent text-accent-foreground"
          >
            Трансфер
          </NavLink>
          <NavLink
            to="/tickets"
            className="px-4 py-2 rounded-lg text-sm font-medium text-foreground transition-all duration-300 hover:bg-accent hover:-translate-y-0.5 hover:shadow-sm"
            activeClassName="bg-accent text-accent-foreground"
          >
            Билеты
          </NavLink>
          <NavLink
            to="/accommodation"
            className="px-4 py-2 rounded-lg text-sm font-medium text-foreground transition-all duration-300 hover:bg-accent hover:-translate-y-0.5 hover:shadow-sm"
            activeClassName="bg-accent text-accent-foreground"
          >
            Жилье
          </NavLink>
        </nav>

        {/* Right Actions */}
        <div className="flex items-center space-x-2">
          <Button
            variant="ghost"
            size="icon"
            className="rounded-full hover:bg-accent transition-all duration-300 hover:scale-110 hover:shadow-md"
            asChild
          >
            <NavLink to="/wishlist">
              <Heart className="h-5 w-5" />
            </NavLink>
          </Button>
          <Button
            variant="ghost"
            size="icon"
            className="rounded-full hover:bg-accent transition-all duration-300 hover:scale-110 hover:shadow-md"
            asChild
          >
            <NavLink to="/auth">
              <User className="h-5 w-5" />
            </NavLink>
          </Button>
          <Button
            variant="ghost"
            size="icon"
            className="rounded-full hover:bg-accent transition-smooth md:hidden"
            onClick={() => setIsMenuOpen(!isMenuOpen)}
          >
            <Menu className="h-5 w-5" />
          </Button>
        </div>
      </div>

      {/* Mobile Menu */}
      {isMenuOpen && (
        <div className="md:hidden glass-liquid-strong border-t border-white/20 animate-slide-up">
          <nav className="container mx-auto px-4 py-4 space-y-2">
            <NavLink
              to="/tours"
              className="block px-4 py-3 rounded-lg text-sm font-medium text-foreground hover:bg-accent transition-smooth"
              activeClassName="bg-accent text-accent-foreground"
              onClick={() => setIsMenuOpen(false)}
            >
              Экскурсии
            </NavLink>
            <NavLink
              to="/transfers"
              className="block px-4 py-3 rounded-lg text-sm font-medium text-foreground hover:bg-accent transition-smooth"
              activeClassName="bg-accent text-accent-foreground"
              onClick={() => setIsMenuOpen(false)}
            >
              Трансфер
            </NavLink>
            <NavLink
              to="/tickets"
              className="block px-4 py-3 rounded-lg text-sm font-medium text-foreground hover:bg-accent transition-smooth"
              activeClassName="bg-accent text-accent-foreground"
              onClick={() => setIsMenuOpen(false)}
            >
              Билеты
            </NavLink>
            <NavLink
              to="/accommodation"
              className="block px-4 py-3 rounded-lg text-sm font-medium text-foreground hover:bg-accent transition-smooth"
              activeClassName="bg-accent text-accent-foreground"
              onClick={() => setIsMenuOpen(false)}
            >
              Жилье
            </NavLink>
          </nav>
        </div>
      )}
    </header>
  );
};
