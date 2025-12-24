import { serve } from "https://deno.land/std@0.168.0/http/server.ts";

const corsHeaders = {
  'Access-Control-Allow-Origin': '*',
  'Access-Control-Allow-Headers': 'authorization, x-client-info, apikey, content-type',
};

serve(async (req) => {
  if (req.method === 'OPTIONS') {
    return new Response(null, { headers: corsHeaders });
  }

  try {
    const { cityName, landmarks, quality = 'high' } = await req.json();

    if (!cityName) {
      return new Response(
        JSON.stringify({ error: 'City name is required' }),
        { headers: { ...corsHeaders, 'Content-Type': 'application/json' }, status: 400 }
      );
    }

    console.log(`Generating ${quality} quality image for ${cityName}`);

    // Construct detailed prompt for landmarks
    const landmarksText = landmarks && landmarks.length > 0 
      ? `featuring ${landmarks.join(', ')}` 
      : 'with its most iconic landmarks';

    const prompt = `A stunning panoramic hero banner image of ${cityName}, ${landmarksText}. Sunset golden hour lighting with warm purple and pink tones in the sky creating a magical atmosphere. Professional travel photography with cinematic composition. Ultra high resolution, 16:9 aspect ratio, photorealistic detail. Seamless blend of iconic architecture and cityscape. Award-winning travel photography style.`;

    const response = await fetch("https://ai.gateway.lovable.dev/v1/chat/completions", {
      method: "POST",
      headers: {
        Authorization: `Bearer ${Deno.env.get('LOVABLE_API_KEY')}`,
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        model: "google/gemini-3-pro-image-preview",
        messages: [
          {
            role: "user",
            content: prompt
          }
        ],
        modalities: ["image", "text"]
      })
    });

    if (!response.ok) {
      const error = await response.text();
      console.error('AI Gateway error:', error);
      throw new Error(`AI Gateway error: ${error}`);
    }

    const data = await response.json();
    const imageUrl = data.choices?.[0]?.message?.images?.[0]?.image_url?.url;

    if (!imageUrl) {
      throw new Error('No image generated');
    }

    console.log(`Successfully generated image for ${cityName}`);

    return new Response(
      JSON.stringify({ 
        success: true,
        imageUrl,
        cityName,
        prompt: prompt.substring(0, 100) + '...'
      }),
      { headers: { ...corsHeaders, 'Content-Type': 'application/json' } }
    );
  } catch (error) {
    console.error('Error generating image:', error);
    return new Response(
      JSON.stringify({ 
        error: 'Failed to generate image', 
        details: error instanceof Error ? error.message : 'Unknown error'
      }),
      { headers: { ...corsHeaders, 'Content-Type': 'application/json' }, status: 500 }
    );
  }
});
