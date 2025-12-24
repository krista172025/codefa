import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { FloatingGlassOrbs } from "@/components/FloatingGlassOrbs";
import { BookingModal } from "@/components/BookingModal";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Star, MapPin, Clock, Users, Heart, Share2, Calendar } from "lucide-react";
import { useState } from "react";
import { useParams } from "react-router-dom";
import tour1 from "@/assets/tour-1.jpg";
const TourDetailPage = () => {
  const {
    id
  } = useParams();
  const [isFavorite, setIsFavorite] = useState(false);
  const [isBookingOpen, setIsBookingOpen] = useState(false);
  return <div className="min-h-screen bg-warm">
      <Header />

      <div className="relative pt-24 pb-20 overflow-hidden">
        <FloatingGlassOrbs />
        <div className="container mx-auto px-4 relative z-10 border-0">
          {/* Header Section */}
          <div className="mb-8 animate-fade-in-up">
            <div className="flex items-start justify-between mb-4">
              <div className="flex-1">
                <h1 className="text-4xl md:text-5xl font-bold mb-4 text-foreground">
                  Историческая прогулка по старому городу
                </h1>
                <div className="flex flex-wrap items-center gap-4 text-muted-foreground">
                  <div className="flex items-center gap-1">
                    <Star className="h-5 w-5 fill-primary text-primary" />
                    <span className="font-semibold text-foreground">4.98</span>
                    <span>(124 отзыва)</span>
                  </div>
                  <div className="flex items-center gap-1">
                    <MapPin className="h-5 w-5" />
                    <span>Прага, Чехия</span>
                  </div>
                </div>
              </div>
              <div className="flex gap-2">
                <Button variant="outline" size="icon" className="rounded-full glass-liquid hover:glass-hover-trust transition-all duration-300" onClick={() => setIsFavorite(!isFavorite)}>
                  <Heart className={`h-5 w-5 transition-smooth ${isFavorite ? "fill-destructive text-destructive" : "text-foreground"}`} />
                </Button>
                <Button variant="outline" size="icon" className="rounded-full glass-liquid hover:glass-hover-trust transition-all duration-300">
                  <Share2 className="h-5 w-5" />
                </Button>
              </div>
            </div>
          </div>

          {/* Image Gallery */}
          <div className="mb-12 animate-fade-in">
            <div className="glass-liquid-strong rounded-3xl overflow-hidden shadow-glass">
              <img src={tour1} alt="Tour" className="w-full h-[500px] object-cover" />
            </div>
          </div>

          <div className="grid grid-cols-1 lg:grid-cols-3 gap-12">
            {/* Main Content */}
            <div className="lg:col-span-2 space-y-8">
              {/* Tour Details */}
              <div className="glass-liquid rounded-2xl p-8 animate-fade-in">
                <div className="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                  <div className="flex items-center gap-3">
                    <div className="w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center">
                      <Clock className="h-6 w-6 text-secondary-foreground" />
                    </div>
                    <div>
                      <div className="text-sm text-muted-foreground">
                        Длительность
                      </div>
                      <div className="font-semibold text-foreground">3 часа</div>
                    </div>
                  </div>
                  <div className="flex items-center gap-3">
                    <div className="w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center">
                      <Users className="h-6 w-6 text-secondary-foreground" />
                    </div>
                    <div>
                      <div className="text-sm text-muted-foreground">
                        Группа
                      </div>
                      <div className="font-semibold text-foreground">До 15</div>
                    </div>
                  </div>
                  <div className="flex items-center gap-3">
                    <div className="w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center">
                      <MapPin className="h-6 w-6 text-secondary-foreground" />
                    </div>
                    <div>
                      <div className="text-sm text-muted-foreground">Встреча</div>
                      <div className="font-semibold text-foreground">В центре</div>
                    </div>
                  </div>
                  <div className="flex items-center gap-3">
                    <div className="w-12 h-12 rounded-2xl bg-secondary flex items-center justify-center">
                      <Calendar className="h-6 w-6 text-secondary-foreground" />
                    </div>
                    <div>
                      <div className="text-sm text-muted-foreground">
                        Доступно
                      </div>
                      <div className="font-semibold text-foreground">
                        Ежедневно
                      </div>
                    </div>
                  </div>
                </div>

                <div className="space-y-6">
                  <div>
                    <h2 className="text-2xl font-bold mb-4 text-foreground">
                      Описание
                    </h2>
                    <p className="text-muted-foreground leading-relaxed">
                      Погрузитесь в атмосферу средневековой Праги во время этой
                      увлекательной пешеходной экскурсии. Мы посетим самые
                      красивые места старого города, узнаем его историю и легенды.
                      Вы увидите Карлов мост, Староместскую площадь, Еврейский
                      квартал и другие достопримечательности.
                    </p>
                  </div>

                  <div>
                    <h2 className="text-2xl font-bold mb-4 text-foreground">
                      Что включено
                    </h2>
                    <ul className="space-y-2">
                      <li className="flex items-start gap-2 text-muted-foreground">
                        <div className="w-1.5 h-1.5 rounded-full bg-primary mt-2" />
                        <span>Профессиональный гид</span>
                      </li>
                      <li className="flex items-start gap-2 text-muted-foreground">
                        <div className="w-1.5 h-1.5 rounded-full bg-primary mt-2" />
                        <span>Радиосистема для комфортного общения</span>
                      </li>
                      <li className="flex items-start gap-2 text-muted-foreground">
                        <div className="w-1.5 h-1.5 rounded-full bg-primary mt-2" />
                        <span>Карта города и рекомендации</span>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>

              {/* Guide Info */}
              <div className="glass-liquid rounded-2xl p-8 animate-fade-in">
                <h2 className="text-2xl font-bold mb-6 text-foreground">
                  Ваш гид
                </h2>
                <div className="flex items-start gap-4">
                  <Avatar className="w-16 h-16">
                    <AvatarImage src="" />
                    <AvatarFallback className="bg-primary text-white text-lg">
                      МК
                    </AvatarFallback>
                  </Avatar>
                  <div className="flex-1">
                    <h3 className="text-xl font-semibold mb-2 text-foreground">
                      Мария Ковалева
                    </h3>
                    <div className="flex items-center gap-4 text-sm text-muted-foreground mb-3">
                      <div className="flex items-center gap-1">
                        <Star className="h-4 w-4 fill-primary text-primary" />
                        <span>4.98 (230 отзывов)</span>
                      </div>
                      <span>•</span>
                      <span>Гид с 2018 года</span>
                    </div>
                    <p className="text-muted-foreground">
                      Профессиональный гид с историческим образованием. Люблю
                      показывать гостям скрытые уголки Праги и делиться
                      интересными историями.
                    </p>
                  </div>
                </div>
              </div>
            </div>

            {/* Booking Card */}
            <div className="lg:col-span-1">
              <div className="glass-liquid-strong rounded-2xl p-6 shadow-glass sticky top-24 animate-scale-in border-[#f5eb00] border-2">
                <div className="mb-6">
                  <div className="flex items-baseline gap-2 mb-2">
                    <span className="text-3xl font-bold text-foreground">
                      3 500₽
                    </span>
                    <span className="text-muted-foreground">/ человек</span>
                  </div>
                  <Badge variant="secondary" className="glass-liquid">
                    Бесплатная отмена
                  </Badge>
                </div>

                <Button size="lg" className="w-full rounded-xl bg-primary hover:shadow-glow transition-all duration-300 hover:-translate-y-0.5" onClick={() => setIsBookingOpen(true)}>
                  Забронировать
                </Button>

                <p className="text-xs text-center text-muted-foreground mt-4">
                  Оплата не требуется до подтверждения
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <BookingModal open={isBookingOpen} onOpenChange={setIsBookingOpen} tourTitle="Историческая прогулка по старому городу" tourPrice={3500} />

      <Footer />
    </div>;
};
export default TourDetailPage;