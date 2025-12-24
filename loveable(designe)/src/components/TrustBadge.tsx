import { Shield, Check, Heart } from "lucide-react";

interface TrustBadgeProps {
  type: "guarantee" | "verified" | "care";
  text: string;
}

export const TrustBadge = ({ type, text }: TrustBadgeProps) => {
  const icons = {
    guarantee: Shield,
    verified: Check,
    care: Heart,
  };

  const Icon = icons[type];

  return (
    <div className="inline-flex items-center gap-3 px-4 py-2.5 rounded-full glass-liquid text-sm font-medium text-foreground shadow-md transition-all duration-300 hover:shadow-lg hover:-translate-y-0.5 hover:glass-hover-trust cursor-pointer">
      <div className="p-2 rounded-full glass-liquid-strong transition-transform duration-300 group-hover:scale-110">
        <Icon className="h-4 w-4" />
      </div>
      <span>{text}</span>
    </div>
  );
};
