import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { FloatingGlassOrbs } from "@/components/FloatingGlassOrbs";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Star, ThumbsUp } from "lucide-react";
import reviewPrague from "@/assets/review-photo-prague.jpg";
import reviewIslands from "@/assets/review-photo-islands.jpg";
import reviewMountains from "@/assets/review-photo-mountains.jpg";

const reviews = [
  {
    id: "1",
    author: "Анна Смирнова",
    avatar: "АС",
    photo: reviewPrague,
    rating: 5,
    date: "2 дня назад",
    city: "Прага",
    tour: "Историческая прогулка по старому городу",
    text: "Потрясающая экскурсия! Гид Мария невероятно знающая и харизматичная. Узнали столько интересных фактов о Праге, которые не найдешь в путеводителях. Время пролетело незаметно. Однозначно рекомендую!",
    likes: 24,
    helpful: true,
  },
  {
    id: "2",
    author: "Дмитрий Ковалев",
    avatar: "ДК",
    photo: reviewIslands,
    rating: 5,
    date: "5 дней назад",
    city: "Пхукет",
    tour: "Морское приключение на острова",
    text: "Лучший день нашего отпуска! Кристально чистая вода, профессиональная команда, вкусный обед на борту. Особенно понравилась остановка у коралловых рифов. Спасибо за незабываемые впечатления!",
    likes: 18,
    helpful: false,
  },
  {
    id: "3",
    author: "Елена Петрова",
    avatar: "ЕП",
    photo: reviewMountains,
    rating: 5,
    date: "1 неделю назад",
    city: "Рим",
    tour: "Гастрономический тур по городу",
    text: "Восхитительное путешествие по кулинарным традициям Италии! Попробовали аутентичные блюда, познакомились с местными производителями. Гид поделился семейными рецептами. Вернулись сытыми и счастливыми!",
    likes: 31,
    helpful: true,
  },
];

const ReviewsPage = () => {
  return (
    <div className="min-h-screen bg-warm">
      <Header />

      <div className="relative pt-32 pb-20 overflow-hidden">
        <FloatingGlassOrbs />
        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center mb-12 animate-fade-in-up">
            <h1 className="text-5xl font-bold mb-4 text-foreground">Отзывы</h1>
            <p className="text-xl text-muted-foreground mb-8">
              Что говорят наши путешественники
            </p>
            <div className="flex items-center justify-center gap-8 glass-liquid-strong rounded-2xl p-6 max-w-2xl mx-auto">
              <div className="text-center">
                <div className="text-4xl font-bold text-foreground mb-1">4.9</div>
                <div className="flex items-center gap-1 justify-center mb-1">
                  {[...Array(5)].map((_, i) => (
                    <Star key={i} className="h-4 w-4 fill-primary text-primary" />
                  ))}
                </div>
                <div className="text-sm text-muted-foreground">Средний рейтинг</div>
              </div>
              <div className="h-12 w-px bg-border" />
              <div className="text-center">
                <div className="text-4xl font-bold text-foreground mb-1">2,487</div>
                <div className="text-sm text-muted-foreground">Всего отзывов</div>
              </div>
            </div>
          </div>

          <div className="max-w-4xl mx-auto space-y-6 mb-12">
            {reviews.map((review, index) => (
              <div
                key={review.id}
                className="glass-liquid rounded-2xl overflow-hidden hover-lift-gentle hover:glass-hover-trust transition-all duration-300 animate-fade-in"
                style={{ animationDelay: `${index * 100}ms` }}
              >
                <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                  {/* Photo */}
                  <div className="md:col-span-1">
                    <img
                      src={review.photo}
                      alt={review.tour}
                      className="w-full h-64 md:h-full object-cover rounded-lg"
                    />
                  </div>

                  {/* Content */}
                  <div className="md:col-span-2 p-6">
                    <div className="flex items-start justify-between mb-4">
                      <div className="flex items-start gap-4">
                        <Avatar className="w-12 h-12">
                          <AvatarImage src="" />
                          <AvatarFallback className="bg-primary text-white">
                            {review.avatar}
                          </AvatarFallback>
                        </Avatar>
                        <div>
                          <h3 className="font-semibold text-foreground">
                            {review.author}
                          </h3>
                          <p className="text-sm text-muted-foreground">{review.date}</p>
                        </div>
                      </div>
                      <div className="flex items-center gap-1">
                        {[...Array(review.rating)].map((_, i) => (
                          <Star key={i} className="h-4 w-4 fill-primary text-primary" />
                        ))}
                      </div>
                    </div>

                    <div className="mb-4 p-3 glass-liquid rounded-xl">
                      <div className="text-xs font-medium text-muted-foreground mb-1">Город:</div>
                      <div className="text-sm font-semibold text-primary mb-2">{review.city}</div>
                      <div className="text-xs font-medium text-muted-foreground mb-1">Экскурсия:</div>
                      <Badge className="bg-secondary/20 text-secondary-foreground border-0">
                        {review.tour}
                      </Badge>
                    </div>

                    <p className="text-foreground leading-relaxed mb-4">{review.text}</p>

                    <div className="flex items-center gap-4 pt-4 border-t border-border/50">
                      <Button
                        variant="ghost"
                        size="sm"
                        className="gap-2 hover:bg-primary/10 hover:text-primary transition-smooth"
                      >
                        <ThumbsUp className="h-4 w-4" />
                        <span>Полезно ({review.likes})</span>
                      </Button>
                    </div>
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Trust Banners */}
          <div className="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
            <div className="glass-liquid-strong rounded-2xl p-8 text-center hover-lift-gentle transition-all duration-300">
              <div className="text-5xl mb-4">🛫</div>
              <h3 className="text-2xl font-bold text-foreground mb-2">Tripster</h3>
              <div className="flex items-center justify-center gap-2 mb-3">
                {[...Array(5)].map((_, i) => (
                  <Star key={i} className="h-5 w-5 fill-primary text-primary" />
                ))}
              </div>
              <p className="text-muted-foreground">Рейтинг 4.9 из 5</p>
              <p className="text-sm text-muted-foreground mt-1">На основе 2,487 отзывов</p>
            </div>

            <div className="glass-liquid-strong rounded-2xl p-8 text-center hover-lift-gentle transition-all duration-300">
              <div className="text-5xl mb-4">🔍</div>
              <h3 className="text-2xl font-bold text-foreground mb-2">Google Reviews</h3>
              <div className="flex items-center justify-center gap-2 mb-3">
                {[...Array(5)].map((_, i) => (
                  <Star key={i} className="h-5 w-5 fill-primary text-primary" />
                ))}
              </div>
              <p className="text-muted-foreground">Рейтинг 4.8 из 5</p>
              <p className="text-sm text-muted-foreground mt-1">На основе 1,234 отзывов</p>
            </div>
          </div>

          <div className="text-center mt-12">
            <Button
              size="lg"
              variant="outline"
              className="rounded-xl border-primary text-primary hover:bg-primary hover:text-primary-foreground transition-smooth"
            >
              Загрузить еще
            </Button>
          </div>
        </div>
      </div>

      <Footer />
    </div>
  );
};

export default ReviewsPage;
