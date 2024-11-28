<?php

namespace App\Imports;

use App\Models\Admin;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class AdminImport implements WithHeadingRow, ToCollection
{
    /**
    * @param Collection \$rows
    *
    * 
    */
    public function collection(collection $rows)
    {
        $adminUser = Auth::guard('admins')->user();
        $updateBy = DB::table('employees')->where('id', $adminUser->id)->pluck('employeeId')->first();

        foreach ($rows as $row) {
            if ($row->filter()->isEmpty()) {
                continue; // Bỏ qua dòng trống
            }

            // Kiểm tra sự tồn tại của dữ liệu
            $count = Admin::where('username', $row['username'])->count();
            $count2 = Employee::where('phoneNumber', $row['phone_number'])->count();

            if (empty($count) && empty($count2)) {
                $date = Date::excelToDateTimeObject($row['date_of_birth']);
                $date_format = Carbon::instance($date)->format('Y-m-d');

                $admin = Admin::create([
                    'username' => $row['username'],
                    'password' => Hash::make($row['password']),
                    'updatedAt' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'updateBy' => $updateBy
                ]);

                $departmentId = DB::table('departments')
                    ->where('departmentName', $row['department'])
                    ->value('departmentId');

                if (!$departmentId) {
                    throw new \Exception("Department '{$row['department']}' does not exist.");
                }

                Employee::create([
                    'name' => $row['name'],
                    'phoneNumber' => $row['phone_number'],
                    'dateOfBirth' => $date_format,
                    'departmentId' => $departmentId,
                    'sex' => strtolower($row['sex']) === 'nam' ? 1 : 0,
                    'id' => $admin->id,
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                ]);
            }
        }
    }
}
