import * as React from "react";
import { Clock, Check } from "lucide-react";
import { cn } from "@/lib/utils";

interface TimeSlot {
  time: string;
  available: boolean;
}

interface TimeSlotPickerProps {
  slots: TimeSlot[];
  selectedTime: string | null;
  onTimeSelect: (time: string) => void;
  className?: string;
}

const DEFAULT_SLOTS: TimeSlot[] = [
  { time: "09:00", available: true },
  { time: "10:00", available: true },
  { time: "11:00", available: false },
  { time: "12:00", available: true },
  { time: "14:00", available: true },
  { time: "15:00", available: true },
  { time: "16:00", available: false },
  { time: "17:00", available: true },
  { time: "18:00", available: true },
];

export const TimeSlotPicker: React.FC<TimeSlotPickerProps> = ({
  slots = DEFAULT_SLOTS,
  selectedTime,
  onTimeSelect,
  className,
}) => {
  return (
    <div className={cn("space-y-3", className)}>
      <div className="flex items-center gap-2 text-sm font-medium text-foreground">
        <Clock className="h-4 w-4 text-primary" />
        <span>Выберите время</span>
      </div>
      
      <div className="grid grid-cols-3 sm:grid-cols-5 gap-2">
        {slots.map((slot) => {
          const isSelected = selectedTime === slot.time;
          const isDisabled = !slot.available;
          
          return (
            <button
              key={slot.time}
              onClick={() => slot.available && onTimeSelect(slot.time)}
              disabled={isDisabled}
              className={cn(
                "relative py-3 px-2 rounded-xl text-sm font-semibold transition-all duration-200",
                "border-2 flex items-center justify-center gap-1",
                isDisabled && "opacity-40 cursor-not-allowed bg-muted/30 border-transparent",
                !isDisabled && !isSelected && [
                  "bg-gradient-to-br from-primary/5 to-primary/15",
                  "border-primary/20 hover:border-primary/50",
                  "hover:shadow-md hover:shadow-primary/10",
                  "cursor-pointer text-foreground"
                ],
                isSelected && [
                  "bg-gradient-to-br from-primary to-primary/80",
                  "border-primary text-white",
                  "shadow-lg scale-105"
                ]
              )}
              style={isSelected ? {
                boxShadow: "0 8px 20px -4px hsl(270 72% 60% / 0.4)"
              } : undefined}
            >
              {isSelected && <Check className="h-3.5 w-3.5" />}
              {slot.time}
            </button>
          );
        })}
      </div>

      {/* Legend */}
      <div className="flex items-center gap-4 pt-2 text-xs text-muted-foreground">
        <div className="flex items-center gap-1.5">
          <div className="w-3 h-3 rounded bg-gradient-to-br from-primary/5 to-primary/15 border border-primary/20" />
          <span>Доступно</span>
        </div>
        <div className="flex items-center gap-1.5">
          <div className="w-3 h-3 rounded bg-muted/30" />
          <span>Занято</span>
        </div>
      </div>
    </div>
  );
};

export default TimeSlotPicker;
