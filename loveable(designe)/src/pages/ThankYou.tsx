import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { FloatingGlassOrbs } from "@/components/FloatingGlassOrbs";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Badge } from "@/components/ui/badge";
import {
  Gift,
  Ticket,
  ShoppingBag,
  Heart,
  Sparkles,
  Check,
  Play,
  Star,
  Award,
  Calendar,
} from "lucide-react";
import { useState } from "react";

const ThankYouPage = () => {
  const [videoPlaying, setVideoPlaying] = useState(false);
  
  // Mock data - in real app this would come from purchase
  const purchaseInfo = {
    orderNumber: "MST-2024-" + Math.floor(Math.random() * 10000),
    tourName: "Историческая прогулка по Парижу",
    date: "15 Декабря 2024",
    guests: 2,
  };

  const benefits = [
    { icon: Gift, text: "Бесплатный путеводитель по городу в подарок", color: "text-secondary" },
    { icon: Award, text: "Скидка 10% на следующее бронирование", color: "text-primary" },
    { icon: Star, text: "+500 бонусных баллов на ваш счет", color: "text-trust" },
    { icon: Calendar, text: "Бесплатная отмена за 24 часа до тура", color: "text-secondary" },
  ];

  const nextSteps = [
    { 
      icon: Ticket, 
      title: "Посмотреть билет", 
      description: "Ваш билет и вся информация",
      link: "/profile/orders",
      color: "from-primary/20 to-primary/5",
      buttonText: "Открыть билет"
    },
    { 
      icon: ShoppingBag, 
      title: "Магазин сувениров", 
      description: "Эксклюзивные товары для путешественников",
      link: "/shop",
      color: "from-secondary/20 to-secondary/5",
      buttonText: "В магазин"
    },
  ];

  return (
    <div className="min-h-screen bg-warm">
      <Header />
      <FloatingGlassOrbs />

      {/* Hero Section - Success Message */}
      <section className="relative pt-32 pb-16 px-6 overflow-hidden">
        {/* Background Gradient */}
        <div
          className="absolute inset-0"
          style={{
            background: `
              radial-gradient(circle at 30% 20%, hsl(var(--primary) / 0.15) 0%, transparent 50%),
              radial-gradient(circle at 70% 80%, hsl(var(--secondary) / 0.15) 0%, transparent 50%),
              linear-gradient(135deg, hsl(var(--background)) 0%, hsl(var(--trust) / 0.05) 100%)
            `,
          }}
        />
        {/* Grain Texture */}
        <div
          className="absolute inset-0 opacity-25"
          style={{
            backgroundImage: `url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='4.2' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E")`,
            mixBlendMode: "soft-light",
          }}
        />

        <div className="max-w-5xl mx-auto relative z-10 text-center">
          {/* Success Animation */}
          <div className="mb-8 inline-flex items-center justify-center w-24 h-24 rounded-full bg-trust/20 glass-liquid-strong animate-scale-in">
            <Check className="h-12 w-12 text-trust animate-gentle-bounce" />
          </div>

          <h1 className="text-4xl sm:text-6xl font-bold text-foreground mb-4 animate-fade-in leading-tight">
            Спасибо за покупку!
          </h1>
          <p className="text-xl sm:text-2xl text-muted-foreground mb-6 animate-fade-in max-w-2xl mx-auto">
            Мы отправили подтверждение на вашу почту. Готовьтесь к незабываемому путешествию!
          </p>

          {/* Order Info Badge */}
          <Badge 
            variant="secondary" 
            className="badge-warm text-base px-6 py-3 mb-12 animate-slide-up"
          >
            <Sparkles className="mr-2 h-4 w-4" />
            Номер заказа: {purchaseInfo.orderNumber}
          </Badge>

          {/* Purchase Summary */}
          <Card className="glass-liquid-strong p-6 sm:p-8 border-0 max-w-2xl mx-auto mb-8 text-left">
            <h3 className="text-lg font-bold text-foreground mb-4 flex items-center gap-2">
              <Ticket className="h-5 w-5 text-primary" />
              Детали бронирования
            </h3>
            <div className="space-y-3">
              <div className="flex justify-between items-center">
                <span className="text-muted-foreground">Экскурсия:</span>
                <span className="font-semibold text-foreground">{purchaseInfo.tourName}</span>
              </div>
              <div className="flex justify-between items-center">
                <span className="text-muted-foreground">Дата:</span>
                <span className="font-semibold text-foreground">{purchaseInfo.date}</span>
              </div>
              <div className="flex justify-between items-center">
                <span className="text-muted-foreground">Гостей:</span>
                <span className="font-semibold text-foreground">{purchaseInfo.guests}</span>
              </div>
            </div>
          </Card>
        </div>
      </section>

      {/* Director's Welcome Video */}
      <section className="py-16 px-6 bg-gradient-to-b from-transparent to-background/50">
        <div className="max-w-5xl mx-auto">
          <div className="text-center mb-8">
            <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-3">
              Приветствие от директора
            </h2>
            <p className="text-lg text-muted-foreground">
              Персональное обращение к вам от основателя My Super Tour
            </p>
          </div>

          <Card className="glass-liquid-strong p-2 sm:p-4 border-0 overflow-hidden">
            <div className="relative aspect-video rounded-xl overflow-hidden bg-muted group cursor-pointer">
              {!videoPlaying ? (
                <>
                  {/* Video Thumbnail */}
                  <div 
                    className="absolute inset-0 bg-gradient-to-br from-primary/40 to-secondary/40 flex items-center justify-center"
                    onClick={() => setVideoPlaying(true)}
                  >
                    <div className="text-center">
                      <div className="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-background/90 backdrop-blur-sm flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform duration-300 shadow-2xl">
                        <Play className="h-10 w-10 sm:h-12 sm:h-12 text-primary ml-2" />
                      </div>
                      <p className="text-background font-bold text-lg sm:text-xl">
                        Смотреть приветствие (1 минута)
                      </p>
                    </div>
                  </div>
                  {/* Director Info */}
                  <div className="absolute bottom-4 left-4 glass-liquid-strong px-4 py-2 rounded-xl">
                    <p className="text-sm font-semibold text-foreground">Александр Петров</p>
                    <p className="text-xs text-muted-foreground">Основатель и Директор</p>
                  </div>
                </>
              ) : (
                <div className="w-full h-full flex items-center justify-center bg-muted">
                  {/* Placeholder for actual video */}
                  <div className="text-center p-8">
                    <Heart className="h-16 w-16 text-primary mx-auto mb-4 animate-gentle-bounce" />
                    <p className="text-lg text-foreground font-semibold mb-2">
                      Спасибо, что выбрали нас!
                    </p>
                    <p className="text-sm text-muted-foreground">
                      Мы гордимся тем, что можем показать вам красоту этого мира. <br/>
                      С уважением, команда My Super Tour
                    </p>
                  </div>
                  {/* In production, replace with actual video embed */}
                  {/* <iframe 
                    className="w-full h-full" 
                    src="YOUR_VIDEO_URL" 
                    allow="autoplay; encrypted-media" 
                    allowFullScreen
                  /> */}
                </div>
              )}
            </div>
          </Card>
        </div>
      </section>

      {/* Benefits Section */}
      <section className="py-16 px-6">
        <div className="max-w-5xl mx-auto">
          <div className="text-center mb-12">
            <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-3">
              Ваши бонусы и подарки
            </h2>
            <p className="text-lg text-muted-foreground">
              Специально для вас мы подготовили приятные сюрпризы
            </p>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-12">
            {benefits.map((benefit, index) => {
              const Icon = benefit.icon;
              return (
                <Card 
                  key={index}
                  className="glass-liquid-strong p-6 border-0 hover-lift-gentle transition-smooth"
                >
                  <div className="flex items-start gap-4">
                    <div className="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary/20 to-secondary/10 flex items-center justify-center flex-shrink-0">
                      <Icon className={`h-6 w-6 ${benefit.color}`} />
                    </div>
                    <p className="text-foreground font-medium leading-relaxed pt-2">
                      {benefit.text}
                    </p>
                  </div>
                </Card>
              );
            })}
          </div>
        </div>
      </section>

      {/* Next Steps Section */}
      <section className="py-16 px-6 bg-gradient-to-b from-background/50 to-transparent">
        <div className="max-w-6xl mx-auto">
          <div className="text-center mb-12">
            <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-3">
              Что дальше?
            </h2>
            <p className="text-lg text-muted-foreground">
              Выберите следующий шаг вашего путешествия
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
            {nextSteps.map((step, index) => {
              const Icon = step.icon;
              return (
                <Card 
                  key={index}
                  className="glass-liquid-strong p-8 border-0 group hover:shadow-2xl transition-all duration-300"
                >
                  <div 
                    className={`w-16 h-16 rounded-2xl bg-gradient-to-br ${step.color} flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300`}
                  >
                    <Icon className="h-8 w-8 text-primary" />
                  </div>
                  <h3 className="text-2xl font-bold text-foreground mb-3">
                    {step.title}
                  </h3>
                  <p className="text-muted-foreground mb-6 leading-relaxed">
                    {step.description}
                  </p>
                  <Button 
                    asChild
                    size="lg"
                    className="w-full bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg"
                  >
                    <a href={step.link}>
                      {step.buttonText}
                    </a>
                  </Button>
                </Card>
              );
            })}
          </div>
        </div>
      </section>

      {/* Social Proof / Reviews Teaser */}
      <section className="py-16 px-6">
        <div className="max-w-4xl mx-auto">
          <Card className="glass-liquid-strong p-8 sm:p-12 border-0 text-center">
            <Sparkles className="h-12 w-12 text-secondary mx-auto mb-6 animate-gentle-bounce" />
            <h3 className="text-2xl sm:text-3xl font-bold text-foreground mb-4">
              Поделитесь впечатлениями после поездки!
            </h3>
            <p className="text-lg text-muted-foreground mb-6 max-w-2xl mx-auto leading-relaxed">
              Ваш отзыв поможет другим путешественникам сделать правильный выбор, 
              а мы дарим дополнительные бонусы за каждый отзыв с фото
            </p>
            <div className="flex flex-wrap gap-4 justify-center">
              <Badge variant="secondary" className="badge-warm text-base px-4 py-2">
                <Star className="mr-2 h-4 w-4" />
                +100 бонусов за отзыв
              </Badge>
              <Badge variant="secondary" className="badge-warm text-base px-4 py-2">
                <Gift className="mr-2 h-4 w-4" />
                +200 бонусов с фото
              </Badge>
            </div>
          </Card>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default ThankYouPage;
