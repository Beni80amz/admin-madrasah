<?php

namespace App\Filament\Resources\StudentBills\Pages;

use App\Filament\Resources\StudentBills\StudentBillResource;
use App\Models\FeeItem;
use Filament\Resources\Pages\CreateRecord;

class CreateStudentBill extends CreateRecord
{
    protected static string $resource = StudentBillResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Set total_amount from the selected FeeItem
        if (isset($data['fee_item_id'])) {
            $feeItem = FeeItem::find($data['fee_item_id']);
            if ($feeItem) {
                $data['total_amount'] = $feeItem->amount;
            }
        }

        // Ensure paid_amount starts at 0
        $data['paid_amount'] = 0;
        $data['status'] = 'unpaid';

        return $data;
    }
}
