<?php

namespace App\Repositories;

use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class VehicleRepository
{
    public function getAllVehicles()
    {
        return Vehicle::with('driver')->latest()->get();
    }
    public function getVehicleById(string $id): ?Vehicle
    {
        return Vehicle::with('driver')->find($id);
    }
    public function createVehicle(array $data): Vehicle
    {
        return Vehicle::create($data);
    }
    public function updateVehicle(string $id, array $data): bool
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return false;
        }
        return $vehicle->update($data);
    }
    public function deleteVehicle(string $id): bool
    {
        $vehicle = Vehicle::find($id);
        if (!$vehicle) {
            return false;
        }
        return $vehicle->delete();
    }
    public function assignDriver(string $vehicleId, string $driverId): ?Vehicle
    {
        // نبدأ Transaction لضمان تنفيذ عمليات نقل التعيين كوحدة واحدة
        return DB::transaction(function () use ($vehicleId, $driverId) {

            // نبحث عن العربية المطلوبة باستخدام الـ ID
            // lockForUpdate يقفل سجل العربية أثناء الـ Transaction
            $vehicle = Vehicle::whereKey($vehicleId)
                ->lockForUpdate()
                ->first();

            // لو العربية غير موجودة، نرجع null
            if (!$vehicle) {
                return null;
            }

            // نفك ارتباط أي عربية أخرى مرتبطة بالسائق الجديد
            // نستثني العربية الحالية حتى لا نفصلها قبل تعيينها
            Vehicle::where('user_id', $driverId)
                ->where('id', '!=', $vehicle->id)
                ->update(['user_id' => null]);

            // نربط العربية المطلوبة بالسائق الجديد
            $vehicle->user_id = $driverId;

            // نحفظ التغيير في قاعدة البيانات
            $vehicle->save();

            // نحمّل بيانات المستخدم المرتبط بالعربية
            // العلاقة user معرفة في Vehicle Model
            return $vehicle->load('user');
        });
    }

    public function getLiveLocations()
    {
        return Vehicle::with(['driver', 'latestLocation'])->where('status', 'active')->get();
    }
}
