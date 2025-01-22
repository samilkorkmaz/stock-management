<?php

namespace App\Filament\Resources\StockRequestResource\Pages;

use App\Filament\Resources\StockRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Item;

class EditStockRequest extends EditRecord
{
    protected static string $resource = StockRequestResource::class;
    private array $originalData = [];

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function beforeSave(): void
    {
        $record = $this->getRecord();

        // Store original values
        $this->originalData = [
            'status' => $record->status,
            'allocated_quantity' => (int)$record->allocated_quantity,
            'item_id' => $record->item_id,
        ];

        Log::info('Original Data Stored', [
            'original_status' => $this->originalData['status'],
            'original_allocation' => $this->originalData['allocated_quantity'],
            'new_status' => $this->data['status'],
            'new_allocation' => (int)$this->data['allocated_quantity']
        ]);
    }

    protected function afterSave(): void
    {
        DB::transaction(function () {
            $record = $this->getRecord();
            $wasApproved = $this->originalData['status'] === 'approved';
            $isApproved = $this->data['status'] === 'approved';
            $oldAllocation = $this->originalData['allocated_quantity'];
            $newAllocation = (int)$this->data['allocated_quantity'];

            Log::info('Processing Stock Update', [
                'was_approved' => $wasApproved,
                'is_approved' => $isApproved,
                'old_allocation' => $oldAllocation,
                'new_allocation' => $newAllocation
            ]);

            // Get item with lock
            $item = $record->item()->lockForUpdate()->first();

            if (!$wasApproved && $isApproved) {
                // Newly approved - deduct allocation
                $item->decrement('quantity', $newAllocation);

                Log::info('New Approval - Deducted Stock', [
                    'item_id' => $item->id,
                    'deducted' => $newAllocation
                ]);
            }
            elseif ($wasApproved && !$isApproved) {
                // Unapproved - return allocation
                $item->increment('quantity', $oldAllocation);

                Log::info('Unapproved - Returned Stock', [
                    'item_id' => $item->id,
                    'returned' => $oldAllocation
                ]);
            }
            elseif ($wasApproved && $isApproved && $oldAllocation !== $newAllocation) {
                // Changed allocation - adjust the difference
                $difference = $oldAllocation - $newAllocation;
                $item->increment('quantity', $difference);

                Log::info('Changed Allocation', [
                    'item_id' => $item->id,
                    'old' => $oldAllocation,
                    'new' => $newAllocation,
                    'difference' => $difference
                ]);
            }

            // Get final state
            $item->refresh();
            Log::info('Final State', [
                'item_id' => $item->id,
                'final_quantity' => $item->quantity
            ]);
        });
    }
}
