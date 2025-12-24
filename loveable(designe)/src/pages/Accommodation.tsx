import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { FloatingGlassOrbs } from "@/components/FloatingGlassOrbs";
import { SearchBar } from "@/components/SearchBar";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { SlidersHorizontal, Star, Wifi, Coffee, Tv, Wind } from "lucide-react";
import { NavLink } from "@/components/NavLink";
import categoryAccommodation from "@/assets/category-accommodation.jpg";

const accommodations = [
  {
    id: "1",
    title: "Роскошные апартаменты с видом на море",
    location: "Барселона, Испания",
    price: 12000,
    rating: 4.95,
    reviews: 87,
    image: categoryAccommodation,
    amenities: ["Wifi", "Кондиционер", "Кухня", "Балкон"],
  },
  {
    id: "2",
    title: "Уютная студия в центре города",
    location: "Прага, Чехия",
    price: 6500,
    rating: 4.88,
    reviews: 124,
    image: categoryAccommodation,
    amenities: ["Wifi", "TV", "Кофемашина"],
  },
  {
    id: "3",
    title: "Современный лофт с панорамными окнами",
    location: "Милан, Италия",
    price: 15000,
    rating: 4.92,
    reviews: 65,
    image: categoryAccommodation,
    amenities: ["Wifi", "Кондиционер", "Терраса", "Парковка"],
  },
];

const AccommodationPage = () => {
  return (
    <div className="min-h-screen bg-warm">
      <Header />

      <section className="relative pt-32 pb-12 container mx-auto px-4 overflow-hidden">
        <FloatingGlassOrbs />
        <div className="max-w-3xl mx-auto text-center mb-8 animate-fade-in-up relative z-10">
          <h1 className="text-5xl font-bold mb-4 text-foreground">Жилье</h1>
          <p className="text-xl text-muted-foreground mb-8">
            Уютное жилье в лучших локациях мира
          </p>
          <SearchBar variant="compact" />
        </div>

        <div className="flex items-center justify-between mb-8">
          <p className="text-muted-foreground">
            Найдено <span className="font-semibold text-foreground">{accommodations.length}</span> вариантов
          </p>
          <Button variant="outline" className="glass-liquid gap-2 hover:glass-hover-trust transition-all duration-300">
            <SlidersHorizontal className="h-4 w-4" />
            Фильтры
          </Button>
        </div>
      </section>

      <section className="pb-20 container mx-auto px-4">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {accommodations.map((item, index) => (
            <NavLink
              key={item.id}
              to={`/accommodation/${item.id}`}
              className="group glass-liquid-strong rounded-2xl overflow-hidden hover-lift-gentle hover:glass-hover-trust transition-all duration-300 animate-fade-in"
              style={{ animationDelay: `${index * 50}ms` }}
            >
              <div className="relative h-64 overflow-hidden">
                <img
                  src={item.image}
                  alt={item.title}
                  className="w-full h-full object-cover transition-smooth group-hover:scale-110"
                />
                <Badge className="absolute top-4 left-4 bg-secondary text-secondary-foreground">
                  Суперхозяин
                </Badge>
              </div>

              <div className="p-6">
                <h3 className="text-lg font-semibold mb-2 text-foreground group-hover:text-primary transition-smooth line-clamp-2">
                  {item.title}
                </h3>
                <p className="text-muted-foreground text-sm mb-3">{item.location}</p>

                <div className="flex flex-wrap gap-2 mb-4">
                  {item.amenities.slice(0, 3).map((amenity) => (
                    <Badge key={amenity} variant="outline" className="text-xs">
                      {amenity}
                    </Badge>
                  ))}
                </div>

                <div className="flex items-center justify-between">
                  <div className="flex items-center gap-1">
                    <Star className="h-4 w-4 fill-primary text-primary" />
                    <span className="font-medium text-foreground">{item.rating}</span>
                    <span className="text-muted-foreground text-sm">({item.reviews})</span>
                  </div>
                  <div className="text-right">
                    <div className="text-xl font-bold text-foreground">
                      {item.price.toLocaleString()}₽
                    </div>
                    <div className="text-xs text-muted-foreground">за ночь</div>
                  </div>
                </div>
              </div>
            </NavLink>
          ))}
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default AccommodationPage;
