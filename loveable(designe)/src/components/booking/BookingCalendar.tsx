import * as React from "react";
import { useState, useMemo } from "react";
import { format, addMonths, startOfMonth, endOfMonth, eachDayOfInterval, isSameDay, isBefore, startOfToday } from "date-fns";
import { ru } from "date-fns/locale";
import { ChevronLeft, ChevronRight, Star, Clock, MapPin, ShieldCheck, Sparkles, Users } from "lucide-react";
import { Button } from "@/components/ui/button";
import { cn } from "@/lib/utils";
import { TimeSlotPicker } from "./TimeSlotPicker";
import { BookingConfirmModal } from "./BookingConfirmModal";

interface TourInfo {
  title: string;
  format: string;
  duration: string;
  children: string;
  transport: string;
  rating: number;
  reviewsCount: number;
  price: number;
  priceNote: string;
}

interface TimeSlot {
  time: string;
  available: boolean;
}

interface AvailableDate {
  date: Date;
  price: number;
  available: boolean;
  timeSlots: TimeSlot[];
}

interface BookingCalendarProps {
  tourInfo?: TourInfo;
  availableDates?: AvailableDate[];
  onDateSelect?: (date: Date, price: number) => void;
  onBooking?: (date: Date, time: string, price: number) => void;
}

const defaultTourInfo: TourInfo = {
  title: "Индивидуальный формат",
  format: "для 1–4 человек",
  duration: "1,5 часа",
  children: "Можно с детьми",
  transport: "Пешком",
  rating: 5.0,
  reviewsCount: 52,
  price: 4800,
  priceNote: "за 1-4 человек, независимо от числа участников",
};

const DEFAULT_TIME_SLOTS: TimeSlot[] = [
  { time: "09:00", available: true },
  { time: "10:00", available: true },
  { time: "11:00", available: false },
  { time: "12:00", available: true },
  { time: "14:00", available: true },
  { time: "16:00", available: true },
  { time: "18:00", available: true },
];

// Generate sample available dates for demo
const generateAvailableDates = (): AvailableDate[] => {
  const today = startOfToday();
  const dates: AvailableDate[] = [];
  
  for (let i = 0; i < 60; i++) {
    const date = new Date(today);
    date.setDate(today.getDate() + i);
    
    const isAvailable = Math.random() > 0.3;
    const basePrice = 4800;
    const priceVariation = Math.floor(Math.random() * 5) * 200;
    
    // Randomize time slots
    const timeSlots = DEFAULT_TIME_SLOTS.map(slot => ({
      ...slot,
      available: slot.available && Math.random() > 0.3
    }));
    
    dates.push({
      date,
      price: basePrice + priceVariation,
      available: isAvailable,
      timeSlots,
    });
  }
  
  return dates;
};

const WEEKDAYS = ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"];

export const BookingCalendar: React.FC<BookingCalendarProps> = ({
  tourInfo = defaultTourInfo,
  availableDates,
  onDateSelect,
  onBooking,
}) => {
  const dates = useMemo(() => availableDates || generateAvailableDates(), [availableDates]);
  
  const [currentMonth, setCurrentMonth] = useState(startOfMonth(new Date()));
  const [selectedDate, setSelectedDate] = useState<Date | null>(null);
  const [selectedTime, setSelectedTime] = useState<string | null>(null);
  const [selectedPrice, setSelectedPrice] = useState<number>(tourInfo.price);
  const [isModalOpen, setIsModalOpen] = useState(false);

  const nextMonth = addMonths(currentMonth, 1);

  const getDateInfo = (date: Date): AvailableDate | undefined => {
    return dates.find((d) => isSameDay(d.date, date));
  };

  const selectedDateInfo = selectedDate ? getDateInfo(selectedDate) : null;

  const handleDateClick = (date: Date, dateInfo: AvailableDate) => {
    if (!dateInfo.available) return;
    setSelectedDate(date);
    setSelectedTime(null); // Reset time when date changes
    setSelectedPrice(dateInfo.price);
    onDateSelect?.(date, dateInfo.price);
  };

  const handleTimeSelect = (time: string) => {
    setSelectedTime(time);
  };

  const handleBooking = () => {
    if (selectedDate && selectedTime) {
      setIsModalOpen(true);
    }
  };

  const handleConfirmBooking = async () => {
    if (selectedDate && selectedTime) {
      onBooking?.(selectedDate, selectedTime, selectedPrice);
    }
  };

  const navigateMonth = (direction: "prev" | "next") => {
    setCurrentMonth(
      direction === "prev"
        ? addMonths(currentMonth, -1)
        : addMonths(currentMonth, 1)
    );
  };

  const renderMonth = (monthStart: Date) => {
    const monthEnd = endOfMonth(monthStart);
    const days = eachDayOfInterval({ start: startOfMonth(monthStart), end: monthEnd });
    
    let startDay = monthStart.getDay();
    startDay = startDay === 0 ? 6 : startDay - 1;
    const emptyCells = Array(startDay).fill(null);

    return (
      <div className="flex-1">
        <div className="text-center mb-4">
          <h3 className="text-lg font-bold text-foreground capitalize">
            {format(monthStart, "LLLL yyyy", { locale: ru })}
          </h3>
        </div>
        
        {/* Weekdays header */}
        <div className="grid grid-cols-7 mb-2">
          {WEEKDAYS.map((day) => (
            <div
              key={day}
              className="text-center text-xs font-semibold text-primary/70 py-2"
            >
              {day}
            </div>
          ))}
        </div>
        
        {/* Days grid */}
        <div className="grid grid-cols-7 gap-1.5">
          {emptyCells.map((_, i) => (
            <div key={`empty-${i}`} className="aspect-square" />
          ))}
          {days.map((day) => {
            const dateInfo = getDateInfo(day);
            const isPast = isBefore(day, startOfToday());
            const isSelected = selectedDate && isSameDay(day, selectedDate);
            const isAvailable = dateInfo?.available && !isPast;
            const isBusy = dateInfo && !dateInfo.available;
            
            return (
              <button
                key={day.toISOString()}
                onClick={() => dateInfo && handleDateClick(day, dateInfo)}
                disabled={!isAvailable}
                className={cn(
                  "aspect-square p-0.5 rounded-xl flex flex-col items-center justify-center transition-all duration-200 relative",
                  "border-2",
                  isPast && "opacity-30 cursor-not-allowed border-transparent",
                  !isPast && !isAvailable && !isBusy && "border-transparent",
                  isAvailable && !isSelected && [
                    "bg-gradient-to-br from-available/10 to-available/25",
                    "border-available/40 hover:border-available",
                    "hover:scale-105 cursor-pointer",
                    "hover:shadow-md hover:shadow-available/20"
                  ],
                  isBusy && !isPast && [
                    "bg-gradient-to-br from-destructive/10 to-destructive/20",
                    "border-destructive/30"
                  ],
                  isSelected && [
                    "bg-gradient-to-br from-primary to-primary/80",
                    "border-primary scale-110",
                    "shadow-xl z-10"
                  ]
                )}
                style={isSelected ? {
                  boxShadow: "0 8px 24px -4px hsl(270 72% 60% / 0.5)"
                } : undefined}
              >
                <span
                  className={cn(
                    "text-sm font-bold",
                    isSelected ? "text-white" : "text-foreground",
                    isPast && "text-muted-foreground"
                  )}
                >
                  {format(day, "d")}
                </span>
                {dateInfo && isAvailable && (
                  <span
                    className={cn(
                      "text-[9px] font-bold",
                      isSelected ? "text-white/90" : "text-available"
                    )}
                  >
                    {dateInfo.price}₽
                  </span>
                )}
              </button>
            );
          })}
        </div>
      </div>
    );
  };

  return (
    <>
      <div className="grid lg:grid-cols-[1fr_400px] gap-8">
        {/* Calendar Section */}
        <div 
          className="p-6 sm:p-8" 
          style={{ 
            borderRadius: "2rem",
            background: "linear-gradient(135deg, hsl(var(--card)) 0%, hsl(270 72% 60% / 0.03) 100%)",
            border: "2px solid hsl(270 72% 60% / 0.15)",
            boxShadow: "0 20px 50px -15px hsl(270 72% 60% / 0.15), inset 0 1px 2px hsl(0 0% 100% / 0.5)"
          }}
        >
          <div className="flex items-center justify-between mb-6">
            <div className="flex items-center gap-3">
              <div className="w-10 h-10 rounded-xl bg-gradient-to-br from-primary to-primary/80 flex items-center justify-center shadow-lg shadow-primary/30">
                <Sparkles className="h-5 w-5 text-white" />
              </div>
              <h2 className="text-2xl font-bold text-foreground">Выберите дату</h2>
            </div>
            <div className="flex items-center gap-2">
              <button
                onClick={() => navigateMonth("prev")}
                className="p-2.5 rounded-xl bg-primary/10 hover:bg-primary/20 border border-primary/20 transition-all duration-200 hover:scale-105"
              >
                <ChevronLeft className="h-5 w-5 text-primary" />
              </button>
              <button
                onClick={() => navigateMonth("next")}
                className="p-2.5 rounded-xl bg-primary/10 hover:bg-primary/20 border border-primary/20 transition-all duration-200 hover:scale-105"
              >
                <ChevronRight className="h-5 w-5 text-primary" />
              </button>
            </div>
          </div>

          {/* Two months view */}
          <div className="grid sm:grid-cols-2 gap-6 sm:gap-8">
            {renderMonth(currentMonth)}
            {renderMonth(nextMonth)}
          </div>

          {/* Legend */}
          <div className="flex flex-wrap items-center gap-6 mt-6 pt-4 border-t border-primary/10">
            <div className="flex items-center gap-2">
              <div className="w-5 h-5 rounded-lg bg-gradient-to-br from-destructive/10 to-destructive/20 border-2 border-destructive/30" />
              <span className="text-sm font-medium text-muted-foreground">День занят</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="w-5 h-5 rounded-lg bg-gradient-to-br from-available/10 to-available/25 border-2 border-available/40" />
              <span className="text-sm font-medium text-muted-foreground">День свободен</span>
            </div>
          </div>

          {/* Time Slots - Show when date is selected */}
          {selectedDate && selectedDateInfo && (
            <div className="mt-6 pt-6 border-t border-primary/10 animate-fade-in">
              <TimeSlotPicker
                slots={selectedDateInfo.timeSlots}
                selectedTime={selectedTime}
                onTimeSelect={handleTimeSelect}
              />
            </div>
          )}
        </div>

        {/* Tour Info Sidebar */}
        <div className="space-y-4">
          {/* Tour Details Card */}
          <div 
            className="p-6" 
            style={{ 
              borderRadius: "2rem",
              background: "linear-gradient(145deg, hsl(51 97% 50% / 0.08) 0%, hsl(var(--card)) 50%, hsl(270 72% 60% / 0.05) 100%)",
              border: "2px solid hsl(51 97% 50% / 0.25)",
              boxShadow: "0 20px 50px -15px hsl(51 97% 50% / 0.2), inset 0 1px 2px hsl(0 0% 100% / 0.5)"
            }}
          >
            <div className="flex items-center gap-2 mb-4">
              <Users className="h-5 w-5 text-primary" />
              <h3 className="text-xl font-bold text-foreground">{tourInfo.title}</h3>
            </div>
            <p className="text-muted-foreground mb-4">{tourInfo.format}</p>
            
            <div className="space-y-3 mb-6">
              <div className="flex items-center justify-between text-sm p-2 rounded-lg bg-primary/5">
                <span className="text-muted-foreground">Длительность</span>
                <span className="font-semibold text-foreground flex items-center gap-1.5">
                  <Clock className="h-4 w-4 text-trust" />
                  {tourInfo.duration}
                </span>
              </div>
              <div className="flex items-center justify-between text-sm p-2 rounded-lg">
                <span className="text-muted-foreground">Дети</span>
                <span className="font-semibold text-foreground">{tourInfo.children}</span>
              </div>
              <div className="flex items-center justify-between text-sm p-2 rounded-lg bg-primary/5">
                <span className="text-muted-foreground">Как проходит</span>
                <span className="font-semibold text-foreground flex items-center gap-1.5">
                  <MapPin className="h-4 w-4 text-primary" />
                  {tourInfo.transport}
                </span>
              </div>
              <div className="flex items-center justify-between text-sm p-2 rounded-lg">
                <span className="text-muted-foreground">Рейтинг</span>
                <span className="font-semibold text-foreground flex items-center gap-1.5">
                  <Star className="h-4 w-4 text-secondary fill-secondary" />
                  {tourInfo.rating}
                  <span className="text-trust underline cursor-pointer text-xs">
                    по {tourInfo.reviewsCount} отзывам
                  </span>
                </span>
              </div>
            </div>

            {/* Price Section */}
            <div 
              className="p-4 mb-4 rounded-2xl"
              style={{
                background: "linear-gradient(135deg, hsl(var(--secondary) / 0.15) 0%, hsl(var(--secondary) / 0.05) 100%)",
                border: "1px solid hsl(var(--secondary) / 0.3)"
              }}
            >
              <div className="text-4xl font-black text-foreground mb-1">
                {selectedPrice}
                <span className="text-2xl ml-1 font-bold text-secondary">₽</span>
              </div>
              <p className="text-sm text-muted-foreground">{tourInfo.priceNote}</p>
            </div>

            {/* Selected info */}
            {selectedDate && (
              <div className="mb-4 p-3 rounded-xl bg-primary/10 border border-primary/20 animate-fade-in">
                <div className="text-sm font-medium text-primary">
                  📅 {format(selectedDate, "d MMMM yyyy", { locale: ru })}
                  {selectedTime && <span className="ml-2">⏰ {selectedTime}</span>}
                </div>
              </div>
            )}

            {/* Booking Button */}
            <Button
              onClick={handleBooking}
              disabled={!selectedDate || !selectedTime}
              className={cn(
                "w-full py-7 text-lg font-bold rounded-2xl transition-all duration-300",
                "bg-gradient-to-r from-primary via-primary to-primary/90",
                "hover:from-primary/90 hover:via-primary/80 hover:to-primary/70",
                "text-white disabled:opacity-50",
                selectedDate && selectedTime && "animate-pulse"
              )}
              style={{
                boxShadow: selectedDate && selectedTime 
                  ? "0 12px 35px -8px hsl(270 72% 60% / 0.5)" 
                  : "0 8px 20px -6px hsl(270 72% 60% / 0.3)"
              }}
            >
              {!selectedDate 
                ? "Выберите дату"
                : !selectedTime 
                  ? "Выберите время" 
                  : `Забронировать на ${format(selectedDate, "d MMM", { locale: ru })} в ${selectedTime}`
              }
            </Button>

            <p className="text-center text-sm text-muted-foreground mt-3">
              <span className="text-primary underline cursor-pointer font-medium">Уточнить детали</span>
              {" "}можно до оплаты
            </p>
          </div>

          {/* Guarantee Badge */}
          <div 
            className="p-4 flex items-start gap-3" 
            style={{ 
              borderRadius: "1.5rem",
              background: "linear-gradient(135deg, hsl(var(--secondary) / 0.1) 0%, hsl(var(--card)) 100%)",
              border: "2px solid hsl(var(--secondary) / 0.3)"
            }}
          >
            <div 
              className="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
              style={{
                background: "linear-gradient(135deg, hsl(var(--secondary)) 0%, hsl(var(--secondary) / 0.8) 100%)",
                boxShadow: "0 6px 20px -4px hsl(51 97% 50% / 0.4)"
              }}
            >
              <ShieldCheck className="h-6 w-6 text-secondary-foreground" />
            </div>
            <div>
              <h4 className="font-bold text-secondary-foreground text-sm mb-1">
                🏆 Гарантия лучшей цены
              </h4>
              <p className="text-xs text-muted-foreground">
                Если вы найдёте цену ниже, мы вернём разницу.{" "}
                <span className="text-primary underline cursor-pointer font-medium">Подробнее</span>
              </p>
            </div>
          </div>
        </div>
      </div>

      {/* Booking Confirmation Modal */}
      {selectedDate && selectedTime && (
        <BookingConfirmModal
          open={isModalOpen}
          onOpenChange={setIsModalOpen}
          tourTitle={tourInfo.title}
          selectedDate={selectedDate}
          selectedTime={selectedTime}
          price={selectedPrice}
          onConfirm={handleConfirmBooking}
        />
      )}
    </>
  );
};

export default BookingCalendar;
