<?php

namespace App\Imports;

use App\Models\Admin;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AdminImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $adminUser = Auth::guard('admins')->user();

        try {
            if (empty($row[0]) || empty($row[4]) || empty($row[5])) {
                throw new \Exception("Missing required fields: username, date_of_birth, or department.");
            }

            $admin = Admin::create([
                'username' => $row[0],
                'password' => Hash::make($row[1]),
                'updatedAt' => Carbon::now('Asia/Ho_Chi_Minh'),
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                'updateBy' => $adminUser->id
            ]);

            $dateOfBirth = Carbon::createFromFormat('m/d/Y', $row[4])->format('Y-m-d');

            $departmentId = DB::table('departments')
                ->where('departmentName', $row[5])
                ->value('departmentId');

            if (!$departmentId) {
                throw new \Exception("Phòng ban '{$row[5]}' không tồn tại.");
            }

            // Tạo dữ liệu cho Employee
            return new Employee([
                'name' => $row[2],
                'phoneNumber' => $row[3],
                'dateOfBirth' => $dateOfBirth,
                'departmentId' => $departmentId,
                'sex' => strtolower($row[6]) === 'nam' ? 1 : 0,
                'id' => $admin->id,
                'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                'updated_at' => Carbon::now('Asia/Ho_Chi_Minh')
            ]);

        } catch (\Exception $e) {
            // Ghi log lỗi hoặc throw exception để chuyển về controller
            throw new \Exception("Dòng dữ liệu có lỗi: " . $e->getMessage());
        }
    }
}
