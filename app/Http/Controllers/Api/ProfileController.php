<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateRequest;

class ProfileController extends Controller
{
    /**
     * عرض بيانات الملف الشخصي
     */
    public function show(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'status' => 'success',
            'message' => 'تم جلب بيانات الملف الشخصي',
            'data' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    /**
     * تحديث الملف الشخصي
     */
    public function update(UpdateRequest $request)
    {
        try {
            $user = $request->user(); // بدل استخدام ID

            $validated = $request->validated();

            // تحديث البيانات
            $user->update($validated);

            return response()->json([
                'status'  => 'success',
                'message' => 'تم تحديث الملف الشخصي بنجاح',
                'data' => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'updated_at' => now()->format('Y-m-d H:i:s'),
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'حدث خطأ أثناء التحديث',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * حذف الحساب
     */
    public function destroy(Request $request)
    {
        try {
            $user = $request->user();

            $user->delete();

            return response()->json([
                'status'  => 'success',
                'message' => 'تم حذف الحساب بنجاح',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'حدث خطأ أثناء الحذف',
                'error'   => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }
}
