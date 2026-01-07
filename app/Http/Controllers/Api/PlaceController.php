<?php

namespace App\Http\Controllers\Api;

use App\Models\Place;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GarageResource;
use Illuminate\Support\Facades\Storage;

class PlaceController extends Controller
{
    /**
     * عرض جميع الأماكن
     * GET /api/places
     */
    public function index(Request $request)
{
    $query = Place::with('parkingSpots');

    if ($request->q) {
        $query->where('name', 'like', "{$request->q}%");
    }

        return GarageResource::collection($query->get());

}



    public function shaw($id)
{
    $place = Place::with('parkingSpots')->find($id);

    if (!$place) {
        return response()->json(['message' => 'Place not found'], 404);
    }

    return response()->json([
        'data' => $place
    ]);
}

    /**
     * البحث عن مكان بالاسم
     * GET /api/places/search?name=اسم_المكان
     */
    public function searchByName(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:2'
            ]);

            $name = $request->name;

            $place = Place::with('parkingSpots')->where('name', 'like', "%{$name}%")
                ->orWhere('street', 'like', "%{$name}%")
                ->orWhere('city', 'like', "%{$name}%")
                ->first();

            if (!$place) {
                return response()->json([
                    'success' => false,
                    'message' => 'لم يتم العثور على المكان'
                ], 404);
            }

            // إعداد بيانات الخريطة
            $mapInfo = [
                'center' => [
                    'lat' => (float) $place->lat,
                    'lng' => (float) $place->lng
                ],
                'zoom' => 15,
                'marker' => [
                    'position' => [
                        'lat' => (float) $place->lat,
                        'lng' => (float) $place->lng
                    ],
                    'title' => $place->name,
                    'infoWindow' => $this->generateInfoWindowHtml($place)
                ],
                'directions' => [
                    'google_maps_url' => $place->google_maps_url ?: "https://maps.google.com/?q={$place->lat},{$place->lng}"
                ]
            ];

            $responseData = [
                'place' => $place,
                'map_info' => $mapInfo
            ];

            return response()->json([
                'success' => true,
                'message' => 'تم العثور على المكان',
                'data' => $responseData
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء البحث',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * إنشاء مكان جديد
     * POST /api/places
     */
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price_per_hour' => 'required|integer|min:0',
        'address' => 'required|string|max:255',
        'lat' => 'required|numeric|between:-90,90',
        'lng' => 'required|numeric|between:-180,180',
        'amenities' => 'nullable|array',
        'amenities.*' => 'string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {
        $validated['image'] = $request->file('image')->store('places', 'public');
    }

    $place = Place::create($validated);

    return response()->json([
        'data' => $place
    ], 201);
}



    /**
     * دالة مساعدة: توليد HTML لـ InfoWindow
     */
    private function generateInfoWindowHtml(Place $place)
    {
        $html = '
        <div style="padding: 15px; max-width: 300px; font-family: Arial, sans-serif;">
            <div style="margin-bottom: 10px;">
                <h3 style="margin: 0 0 5px 0; color: #333; font-size: 18px;">' . htmlspecialchars($place->name) . '</h3>
                <div style="color: #666; font-size: 14px; margin-bottom: 5px;">
                    📍 ' . htmlspecialchars($place->street) . '
                </div>
                <div style="color: #666; font-size: 14px; margin-bottom: 5px;">
                    🏙️ ' . htmlspecialchars($place->city) . ', ' . htmlspecialchars($place->country) . '
                </div>
                <div style="color: #2ecc71; font-size: 16px; font-weight: bold; margin: 10px 0;">
                    💰 $' . number_format($place->price, 2) . ' يومياً
                </div>
            </div>

            <div style="margin: 10px 0;">
                <p style="margin: 5px 0; color: #555; font-size: 14px;">
                    ' . substr(htmlspecialchars($place->description), 0, 100) . '...
                </p>
            </div>

            <div style="margin-top: 15px;">
                <a href="' . htmlspecialchars($place->google_maps_url ?: "https://maps.google.com/?q={$place->lat},{$place->lng}") . '"
                   target="_blank"
                   style="display: inline-block; padding: 8px 15px; background: #4285f4; color: white;
                          text-decoration: none; border-radius: 4px; text-align: center; font-size: 14px;">
                    🗺️ فتح في خرائط جوجل
                </a>
            </div>
        </div>';

        return $html;
    }


    /**
     * تحديث مكان
     * PUT /api/places/{id}
     */
   public function update(Request $request, $id)
{
    $place = Place::find($id);

    if (!$place) {
        return response()->json(['message' => 'Place not found'], 404);
    }

    $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'description' => 'nullable|string',
        'price_per_hour' => 'sometimes|integer|min:0',
        'address' => 'sometimes|string|max:255',
        'lat' => 'sometimes|numeric|between:-90,90',
        'lng' => 'sometimes|numeric|between:-180,180',
        'amenities' => 'nullable|array',
        'amenities.*' => 'string',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {
        if ($place->image && Storage::disk('public')->exists($place->image)) {
            Storage::disk('public')->delete($place->image);
        }

        $validated['image'] = $request->file('image')->store('places', 'public');
    }

    $place->update($validated);

    return response()->json([
        'data' => $place
    ]);
}




    /**
     * حذف مكان
     * DELETE /api/places/{id}
     */
    public function destroy($id)
{
    $place = Place::find($id);

    if (!$place) {
        return response()->json(['message' => 'Place not found'], 404);
    }

    $place->delete();

    return response()->json(['message' => 'Deleted']);
}

}
