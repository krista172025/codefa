import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { FloatingGlassOrbs } from "@/components/FloatingGlassOrbs";
import { Button } from "@/components/ui/button";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Badge } from "@/components/ui/badge";
import {
  ShoppingBag,
  User,
  MessageSquare,
  Gift,
  Heart,
  Award,
  Settings,
  Download,
  FileText,
} from "lucide-react";
import { NavLink } from "@/components/NavLink";

const menuItems = [
  { icon: ShoppingBag, label: "Мои заказы", href: "/profile/orders" },
  { icon: User, label: "Профиль", href: "/profile/settings" },
  { icon: MessageSquare, label: "Чат", href: "/profile/chat" },
  { icon: Gift, label: "Подарки", href: "/profile/gifts" },
  { icon: Heart, label: "Избранное", href: "/wishlist" },
  { icon: Award, label: "Бонусы", href: "/profile/bonuses" },
];

const orders = [
  {
    id: "1",
    title: "Историческая прогулка по старому городу",
    date: "15 июня 2024",
    status: "Подтверждено",
    price: 3500,
    guests: 2,
  },
  {
    id: "2",
    title: "Морское приключение на острова",
    date: "22 июня 2024",
    status: "Ожидает подтверждения",
    price: 5200,
    guests: 4,
  },
];

const ProfilePage = () => {
  return (
    <div className="min-h-screen bg-warm">
      <Header />

      <div className="relative pt-24 pb-20 overflow-hidden">
        <FloatingGlassOrbs />
        <div className="container mx-auto px-4 relative z-10">
          <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {/* Sidebar */}
            <aside className="lg:col-span-1">
              <div className="glass-liquid-strong rounded-2xl p-6 shadow-glass sticky top-24">
                {/* User Info */}
                <div className="text-center mb-8">
                  <Avatar className="w-24 h-24 mx-auto mb-4">
                    <AvatarImage src="" />
                    <AvatarFallback className="bg-primary text-white text-2xl">
                      ИП
                    </AvatarFallback>
                  </Avatar>
                  <h2 className="text-xl font-bold text-foreground mb-1">
                    Иван Петров
                  </h2>
                  <p className="text-sm text-muted-foreground mb-3">
                    ivan@example.com
                  </p>
                  <Badge variant="secondary" className="glass">
                    <Award className="h-3 w-3 mr-1" />
                    Постоянный клиент
                  </Badge>
                </div>

                {/* Menu */}
                <nav className="space-y-1">
                  {menuItems.map((item) => (
                    <NavLink
                      key={item.href}
                      to={item.href}
                      className="flex items-center gap-3 px-4 py-3 rounded-xl text-foreground hover:bg-accent transition-smooth"
                      activeClassName="bg-accent text-accent-foreground font-medium"
                    >
                      <item.icon className="h-5 w-5" />
                      <span>{item.label}</span>
                    </NavLink>
                  ))}
                </nav>

                <div className="mt-6 pt-6 border-t border-border">
                  <Button
                    variant="ghost"
                    className="w-full justify-start gap-3 text-muted-foreground hover:text-foreground"
                  >
                    <Settings className="h-5 w-5" />
                    Настройки
                  </Button>
                </div>
              </div>
            </aside>

            {/* Main Content */}
            <main className="lg:col-span-3">
              <div className="mb-8 animate-fade-in-up">
                <h1 className="text-4xl font-bold mb-2 text-foreground">
                  Мои заказы
                </h1>
                <p className="text-muted-foreground">
                  Управляйте своими бронированиями и заказами
                </p>
              </div>

              {/* Orders List */}
              <div className="space-y-4">
                {orders.map((order, index) => (
                  <div
                    key={order.id}
                    className="glass-liquid-strong rounded-2xl p-6 hover-lift hover:shadow-glass transition-smooth animate-fade-in relative"
                    style={{ animationDelay: `${index * 100}ms` }}
                  >
                    <div className="flex flex-col gap-4">
                      <div className="flex items-start justify-between">
                        <div className="flex-1">
                          <div className="flex items-center gap-3 mb-3">
                            <h3 className="text-lg font-semibold text-foreground">
                              {order.title}
                            </h3>
                            <Badge
                              variant={
                                order.status === "Подтверждено"
                                  ? "default"
                                  : "secondary"
                              }
                            >
                              {order.status}
                            </Badge>
                          </div>
                          <p className="text-sm text-muted-foreground mb-2">
                            Дата: {order.date}
                          </p>
                          <p className="text-sm text-muted-foreground mb-3">
                            Количество гостей: <span className="font-semibold text-foreground">{order.guests}</span>
                          </p>
                          <div className="text-xl font-bold text-foreground">
                            {order.price.toLocaleString()}₽
                          </div>
                        </div>

                        {/* Action Buttons - Top Right */}
                        <div className="flex gap-2">
                          <Button
                            variant="outline"
                            size="sm"
                            className="glass-liquid gap-2"
                          >
                            <Download className="h-4 w-4" />
                            Скачать подарок
                          </Button>
                          <Button
                            variant="default"
                            size="sm"
                            className="bg-primary hover:shadow-glow gap-2"
                          >
                            <FileText className="h-4 w-4" />
                            Открыть билет
                          </Button>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>

              {/* Stats Cards */}
              <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                <div className="glass-liquid-strong rounded-2xl p-6 text-center animate-scale-in">
                  <div className="w-12 h-12 rounded-2xl bg-secondary mx-auto mb-4 flex items-center justify-center">
                    <ShoppingBag className="h-6 w-6 text-secondary-foreground" />
                  </div>
                  <div className="text-3xl font-bold text-foreground mb-2">
                    12
                  </div>
                  <div className="text-sm text-muted-foreground">
                    Всего заказов
                  </div>
                </div>

                <div className="glass-liquid-strong rounded-2xl p-6 text-center animate-scale-in">
                  <div className="w-12 h-12 rounded-2xl bg-secondary mx-auto mb-4 flex items-center justify-center">
                    <Heart className="h-6 w-6 text-secondary-foreground" />
                  </div>
                  <div className="text-3xl font-bold text-foreground mb-2">8</div>
                  <div className="text-sm text-muted-foreground">В избранном</div>
                </div>

                <div className="glass-liquid-strong rounded-2xl p-6 text-center animate-scale-in">
                  <div className="w-12 h-12 rounded-2xl bg-secondary mx-auto mb-4 flex items-center justify-center">
                    <Award className="h-6 w-6 text-secondary-foreground" />
                  </div>
                  <div className="text-3xl font-bold text-foreground mb-2">
                    1,250
                  </div>
                  <div className="text-sm text-muted-foreground">
                    Бонусных баллов
                  </div>
                </div>
              </div>
            </main>
          </div>
        </div>
      </div>

      <Footer />
    </div>
  );
};

export default ProfilePage;
