import { useState } from "react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import * as z from "zod";
import { format } from "date-fns";
import { CalendarIcon, Users, Mail, Phone, User, Sparkles } from "lucide-react";
import {
  Dialog,
  DialogContent,
  DialogDescription,
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
import {
  Popover,
  PopoverContent,
  PopoverTrigger,
} from "@/components/ui/popover";
import { Input } from "@/components/ui/input";
import { Button } from "@/components/ui/button";
import { Calendar } from "@/components/ui/calendar";
import { cn } from "@/lib/utils";
import { toast } from "sonner";

const bookingSchema = z.object({
  name: z
    .string()
    .trim()
    .min(2, { message: "Имя должно содержать минимум 2 символа" })
    .max(100, { message: "Имя не может быть длиннее 100 символов" }),
  email: z
    .string()
    .trim()
    .email({ message: "Неверный формат email" })
    .max(255, { message: "Email не может быть длиннее 255 символов" }),
  phone: z
    .string()
    .trim()
    .min(10, { message: "Введите корректный номер телефона" })
    .max(20, { message: "Номер телефона слишком длинный" }),
  date: z.date({
    required_error: "Выберите дату тура",
  }),
  guests: z
    .number()
    .min(1, { message: "Минимум 1 человек" })
    .max(20, { message: "Максимум 20 человек" }),
});

type BookingFormData = z.infer<typeof bookingSchema>;

interface BookingModalProps {
  open: boolean;
  onOpenChange: (open: boolean) => void;
  tourTitle: string;
  tourPrice: number;
}

export const BookingModal = ({
  open,
  onOpenChange,
  tourTitle,
  tourPrice,
}: BookingModalProps) => {
  const [isSubmitting, setIsSubmitting] = useState(false);

  const form = useForm<BookingFormData>({
    resolver: zodResolver(bookingSchema),
    defaultValues: {
      name: "",
      email: "",
      phone: "",
      guests: 1,
    },
  });

  const guests = form.watch("guests");
  const totalPrice = tourPrice * (guests || 1);

  const onSubmit = async (data: BookingFormData) => {
    setIsSubmitting(true);
    
    // Simulate API call
    await new Promise((resolve) => setTimeout(resolve, 1500));
    
    console.log("Booking data:", {
      ...data,
      tourTitle,
      totalPrice,
      date: format(data.date, "PPP"),
    });

    toast.success("Бронирование успешно!", {
      description: `Мы свяжемся с вами по email: ${data.email}`,
    });

    setIsSubmitting(false);
    onOpenChange(false);
    form.reset();
  };

  return (
    <Dialog open={open} onOpenChange={onOpenChange}>
      <DialogContent className="glass-liquid-strong border-2 border-white/50 shadow-2xl max-w-md sm:max-w-lg">
        <DialogHeader>
          <div className="flex items-center gap-2 mb-2">
            <div className="p-2 rounded-full bg-primary/10">
              <Sparkles className="h-5 w-5 text-primary" />
            </div>
            <DialogTitle className="text-2xl">Забронировать тур</DialogTitle>
          </div>
          <DialogDescription className="text-base">
            {tourTitle}
          </DialogDescription>
        </DialogHeader>

        <Form {...form}>
          <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4 mt-4">
            {/* Name */}
            <FormField
              control={form.control}
              name="name"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Ваше имя</FormLabel>
                  <FormControl>
                    <div className="relative">
                      <User className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                      <Input
                        placeholder="Иван Иванов"
                        className="pl-10 glass-liquid border-white/30 focus:border-primary/50 transition-all duration-300"
                        {...field}
                      />
                    </div>
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            {/* Email */}
            <FormField
              control={form.control}
              name="email"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Email</FormLabel>
                  <FormControl>
                    <div className="relative">
                      <Mail className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                      <Input
                        type="email"
                        placeholder="ivan@example.com"
                        className="pl-10 glass-liquid border-white/30 focus:border-primary/50 transition-all duration-300"
                        {...field}
                      />
                    </div>
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            {/* Phone */}
            <FormField
              control={form.control}
              name="phone"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Телефон</FormLabel>
                  <FormControl>
                    <div className="relative">
                      <Phone className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                      <Input
                        type="tel"
                        placeholder="+7 (999) 123-45-67"
                        className="pl-10 glass-liquid border-white/30 focus:border-primary/50 transition-all duration-300"
                        {...field}
                      />
                    </div>
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            {/* Date */}
            <FormField
              control={form.control}
              name="date"
              render={({ field }) => (
                <FormItem className="flex flex-col">
                  <FormLabel>Дата тура</FormLabel>
                  <Popover>
                    <PopoverTrigger asChild>
                      <FormControl>
                        <Button
                          variant="outline"
                          className={cn(
                            "w-full pl-10 text-left font-normal glass-liquid border-white/30 hover:border-primary/50 transition-all duration-300",
                            !field.value && "text-muted-foreground"
                          )}
                        >
                          <CalendarIcon className="absolute left-3 h-4 w-4" />
                          {field.value ? (
                            format(field.value, "PPP")
                          ) : (
                            <span>Выберите дату</span>
                          )}
                        </Button>
                      </FormControl>
                    </PopoverTrigger>
                    <PopoverContent className="w-auto p-0 glass-liquid-strong border-white/50 shadow-xl" align="start">
                      <Calendar
                        mode="single"
                        selected={field.value}
                        onSelect={field.onChange}
                        disabled={(date) => date < new Date()}
                        initialFocus
                        className={cn("p-3 pointer-events-auto")}
                      />
                    </PopoverContent>
                  </Popover>
                  <FormMessage />
                </FormItem>
              )}
            />

            {/* Guests */}
            <FormField
              control={form.control}
              name="guests"
              render={({ field }) => (
                <FormItem>
                  <FormLabel>Количество человек</FormLabel>
                  <FormControl>
                    <div className="relative">
                      <Users className="absolute left-3 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground" />
                      <Input
                        type="number"
                        min="1"
                        max="20"
                        className="pl-10 glass-liquid border-white/30 focus:border-primary/50 transition-all duration-300"
                        {...field}
                        onChange={(e) => field.onChange(parseInt(e.target.value) || 1)}
                      />
                    </div>
                  </FormControl>
                  <FormMessage />
                </FormItem>
              )}
            />

            {/* Total Price */}
            <div className="glass-liquid p-4 rounded-xl border border-white/30">
              <div className="flex items-center justify-between">
                <span className="text-muted-foreground">Итого:</span>
                <span className="text-2xl font-bold text-primary">
                  {totalPrice.toLocaleString()}₽
                </span>
              </div>
            </div>

            {/* Submit Button */}
            <Button
              type="submit"
              className="w-full bg-primary hover:bg-primary/90 text-primary-foreground shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-0.5"
              disabled={isSubmitting}
            >
              {isSubmitting ? "Обработка..." : "Забронировать"}
            </Button>
          </form>
        </Form>
      </DialogContent>
    </Dialog>
  );
};
