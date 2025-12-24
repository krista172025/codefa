import { useParallax } from "@/hooks/useParallax";

export const FloatingGlassOrbs = () => {
  const scrollY = useParallax();

  return (
    <div className="absolute inset-0 overflow-hidden pointer-events-none">
      {/* Large orbs */}
      <div
        className="absolute top-[10%] left-[15%] w-64 h-64 rounded-full glass-liquid opacity-40 animate-float-slow"
        style={{
          animationDelay: "0s",
          filter: "blur(40px)",
          transform: `translateY(${scrollY * 0.3}px)`,
        }}
      />
      <div
        className="absolute top-[60%] right-[10%] w-80 h-80 rounded-full glass-liquid opacity-30 animate-float-medium"
        style={{
          animationDelay: "2s",
          filter: "blur(50px)",
          transform: `translateY(${scrollY * 0.5}px)`,
        }}
      />
      <div
        className="absolute bottom-[15%] left-[40%] w-72 h-72 rounded-full glass-liquid opacity-35 animate-float-slow"
        style={{
          animationDelay: "4s",
          filter: "blur(45px)",
          transform: `translateY(${scrollY * 0.2}px)`,
        }}
      />
      
      {/* Medium orbs */}
      <div
        className="absolute top-[30%] right-[25%] w-48 h-48 rounded-full glass-liquid-strong opacity-25 animate-float-fast"
        style={{
          animationDelay: "1s",
          filter: "blur(30px)",
          transform: `translateY(${scrollY * 0.4}px)`,
        }}
      />
      <div
        className="absolute bottom-[40%] left-[20%] w-56 h-56 rounded-full glass-liquid-strong opacity-20 animate-float-medium"
        style={{
          animationDelay: "3s",
          filter: "blur(35px)",
          transform: `translateY(${scrollY * 0.35}px)`,
        }}
      />
      
      {/* Small accent orbs */}
      <div
        className="absolute top-[45%] left-[60%] w-32 h-32 rounded-full glass-liquid opacity-30 animate-float-fast"
        style={{
          animationDelay: "5s",
          filter: "blur(25px)",
          transform: `translateY(${scrollY * 0.6}px)`,
        }}
      />
      <div
        className="absolute bottom-[25%] right-[35%] w-40 h-40 rounded-full glass-liquid opacity-25 animate-float-slow"
        style={{
          animationDelay: "1.5s",
          filter: "blur(28px)",
          transform: `translateY(${scrollY * 0.45}px)`,
        }}
      />
    </div>
  );
};
