import { useParams } from "react-router-dom";
import { Star, MapPin, Languages, Award, Calendar, Heart, Share2 } from "lucide-react";
import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { Button } from "@/components/ui/button";
import { Card, CardContent } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { FloatingGlassOrbs } from "@/components/FloatingGlassOrbs";

// Mock data for guides
const guidesData = {
  "1": {
    id: "1",
    name: "Мария Иванова",
    avatar: "https://api.dicebear.com/7.x/avataaars/svg?seed=Maria",
    location: "Санкт-Петербург",
    rating: 4.9,
    reviews: 127,
    languages: ["Русский", "Английский", "Французский"],
    specialties: ["Исторические туры", "Музеи", "Архитектура"],
    experience: 8,
    toursCount: 234,
    bio: "Профессиональный гид с 8-летним опытом. Специализируюсь на исторических турах по Санкт-Петербургу. Влюблена в архитектуру и историю своего города. Каждая экскурсия - это увлекательное путешествие во времени.",
    achievements: [
      "Лучший гид 2023 года",
      "Сертифицированный историк",
      "500+ довольных туристов"
    ],
    tourHighlights: [
      {
        title: "Эрмитаж: Шедевры",
        duration: "3 часа",
        price: "от 3500₽",
        rating: 5.0,
        image: "https://images.unsplash.com/photo-1581016231025-e1d37735454e?w=400"
      },
      {
        title: "Ночной Петербург",
        duration: "2.5 часа",
        price: "от 2800₽",
        rating: 4.9,
        image: "https://images.unsplash.com/photo-1592774493653-f7b28f0eb474?w=400"
      },
      {
        title: "Царское Село",
        duration: "5 часов",
        price: "от 5000₽",
        rating: 5.0,
        image: "https://images.unsplash.com/photo-1576074203094-e0e7f3c9e4f8?w=400"
      }
    ],
    testimonials: [
      {
        author: "Анна С.",
        rating: 5,
        text: "Невероятная экскурсия! Мария рассказала так много интересного об истории города. Рекомендую!",
        date: "2 дня назад"
      },
      {
        author: "Джон М.",
        rating: 5,
        text: "Best tour guide in St. Petersburg! Very knowledgeable and friendly. Her English is perfect.",
        date: "1 неделю назад"
      },
      {
        author: "Елена К.",
        rating: 5,
        text: "Профессионал своего дела. Время пролетело незаметно. Обязательно вернемся на другие туры!",
        date: "2 недели назад"
      }
    ]
  },
  "2": {
    id: "2",
    name: "Дмитрий Соколов",
    avatar: "https://api.dicebear.com/7.x/avataaars/svg?seed=Dmitry",
    location: "Москва",
    rating: 4.8,
    reviews: 98,
    languages: ["Русский", "Английский", "Немецкий"],
    specialties: ["Кремль", "Советская история", "Современное искусство"],
    experience: 6,
    toursCount: 189,
    bio: "Историк и москвовед с глубокими знаниями о столице. Провожу увлекательные туры по Кремлю, Красной площади и современным арт-пространствам. Умею находить подход к любой аудитории.",
    achievements: [
      "Аккредитованный гид Кремля",
      "Диплом историка МГУ",
      "Автор туров"
    ],
    tourHighlights: [
      {
        title: "Кремль и Красная площадь",
        duration: "3 часа",
        price: "от 3000₽",
        rating: 4.8,
        image: "https://images.unsplash.com/photo-1513326738677-b964603b136d?w=400"
      }
    ],
    testimonials: [
      {
        author: "Михаил П.",
        rating: 5,
        text: "Отличный рассказчик! Узнал много нового о Москве.",
        date: "5 дней назад"
      }
    ]
  }
};

export default function GuideProfile() {
  const { id } = useParams();
  const guide = guidesData[id as keyof typeof guidesData] || guidesData["1"];

  return (
    <div className="min-h-screen bg-background relative overflow-hidden">
      <FloatingGlassOrbs />
      <Header />
      
      <main className="relative z-10">
        {/* Hero Section */}
        <section className="pt-24 pb-12 px-4">
          <div className="container mx-auto max-w-6xl">
            <Card className="glass-liquid overflow-hidden border-border/20">
              <CardContent className="p-0">
                <div className="grid md:grid-cols-3 gap-0">
                  {/* Left: Avatar & Quick Info */}
                  <div className="md:col-span-1 bg-accent/5 p-8 flex flex-col items-center text-center border-r border-border/10">
                    <Avatar className="w-32 h-32 mb-4 ring-4 ring-primary/20 hover-lift-gentle transition-smooth">
                      <AvatarImage src={guide.avatar} alt={guide.name} />
                      <AvatarFallback>{guide.name[0]}</AvatarFallback>
                    </Avatar>
                    
                    <h1 className="text-2xl font-bold mb-1">{guide.name}</h1>
                    
                    <div className="flex items-center gap-1 text-muted-foreground mb-4">
                      <MapPin className="w-4 h-4" />
                      <span className="text-sm">{guide.location}</span>
                    </div>
                    
                    <div className="flex items-center gap-1 mb-4">
                      <Star className="w-5 h-5 fill-amber-400 text-amber-400" />
                      <span className="font-semibold text-lg">{guide.rating}</span>
                      <span className="text-muted-foreground text-sm">({guide.reviews} отзывов)</span>
                    </div>

                    <div className="w-full space-y-2 mb-6">
                      <Button className="w-full gap-2 hover-lift-gentle">
                        <Calendar className="w-4 h-4" />
                        Забронировать тур
                      </Button>
                      <div className="flex gap-2">
                        <Button variant="outline" size="icon" className="flex-1">
                          <Heart className="w-4 h-4" />
                        </Button>
                        <Button variant="outline" size="icon" className="flex-1">
                          <Share2 className="w-4 h-4" />
                        </Button>
                      </div>
                    </div>

                    <div className="w-full space-y-3 text-sm">
                      <div className="flex justify-between">
                        <span className="text-muted-foreground">Опыт</span>
                        <span className="font-medium">{guide.experience} лет</span>
                      </div>
                      <div className="flex justify-between">
                        <span className="text-muted-foreground">Туров проведено</span>
                        <span className="font-medium">{guide.toursCount}</span>
                      </div>
                    </div>
                  </div>

                  {/* Right: Detailed Info */}
                  <div className="md:col-span-2 p-8">
                    {/* Languages */}
                    <div className="mb-6">
                      <div className="flex items-center gap-2 mb-3">
                        <Languages className="w-5 h-5 text-primary" />
                        <h3 className="font-semibold">Языки</h3>
                      </div>
                      <div className="flex flex-wrap gap-2">
                        {guide.languages.map((lang) => (
                          <Badge key={lang} variant="secondary" className="badge-warm">
                            {lang}
                          </Badge>
                        ))}
                      </div>
                    </div>

                    {/* Specialties */}
                    <div className="mb-6">
                      <div className="flex items-center gap-2 mb-3">
                        <Award className="w-5 h-5 text-primary" />
                        <h3 className="font-semibold">Специализация</h3>
                      </div>
                      <div className="flex flex-wrap gap-2">
                        {guide.specialties.map((specialty) => (
                          <Badge key={specialty} className="badge-trust">
                            {specialty}
                          </Badge>
                        ))}
                      </div>
                    </div>

                    {/* Bio */}
                    <div className="mb-6">
                      <h3 className="font-semibold mb-3">О гиде</h3>
                      <p className="text-muted-foreground leading-relaxed">
                        {guide.bio}
                      </p>
                    </div>

                    {/* Achievements */}
                    <div>
                      <h3 className="font-semibold mb-3">Достижения</h3>
                      <div className="grid gap-2">
                        {guide.achievements.map((achievement, idx) => (
                          <div
                            key={idx}
                            className="flex items-center gap-2 text-sm bg-accent/30 px-3 py-2 rounded-lg"
                          >
                            <Award className="w-4 h-4 text-primary" />
                            <span>{achievement}</span>
                          </div>
                        ))}
                      </div>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>
        </section>

        {/* Tour Highlights */}
        <section className="py-12 px-4">
          <div className="container mx-auto max-w-6xl">
            <h2 className="text-3xl font-bold mb-8">Популярные туры</h2>
            <div className="grid md:grid-cols-3 gap-6">
              {guide.tourHighlights.map((tour, idx) => (
                <Card
                  key={idx}
                  className="glass-liquid overflow-hidden hover-lift-gentle transition-smooth cursor-pointer group"
                >
                  <div className="aspect-video overflow-hidden">
                    <img
                      src={tour.image}
                      alt={tour.title}
                      className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                    />
                  </div>
                  <CardContent className="p-4">
                    <div className="flex items-start justify-between mb-2">
                      <h3 className="font-semibold flex-1">{tour.title}</h3>
                      <div className="flex items-center gap-1 text-sm">
                        <Star className="w-4 h-4 fill-amber-400 text-amber-400" />
                        <span>{tour.rating}</span>
                      </div>
                    </div>
                    <div className="flex items-center justify-between text-sm text-muted-foreground">
                      <span>{tour.duration}</span>
                      <span className="font-semibold text-foreground">{tour.price}</span>
                    </div>
                  </CardContent>
                </Card>
              ))}
            </div>
          </div>
        </section>

        {/* Testimonials */}
        <section className="py-12 px-4 bg-accent/5">
          <div className="container mx-auto max-w-6xl">
            <h2 className="text-3xl font-bold mb-8">Отзывы туристов</h2>
            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
              {guide.testimonials.map((testimonial, idx) => (
                <Card key={idx} className="card-warm hover-lift-gentle transition-smooth">
                  <CardContent className="p-6">
                    <div className="flex items-center justify-between mb-3">
                      <span className="font-semibold">{testimonial.author}</span>
                      <div className="flex items-center gap-1">
                        {[...Array(testimonial.rating)].map((_, i) => (
                          <Star key={i} className="w-4 h-4 fill-amber-400 text-amber-400" />
                        ))}
                      </div>
                    </div>
                    <p className="text-muted-foreground text-sm mb-3 leading-relaxed">
                      {testimonial.text}
                    </p>
                    <span className="text-xs text-muted-foreground">{testimonial.date}</span>
                  </CardContent>
                </Card>
              ))}
            </div>
          </div>
        </section>
      </main>

      <Footer />
    </div>
  );
}
