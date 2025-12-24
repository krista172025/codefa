import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { TrustBadge } from "@/components/TrustBadge";
import { SearchBar } from "@/components/SearchBar";
import { CategoryCard } from "@/components/CategoryCard";
import { TourCard } from "@/components/TourCard";
import { ProductCard } from "@/components/ProductCard";
import { FloatingGlassOrbs } from "@/components/FloatingGlassOrbs";
import SnowfallEffect from "@/components/SnowfallEffect";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { NavLink } from "@/components/NavLink";
import { Carousel, CarouselContent, CarouselItem, CarouselNext, CarouselPrevious } from "@/components/ui/carousel";
import { Compass, Car, Ticket, Home, Star, Users, Award, Shield, Heart, CheckCircle2, MessageCircle, Sparkles, BookOpen } from "lucide-react";
import { BookingCalendar } from "@/components/booking/BookingCalendar";
import { AdminCalendarPanel } from "@/components/booking/AdminCalendarPanel";
import landmarksHero from "@/assets/landmarks-hero-paris.jpg";
import categoryTours from "@/assets/category-tours.jpg";
import categoryTransfers from "@/assets/category-transfers.jpg";
import categoryTickets from "@/assets/category-tickets.jpg";
import categoryAccommodation from "@/assets/category-accommodation.jpg";
import tour1 from "@/assets/tour-1.jpg";
import tour2 from "@/assets/tour-2.jpg";
import tour3 from "@/assets/tour-3.jpg";
import reviewPrague from "@/assets/review-photo-prague.jpg";
import reviewIslands from "@/assets/review-photo-islands.jpg";
import reviewMountains from "@/assets/review-photo-mountains.jpg";
import guest1 from "@/assets/guest-1.jpg";
import guest2 from "@/assets/guest-2.jpg";
import guest3 from "@/assets/guest-3.jpg";
import guest4 from "@/assets/guest-4.jpg";
import guest5 from "@/assets/guest-5.jpg";
import guest6 from "@/assets/guest-6.jpg";
import architectTour from "@/assets/architect-tour.jpg";
import familyTour from "@/assets/family-tour.jpg";
import memorableTour from "@/assets/memorable-tour.jpg";
import christmasParis from "@/assets/christmas-paris.jpg";
import guideMaria from "@/assets/guide-maria.jpg";
import guideElena from "@/assets/guide-elena.jpg";
import guideAnton from "@/assets/guide-anton.jpg";
const categories = [{
  title: "Экскурсии",
  description: "Необычные экскурсии от местных жителей",
  icon: Compass,
  href: "/tours",
  image: categoryTours
}, {
  title: "Трансфер",
  description: "Комфортные поездки по лучшим маршрутам",
  icon: Car,
  href: "/transfers",
  image: categoryTransfers
}, {
  title: "Билеты",
  description: "Билеты в музеи, театры и на мероприятия",
  icon: Ticket,
  href: "/tickets",
  image: categoryTickets
}, {
  title: "Жилье",
  description: "Уютное жилье в лучших локациях",
  icon: Home,
  href: "/accommodation",
  image: categoryAccommodation
}];
const featuredTours = [{
  id: "1",
  title: "Историческая прогулка по старому городу",
  location: "Прага, Чехия",
  price: 3500,
  rating: 4.98,
  reviews: 124,
  image: tour1,
  category: "Экскурсия"
}, {
  id: "2",
  title: "Морское приключение на острова",
  location: "Пхукет, Таиланд",
  price: 5200,
  rating: 4.95,
  reviews: 89,
  image: tour2,
  category: "Экскурсия"
}, {
  id: "3",
  title: "Горный треккинг с гидом",
  location: "Шамони, Франция",
  price: 4800,
  rating: 4.92,
  reviews: 156,
  image: tour3,
  category: "Активный отдых"
}, {
  id: "4",
  title: "Гастрономический тур по городу",
  location: "Рим, Италия",
  price: 4200,
  rating: 4.96,
  reviews: 203,
  image: tour1,
  category: "Гастрономия"
}];
const reviews = [{
  author: "Анна С.",
  avatar: "АС",
  text: "Спасибо за честность! Все было именно так, как обещали. Никаких скрытых доплат!",
  rating: 5,
  date: "15 ноября 2024",
  image: reviewPrague,
  city: "Прага",
  productName: "Историческая прогулка по старому городу"
}, {
  author: "Дмитрий К.",
  avatar: "ДК",
  text: "Поддержка ответила за 2 минуты и решила все вопросы. Чувствуешь, что о тебе заботятся.",
  rating: 5,
  date: "10 ноября 2024",
  image: reviewIslands,
  city: "Пхукет",
  productName: "Морское приключение на острова"
}, {
  author: "Елена П.",
  avatar: "ЕП",
  text: "Вернули деньги без вопросов, когда не смогли поехать. Это настоящее доверие!",
  rating: 5,
  date: "5 ноября 2024",
  image: reviewMountains,
  city: "Шамони",
  productName: "Горный треккинг с гидом"
}, {
  author: "Михаил Р.",
  avatar: "МР",
  text: "Экскурсия превзошла все ожидания! Гид был профессионалом, маршрут продуман до мелочей.",
  rating: 5,
  date: "1 ноября 2024",
  image: reviewPrague,
  city: "Прага",
  productName: "Гастрономический тур по городу"
}];
const guests = [{
  image: guest1,
  name: "Семья Петровых",
  city: "Москва",
  date: "Октябрь 2024"
}, {
  image: guest2,
  name: "Анна и Максим",
  city: "Санкт-Петербург",
  date: "Сентябрь 2024"
}, {
  image: guest3,
  name: "Группа друзей",
  city: "Казань",
  date: "Август 2024"
}, {
  image: guest4,
  name: "Николай и Вера",
  city: "Екатеринбург",
  date: "Июль 2024"
}, {
  image: guest5,
  name: "Мария",
  city: "Новосибирск",
  date: "Июнь 2024"
}, {
  image: guest6,
  name: "Подруги",
  city: "Краснодар",
  date: "Май 2024"
}];

const shopDemoProduct = {
  title: "ПАРИЖ ЗА 3 ЧАСА",
  city: "Paris",
  price: 70,
  oldPrice: 100,
  image: christmasParis,
  groupLabel: "Групповая",
  durationLabel: "2:00",
  transportLabel: "Авто",
  guideImage: guideMaria,
  rating: 5,
  reviews: 0,
};

const HomePage = () => {
  return <div className="min-h-screen bg-warm">
      <Header />

      {/* Hero Section - Grainy Background with Paris */}
      <section className="relative pt-32 pb-20 overflow-hidden">
        <FloatingGlassOrbs />
        
        {/* Paris Photo Background */}
        <div className="absolute inset-0 bg-cover bg-center opacity-30" style={{
        backgroundImage: `url(${landmarksHero})`
      }} />
        
        {/* Gradient Overlay */}
        <div className="absolute inset-0" style={{
        background: `
              radial-gradient(circle at 20% 30%, hsl(var(--primary) / 0.25) 0%, transparent 50%),
              radial-gradient(circle at 80% 70%, hsl(var(--secondary) / 0.28) 0%, transparent 50%),
              linear-gradient(135deg, hsl(var(--background) / 0.7) 0%, hsl(var(--primary) / 0.15) 50%, hsl(var(--secondary) / 0.15) 100%)
            `,
        backgroundBlendMode: 'normal'
      }} />
        
        {/* Strong Grain Texture Overlay */}
        <div className="absolute inset-0 opacity-50" style={{
        backgroundImage: `url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='5.5' numOctaves='6' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E")`,
        mixBlendMode: 'soft-light'
      }} />
        
        <div className="container mx-auto px-4 sm:px-6 relative z-10">
          <div className="max-w-5xl mx-auto">
            {/* Trust Badges */}
            <div className="flex flex-wrap justify-center gap-3 mb-8 animate-slide-up">
              <TrustBadge type="guarantee" text="100% гарантия возврата" />
              <TrustBadge type="verified" text="Проверенные гиды" />
              <TrustBadge type="care" text="Поддержка 24/7" />
            </div>

            {/* Main Heading */}
            <div className="text-center mb-12 animate-fade-in-up">
            <h1 className="font-display text-5xl sm:text-6xl lg:text-7xl font-bold mb-6 text-foreground leading-tight">
              Откройте мир
              <span className="block text-primary mt-2">без скрытых платежей</span>
            </h1>
              <p className="text-xl sm:text-2xl text-muted-foreground mb-4 max-w-3xl mx-auto leading-relaxed">
                Мы показываем реальную цену сразу. Честно рассказываем о турах. Заботимся о каждом путешественнике.
              </p>
              <div className="flex items-center justify-center gap-2 text-sm text-muted-foreground">
                <CheckCircle2 className="h-5 w-5 text-trust" />
                <span>Более 50,000 довольных туристов доверяют нам</span>
              </div>
            </div>

            {/* Search */}
            <div className="flex justify-center mb-8">
              <div className="w-full max-w-2xl">
                <SearchBar variant="hero" />
                <p className="text-center mt-4 text-sm text-muted-foreground flex items-center justify-center gap-2">
                  <Sparkles className="h-4 w-4 text-secondary" />
                  <span>Все цены финальные. Никаких сюрпризов при оплате</span>
                </p>
              </div>
            </div>
          </div>
        </div>

        {/* Floating decoration */}
        <div className="absolute top-20 right-10 w-32 h-32 bg-secondary/10 rounded-full blur-3xl animate-gentle-bounce" />
        <div className="absolute bottom-20 left-10 w-40 h-40 bg-primary/10 rounded-full blur-3xl animate-gentle-bounce" style={{
        animationDelay: '1s'
      }} />
      </section>

      {/* Marquee Section - Advantages */}
      <section className="py-4 bg-primary overflow-hidden">
        <div className="flex animate-marquee whitespace-nowrap">
          {[...Array(3)].map((_, i) => <div key={i} className="flex items-center gap-12 px-6">
              <span className="text-primary-foreground font-semibold text-lg">⚡ Лицензированные гиды</span>
              <span className="text-primary-foreground/60">•</span>
              <span className="text-primary-foreground font-semibold text-lg">✨ Персональные туры</span>
              <span className="text-primary-foreground/60">•</span>
              <span className="text-primary-foreground font-semibold text-lg">🏆 Сертификация качества команды</span>
              <span className="text-primary-foreground/60">•</span>
            </div>)}
        </div>
      </section>

      {/* Stats Section */}
      <section className="py-16 container mx-auto px-4 sm:px-6">
        <div className="grid sm:grid-cols-3 gap-6 max-w-5xl mx-auto">
          {/* Персональный подход */}
          <div className="glass-liquid-strong p-8 hover-lift-gentle transition-smooth text-center" style={{
          borderRadius: "1.5rem"
        }}>
            <div className="text-5xl font-bold text-primary mb-2">1:1</div>
            <div className="text-lg font-bold text-foreground mb-2">ПЕРСОНАЛЬНЫЙ ПОДХОД</div>
            <div className="text-sm text-muted-foreground">На связи с каждым гостем наш профессиональный менеджер</div>
          </div>

          {/* Всё продумано до мелочей */}
          <div className="glass-liquid-strong p-8 hover-lift-gentle transition-smooth text-center" style={{
          borderRadius: "1.5rem"
        }}>
            <div className="text-5xl font-bold text-secondary mb-2">100%</div>
            <div className="text-lg font-bold text-foreground mb-2">ВСЁ ПРОДУМАНО ДО МЕЛОЧЕЙ!</div>
            <div className="text-sm text-muted-foreground">Огромный опыт помощи в планировании путешествий!</div>
          </div>

          {/* Восторженные отзывы */}
          <div className="glass-liquid-strong p-8 hover-lift-gentle transition-smooth text-center" style={{
          borderRadius: "1.5rem"
        }}>
            <div className="text-5xl font-bold text-trust mb-2">3407+</div>
            <div className="text-lg font-bold text-foreground mb-2">ВОСТОРЖЕННЫХ ОТЗЫВОВ!</div>
            <div className="text-sm text-muted-foreground">В Париже мы занимаем первое место по русскоязычным отзывам на всех платформах!</div>
          </div>
        </div>
      </section>

      {/* Why Trust Us Section */}
      <section className="py-16 container mx-auto px-4 sm:px-6">
        <div className="max-w-6xl mx-auto">
          <div className="glass-liquid-strong p-8 sm:p-12" style={{
          borderRadius: "2rem"
        }}>
            <div className="flex items-center gap-3 mb-8 justify-center">
              <div className="w-12 h-12 rounded-2xl bg-trust/10 flex items-center justify-center">
                <Shield className="h-6 w-6 text-trust" />
              </div>
              <h2 className="text-3xl sm:text-4xl font-bold text-foreground">Почему нам доверяют?</h2>
            </div>
            
            <div className="grid grid-cols-2 sm:grid-cols-3 gap-4 sm:gap-6">
              <div className="text-center p-4 glass-liquid rounded-xl hover-lift-gentle transition-smooth">
                <div className="text-3xl sm:text-4xl font-bold text-primary mb-2">100%</div>
                <div className="text-sm font-medium text-foreground mb-1">Прозрачность</div>
                <div className="text-xs text-muted-foreground leading-tight">Все включено в цену. Без доплат</div>
              </div>
              <div className="text-center p-4 glass-liquid rounded-xl hover-lift-gentle transition-smooth">
                <div className="text-3xl sm:text-4xl font-bold text-secondary mb-2">24/7</div>
                <div className="text-sm font-medium text-foreground mb-1">Поддержка</div>
                <div className="text-xs text-muted-foreground leading-tight">Ответим за 2 минуты в любое время</div>
              </div>
              <div className="text-center p-4 glass-liquid rounded-xl hover-lift-gentle transition-smooth">
                <div className="text-3xl sm:text-4xl font-bold text-trust mb-2">14 дней</div>
                <div className="text-sm font-medium text-foreground mb-1">Возврат</div>
                <div className="text-xs text-muted-foreground leading-tight">Вернем деньги без лишних вопросов</div>
              </div>
              <div className="text-center p-4 glass-liquid rounded-xl hover-lift-gentle transition-smooth">
                <div className="text-3xl sm:text-4xl font-bold text-primary mb-2">5★</div>
                <div className="text-sm font-medium text-foreground mb-1">Рейтинг</div>
                <div className="text-xs text-muted-foreground leading-tight">Высшие оценки на всех платформах</div>
              </div>
              <div className="text-center p-4 glass-liquid rounded-xl hover-lift-gentle transition-smooth">
                <div className="text-3xl sm:text-4xl font-bold text-secondary mb-2">257</div>
                <div className="text-sm font-medium text-foreground mb-1">Гидов</div>
                <div className="text-xs text-muted-foreground leading-tight">Аккредитованных по всему миру</div>
              </div>
              <div className="text-center p-4 glass-liquid rounded-xl hover-lift-gentle transition-smooth border-[#f5eb00] border-0">
                <div className="text-3xl sm:text-4xl font-bold text-trust mb-2">10+</div>
                <div className="text-sm font-medium text-foreground mb-1">Лет опыта</div>
                <div className="text-xs text-muted-foreground leading-tight">Помогаем путешественникам</div>
              </div>
              <div className="text-center p-4 glass-liquid rounded-xl hover-lift-gentle transition-smooth">
                <div className="text-3xl sm:text-4xl font-bold text-primary mb-2">50К+</div>
                <div className="text-sm font-medium text-foreground mb-1">Гостей</div>
                <div className="text-xs text-muted-foreground leading-tight">Довольных путешественников</div>
              </div>
              <div className="text-center p-4 glass-liquid rounded-xl hover-lift-gentle transition-smooth border-0">
                <div className="text-3xl sm:text-4xl font-bold text-secondary mb-2">€0</div>
                <div className="text-sm font-medium text-foreground mb-1">Комиссий</div>
                <div className="text-xs text-muted-foreground leading-tight">Финальная цена сразу</div>
              </div>
              <div className="text-center p-4 glass-liquid rounded-xl hover-lift-gentle transition-smooth">
                <div className="text-3xl sm:text-4xl font-bold text-trust mb-2">100%</div>
                <div className="text-sm font-medium text-foreground mb-1">Лицензии</div>
                <div className="text-xs text-muted-foreground leading-tight">Все гиды проверены и сертифицированы</div>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Categories Section */}
      <section className="py-16 container mx-auto px-4 sm:px-6">
        <div className="mb-12 text-center">
          <h2 className="text-4xl sm:text-5xl font-bold mb-4 text-foreground">
            Что вас интересует?
          </h2>
          <p className="text-lg text-muted-foreground">
            Выберите то, что близко вашему сердцу
          </p>
        </div>
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          {categories.map((category, index) => <div key={category.title} className="animate-slide-up" style={{
          animationDelay: `${index * 100}ms`
        }}>
              <CategoryCard {...category} />
            </div>)}
        </div>
      </section>

      {/* Promo Banner - Popular This Month */}
      <section className="py-16 container mx-auto px-4 sm:px-6">
        <div className="relative max-w-6xl mx-auto overflow-hidden glass-liquid-strong hover-lift-gentle group" style={{
        borderRadius: "2rem"
      }}>
          <div className="absolute inset-0 bg-gradient-to-r from-primary/20 via-secondary/20 to-primary/20 animate-shimmer" style={{
          backgroundSize: "200% 100%"
        }} />
          
          <div className="relative z-10 grid lg:grid-cols-[1.5fr_1fr] gap-6 items-center">
            {/* Left side - Text content */}
            <div className="p-6 sm:p-8 lg:pl-12">
              <Badge className="mb-4 bg-secondary text-secondary-foreground border-0 px-4 py-2 shadow-lg">
                <Sparkles className="h-4 w-4 mr-2" />
                Популярное в ноябре
              </Badge>
              <h2 className="text-2xl sm:text-3xl lg:text-4xl font-bold mb-3 text-foreground leading-tight">
                Рождественский Париж
              </h2>
              <p className="text-base sm:text-lg text-muted-foreground mb-4 leading-relaxed">
                Окунитесь в атмосферу праздника! Специальные туры по украшенным улицам, рождественским ярмаркам и волшебным витринам.
              </p>
              
              <div className="flex flex-wrap items-center gap-4 sm:gap-6 mb-4 text-sm">
                <div className="flex items-center gap-2">
                  <Users className="h-4 w-4 text-primary" />
                  <span className="text-muted-foreground">156 довольных гостей</span>
                </div>
                <div className="flex items-center gap-2">
                  <Star className="h-4 w-4 fill-secondary text-secondary" />
                  <span className="text-muted-foreground">Рейтинг 4.98</span>
                </div>
              </div>

              <div className="flex items-center gap-3 mb-4">
                <div className="text-left">
                  <div className="text-xs sm:text-sm text-muted-foreground">Всего от</div>
                  <div className="text-2xl sm:text-3xl font-bold text-primary">2,990₽</div>
                </div>
              </div>
              
              <div className="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <Button size="lg" className="bg-primary hover:bg-primary/90 text-primary-foreground shadow-xl hover:shadow-2xl transition-smooth w-full sm:w-auto" style={{
                borderRadius: "1.5rem"
              }}>
                  Забронировать сейчас
                </Button>
                <Button size="lg" variant="outline" className="glass-liquid border-white/30 hover:border-white/50 hover:glass-hover-trust transition-smooth gap-2 w-full sm:w-auto" style={{
                borderRadius: "1.5rem"
              }} asChild>
                  <NavLink to="/blog">
                    <BookOpen className="h-5 w-5" />
                    Полезная информация
                  </NavLink>
                </Button>
              </div>
            </div>
            
            {/* Right side - Image */}
            <div className="relative h-64 sm:h-80 lg:h-full lg:min-h-[450px]">
              <img src={christmasParis} alt="Рождественский Париж" className="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 rounded-2xl lg:rounded-r-2xl lg:rounded-l-none" />
              <div className="absolute inset-0 bg-gradient-to-t lg:bg-gradient-to-l from-transparent via-transparent to-warm/60 lg:to-warm/40 rounded-2xl lg:rounded-r-2xl lg:rounded-l-none" />
            </div>
          </div>
        </div>
      </section>

      {/* Featured Tours */}
      <section className="py-16 bg-pattern-organic">
        <div className="container mx-auto px-4 sm:px-6">
          <div className="mb-12 text-center">
            <Badge className="mb-4 bg-secondary/20 text-secondary-foreground border-0 px-4 py-1">
              <Star className="h-3 w-3 mr-1" />
              Рекомендуем
            </Badge>
            <h2 className="text-4xl sm:text-5xl font-bold mb-4 text-foreground">
              Полюбились туристам
            </h2>
            <p className="text-lg text-muted-foreground">
              Экскурсии с самыми высокими оценками
            </p>
          </div>
          
          <div className="max-w-6xl mx-auto px-0">
            <Carousel opts={{
            align: "start",
            loop: true
          }} className="w-full">
              <CarouselContent className="-ml-4">
                {featuredTours.map((tour, index) => <CarouselItem key={tour.id} className="pl-4 md:basis-1/2 lg:basis-1/4">
                    <div className="animate-slide-up" style={{
                  animationDelay: `${index * 100}ms`
                }}>
                      <TourCard {...tour} />
                    </div>
                  </CarouselItem>)}
              </CarouselContent>
              <CarouselPrevious className="glass-liquid-strong hover:glass-hover-trust" />
              <CarouselNext className="glass-liquid-strong hover:glass-hover-trust" />
            </Carousel>
          </div>

          {/* Trust Element 3 */}
          
        </div>
      </section>

      {/* Real Reviews */}
      <section className="py-16 container mx-auto px-4 sm:px-6">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-12">
            <h2 className="text-4xl sm:text-5xl font-bold mb-4 text-foreground">
              Честные отзывы
            </h2>
            <p className="text-lg text-muted-foreground mb-8">
              Без фейков. Только реальные впечатления путешественников
            </p>

            {/* Review Platform Stats */}
            <div className="flex flex-col sm:flex-row gap-4 justify-center items-center max-w-2xl mx-auto">
              {/* TripAdvisor */}
              <div className="glass-liquid-strong p-6 rounded-2xl flex items-center gap-4 w-full sm:w-auto">
                <div className="flex items-center gap-2">
                  <div className="w-10 h-10 rounded-full bg-[#34E0A1]/10 flex items-center justify-center">
                    <svg className="w-6 h-6" viewBox="0 0 24 24" fill="#34E0A1">
                      <circle cx="12" cy="12" r="10" />
                      <circle cx="9" cy="12" r="2" fill="white" />
                      <circle cx="15" cy="12" r="2" fill="white" />
                    </svg>
                  </div>
                  <div className="text-left">
                    <div className="text-xs text-muted-foreground">TripAdvisor</div>
                    <div className="flex gap-0.5">
                      {[...Array(5)].map((_, i) => <Star key={i} className="h-3 w-3 fill-[#34E0A1] text-[#34E0A1]" />)}
                    </div>
                  </div>
                </div>
                <div className="text-left">
                  <div className="text-2xl font-bold text-foreground">4.9</div>
                  <div className="text-xs text-muted-foreground">Средний рейтинг</div>
                </div>
              </div>

              {/* Google Reviews */}
              <div className="glass-liquid-strong p-6 rounded-2xl flex items-center gap-4 w-full sm:w-auto">
                <div className="flex items-center gap-2">
                  <div className="w-10 h-10 rounded-full bg-primary/10 flex items-center justify-center">
                    <span className="text-primary font-bold text-lg">G</span>
                  </div>
                  <div className="text-left">
                    <div className="text-xs text-muted-foreground">Google</div>
                    <div className="flex gap-0.5">
                      {[...Array(5)].map((_, i) => <Star key={i} className="h-3 w-3 fill-secondary text-secondary" />)}
                    </div>
                  </div>
                </div>
                <div className="text-left">
                  <div className="text-2xl font-bold text-foreground">2,487</div>
                  <div className="text-xs text-muted-foreground">Всего отзывов</div>
                </div>
              </div>
            </div>
          </div>

          <div className="max-w-6xl mx-auto px-12">
            <Carousel opts={{
            align: "start",
            loop: true
          }} className="w-full">
              <CarouselContent className="-ml-4">
                {reviews.map((review, index) => <CarouselItem key={index} className="pl-4 md:basis-1/2 lg:basis-1/3">
                    <div className="glass-liquid-strong overflow-hidden hover-lift-gentle hover:glass-hover-trust animate-slide-up h-full" style={{
                  animationDelay: `${index * 100}ms`,
                  borderRadius: "1.5rem"
                }}>
                      {review.image && <div className="relative h-48 overflow-hidden">
                          <img src={review.image} alt={`Фото от ${review.author}`} className="w-full h-full object-cover transition-transform duration-300 hover:scale-110" />
                        </div>}
                      <div className="p-6">
                        <div className="flex items-center gap-3 mb-4">
                          <Avatar className="w-12 h-12 border-2 border-primary/20">
                            <AvatarImage src="" />
                            <AvatarFallback className="bg-primary-soft text-primary font-semibold">
                              {review.avatar}
                            </AvatarFallback>
                          </Avatar>
                          <div className="flex-1">
                            <div className="font-semibold text-foreground">{review.author}</div>
                            <div className="flex gap-0.5">
                              {[...Array(review.rating)].map((_, i) => <Star key={i} className="h-3 w-3 fill-secondary text-secondary" />)}
                            </div>
                          </div>
                          <div className="text-xs text-muted-foreground">
                            {review.date}
                          </div>
                        </div>
                        <div className="mb-3 flex flex-col gap-1">
                          <div className="text-xs font-medium text-primary">{review.city}</div>
                          <div className="text-sm font-semibold text-foreground">{review.productName}</div>
                        </div>
                        <p className="text-foreground text-sm leading-relaxed">{review.text}</p>
                      </div>
                    </div>
                  </CarouselItem>)}
              </CarouselContent>
              <CarouselPrevious className="glass-liquid-strong hover:glass-hover-trust" />
              <CarouselNext className="glass-liquid-strong hover:glass-hover-trust" />
            </Carousel>
          </div>

          <div className="text-center mt-8">
            <Button variant="outline" size="lg" className="glass-liquid hover-lift-gentle" asChild>
              <NavLink to="/reviews">
                Более 2,500 отзывов
                <MessageCircle className="ml-2 h-5 w-5" />
              </NavLink>
            </Button>
          </div>
        </div>
      </section>

      {/* Why Trust Us - Group 2 */}
      <section className="py-8 container mx-auto px-4 sm:px-6">
        <div className="max-w-6xl mx-auto">
          
        </div>
      </section>

      {/* Why Choose Us Section - Reimagined */}
      <section className="py-16 sm:py-20 container mx-auto px-4 sm:px-6">
        <div className="relative max-w-6xl mx-auto">
          <div className="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            {/* Card 1 - Архитектор */}
            <div className="relative glass-liquid-strong overflow-hidden hover-lift-gentle group transition-smooth animate-slide-up rounded-2xl">
              <div className="absolute inset-0 bg-gradient-to-br from-primary/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
              <div className="relative h-56 sm:h-64 overflow-hidden">
                <img src={architectTour} alt="Архитектурные маршруты" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent" />
                <Badge className="absolute top-4 left-4 bg-primary text-primary-foreground border-0 shadow-xl">
                  <Award className="h-3 w-3 mr-1" />
                  Профессионально
                </Badge>
              </div>
              <div className="p-6 sm:p-8 relative">
                <h3 className="text-lg sm:text-xl font-bold text-foreground text-center mb-3 leading-tight">
                  Маршруты разработаны архитектором!
                </h3>
                <p className="text-muted-foreground text-center leading-relaxed text-sm">
                  С выстроенной сценографией и эффектами Вау! Минимум лестниц, максимум всемирноизвестных сокровищ.
                </p>
              </div>
            </div>

            {/* Card 2 - Семья */}
            <div className="relative glass-liquid-strong overflow-hidden hover-lift-gentle group transition-smooth animate-slide-up rounded-2xl" style={{
            animationDelay: "100ms"
          }}>
              <div className="absolute inset-0 bg-gradient-to-br from-secondary/20 via-transparent to-transparent transition-opacity duration-500 shadow-none opacity-0" />
              <div className="relative h-56 sm:h-64 overflow-hidden">
                <img src={familyTour} alt="Семейные экскурсии" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent" />
                <Badge className="absolute top-4 left-4 bg-secondary text-secondary-foreground border-0 shadow-xl">
                  <Heart className="h-3 w-3 mr-1" />
                  Для всей семьи
                </Badge>
              </div>
              <div className="p-6 sm:p-8 relative">
                <h3 className="text-lg sm:text-xl font-bold text-foreground text-center mb-3 leading-tight">
                  Захватывающие для любого возраста!
                </h3>
                <div className="glass-liquid p-3 sm:p-4 rounded-xl bg-white/40">
                  <p className="text-xs sm:text-sm text-foreground italic text-center leading-relaxed">
                    "Спустя год, мои малышки 7 и 9 лет вспоминают Вашу экскурсию в Лувре чаще, чем сам Диснейленд!"
                  </p>
                  <p className="text-xs text-muted-foreground text-center mt-2 font-medium">
                    — Анна, Тель-Авив
                  </p>
                </div>
              </div>
            </div>

            {/* Card 3 - Незабываемо */}
            <div className="relative glass-liquid-strong overflow-hidden hover-lift-gentle group transition-smooth animate-slide-up rounded-2xl" style={{
            animationDelay: "200ms"
          }}>
              <div className="absolute inset-0 bg-gradient-to-br from-trust/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500" />
              <div className="relative h-56 sm:h-64 overflow-hidden">
                <img src={memorableTour} alt="Незабываемые впечатления" className="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" />
                <div className="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent" />
                <Badge className="absolute top-4 left-4 bg-trust text-trust-foreground border-0 shadow-xl">
                  <Sparkles className="h-3 w-3 mr-1" />
                  Эмоции
                </Badge>
              </div>
              <div className="p-6 sm:p-8 relative">
                <h3 className="text-lg sm:text-xl font-bold text-foreground text-center mb-3 leading-tight">
                  Запомните надолго!
                </h3>
                <p className="text-muted-foreground text-center leading-relaxed text-sm">
                  Экскурсии разработаны так, чтобы Ваши новые знания и эмоции остались на долгие годы!
                </p>
              </div>
            </div>
          </div>
        </div>

        {/* Trust Element 5 */}
        
      </section>

      {/* Our Team Section */}
      <section className="py-16 container mx-auto px-4 sm:px-6">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-12">
            <h2 className="text-4xl sm:text-5xl font-bold mb-4 text-foreground">Наша команда</h2>
            <p className="text-lg text-muted-foreground mb-8">257 аккредитованных гидов по всему миру, для которых увлекать – это призвание.</p>
          </div>

          {/* Guides Carousel - Horizontal Row of Circular Photos */}
          <div className="flex justify-center items-center gap-6 sm:gap-8 mb-12 flex-wrap">
            <div className="text-center group">
              <div className="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden glass-liquid-strong p-1 hover-lift-gentle transition-smooth mb-3 mx-auto">
                <img src={guideMaria} alt="Мария" className="w-full h-full rounded-full object-cover group-hover:scale-110 transition-transform duration-500" />
              </div>
              <p className="text-sm font-semibold text-foreground">Мария</p>
              <p className="text-xs text-muted-foreground">Париж</p>
            </div>

            <div className="text-center group">
              <div className="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden glass-liquid-strong p-1 hover-lift-gentle transition-smooth mb-3 mx-auto">
                <img src={guideElena} alt="Елена" className="w-full h-full rounded-full object-cover group-hover:scale-110 transition-transform duration-500" />
              </div>
              <p className="text-sm font-semibold text-foreground">Елена</p>
              <p className="text-xs text-muted-foreground">Рим</p>
            </div>

            <div className="text-center group">
              <div className="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden glass-liquid-strong p-1 hover-lift-gentle transition-smooth mb-3 mx-auto">
                <img src={guideAnton} alt="Антон" className="w-full h-full rounded-full object-cover group-hover:scale-110 transition-transform duration-500" />
              </div>
              <p className="text-sm font-semibold text-foreground">Антон</p>
              <p className="text-xs text-muted-foreground">Барселона</p>
            </div>

            <div className="text-center group">
              <div className="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden glass-liquid-strong p-1 hover-lift-gentle transition-smooth mb-3 mx-auto">
                <img src={guideMaria} alt="София" className="w-full h-full rounded-full object-cover group-hover:scale-110 transition-transform duration-500" />
              </div>
              <p className="text-sm font-semibold text-foreground">София</p>
              <p className="text-xs text-muted-foreground">Прага</p>
            </div>

            <div className="text-center group">
              <div className="w-24 h-24 sm:w-32 sm:h-32 rounded-full overflow-hidden glass-liquid-strong p-1 hover-lift-gentle transition-smooth mb-3 mx-auto">
                <img src={guideElena} alt="Дмитрий" className="w-full h-full rounded-full object-cover group-hover:scale-110 transition-transform duration-500" />
              </div>
              <p className="text-sm font-semibold text-foreground">Дмитрий</p>
              <p className="text-xs text-muted-foreground">Лондон</p>
            </div>
          </div>

          <div className="text-center">
            <Button size="lg" className="bg-primary hover:bg-primary/90 text-primary-foreground shadow-xl" style={{
            borderRadius: "1.5rem"
          }} asChild>
              <NavLink to="/guides">Познакомьтесь с нашими гидами</NavLink>
            </Button>
          </div>

          {/* Trust Element 6 */}
          
        </div>
      </section>

      {/* Our Guests Section */}
      <section className="py-16 bg-pattern-organic">
        <div className="container mx-auto px-4 sm:px-6">
          <div className="text-center mb-12">
            <h2 className="text-4xl sm:text-5xl font-bold mb-4 text-foreground">
              Наши гости
            </h2>
            <p className="text-lg text-muted-foreground">
              Реальные фотографии путешественников, которые выбрали нас
            </p>
          </div>

          <div className="max-w-6xl mx-auto px-12">
            <Carousel opts={{
            align: "start",
            loop: true
          }} className="w-full">
              <CarouselContent className="-ml-4">
                {guests.map((guest, index) => <CarouselItem key={index} className="pl-4 sm:basis-1/2 lg:basis-1/4">
                    <div className="glass-liquid-strong overflow-hidden hover-lift-gentle transition-smooth animate-slide-up group h-full" style={{
                  animationDelay: `${index * 100}ms`,
                  borderRadius: "1.5rem"
                }}>
                      <div className="relative h-64 sm:h-80 overflow-hidden">
                        <img src={guest.image} alt={guest.name} className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-100 transition-opacity duration-300" />
                        <div className="absolute bottom-0 left-0 right-0 p-4 text-white">
                          <div className="font-semibold text-lg mb-1">{guest.name}</div>
                          <div className="flex items-center gap-3 text-sm text-white/90">
                            <span className="flex items-center gap-1">
                              📍 {guest.city}
                            </span>
                            <span className="flex items-center gap-1">
                              📅 {guest.date}
                            </span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </CarouselItem>)}
              </CarouselContent>
              <CarouselPrevious className="glass-liquid-strong hover:glass-hover-trust" />
              <CarouselNext className="glass-liquid-strong hover:glass-hover-trust" />
            </Carousel>
          </div>
        </div>
      </section>

      {/* Why Trust Us - Group 3 */}
      

      {/* CTA Section */}
      <section className="py-20 container mx-auto px-4 sm:px-6">
        <div className="glass-liquid-strong p-12 sm:p-16 text-center max-w-4xl mx-auto relative overflow-hidden" style={{
        borderRadius: "2rem"
      }}>
          <div className="absolute top-0 right-0 w-64 h-64 bg-secondary/10 rounded-full blur-3xl" />
          <div className="absolute bottom-0 left-0 w-64 h-64 bg-primary/10 rounded-full blur-3xl" />
          
          <div className="relative z-10">
            <div className="inline-flex items-center gap-2 glass-liquid px-4 py-2 rounded-full mb-6 shadow-sm">
              <MessageCircle className="h-5 w-5 text-primary" />
              <span className="text-sm font-medium text-foreground">Есть вопросы? Напишите нам!</span>
            </div>

            <h2 className="text-4xl sm:text-5xl font-bold mb-4 text-foreground">
              Начните путешествие сегодня
            </h2>
            <p className="text-lg text-muted-foreground mb-8 max-w-2xl mx-auto">
              Мы обещаем быть честными с вами на каждом шаге. Никаких скрытых условий. Только забота о вашем отдыхе.
            </p>

            <div className="flex flex-col sm:flex-row gap-4 justify-center">
              <Button size="lg" className="bg-primary hover:bg-primary/90 text-primary-foreground shadow-xl hover:shadow-2xl transition-smooth" style={{
              borderRadius: "1.5rem",
              height: "3.5rem",
              paddingLeft: "2rem",
              paddingRight: "2rem"
            }}>
                Найти экскурсию
              </Button>
              <Button size="lg" variant="outline" className="glass-liquid border-white/30 hover:border-white/50 hover:glass-hover-trust transition-smooth" style={{
              borderRadius: "1.5rem",
              height: "3.5rem",
              paddingLeft: "2rem",
              paddingRight: "2rem"
            }}>
                Связаться с нами
              </Button>
            </div>

            <div className="mt-8 flex flex-wrap justify-center gap-6 text-sm text-muted-foreground">
              <div className="flex items-center gap-2">
                <CheckCircle2 className="h-4 w-4 text-trust" />
                <span>Бесплатная отмена</span>
              </div>
              <div className="flex items-center gap-2">
                <CheckCircle2 className="h-4 w-4 text-trust" />
                <span>Без комиссий</span>
              </div>
              <div className="flex items-center gap-2">
                <CheckCircle2 className="h-4 w-4 text-trust" />
                <span>Мгновенное подтверждение</span>
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Demo: WooCommerce-like product card with modern hover */}
      <section className="py-16 container mx-auto px-4 sm:px-6">
        <div className="max-w-6xl mx-auto">
          <div className="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
              <h2 className="text-3xl sm:text-4xl font-bold text-foreground leading-tight">
                Товар из магазина
              </h2>
              <p className="text-muted-foreground mt-2">
                Пример карточки: ховер только на бейджах/иконках + мягкая обводка при наведении
              </p>
            </div>
            <Button
              variant="outline"
              className="glass-liquid border-white/30 hover:border-white/50 hover:glass-hover-trust transition-smooth"
              style={{ borderRadius: "1.25rem" }}
              asChild
            >
              <NavLink to="/shop">Перейти в магазин</NavLink>
            </Button>
          </div>

          <div className="max-w-sm">
            <ProductCard {...shopDemoProduct} />
          </div>
        </div>
      </section>

      {/* Booking Calendar Section */}
      <section className="py-16 container mx-auto px-4 sm:px-6">
        <div className="max-w-6xl mx-auto">
          <div className="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
              <h2 className="text-3xl sm:text-4xl font-bold text-foreground leading-tight">
                Забронировать экскурсию
              </h2>
              <p className="text-muted-foreground mt-2">
                Выберите удобную дату и оформите бронирование
              </p>
            </div>
          </div>

          <BookingCalendar 
            onBooking={(date, price) => {
              console.log("Booking:", date, price);
            }}
          />
        </div>
      </section>

      {/* Admin Calendar Panel Section */}
      <section className="py-16 container mx-auto px-4 sm:px-6 bg-gradient-to-b from-transparent to-muted/20">
        <div className="max-w-6xl mx-auto">
          <AdminCalendarPanel 
            onSave={(dates) => {
              console.log("Saved dates:", dates);
            }}
          />
        </div>
      </section>

      <Footer />
    </div>;
};
export default HomePage;