<?php

namespace App\Exports;

use App\Models\Admin;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class AdminExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Admin::select("id", "username", "role")->get();
    }
    public function headings(): array
    {
        return ["ID", "User Name", "Role"];
    }
}
