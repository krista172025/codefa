import * as React from "react";
import { useState } from "react";
import { format, addMonths, startOfMonth, endOfMonth, eachDayOfInterval, isSameDay, isBefore, startOfToday } from "date-fns";
import { ru } from "date-fns/locale";
import { ChevronLeft, ChevronRight, Calendar, Settings, Save, X, Zap } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Switch } from "@/components/ui/switch";
import { cn } from "@/lib/utils";
import { toast } from "@/hooks/use-toast";

interface DateSlot {
  date: Date;
  price: number;
  available: boolean;
  times: string[];
}

interface AdminCalendarPanelProps {
  initialDates?: DateSlot[];
  onSave?: (dates: DateSlot[]) => void;
}

const WEEKDAYS = ["Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"];

const DEFAULT_TIMES = ["09:00", "10:00", "12:00", "14:00", "16:00", "18:00"];

const generateInitialDates = (): DateSlot[] => {
  const today = startOfToday();
  const dates: DateSlot[] = [];
  
  for (let i = 0; i < 60; i++) {
    const date = new Date(today);
    date.setDate(today.getDate() + i);
    
    dates.push({
      date,
      price: 4800,
      available: Math.random() > 0.3,
      times: DEFAULT_TIMES.slice(0, Math.floor(Math.random() * 4) + 2),
    });
  }
  
  return dates;
};

export const AdminCalendarPanel: React.FC<AdminCalendarPanelProps> = ({
  initialDates,
  onSave,
}) => {
  const [currentMonth, setCurrentMonth] = useState(startOfMonth(new Date()));
  const [dates, setDates] = useState<DateSlot[]>(initialDates || generateInitialDates());
  const [selectedDate, setSelectedDate] = useState<Date | null>(null);
  const [editingSlot, setEditingSlot] = useState<DateSlot | null>(null);
  const [bulkPrice, setBulkPrice] = useState<string>("4800");
  const [bulkMode, setBulkMode] = useState(false);

  const nextMonth = addMonths(currentMonth, 1);

  const getDateSlot = (date: Date): DateSlot | undefined => {
    return dates.find((d) => isSameDay(d.date, date));
  };

  const updateDateSlot = (date: Date, updates: Partial<DateSlot>) => {
    setDates((prev) =>
      prev.map((slot) =>
        isSameDay(slot.date, date) ? { ...slot, ...updates } : slot
      )
    );
  };

  const handleDateClick = (date: Date) => {
    if (bulkMode) {
      const slot = getDateSlot(date);
      if (slot) {
        updateDateSlot(date, { 
          available: !slot.available,
          price: Number(bulkPrice) || 4800
        });
      }
    } else {
      setSelectedDate(date);
      const slot = getDateSlot(date);
      if (slot) {
        setEditingSlot({ ...slot });
      }
    }
  };

  const handleSaveSlot = () => {
    if (editingSlot && selectedDate) {
      updateDateSlot(selectedDate, editingSlot);
      setSelectedDate(null);
      setEditingSlot(null);
      toast({
        title: "✅ Сохранено",
        description: `Дата ${format(selectedDate, "d MMMM", { locale: ru })} обновлена`,
      });
    }
  };

  const handleSaveAll = () => {
    onSave?.(dates);
    toast({
      title: "🎉 Календарь сохранён",
      description: "Все изменения применены успешно",
    });
  };

  const toggleTimeSlot = (time: string) => {
    if (!editingSlot) return;
    
    const times = editingSlot.times.includes(time)
      ? editingSlot.times.filter((t) => t !== time)
      : [...editingSlot.times, time].sort();
    
    setEditingSlot({ ...editingSlot, times });
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
        
        <div className="grid grid-cols-7 gap-1.5">
          {emptyCells.map((_, i) => (
            <div key={`empty-${i}`} className="aspect-square" />
          ))}
          {days.map((day) => {
            const slot = getDateSlot(day);
            const isPast = isBefore(day, startOfToday());
            const isSelected = selectedDate && isSameDay(day, selectedDate);
            
            return (
              <button
                key={day.toISOString()}
                onClick={() => !isPast && handleDateClick(day)}
                disabled={isPast}
                className={cn(
                  "aspect-square p-0.5 rounded-xl flex flex-col items-center justify-center transition-all duration-200 relative border-2",
                  isPast && "opacity-30 cursor-not-allowed border-transparent",
                  !isPast && "hover:scale-105 cursor-pointer",
                  slot?.available 
                    ? "bg-gradient-to-br from-available/15 to-available/30 border-available/50 hover:border-available" 
                    : "bg-gradient-to-br from-destructive/10 to-destructive/20 border-destructive/30",
                  isSelected && "ring-2 ring-primary ring-offset-2 scale-110 z-10"
                )}
              >
                <span className={cn(
                  "text-sm font-bold",
                  isPast && "text-muted-foreground"
                )}>
                  {format(day, "d")}
                </span>
                {slot && !isPast && (
                  <span className={cn(
                    "text-[9px] font-bold",
                    slot.available ? "text-available" : "text-destructive"
                  )}>
                    {slot.price}₽
                  </span>
                )}
              </button>
            );
          })}
        </div>
      </div>
    );
  };

  const availableDatesCount = dates.filter((d) => d.available && !isBefore(d.date, startOfToday())).length;
  const busyDatesCount = dates.filter((d) => !d.available && !isBefore(d.date, startOfToday())).length;

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div className="flex items-center gap-4">
          <div 
            className="w-14 h-14 rounded-2xl flex items-center justify-center"
            style={{
              background: "linear-gradient(135deg, hsl(var(--primary)) 0%, hsl(var(--primary) / 0.8) 100%)",
              boxShadow: "0 8px 24px -4px hsl(270 72% 60% / 0.4)"
            }}
          >
            <Settings className="h-7 w-7 text-white" />
          </div>
          <div>
            <h2 className="text-2xl font-black text-foreground">Админ-панель календаря</h2>
            <p className="text-sm text-muted-foreground">Управление доступными датами и ценами</p>
          </div>
        </div>
        <Button
          onClick={handleSaveAll}
          className="rounded-xl bg-gradient-to-r from-available to-available/80 hover:from-available/90 hover:to-available/70 text-white font-bold px-6"
          style={{
            boxShadow: "0 6px 20px -4px hsl(142 70% 45% / 0.4)"
          }}
        >
          <Save className="h-4 w-4 mr-2" />
          Сохранить все
        </Button>
      </div>

      {/* Bulk Mode Toggle */}
      <div 
        className="p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4" 
        style={{ 
          borderRadius: "1.5rem",
          background: "linear-gradient(135deg, hsl(var(--secondary) / 0.1) 0%, hsl(var(--card)) 100%)",
          border: "2px solid hsl(var(--secondary) / 0.3)"
        }}
      >
        <div className="flex items-center gap-3">
          <Switch
            checked={bulkMode}
            onCheckedChange={setBulkMode}
            className="data-[state=checked]:bg-primary"
          />
          <div className="flex items-center gap-2">
            <Zap className={cn("h-4 w-4", bulkMode ? "text-secondary" : "text-muted-foreground")} />
            <span className="font-bold text-foreground">Режим быстрого редактирования</span>
          </div>
        </div>
        {bulkMode && (
          <div className="flex items-center gap-2 px-4 py-2 rounded-xl bg-secondary/10 border border-secondary/30">
            <span className="text-sm font-medium text-foreground">Цена:</span>
            <Input
              type="number"
              value={bulkPrice}
              onChange={(e) => setBulkPrice(e.target.value)}
              className="w-24 h-9 rounded-lg border-secondary/50 focus:border-secondary"
              placeholder="4800"
            />
            <span className="text-sm font-bold text-secondary">₽</span>
          </div>
        )}
        <p className="text-xs text-muted-foreground">
          {bulkMode 
            ? "⚡ Кликайте на даты для переключения доступности" 
            : "📝 Кликайте на дату для детального редактирования"
          }
        </p>
      </div>

      <div className="grid lg:grid-cols-[1fr_380px] gap-6">
        {/* Calendar */}
        <div 
          className="p-6" 
          style={{ 
            borderRadius: "2rem",
            background: "linear-gradient(135deg, hsl(var(--card)) 0%, hsl(270 72% 60% / 0.03) 100%)",
            border: "2px solid hsl(270 72% 60% / 0.15)",
            boxShadow: "0 20px 50px -15px hsl(270 72% 60% / 0.15)"
          }}
        >
          <div className="flex items-center justify-between mb-6">
            <h3 className="text-lg font-bold text-foreground flex items-center gap-2">
              <Calendar className="h-5 w-5 text-primary" />
              Календарь дат
            </h3>
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

          <div className="grid sm:grid-cols-2 gap-6">
            {renderMonth(currentMonth)}
            {renderMonth(nextMonth)}
          </div>

          {/* Legend */}
          <div className="flex flex-wrap items-center gap-6 mt-6 pt-4 border-t border-primary/10">
            <div className="flex items-center gap-2">
              <div className="w-5 h-5 rounded-lg bg-gradient-to-br from-destructive/10 to-destructive/20 border-2 border-destructive/30" />
              <span className="text-sm font-medium text-muted-foreground">Недоступно</span>
            </div>
            <div className="flex items-center gap-2">
              <div className="w-5 h-5 rounded-lg bg-gradient-to-br from-available/15 to-available/30 border-2 border-available/50" />
              <span className="text-sm font-medium text-muted-foreground">Доступно</span>
            </div>
          </div>
        </div>

        {/* Date Editor Sidebar */}
        <div className="space-y-4">
          {selectedDate && editingSlot ? (
            <div 
              className="p-6" 
              style={{ 
                borderRadius: "2rem",
                background: "linear-gradient(135deg, hsl(var(--card)) 0%, hsl(var(--primary) / 0.05) 100%)",
                border: "2px solid hsl(var(--primary) / 0.2)"
              }}
            >
              <div className="flex items-center justify-between mb-4">
                <h3 className="text-lg font-bold text-foreground">
                  📅 {format(selectedDate, "d MMMM yyyy", { locale: ru })}
                </h3>
                <button
                  onClick={() => {
                    setSelectedDate(null);
                    setEditingSlot(null);
                  }}
                  className="p-2 rounded-lg hover:bg-muted/50 transition-colors"
                >
                  <X className="h-5 w-5 text-muted-foreground" />
                </button>
              </div>

              <div className="space-y-4">
                {/* Availability Toggle */}
                <div className="flex items-center justify-between p-3 rounded-xl bg-primary/5 border border-primary/10">
                  <span className="font-bold text-foreground">Доступность</span>
                  <Switch
                    checked={editingSlot.available}
                    onCheckedChange={(checked) =>
                      setEditingSlot({ ...editingSlot, available: checked })
                    }
                    className="data-[state=checked]:bg-available"
                  />
                </div>

                {/* Price Input */}
                <div>
                  <label className="text-sm font-bold text-foreground mb-2 block">
                    💰 Цена (₽)
                  </label>
                  <Input
                    type="number"
                    value={editingSlot.price}
                    onChange={(e) =>
                      setEditingSlot({ ...editingSlot, price: Number(e.target.value) })
                    }
                    className="rounded-xl border-2 border-primary/20 focus:border-primary h-12 text-lg font-bold"
                  />
                </div>

                {/* Time Slots */}
                <div>
                  <label className="text-sm font-bold text-foreground mb-3 block">
                    ⏰ Доступное время
                  </label>
                  <div className="grid grid-cols-3 gap-2">
                    {DEFAULT_TIMES.map((time) => (
                      <button
                        key={time}
                        onClick={() => toggleTimeSlot(time)}
                        className={cn(
                          "py-3 px-3 rounded-xl text-sm font-bold transition-all duration-200 border-2",
                          editingSlot.times.includes(time)
                            ? "bg-gradient-to-br from-primary to-primary/80 text-white border-primary shadow-lg shadow-primary/30"
                            : "bg-muted/20 text-muted-foreground border-transparent hover:border-primary/30"
                        )}
                      >
                        {time}
                      </button>
                    ))}
                  </div>
                </div>

                {/* Save Button */}
                <Button
                  onClick={handleSaveSlot}
                  className="w-full rounded-xl bg-gradient-to-r from-primary to-primary/80 hover:from-primary/90 hover:to-primary/70 font-bold py-6"
                  style={{
                    boxShadow: "0 8px 24px -4px hsl(270 72% 60% / 0.4)"
                  }}
                >
                  <Save className="h-4 w-4 mr-2" />
                  Сохранить дату
                </Button>
              </div>
            </div>
          ) : (
            <div 
              className="p-6 text-center" 
              style={{ 
                borderRadius: "2rem",
                background: "linear-gradient(135deg, hsl(var(--muted) / 0.3) 0%, hsl(var(--card)) 100%)",
                border: "2px dashed hsl(var(--border))"
              }}
            >
              <Calendar className="h-12 w-12 text-muted-foreground mx-auto mb-4" />
              <h3 className="font-bold text-foreground mb-2">
                Выберите дату
              </h3>
              <p className="text-sm text-muted-foreground">
                Кликните на дату в календаре для редактирования цены и доступного времени
              </p>
            </div>
          )}

          {/* Quick Stats */}
          <div 
            className="p-4 grid grid-cols-2 gap-4" 
            style={{ 
              borderRadius: "1.5rem",
              background: "linear-gradient(135deg, hsl(var(--card)) 0%, hsl(var(--muted) / 0.3) 100%)",
              border: "2px solid hsl(var(--border) / 0.5)"
            }}
          >
            <div className="text-center p-3 rounded-xl bg-available/10 border border-available/30">
              <div className="text-3xl font-black text-available">
                {availableDatesCount}
              </div>
              <div className="text-xs font-medium text-muted-foreground">Доступных дат</div>
            </div>
            <div className="text-center p-3 rounded-xl bg-destructive/10 border border-destructive/30">
              <div className="text-3xl font-black text-destructive">
                {busyDatesCount}
              </div>
              <div className="text-xs font-medium text-muted-foreground">Занятых дат</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AdminCalendarPanel;
