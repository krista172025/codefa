import { Toaster } from "@/components/ui/toaster";
import { Toaster as Sonner } from "@/components/ui/sonner";
import { TooltipProvider } from "@/components/ui/tooltip";
import { QueryClient, QueryClientProvider } from "@tanstack/react-query";
import { BrowserRouter, Routes, Route } from "react-router-dom";
import Home from "./pages/HomeNew";
import Tours from "./pages/Tours";
import TourDetail from "./pages/TourDetail";
import Accommodation from "./pages/Accommodation";
import Shop from "./pages/Shop";
import Reviews from "./pages/Reviews";
import Guides from "./pages/Guides";
import GuideProfile from "./pages/GuideProfile";
import Profile from "./pages/Profile";
import Auth from "./pages/Auth";
import City from "./pages/City";
import ThankYou from "./pages/ThankYou";
import NotFound from "./pages/NotFound";

const queryClient = new QueryClient();

const App = () => (
  <QueryClientProvider client={queryClient}>
    <TooltipProvider>
      <Toaster />
      <Sonner />
      <BrowserRouter>
        <Routes>
          <Route path="/" element={<Home />} />
          <Route path="/tours" element={<Tours />} />
          <Route path="/transfers" element={<Tours />} />
          <Route path="/tickets" element={<Tours />} />
          <Route path="/accommodation" element={<Accommodation />} />
          <Route path="/tour/:id" element={<TourDetail />} />
          <Route path="/shop" element={<Shop />} />
          <Route path="/reviews" element={<Reviews />} />
          <Route path="/guides" element={<Guides />} />
          <Route path="/guide/:id" element={<GuideProfile />} />
          <Route path="/city/:cityName" element={<City />} />
          <Route path="/thank-you" element={<ThankYou />} />
          <Route path="/profile" element={<Profile />} />
          <Route path="/profile/*" element={<Profile />} />
          <Route path="/wishlist" element={<Profile />} />
          <Route path="/auth" element={<Auth />} />
          {/* ADD ALL CUSTOM ROUTES ABOVE THE CATCH-ALL "*" ROUTE */}
          <Route path="*" element={<NotFound />} />
        </Routes>
      </BrowserRouter>
    </TooltipProvider>
  </QueryClientProvider>
);

export default App;
