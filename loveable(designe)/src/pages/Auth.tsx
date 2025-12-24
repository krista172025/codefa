import { useState, useEffect } from "react";
import { useNavigate } from "react-router-dom";
import { supabase } from "@/integrations/supabase/client";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { useToast } from "@/hooks/use-toast";
import { z } from "zod";
import { User, Session } from "@supabase/supabase-js";
import { Eye, EyeOff, Sparkles } from "lucide-react";

const authSchema = z.object({
  email: z
    .string()
    .trim()
    .email({ message: "Введите корректный email" })
    .max(255, { message: "Email слишком длинный" }),
  password: z
    .string()
    .min(6, { message: "Пароль должен содержать минимум 6 символов" })
    .max(100, { message: "Пароль слишком длинный" }),
  fullName: z
    .string()
    .trim()
    .min(2, { message: "Имя должно содержать минимум 2 символа" })
    .max(100, { message: "Имя слишком длинное" })
    .optional(),
});

const Auth = () => {
  const [isLogin, setIsLogin] = useState(true);
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [fullName, setFullName] = useState("");
  const [showPassword, setShowPassword] = useState(false);
  const [isLoading, setIsLoading] = useState(false);
  const [user, setUser] = useState<User | null>(null);
  const [session, setSession] = useState<Session | null>(null);
  const navigate = useNavigate();
  const { toast } = useToast();

  useEffect(() => {
    // Set up auth state listener FIRST
    const {
      data: { subscription },
    } = supabase.auth.onAuthStateChange((event, session) => {
      setSession(session);
      setUser(session?.user ?? null);

      // Redirect authenticated users to home
      if (session?.user) {
        setTimeout(() => {
          navigate("/");
        }, 0);
      }
    });

    // THEN check for existing session
    supabase.auth.getSession().then(({ data: { session } }) => {
      setSession(session);
      setUser(session?.user ?? null);

      // Redirect if already authenticated
      if (session?.user) {
        navigate("/");
      }
    });

    return () => subscription.unsubscribe();
  }, [navigate]);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      // Validate input
      const validationData = isLogin
        ? { email, password }
        : { email, password, fullName };

      const result = authSchema.safeParse(validationData);

      if (!result.success) {
        const firstError = result.error.errors[0];
        toast({
          title: "Ошибка валидации",
          description: firstError.message,
          variant: "destructive",
        });
        return;
      }

      if (isLogin) {
        // Login
        const { error } = await supabase.auth.signInWithPassword({
          email: result.data.email,
          password: result.data.password,
        });

        if (error) {
          if (error.message.includes("Invalid login credentials")) {
            throw new Error("Неверный email или пароль");
          }
          throw error;
        }

        toast({
          title: "Успешный вход!",
          description: "Добро пожаловать обратно",
        });
      } else {
        // Sign up
        const redirectUrl = `${window.location.origin}/`;

        const { error } = await supabase.auth.signUp({
          email: result.data.email,
          password: result.data.password,
          options: {
            emailRedirectTo: redirectUrl,
            data: {
              full_name: result.data.fullName || "",
            },
          },
        });

        if (error) {
          if (error.message.includes("already registered")) {
            throw new Error("Пользователь с таким email уже зарегистрирован");
          }
          throw error;
        }

        toast({
          title: "Регистрация успешна!",
          description: "Добро пожаловать! Вы автоматически вошли в систему.",
        });
      }
    } catch (error: any) {
      toast({
        title: "Ошибка",
        description: error.message || "Что-то пошло не так",
        variant: "destructive",
      });
    } finally {
      setIsLoading(false);
    }
  };

  // Don't show auth form if already authenticated
  if (session?.user) {
    return (
      <div className="min-h-screen flex items-center justify-center bg-warm">
        <div className="text-center">
          <div className="animate-spin h-8 w-8 border-4 border-primary border-t-transparent rounded-full mx-auto mb-4" />
          <p className="text-muted-foreground">Перенаправление...</p>
        </div>
      </div>
    );
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-warm p-4">
      {/* Background decorative elements */}
      <div className="absolute inset-0 overflow-hidden pointer-events-none">
        <div className="absolute top-20 left-10 w-64 h-64 bg-primary/10 rounded-full blur-3xl animate-float" />
        <div className="absolute bottom-20 right-10 w-96 h-96 bg-secondary/10 rounded-full blur-3xl animate-float-delayed" />
        <div className="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-trust/5 rounded-full blur-3xl" />
      </div>

      {/* Auth card */}
      <div className="relative w-full max-w-md">
        <div className="glass-liquid-strong rounded-3xl p-8 shadow-warm border border-white/30 backdrop-blur-xl">
          {/* Header */}
          <div className="text-center mb-8">
            <div className="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary to-primary-soft rounded-2xl mb-4 animate-glow">
              <Sparkles className="w-8 h-8 text-white" />
            </div>
            <h1 className="text-3xl font-bold text-foreground mb-2">
              {isLogin ? "С возвращением!" : "Добро пожаловать!"}
            </h1>
            <p className="text-muted-foreground">
              {isLogin
                ? "Войдите, чтобы продолжить"
                : "Создайте аккаунт для начала"}
            </p>
          </div>

          {/* Form */}
          <form onSubmit={handleSubmit} className="space-y-4">
            {!isLogin && (
              <div className="space-y-2">
                <Label htmlFor="fullName" className="text-foreground">
                  Полное имя
                </Label>
                <Input
                  id="fullName"
                  type="text"
                  placeholder="Иван Иванов"
                  value={fullName}
                  onChange={(e) => setFullName(e.target.value)}
                  required={!isLogin}
                  className="bg-background/50 border-border/50 focus:border-primary transition-all"
                  disabled={isLoading}
                />
              </div>
            )}

            <div className="space-y-2">
              <Label htmlFor="email" className="text-foreground">
                Email
              </Label>
              <Input
                id="email"
                type="email"
                placeholder="your@email.com"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                required
                className="bg-background/50 border-border/50 focus:border-primary transition-all"
                disabled={isLoading}
              />
            </div>

            <div className="space-y-2">
              <Label htmlFor="password" className="text-foreground">
                Пароль
              </Label>
              <div className="relative">
                <Input
                  id="password"
                  type={showPassword ? "text" : "password"}
                  placeholder="••••••••"
                  value={password}
                  onChange={(e) => setPassword(e.target.value)}
                  required
                  className="bg-background/50 border-border/50 focus:border-primary transition-all pr-10"
                  disabled={isLoading}
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-3 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground transition-colors"
                  disabled={isLoading}
                >
                  {showPassword ? (
                    <EyeOff className="w-4 h-4" />
                  ) : (
                    <Eye className="w-4 h-4" />
                  )}
                </button>
              </div>
            </div>

            <Button
              type="submit"
              className="w-full bg-gradient-to-r from-primary to-primary-soft hover:opacity-90 transition-all shadow-md hover:shadow-lg"
              disabled={isLoading}
            >
              {isLoading ? (
                <div className="flex items-center space-x-2">
                  <div className="animate-spin h-4 w-4 border-2 border-white border-t-transparent rounded-full" />
                  <span>Загрузка...</span>
                </div>
              ) : isLogin ? (
                "Войти"
              ) : (
                "Зарегистрироваться"
              )}
            </Button>
          </form>

          {/* Toggle */}
          <div className="mt-6 text-center">
            <button
              type="button"
              onClick={() => {
                setIsLogin(!isLogin);
                setEmail("");
                setPassword("");
                setFullName("");
              }}
              className="text-sm text-muted-foreground hover:text-foreground transition-colors"
              disabled={isLoading}
            >
              {isLogin ? (
                <>
                  Нет аккаунта?{" "}
                  <span className="text-primary font-medium">
                    Зарегистрируйтесь
                  </span>
                </>
              ) : (
                <>
                  Уже есть аккаунт?{" "}
                  <span className="text-primary font-medium">Войдите</span>
                </>
              )}
            </button>
          </div>
        </div>

        {/* Bottom decoration */}
        <div className="absolute -bottom-4 left-1/2 -translate-x-1/2 w-3/4 h-4 bg-primary/20 blur-xl rounded-full" />
      </div>
    </div>
  );
};

export default Auth;
