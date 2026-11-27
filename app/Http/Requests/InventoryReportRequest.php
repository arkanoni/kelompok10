<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InventoryReportRequest extends FormRequest
{
    protected $redirectRoute = 'reports.inventory';

    public function authorize(): bool
    {
        $role = $this->user()?->role;

        // Hak akses khusus laporan, tanpa mengubah middleware modul lain.
        return match ($this->route()?->getActionMethod()) {
            'inventory' => in_array($role, ['admin', 'pegawai'], true),
            'export' => $role === 'admin',
            default => false,
        };
    }

    /** @return array<string, array<string>> */
    public function rules(): array
    {
        return [
            'category' => ['nullable', 'integer', 'exists:categories,id'],
        ];
    }

    /** @return array<string, string> */
    public function messages(): array
    {
        return [
            'category.integer' => 'Kategori yang dipilih tidak valid.',
            'category.exists' => 'Kategori yang dipilih tidak ditemukan.',
        ];
    }
}
