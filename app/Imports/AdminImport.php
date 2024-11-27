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
use Maatwebsite\Excel\Concerns\WithUpserts;

class AdminImport implements ToModel, ToCollection, WithHeadingRow
{
    private $num = 0;
    /**
    * @param collection $collection
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $collection)
    {
        //dd($collection);
    }
    public function model(array $row)
    {
        $this->num++;

        $adminUser = Auth::guard('admins')->user();

        $updateBy = DB::table('employees')->where('id', $adminUser->id)->pluck('employeeId')->first();

        if ($this->num > 1) {
            $count = Admin::where('username', $row['username'])->count();
            $count2 = Employee::where('phoneNumber', $row['phone_number'])->count();

            if (empty($count) && empty($count2)) {
                $admin = Admin::create([
                    'username' => $row['username'],
                    'password' => Hash::make($row['password']),
                    'updatedAt' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    'updateBy' => $updateBy
                ]);
                // Format date_of_birth
                $dateOfBirth = Carbon::createFromFormat('d/m/Y', $row['date_of_birth'])->format('Y-m-d');

                // Get department ID
                $departmentId = DB::table('departments')
                    ->where('departmentName', $row['department'])
                    ->value('departmentId');

                if (!$departmentId) {
                    throw new \Exception("Department '{$row['department']}' does not exist.");
                }

                
                    return new Employee([
                        'name' => $row['name'],
                        'phoneNumber' => $row['phone_number'],
                        'dateOfBirth' => $dateOfBirth,
                        'departmentId' => $departmentId,
                        'sex' => strtolower($row['sex']) === 'Nam' ? 1 : 0,
                        'id' => $admin->id,
                        'created_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                        'updated_at' => Carbon::now('Asia/Ho_Chi_Minh'),
                    ]);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
