<?php

namespace App\Imports;

use App\Models\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LeadsImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new Lead([
            'name'  => $row['name'],
            'email' => $row['email'] ?? null,
            'phone' => $row['phone'],
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:leads,email',
            'phone' => 'required|numeric',
        ];
    }
}