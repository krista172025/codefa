import * as React from "react";
import { useState } from "react";
import { format } from "date-fns";
import { ru } from "date-fns/locale";
import { z } from "zod";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { X, User, Mail, Phone, Calendar, Clock, CheckCircle2, Loader2 } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import {
  Dialog,
  DialogContent,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form";
import { toast } from "@/hooks/use-toast";
import { cn } from "@/lib/utils";

const bookingSchema = z.object({
  name: z
    .string()
    .trim()
    .min(2, { message: "Имя должно содержать минимум 2 символа" })
    .max(100, { message: "Имя не должно превышать 100 символов" }),
  email: z
    .string()
    .trim()
    .email({ message: "Введите корректный email" })
    .max(255, { message: "Email не должен превышать 255 символов" }),
  phone: z
    .string()
    .trim()
    .min(10, { message: "Введите корректный номер телефона" })
    .max(20, { message: "Номер телефона слишком длинный" })
    .regex(/^[\d\s\+\-\(\)]+$/, { message: "Номер должен содержать только цифры" }),
});

type BookingFormData = z.infer<typeof bookingSchema>;

interface BookingConfirmModalProps {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  tourTitle: string;
  selectedDate: Date;
  selectedTime: string;
  price: number;
  onConfirm?: (data: BookingFormData) => Promise<void>;
}

export const BookingConfirmModal: React.FC<BookingConfirmModalProps> = ({
  open,
  onOpenChange,
  tourTitle,
  selectedDate,
  selectedTime,
  price,
  onConfirm,
}) => {
  const [isSubmitting, setIsSubmitting] = useState(false);
  const [isSuccess, setIsSuccess] = useState(false);

  const form = useForm<BookingFormData>({
    resolver: zodResolver(bookingSchema),
    defaultValues: {
      name: "",
      email: "",
      phone: "",
    },
  });

  const handleSubmit = async (data: BookingFormData) => {
    setIsSubmitting(true);
    try {
      if (onConfirm) {
        await onConfirm(data);
      } else {
        // Simulate API call
        await new Promise((resolve) => setTimeout(resolve, 1500));
      }
      setIsSuccess(true);
      toast({
        title: "Бронирование подтверждено!",
        description: `${tourTitle} на ${format(selectedDate, "d MMMM", { locale: ru })} в ${selectedTime}`,
      });
      setTimeout(() => {
        onOpenChange(false);
        setIsSuccess(false);
        form.reset();
      }, 2000);
    } catch (error) {
      toast({
        title: "Ошибка бронирования",
        description: "Попробуйте ещё раз или свяжитесь с нами",
        variant: "destructive",
      });
    } finally {
      setIsSubmitting(false);
    }
  };

  const handleClose = () => {
    if (!isSubmitting) {
      onOpenChange(false);
      setIsSuccess(false);
      form.reset();
    }
  };

  return (
    <Dialog open={open} onOpenChange={handleClose}>
      <DialogContent 
        className="sm:max-w-[480px] p-0 overflow-hidden border-0"
        style={{ 
          borderRadius: "2rem",
          background: "linear-gradient(135deg, hsl(var(--card)) 0%, hsl(var(--background)) 100%)"
        }}
      >
        {/* Header with gradient */}
        <div 
          className="px-6 pt-6 pb-4"
          style={{
            background: "linear-gradient(135deg, hsl(var(--primary) / 0.1) 0%, hsl(var(--trust) / 0.1) 100%)"
          }}
        >
          <DialogHeader>
            <DialogTitle className="text-2xl font-bold text-foreground">
              Подтверждение брони
            </DialogTitle>
          </DialogHeader>
          
          {/* Booking Summary */}
          <div className="mt-4 p-4 glass-liquid rounded-2xl space-y-2">
            <h3 className="font-semibold text-foreground">{tourTitle}</h3>
            <div className="flex flex-wrap gap-4 text-sm">
              <div className="flex items-center gap-2 text-muted-foreground">
                <Calendar className="h-4 w-4 text-primary" />
                <span>{format(selectedDate, "d MMMM yyyy", { locale: ru })}</span>
              </div>
              <div className="flex items-center gap-2 text-muted-foreground">
                <Clock className="h-4 w-4 text-trust" />
                <span>{selectedTime}</span>
              </div>
            </div>
            <div className="pt-2 border-t border-border/30">
              <span className="text-2xl font-bold text-primary">{price}₽</span>
            </div>
          </div>
        </div>

        {/* Form Content */}
        <div className="px-6 pb-6">
          {isSuccess ? (
            <div className="py-8 text-center animate-fade-in">
              <div className="w-20 h-20 mx-auto mb-4 rounded-full bg-available/20 flex items-center justify-center">
                <CheckCircle2 className="h-10 w-10 text-available" />
              </div>
              <h3 className="text-xl font-bold text-foreground mb-2">
                Бронирование успешно!
              </h3>
              <p className="text-muted-foreground">
                Мы отправили подтверждение на вашу почту
              </p>
            </div>
          ) : (
            <Form {...form}>
              <form onSubmit={form.handleSubmit(handleSubmit)} className="space-y-4 pt-4">
                <FormField
                  control={form.control}
                  name="name"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel className="text-foreground font-medium">
                        Ваше имя
                      </FormLabel>
                      <FormControl>
                        <div className="relative">
                          <User className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-muted-foreground" />
                          <Input
                            {...field}
                            placeholder="Иван Иванов"
                            className="pl-10 h-12 rounded-xl border-2 border-border/50 focus:border-primary transition-colors"
                          />
                        </div>
                      </FormControl>
                      <FormMessage />
                    </FormItem>
                  )}
                />

                <FormField
                  control={form.control}
                  name="email"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel className="text-foreground font-medium">
                        Email
                      </FormLabel>
                      <FormControl>
                        <div className="relative">
                          <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-muted-foreground" />
                          <Input
                            {...field}
                            type="email"
                            placeholder="example@mail.com"
                            className="pl-10 h-12 rounded-xl border-2 border-border/50 focus:border-primary transition-colors"
                          />
                        </div>
                      </FormControl>
                      <FormMessage />
                    </FormItem>
                  )}
                />

                <FormField
                  control={form.control}
                  name="phone"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel className="text-foreground font-medium">
                        Телефон
                      </FormLabel>
                      <FormControl>
                        <div className="relative">
                          <Phone className="absolute left-3 top-1/2 -translate-y-1/2 h-5 w-5 text-muted-foreground" />
                          <Input
                            {...field}
                            type="tel"
                            placeholder="+7 (999) 123-45-67"
                            className="pl-10 h-12 rounded-xl border-2 border-border/50 focus:border-primary transition-colors"
                          />
                        </div>
                      </FormControl>
                      <FormMessage />
                    </FormItem>
                  )}
                />

                <Button
                  type="submit"
                  disabled={isSubmitting}
                  className={cn(
                    "w-full h-14 text-lg font-semibold rounded-2xl transition-all duration-300",
                    "bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary/70",
                    "text-white shadow-lg",
                    isSubmitting && "opacity-80"
                  )}
                  style={{
                    boxShadow: "0 8px 28px -6px hsl(270 72% 60% / 0.35)"
                  }}
                >
                  {isSubmitting ? (
                    <>
                      <Loader2 className="h-5 w-5 mr-2 animate-spin" />
                      Оформляем...
                    </>
                  ) : (
                    "Подтвердить бронирование"
                  )}
                </Button>

                <p className="text-center text-xs text-muted-foreground pt-2">
                  Нажимая кнопку, вы соглашаетесь с{" "}
                  <span className="text-primary underline cursor-pointer">
                    условиями бронирования
                  </span>
                </p>
              </form>
            </Form>
          )}
        </div>
      </DialogContent>
    </Dialog>
  );
};

export default BookingConfirmModal;
