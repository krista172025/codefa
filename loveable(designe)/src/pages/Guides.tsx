import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Star, MapPin, Languages, Award, Zap, GraduationCap } from "lucide-react";
import { NavLink } from "@/components/NavLink";
import guideMaria from "@/assets/guide-maria.jpg";
import guideAnton from "@/assets/guide-anton.jpg";
import guideElena from "@/assets/guide-elena.jpg";

const guides = [
  {
    id: "1",
    name: "Мария Ковалева",
    avatar: "МК",
    photo: guideMaria,
    location: "Прага, Чехия",
    rating: 4.98,
    reviews: 230,
    tours: 45,
    languages: ["Русский", "Английский", "Чешский"],
    specialties: ["История", "Архитектура", "Культура"],
    verified: true,
    isOurGuide: true,
    academicTitle: null,
  },
  {
    id: "2",
    name: "Антон Смирнов",
    avatar: "АС",
    photo: guideAnton,
    location: "Рим, Италия",
    rating: 4.95,
    reviews: 187,
    tours: 38,
    languages: ["Русский", "Итальянский"],
    specialties: ["Гастрономия", "История"],
    verified: true,
    isOurGuide: false,
    academicTitle: "Доктор наук",
  },
  {
    id: "3",
    name: "Елена Петрова",
    avatar: "ЕП",
    photo: guideElena,
    location: "Барселона, Испания",
    rating: 4.97,
    reviews: 156,
    tours: 32,
    languages: ["Русский", "Испанский", "Английский"],
    specialties: ["Искусство", "Модернизм", "Гауди"],
    verified: true,
    isOurGuide: true,
    academicTitle: null,
  },
];

const GuidesPage = () => {
  return (
    <div className="min-h-screen bg-pattern">
      <Header />

      <div className="pt-32 pb-20">
        <div className="container mx-auto px-4">
          <div className="text-center mb-12 animate-fade-in-up">
            <h1 className="text-5xl font-bold mb-4 text-foreground">Наши гиды</h1>
            <p className="text-xl text-muted-foreground mb-8">
              Профессиональные и сертифицированные экскурсоводы
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
            {guides.map((guide, index) => (
              <div
                key={guide.id}
                className="glass-liquid rounded-2xl overflow-hidden hover-lift hover:glass-hover-trust transition-smooth animate-fade-in"
                style={{ animationDelay: `${index * 100}ms` }}
              >
                {/* Guide Photo */}
                <div className="relative h-64 overflow-hidden">
                  <img
                    src={guide.photo}
                    alt={guide.name}
                    className="w-full h-full object-cover"
                  />
                  <div className="absolute inset-0 bg-gradient-to-t from-background/80 to-transparent" />
                  <div className="absolute bottom-4 left-4 right-4">
                    <h3 className="text-2xl font-bold text-white mb-1">
                      {guide.name}
                    </h3>
                    <div className="flex items-center gap-2 text-white/90">
                      <MapPin className="h-4 w-4" />
                      <span className="text-sm">{guide.location}</span>
                    </div>
                  </div>
                </div>

                <div className="p-6">
                  {/* Badges */}
                  <div className="flex flex-wrap gap-2 mb-4">
                    {guide.verified && (
                      <Badge className="bg-secondary text-secondary-foreground">
                        <Award className="h-3 w-3 mr-1" />
                        Верифицирован
                      </Badge>
                    )}
                    {guide.isOurGuide && (
                      <Badge className="bg-primary text-primary-foreground">
                        <Zap className="h-3 w-3 mr-1" />
                        My Super Tour
                      </Badge>
                    )}
                    {!guide.isOurGuide && (
                      <Badge variant="outline" className="border-primary/50 text-primary">
                        Партнер
                      </Badge>
                    )}
                    {guide.academicTitle && (
                      <Badge className="bg-accent text-accent-foreground">
                        <GraduationCap className="h-3 w-3 mr-1" />
                        {guide.academicTitle}
                      </Badge>
                    )}
                  </div>

                  <div className="space-y-4 mb-6">
                    <div className="flex items-center justify-between text-sm">
                      <span className="text-muted-foreground">Рейтинг</span>
                      <div className="flex items-center gap-1">
                        <Star className="h-4 w-4 fill-primary text-primary" />
                        <span className="font-semibold text-foreground">
                          {guide.rating}
                        </span>
                        <span className="text-muted-foreground">
                          ({guide.reviews})
                        </span>
                      </div>
                    </div>

                    <div className="flex items-center justify-between text-sm">
                      <span className="text-muted-foreground">Экскурсий</span>
                      <span className="font-semibold text-foreground">
                        {guide.tours}
                      </span>
                    </div>

                    <div className="pt-4 border-t border-border/50">
                      <div className="flex items-center gap-2 text-sm text-muted-foreground mb-2">
                        <Languages className="h-4 w-4" />
                        <span>Языки:</span>
                      </div>
                      <div className="flex flex-wrap gap-2">
                        {guide.languages.map((lang) => (
                          <Badge key={lang} variant="outline" className="text-xs">
                            {lang}
                          </Badge>
                        ))}
                      </div>
                    </div>

                    <div>
                      <div className="text-sm text-muted-foreground mb-2">
                        Специализация:
                      </div>
                      <div className="flex flex-wrap gap-2">
                        {guide.specialties.map((spec) => (
                          <Badge
                            key={spec}
                            className="bg-primary/10 text-primary border-0 text-xs"
                          >
                            {spec}
                          </Badge>
                        ))}
                      </div>
                    </div>
                  </div>

                  <Button className="w-full bg-primary hover:shadow-glow" asChild>
                    <NavLink to={`/guide/${guide.id}`}>Посмотреть профиль</NavLink>
                  </Button>
                </div>
              </div>
            ))}
          </div>

          {/* CTA Section */}
          <div className="glass-strong rounded-3xl p-12 text-center max-w-3xl mx-auto">
            <h2 className="text-3xl font-bold mb-4 text-foreground">
              Хотите стать гидом?
            </h2>
            <p className="text-lg text-muted-foreground mb-6">
              Присоединяйтесь к нашей команде профессионалов и делитесь своими знаниями с путешественниками со всего мира
            </p>
            <Button
              size="lg"
              className="bg-secondary text-secondary-foreground hover:bg-secondary/90"
            >
              Подать заявку
            </Button>
          </div>
        </div>
      </div>

      <Footer />
    </div>
  );
};

export default GuidesPage;
