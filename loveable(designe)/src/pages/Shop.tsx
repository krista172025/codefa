import { Header } from "@/components/Header";
import { Footer } from "@/components/Footer";
import { FloatingGlassOrbs } from "@/components/FloatingGlassOrbs";
import { Button } from "@/components/ui/button";
import { Badge } from "@/components/ui/badge";
import { ShoppingCart, Heart } from "lucide-react";
import { useState } from "react";
import tour1 from "@/assets/tour-1.jpg";

const products = [
  {
    id: "1",
    name: "Карта путешественника",
    price: 2500,
    image: tour1,
    category: "Аксессуары",
    inStock: true,
  },
  {
    id: "2",
    name: "Путеводитель по Европе",
    price: 1800,
    image: tour1,
    category: "Книги",
    inStock: true,
  },
  {
    id: "3",
    name: "Дорожный набор",
    price: 3200,
    image: tour1,
    category: "Наборы",
    inStock: false,
  },
  {
    id: "4",
    name: "Брендированная футболка",
    price: 1500,
    image: tour1,
    category: "Одежда",
    inStock: true,
  },
];

const ShopPage = () => {
  const [cart, setCart] = useState<string[]>([]);

  return (
    <div className="min-h-screen bg-warm">
      <Header />

      <div className="relative pt-32 pb-20 overflow-hidden">
        <FloatingGlassOrbs />
        <div className="container mx-auto px-4 relative z-10">
          <div className="text-center mb-12 animate-fade-in-up">
            <h1 className="text-5xl font-bold mb-4 text-foreground">Магазин</h1>
            <p className="text-xl text-muted-foreground">
              Сувениры и товары для путешествий
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {products.map((product, index) => (
              <div
                key={product.id}
                className="glass-liquid-strong rounded-2xl overflow-hidden hover-lift-gentle hover:glass-hover-trust transition-all duration-300 animate-fade-in"
                style={{ animationDelay: `${index * 50}ms` }}
              >
                <div className="relative h-64 overflow-hidden group">
                  <img
                    src={product.image}
                    alt={product.name}
                    className="w-full h-full object-cover transition-smooth group-hover:scale-110"
                  />
                  {!product.inStock && (
                    <Badge className="absolute top-4 left-4 bg-destructive">
                      Нет в наличии
                    </Badge>
                  )}
                  {product.category && (
                    <Badge className="absolute top-4 right-4 bg-secondary text-secondary-foreground">
                      {product.category}
                    </Badge>
                  )}
                </div>

                <div className="p-6">
                  <h3 className="text-lg font-semibold mb-2 text-foreground">
                    {product.name}
                  </h3>
                  <div className="text-2xl font-bold text-foreground mb-4">
                    {product.price.toLocaleString()}₽
                  </div>

                  <div className="flex gap-2">
                    <Button
                      className="flex-1 bg-primary hover:shadow-glow"
                      disabled={!product.inStock}
                    >
                      <ShoppingCart className="h-4 w-4 mr-2" />
                      В корзину
                    </Button>
                    <Button
                      size="icon"
                      variant="outline"
                      className="hover:bg-primary/10 hover:text-primary transition-smooth"
                    >
                      <Heart className="h-4 w-4" />
                    </Button>
                  </div>
                </div>
              </div>
            ))}
          </div>
        </div>
      </div>

      <Footer />
    </div>
  );
};

export default ShopPage;
