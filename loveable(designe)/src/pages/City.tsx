import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { FloatingGlassOrbs } from "@/components/FloatingGlassOrbs";
import { CityImageExporter } from "@/components/CityImageExporter";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { Card } from "@/components/ui/card";
import { useState } from "react";
import {
  Compass,
  Ticket,
  Home,
  Car,
  MapPin,
  Users,
  Clock,
  Trophy,
  CheckCircle2,
  XCircle,
} from "lucide-react";
import landmarksHeroParis from "@/assets/landmarks-hero-paris.jpg";

const cityData = {
  name: "Paris",
  country: "France",
  description: "Город любви и света, столица моды и искусства. Париж очаровывает своей архитектурой, богатой историей и неповторимой атмосферой. От Эйфелевой башни до уютных кафе на Монмартре - каждый уголок города дышит романтикой и вдохновением.",
  stats: [
    { label: "Население", value: "2.1М", icon: Users },
    { label: "Часовой пояс", value: "UTC+1", icon: Clock },
    { label: "Лучшее время", value: "Апр-Окт", icon: MapPin },
  ],
  image: landmarksHeroParis,
};

const quizQuestions = [
  {
    question: "В каком году была построена Эйфелева башня?",
    options: ["1889", "1900", "1878", "1901"],
    correct: 0,
  },
  {
    question: "Какой музей является самым посещаемым в Париже?",
    options: ["Музей Орсе", "Лувр", "Центр Помпиду", "Музей Родена"],
    correct: 1,
  },
  {
    question: "Сколько районов (округов) в Париже?",
    options: ["15", "18", "20", "25"],
    correct: 2,
  },
  {
    question: "Как называется знаменитый собор в Париже?",
    options: ["Сакре-Кёр", "Сент-Шапель", "Нотр-Дам де Пари", "Пантеон"],
    correct: 2,
  },
  {
    question: "Какая река протекает через Париж?",
    options: ["Луара", "Сена", "Рона", "Гаронна"],
    correct: 1,
  },
];

const categories = [
  {
    title: "Экскурсии",
    description: "Необычные маршруты по Парижу",
    icon: Compass,
    href: "/tours",
    count: "127+",
    gradient: "from-primary/20 to-primary/5",
  },
  {
    title: "Билеты",
    description: "Музеи, театры и достопримечательности",
    icon: Ticket,
    href: "/tickets",
    count: "89+",
    gradient: "from-secondary/20 to-secondary/5",
  },
  {
    title: "Жилье",
    description: "Уютные апартаменты в центре",
    icon: Home,
    href: "/accommodation",
    count: "234+",
    gradient: "from-trust/20 to-trust/5",
  },
  {
    title: "Трансфер",
    description: "Комфортные поездки по городу",
    icon: Car,
    href: "/transfers",
    count: "45+",
    gradient: "from-primary/20 to-primary/5",
  },
];

const CityPage = () => {
  const [quizStarted, setQuizStarted] = useState(false);
  const [currentQuestion, setCurrentQuestion] = useState(0);
  const [selectedAnswer, setSelectedAnswer] = useState<number | null>(null);
  const [score, setScore] = useState(0);
  const [showResult, setShowResult] = useState(false);
  const [answers, setAnswers] = useState<boolean[]>([]);

  const handleStartQuiz = () => {
    setQuizStarted(true);
    setCurrentQuestion(0);
    setScore(0);
    setShowResult(false);
    setAnswers([]);
    setSelectedAnswer(null);
  };

  const handleAnswerClick = (index: number) => {
    if (selectedAnswer !== null) return; // Already answered

    setSelectedAnswer(index);
    const isCorrect = index === quizQuestions[currentQuestion].correct;
    setAnswers([...answers, isCorrect]);

    if (isCorrect) {
      setScore(score + 1);
    }

    // Move to next question after 1.5s
    setTimeout(() => {
      if (currentQuestion < quizQuestions.length - 1) {
        setCurrentQuestion(currentQuestion + 1);
        setSelectedAnswer(null);
      } else {
        setShowResult(true);
      }
    }, 1500);
  };

  const getScoreMessage = () => {
    const percentage = (score / quizQuestions.length) * 100;
    if (percentage === 100) return "Превосходно! Вы настоящий знаток Парижа! 🏆";
    if (percentage >= 80) return "Отлично! Вы хорошо знаете Париж! 🌟";
    if (percentage >= 60) return "Хорошо! Но есть куда расти 👍";
    if (percentage >= 40) return "Неплохо! Стоит узнать город получше 📚";
    return "Время открыть Париж заново! 🗼";
  };

  return (
    <div className="min-h-screen bg-warm">
      <Header />
      <FloatingGlassOrbs />

      {/* Hero Section - City Overview with Photo Banner */}
      <section className="relative pt-32 pb-16 px-6 overflow-hidden">
        {/* Beautiful City Photo Banner */}
        <div
          className="absolute inset-0 bg-cover bg-center"
          style={{ backgroundImage: `url(${cityData.image})` }}
        />
        {/* Gradient Overlay */}
        <div
          className="absolute inset-0"
          style={{
            background: `
              linear-gradient(135deg, 
                hsl(var(--background) / 0.95) 0%, 
                hsl(var(--background) / 0.85) 30%,
                hsl(var(--primary) / 0.3) 70%,
                hsl(var(--secondary) / 0.2) 100%
              )
            `,
          }}
        />
        {/* Grain Texture */}
        <div
          className="absolute inset-0 opacity-35"
          style={{
            backgroundImage: `url("data:image/svg+xml,%3Csvg viewBox='0 0 400 400' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='4.8' numOctaves='5' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E")`,
            mixBlendMode: "soft-light",
          }}
        />

        <div className="max-w-6xl mx-auto relative z-10">
          <div className="glass-liquid-strong p-8 sm:p-12 rounded-3xl shadow-2xl">
            <div className="flex items-center gap-3 mb-6">
              <MapPin className="h-8 w-8 text-primary" />
              <div>
                <h1 className="text-4xl sm:text-5xl font-bold text-foreground">
                  {cityData.name}
                </h1>
                <p className="text-lg text-muted-foreground">{cityData.country}</p>
              </div>
            </div>

            <p className="text-lg text-muted-foreground leading-relaxed mb-8 max-w-3xl">
              {cityData.description}
            </p>

            {/* City Stats */}
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
              {cityData.stats.map((stat, index) => {
                const Icon = stat.icon;
                return (
                  <div
                    key={index}
                    className="glass-liquid p-4 rounded-xl text-center hover-lift-gentle transition-smooth"
                  >
                    <Icon className="h-6 w-6 text-primary mx-auto mb-2" />
                    <div className="text-2xl font-bold text-foreground mb-1">
                      {stat.value}
                    </div>
                    <div className="text-sm text-muted-foreground">{stat.label}</div>
                  </div>
                );
              })}
            </div>

            {/* Action Buttons */}
            {!quizStarted && (
              <div className="flex flex-col sm:flex-row gap-4">
                <Button
                  onClick={handleStartQuiz}
                  size="lg"
                  className="flex-1 sm:flex-initial px-8 py-6 text-lg font-bold bg-primary hover:bg-primary/90 text-primary-foreground shadow-2xl hover:scale-105 transition-all duration-300 border-0"
                >
                  <Trophy className="mr-2 h-6 w-6" />
                  Насколько хорошо вы знаете {cityData.name}? Пройти тест
                </Button>
                
                <CityImageExporter 
                  cityName={cityData.name}
                  defaultLandmarks={["Эйфелева башня", "Нотр-Дам де Пари", "Лувр", "Триумфальная арка"]}
                />
              </div>
            )}
          </div>
        </div>
      </section>

      {/* Quiz Section */}
      {quizStarted && !showResult && (
        <section className="py-16 px-6">
          <div className="max-w-4xl mx-auto">
            <Card className="glass-liquid-strong p-8 sm:p-12 border-0">
              {/* Progress */}
              <div className="mb-8">
                <div className="flex items-center justify-between mb-3">
                  <span className="text-sm font-semibold text-muted-foreground">
                    Вопрос {currentQuestion + 1} из {quizQuestions.length}
                  </span>
                  <Badge variant="secondary" className="badge-warm">
                    Счет: {score}
                  </Badge>
                </div>
                <div className="h-2 bg-muted rounded-full overflow-hidden">
                  <div
                    className="h-full bg-gradient-to-r from-primary to-secondary transition-all duration-500"
                    style={{
                      width: `${((currentQuestion + 1) / quizQuestions.length) * 100}%`,
                    }}
                  />
                </div>
              </div>

              {/* Question */}
              <h2 className="text-2xl sm:text-3xl font-bold text-foreground mb-8 leading-tight">
                {quizQuestions[currentQuestion].question}
              </h2>

              {/* Answer Options */}
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {quizQuestions[currentQuestion].options.map((option, index) => {
                  const isSelected = selectedAnswer === index;
                  const isCorrect =
                    index === quizQuestions[currentQuestion].correct;
                  const showFeedback = selectedAnswer !== null;

                  return (
                    <button
                      key={index}
                      onClick={() => handleAnswerClick(index)}
                      disabled={selectedAnswer !== null}
                      className={`
                        glass-liquid p-6 rounded-xl text-left transition-all duration-300
                        hover:scale-105 hover:shadow-lg
                        disabled:cursor-not-allowed
                        ${isSelected && showFeedback && isCorrect
                          ? "bg-trust/20 border-trust/50 border-2"
                          : ""
                        }
                        ${isSelected && showFeedback && !isCorrect
                          ? "bg-destructive/20 border-destructive/50 border-2"
                          : ""
                        }
                        ${!isSelected && showFeedback && isCorrect
                          ? "bg-trust/10 border-trust/30 border-2"
                          : ""
                        }
                      `}
                    >
                      <div className="flex items-center justify-between">
                        <span className="text-lg font-medium text-foreground">
                          {option}
                        </span>
                        {showFeedback && isSelected && isCorrect && (
                          <CheckCircle2 className="h-6 w-6 text-trust animate-scale-in" />
                        )}
                        {showFeedback && isSelected && !isCorrect && (
                          <XCircle className="h-6 w-6 text-destructive animate-scale-in" />
                        )}
                        {showFeedback && !isSelected && isCorrect && (
                          <CheckCircle2 className="h-6 w-6 text-trust animate-scale-in" />
                        )}
                      </div>
                    </button>
                  );
                })}
              </div>
            </Card>
          </div>
        </section>
      )}

      {/* Quiz Results */}
      {showResult && (
        <section className="py-16 px-6">
          <div className="max-w-4xl mx-auto">
            <Card className="glass-liquid-strong p-8 sm:p-12 border-0 text-center">
              <Trophy className="h-16 w-16 text-secondary mx-auto mb-6 animate-gentle-bounce" />
              <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
                Тест завершен!
              </h2>
              <p className="text-xl text-muted-foreground mb-8">
                {getScoreMessage()}
              </p>
              <div className="glass-liquid p-8 rounded-2xl mb-8 inline-block">
                <div className="text-6xl font-bold text-primary mb-2">
                  {score}/{quizQuestions.length}
                </div>
                <div className="text-sm text-muted-foreground">
                  правильных ответов
                </div>
              </div>
              <div className="flex flex-wrap gap-4 justify-center">
                <Button
                  onClick={handleStartQuiz}
                  size="lg"
                  variant="default"
                  className="px-8"
                >
                  Пройти еще раз
                </Button>
                <Button
                  onClick={() => setQuizStarted(false)}
                  size="lg"
                  variant="outline"
                  className="glass-liquid px-8"
                >
                  Вернуться к городу
                </Button>
              </div>
            </Card>
          </div>
        </section>
      )}

      {/* Service Categories */}
      <section className="py-16 px-6 bg-gradient-to-b from-transparent to-background/50">
        <div className="max-w-7xl mx-auto">
          <div className="text-center mb-12">
            <h2 className="text-3xl sm:text-4xl font-bold text-foreground mb-4">
              Что мы предлагаем в {cityData.name}
            </h2>
            <p className="text-lg text-muted-foreground max-w-2xl mx-auto">
              Выберите услугу и начните свое незабываемое путешествие
            </p>
          </div>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            {categories.map((category, index) => {
              const Icon = category.icon;
              return (
                <a
                  key={index}
                  href={category.href}
                  className="group block"
                >
                  <Card
                    className={`
                      glass-liquid-strong p-6 border-0 h-full
                      hover-lift-gentle transition-smooth cursor-pointer
                      hover:shadow-2xl
                    `}
                  >
                    <div
                      className={`
                        w-14 h-14 rounded-2xl bg-gradient-to-br ${category.gradient}
                        flex items-center justify-center mb-4
                        group-hover:scale-110 transition-transform duration-300
                      `}
                    >
                      <Icon className="h-7 w-7 text-primary" />
                    </div>
                    <h3 className="text-xl font-bold text-foreground mb-2 leading-tight">
                      {category.title}
                    </h3>
                    <p className="text-sm text-muted-foreground mb-4 leading-relaxed">
                      {category.description}
                    </p>
                    <Badge variant="secondary" className="badge-warm">
                      {category.count} предложений
                    </Badge>
                  </Card>
                </a>
              );
            })}
          </div>
        </div>
      </section>

      <Footer />
    </div>
  );
};

export default CityPage;
