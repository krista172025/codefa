import { Facebook, Instagram, Twitter, Mail, Phone, MapPin } from "lucide-react";
import { NavLink } from "@/components/NavLink";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";

export const Footer = () => {
  return (
    <footer className="bg-pattern-organic border-t border-white/20 mt-20 glass-liquid">
      <div className="container mx-auto px-4 py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
          {/* Brand */}
          <div className="space-y-4">
            <div className="flex items-center space-x-2">
              <div className="w-10 h-10 rounded-2xl bg-primary flex items-center justify-center shadow-glass">
                <span className="text-white text-xl font-bold">M</span>
              </div>
              <span className="text-xl font-bold text-foreground">My Super Tour</span>
            </div>
            <p className="text-muted-foreground text-sm">
              Откройте мир с нами. Необычные экскурсии от местных жителей в 920 городах мира.
            </p>
            <div className="flex gap-3">
              <Button
                size="icon"
                variant="ghost"
                className="rounded-full hover:bg-primary/10 hover:text-primary transition-all duration-300 hover:scale-110 hover:shadow-md"
              >
                <Facebook className="h-5 w-5" />
              </Button>
              <Button
                size="icon"
                variant="ghost"
                className="rounded-full hover:bg-primary/10 hover:text-primary transition-all duration-300 hover:scale-110 hover:shadow-md"
              >
                <Instagram className="h-5 w-5" />
              </Button>
              <Button
                size="icon"
                variant="ghost"
                className="rounded-full hover:bg-primary/10 hover:text-primary transition-all duration-300 hover:scale-110 hover:shadow-md"
              >
                <Twitter className="h-5 w-5" />
              </Button>
            </div>
          </div>

          {/* Quick Links */}
          <div>
            <h3 className="font-semibold text-foreground mb-4">Категории</h3>
            <ul className="space-y-3">
              <li>
                <NavLink
                  to="/tours"
                  className="text-muted-foreground hover:text-primary transition-all duration-300 text-sm inline-block hover:translate-x-1"
                >
                  Экскурсии
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/transfers"
                  className="text-muted-foreground hover:text-primary transition-all duration-300 text-sm inline-block hover:translate-x-1"
                >
                  Трансферы
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/tickets"
                  className="text-muted-foreground hover:text-primary transition-all duration-300 text-sm inline-block hover:translate-x-1"
                >
                  Билеты
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/accommodation"
                  className="text-muted-foreground hover:text-primary transition-all duration-300 text-sm inline-block hover:translate-x-1"
                >
                  Жилье
                </NavLink>
              </li>
            </ul>
          </div>

          <div>
            <h3 className="font-semibold text-foreground mb-4">Компания</h3>
            <ul className="space-y-3">
              <li>
                <NavLink
                  to="/about"
                  className="text-muted-foreground hover:text-primary transition-all duration-300 text-sm inline-block hover:translate-x-1"
                >
                  О нас
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/guides"
                  className="text-muted-foreground hover:text-primary transition-all duration-300 text-sm inline-block hover:translate-x-1"
                >
                  Стать гидом
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/reviews"
                  className="text-muted-foreground hover:text-primary transition-all duration-300 text-sm inline-block hover:translate-x-1"
                >
                  Отзывы
                </NavLink>
              </li>
              <li>
                <NavLink
                  to="/shop"
                  className="text-muted-foreground hover:text-primary transition-all duration-300 text-sm inline-block hover:translate-x-1"
                >
                  Магазин
                </NavLink>
              </li>
            </ul>
          </div>

          {/* Newsletter */}
          <div>
            <h3 className="font-semibold text-foreground mb-4">Подписка</h3>
            <p className="text-muted-foreground text-sm mb-4">
              Получайте лучшие предложения и новости
            </p>
            <div className="flex gap-2 mb-4">
              <Input
                type="email"
                placeholder="Ваш email"
                className="rounded-xl bg-background/50 transition-all duration-300 focus:shadow-md focus:bg-background/70"
              />
              <Button className="bg-secondary hover:bg-secondary/90 text-secondary-foreground rounded-xl hover:shadow-lg hover:-translate-y-0.5">
                <Mail className="h-4 w-4" />
              </Button>
            </div>
            <div className="space-y-2 text-sm text-muted-foreground">
              <div className="flex items-center gap-2">
                <Phone className="h-4 w-4 text-primary" />
                <span>+7 (999) 123-45-67</span>
              </div>
              <div className="flex items-center gap-2">
                <Mail className="h-4 w-4 text-primary" />
                <span>info@mysupertour.com</span>
              </div>
            </div>
          </div>
        </div>

        {/* Bottom */}
        <div className="border-t border-border/50 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
          <p className="text-sm text-muted-foreground">
            © 2024 My Super Tour. Все права защищены.
          </p>
          <div className="flex gap-6 text-sm">
            <NavLink
              to="/privacy"
              className="text-muted-foreground hover:text-primary transition-all duration-300 hover:underline"
            >
              Политика конфиденциальности
            </NavLink>
            <NavLink
              to="/terms"
              className="text-muted-foreground hover:text-primary transition-all duration-300 hover:underline"
            >
              Условия использования
            </NavLink>
          </div>
        </div>
      </div>
    </footer>
  );
};
