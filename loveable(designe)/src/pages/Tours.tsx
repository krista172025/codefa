import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { FloatingGlassOrbs } from "@/components/FloatingGlassOrbs";
import { SearchBar } from "@/components/SearchBar";
import { TourCard } from "@/components/TourCard";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { SlidersHorizontal } from "lucide-react";
import { useState } from "react";
import tour1 from "@/assets/tour-1.jpg";
import tour2 from "@/assets/tour-2.jpg";
import tour3 from "@/assets/tour-3.jpg";
const categories = [{
  id: "all",
  label: "Все",
  count: 1307
}, {
  id: "tretyakov",
  label: "Третьяковская галерея",
  count: 29
}, {
  id: "gastro",
  label: "Гастрономические",
  count: 50
}, {
  id: "kremlin",
  label: "Московский Кремль",
  count: 84
}, {
  id: "sightseeing",
  label: "Обзорные",
  count: 49
}, {
  id: "city",
  label: "За городом",
  count: 114
}, {
  id: "kids",
  label: "Для детей",
  count: 169
}, {
  id: "masterclass",
  label: "Мастер-классы",
  count: 23
}, {
  id: "moscow-city",
  label: "«Москва-Сити»",
  count: 26
}, {
  id: "mystical",
  label: "Мистические",
  count: 37
}, {
  id: "october",
  label: "«Красный Октябрь»",
  count: 15
}];
const tours = [{
  id: "1",
  title: "Историческая прогулка по старому городу",
  location: "Прага, Чехия",
  price: 3500,
  rating: 4.98,
  reviews: 124,
  image: tour1,
  category: "Пешая экскурсия"
}, {
  id: "2",
  title: "Морское приключение на острова",
  location: "Пхукет, Таиланд",
  price: 5200,
  rating: 4.95,
  reviews: 89,
  image: tour2,
  category: "Водная экскурсия"
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
}, {
  id: "5",
  title: "Закат на яхте",
  location: "Санторини, Греция",
  price: 6500,
  rating: 4.99,
  reviews: 87,
  image: tour2,
  category: "Романтическое"
}, {
  id: "6",
  title: "Культурный тур по музеям",
  location: "Париж, Франция",
  price: 3800,
  rating: 4.94,
  reviews: 145,
  image: tour3,
  category: "Культура"
}];
const ToursPage = () => {
  const [selectedCategory, setSelectedCategory] = useState("all");
  return <div className="min-h-screen bg-warm">
      <Header />

      {/* Background Effects */}
      <FloatingGlassOrbs />

      {/* Hero Section with Search and Filters */}
      <section className="relative pt-32 pb-16 px-6 overflow-hidden">
        {/* Grainy Gradient Background */}
        <div className="absolute inset-0" style={{
        background: `
              radial-gradient(circle at 30% 40%, hsl(var(--primary) / 0.12) 0%, transparent 50%),
              radial-gradient(circle at 70% 60%, hsl(var(--secondary) / 0.12) 0%, transparent 50%),
              linear-gradient(135deg, hsl(var(--background)) 0%, hsl(var(--primary) / 0.05) 100%)
            `
      }}>
          <div className="absolute inset-0 opacity-15" style={{
          backgroundImage: `url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='3.8' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E")`,
          mixBlendMode: 'overlay'
        }} />
        </div>
        
        <div className="max-w-7xl mx-auto relative z-10 space-y-8">
          {/* Page Title */}
          <div className="text-center space-y-4 mb-8">
            <h1 className="text-4xl md:text-5xl font-bold text-foreground">
              Найдите свою <span className="text-primary">идеальную экскурсию</span>
            </h1>
            <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
              Более {tours.length} экскурсий по самым интересным местам
            </p>
          </div>
          
          {/* Search Bar */}
          
          
          {/* Filter Pills */}
          <div className="space-y-4">
            <div className="flex items-center justify-between flex-wrap gap-4">
              <p className="text-muted-foreground text-sm">
                Найдено <span className="font-semibold text-foreground">{tours.length}</span> экскурсий
              </p>
              <Button variant="outline" className="glass-liquid gap-2 hover:glass-hover-trust transition-all duration-300">
                <SlidersHorizontal className="h-4 w-4" />
                Фильтры
              </Button>
            </div>
            
            <div className="space-y-3">
              <h3 className="text-sm font-semibold text-foreground uppercase tracking-wide">Рубрики</h3>
              <div className="flex flex-wrap gap-2">
                {categories.map(category => <Badge key={category.id} variant={selectedCategory === category.id ? "default" : "outline"} className={`
                      px-4 py-2.5 cursor-pointer transition-all duration-300 text-sm font-medium
                      ${selectedCategory === category.id ? 'bg-primary text-primary-foreground shadow-lg scale-105 border-primary' : 'glass-liquid hover:scale-105 hover:shadow-md hover:bg-primary/10 hover:border-primary/30'}
                    `} onClick={() => setSelectedCategory(category.id)}>
                    {category.label}
                    <span className={`ml-2 px-2 py-0.5 rounded-full text-xs font-semibold ${selectedCategory === category.id ? 'bg-primary-foreground/20 text-primary-foreground' : 'bg-muted text-muted-foreground'}`}>
                      {category.count}
                    </span>
                  </Badge>)}
              </div>
            </div>
          </div>
        </div>
      </section>

      {/* Tours Grid */}
      <section className="pb-20 container mx-auto px-4">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {tours.map((tour, index) => <div key={tour.id} className="animate-fade-in" style={{
          animationDelay: `${index * 50}ms`
        }}>
              <TourCard {...tour} />
            </div>)}
        </div>
      </section>

      <Footer />
    </div>;
};
export default ToursPage;