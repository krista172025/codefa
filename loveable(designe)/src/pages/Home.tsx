import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { SearchBar } from "@/components/SearchBar";
import { CategoryCard } from "@/components/CategoryCard";
import { TourCard } from "@/components/TourCard";
import { Button } from "@/components/ui/button";
import { Compass, Car, Ticket, Home, Star, Users, Award } from "lucide-react";
import heroImage from "@/assets/hero-image.jpg";
import categoryTours from "@/assets/category-tours.jpg";
import categoryTransfers from "@/assets/category-transfers.jpg";
import categoryTickets from "@/assets/category-tickets.jpg";
import categoryAccommodation from "@/assets/category-accommodation.jpg";
import tour1 from "@/assets/tour-1.jpg";
import tour2 from "@/assets/tour-2.jpg";
import tour3 from "@/assets/tour-3.jpg";

const categories = [
  {
    title: "Экскурсии",
    description: "Необычные экскурсии от местных жителей",
    icon: Compass,
    href: "/tours",
    image: categoryTours,
  },
  {
    title: "Трансфер",
    description: "Комфортные поездки по лучшим маршрутам",
    icon: Car,
    href: "/transfers",
    image: categoryTransfers,
  },
  {
    title: "Билеты",
    description: "Билеты в музеи, театры и на мероприятия",
    icon: Ticket,
    href: "/tickets",
    image: categoryTickets,
  },
  {
    title: "Жилье",
    description: "Уютное жилье в лучших локациях",
    icon: Home,
    href: "/accommodation",
    image: categoryAccommodation,
  },
];

const featuredTours = [
  {
    id: "1",
    title: "Историческая прогулка по старому городу",
    location: "Прага, Чехия",
    price: 3500,
    rating: 4.98,
    reviews: 124,
    image: tour1,
    category: "Экскурсия",
  },
  {
    id: "2",
    title: "Морское приключение на острова",
    location: "Пхукет, Таиланд",
    price: 5200,
    rating: 4.95,
    reviews: 89,
    image: tour2,
    category: "Экскурсия",
  },
  {
    id: "3",
    title: "Горный треккинг с гидом",
    location: "Шамони, Франция",
    price: 4800,
    rating: 4.92,
    reviews: 156,
    image: tour3,
    category: "Активный отдых",
  },
];

const HomePage = () => {
  return (
    <div className="min-h-screen bg-pattern">
      <Header />

      {/* Hero Section */}
      <section className="relative h-[600px] flex items-center justify-center overflow-hidden">
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{ backgroundImage: `url(${heroImage})` }}
        >
          <div className="absolute inset-0 bg-gradient-to-b from-foreground/40 via-foreground/30 to-background" />
        </div>

        <div className="relative z-10 container mx-auto px-4 text-center animate-fade-in-up">
          <h1 className="text-5xl md:text-6xl font-bold text-white mb-6 drop-shadow-lg">
            Откройте мир с My Super Tour
          </h1>
          <p className="text-xl md:text-2xl text-white/90 mb-12 drop-shadow-md">
            Необычные экскурсии от местных жителей
          </p>
          <div className="flex justify-center">
            <SearchBar variant="hero" />
          </div>
        </div>
      </section>

      {/* Categories Section */}
      <section className="py-20 container mx-auto px-4">
        <div className="mb-12 text-center">
          <h2 className="text-4xl font-bold mb-4 text-foreground">
            Выберите категорию
          </h2>
          <p className="text-lg text-muted-foreground">
            Найдите идеальный опыт для вашего путешествия
          </p>
        </div>
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          {categories.map((category, index) => (
            <div
              key={category.title}
              className="animate-fade-in"
              style={{ animationDelay: `${index * 100}ms` }}
            >
              <CategoryCard {...category} />
            </div>
          ))}
        </div>
      </section>

      {/* Featured Tours Section */}
      <section className="py-20 bg-muted/30">
        <div className="container mx-auto px-4">
          <div className="mb-12 text-center">
            <h2 className="text-4xl font-bold mb-4 text-foreground">
              Популярные экскурсии
            </h2>
            <p className="text-lg text-muted-foreground">
              Лучшие впечатления по отзывам путешественников
            </p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {featuredTours.map((tour, index) => (
              <div
                key={tour.id}
                className="animate-fade-in"
                style={{ animationDelay: `${index * 100}ms` }}
              >
                <TourCard {...tour} />
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Stats Section */}
      <section className="py-20 container mx-auto px-4">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          <div className="glass rounded-3xl p-8 text-center hover-lift hover-glow group">
            <div className="w-16 h-16 rounded-2xl bg-secondary mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-smooth">
              <Users className="h-8 w-8 text-secondary-foreground" />
            </div>
            <div className="text-4xl font-bold text-foreground mb-2">50K+</div>
            <div className="text-muted-foreground">Довольных туристов</div>
          </div>

          <div className="glass rounded-3xl p-8 text-center hover-lift hover-glow group">
            <div className="w-16 h-16 rounded-2xl bg-secondary mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-smooth">
              <Star className="h-8 w-8 text-secondary-foreground" />
            </div>
            <div className="text-4xl font-bold text-foreground mb-2">4.9</div>
            <div className="text-muted-foreground">Средний рейтинг</div>
          </div>

          <div className="glass rounded-3xl p-8 text-center hover-lift hover-glow group">
            <div className="w-16 h-16 rounded-2xl bg-secondary mx-auto mb-4 flex items-center justify-center group-hover:scale-110 transition-smooth">
              <Award className="h-8 w-8 text-secondary-foreground" />
            </div>
            <div className="text-4xl font-bold text-foreground mb-2">920</div>
            <div className="text-muted-foreground">Городов по всему миру</div>
          </div>
        </div>
      </section>

      {/* CTA Section */}
      <section className="py-20 container mx-auto px-4">
        <div className="glass-strong rounded-3xl p-12 md:p-16 text-center shadow-glass relative overflow-hidden">
          <div className="absolute inset-0 bg-dots opacity-30" />
          <div className="relative z-10">
            <h2 className="text-4xl md:text-5xl font-bold mb-4 text-foreground">
              Готовы начать свое приключение?
            </h2>
            <p className="text-lg md:text-xl text-muted-foreground mb-8 max-w-2xl mx-auto">
              Присоединяйтесь к тысячам путешественников, открывающих мир вместе с нами
            </p>
            <div className="flex justify-center mb-6">
              <SearchBar variant="hero" />
            </div>
            <Button
              size="lg"
              variant="outline"
              className="rounded-xl border-primary text-primary hover:bg-primary hover:text-primary-foreground transition-smooth"
            >
              Посмотреть все экскурсии
            </Button>
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default HomePage;
