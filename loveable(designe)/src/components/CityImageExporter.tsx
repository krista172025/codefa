import { useState } from "react";
import { Button } from "@/components/ui/button";
import { 
  Dialog, 
  DialogContent, 
  DialogDescription, 
  DialogHeader, 
  DialogTitle, 
  DialogTrigger 
} from "@/components/ui/dialog";
import { Download, Image as ImageIcon, Loader2, Sparkles } from "lucide-react";
import { supabase } from "@/integrations/supabase/client";
import { toast } from "sonner";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";

interface CityImageExporterProps {
  cityName: string;
  defaultLandmarks?: string[];
}

export const CityImageExporter = ({ cityName, defaultLandmarks = [] }: CityImageExporterProps) => {
  const [isGenerating, setIsGenerating] = useState(false);
  const [generatedImageUrl, setGeneratedImageUrl] = useState<string | null>(null);
  const [landmarks, setLandmarks] = useState(defaultLandmarks.join(", "));
  const [isOpen, setIsOpen] = useState(false);

  const generateImage = async () => {
    setIsGenerating(true);
    setGeneratedImageUrl(null);

    try {
      const landmarksArray = landmarks
        .split(",")
        .map(l => l.trim())
        .filter(l => l.length > 0);

      const { data, error } = await supabase.functions.invoke('generate-city-image', {
        body: { 
          cityName,
          landmarks: landmarksArray.length > 0 ? landmarksArray : undefined,
          quality: 'high'
        }
      });

      if (error) throw error;

      if (data.success && data.imageUrl) {
        setGeneratedImageUrl(data.imageUrl);
        toast.success(`Изображение ${cityName} создано!`, {
          description: "Теперь вы можете скачать его в высоком разрешении"
        });
      } else {
        throw new Error('Не удалось создать изображение');
      }
    } catch (error) {
      console.error('Error generating image:', error);
      toast.error("Ошибка генерации", {
        description: "Не удалось создать изображение. Попробуйте еще раз."
      });
    } finally {
      setIsGenerating(false);
    }
  };

  const downloadImage = () => {
    if (!generatedImageUrl) return;

    const link = document.createElement('a');
    link.href = generatedImageUrl;
    link.download = `${cityName.toLowerCase().replace(/\s+/g, '-')}-hd.png`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    toast.success("Скачивание началось!", {
      description: "Изображение сохраняется на ваше устройство"
    });
  };

  return (
    <Dialog open={isOpen} onOpenChange={setIsOpen}>
      <DialogTrigger asChild>
        <Button 
          variant="secondary" 
          className="gap-2"
        >
          <Download className="h-4 w-4" />
          Экспорт HD изображения
        </Button>
      </DialogTrigger>
      <DialogContent className="sm:max-w-[600px] glass-liquid-strong">
        <DialogHeader>
          <DialogTitle className="flex items-center gap-2 text-2xl">
            <Sparkles className="h-6 w-6 text-secondary" />
            Создать HD изображение города
          </DialogTitle>
          <DialogDescription>
            Генерируйте высококачественные изображения {cityName} с ИИ
          </DialogDescription>
        </DialogHeader>

        <div className="space-y-4 py-4">
          <div className="space-y-2">
            <Label htmlFor="landmarks">Достопримечательности (через запятую)</Label>
            <Input
              id="landmarks"
              value={landmarks}
              onChange={(e) => setLandmarks(e.target.value)}
              placeholder="Эйфелева башня, Лувр, Нотр-Дам..."
              disabled={isGenerating}
            />
            <p className="text-xs text-muted-foreground">
              Укажите достопримечательности, которые должны быть на изображении
            </p>
          </div>

          {!generatedImageUrl && (
            <Button 
              onClick={generateImage} 
              disabled={isGenerating}
              className="w-full gap-2"
              size="lg"
            >
              {isGenerating ? (
                <>
                  <Loader2 className="h-5 w-5 animate-spin" />
                  Генерация изображения...
                </>
              ) : (
                <>
                  <ImageIcon className="h-5 w-5" />
                  Создать изображение
                </>
              )}
            </Button>
          )}

          {generatedImageUrl && (
            <div className="space-y-4">
              <div className="relative rounded-lg overflow-hidden border-2 border-primary/20">
                <img 
                  src={generatedImageUrl} 
                  alt={`HD изображение ${cityName}`}
                  className="w-full h-auto"
                />
              </div>
              
              <div className="flex gap-2">
                <Button 
                  onClick={downloadImage}
                  className="flex-1 gap-2"
                  size="lg"
                >
                  <Download className="h-5 w-5" />
                  Скачать HD изображение
                </Button>
                <Button 
                  onClick={() => {
                    setGeneratedImageUrl(null);
                    setIsGenerating(false);
                  }}
                  variant="outline"
                  size="lg"
                >
                  Создать новое
                </Button>
              </div>
            </div>
          )}
        </div>
      </DialogContent>
    </Dialog>
  );
};
