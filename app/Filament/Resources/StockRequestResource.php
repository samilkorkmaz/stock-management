<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockRequestResource\Pages;
use App\Models\StockRequest;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class StockRequestResource extends Resource
{
    protected static ?string $model = StockRequest::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('shop_id')
                    ->relationship('shop', 'name')
                    ->required()
                    ->disabled(),
                Select::make('item_id')
                    ->relationship('item', 'name')
                    ->required()
                    ->disabled(),
                TextInput::make('quantity')
                    ->numeric()
                    ->required()
                    ->disabled(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                TextInput::make('allocated_quantity')
                    ->numeric()
                    ->required()
                    ->afterStateHydrated(function (TextInput $component, $state, ?StockRequest $record) {
                        if ($record && !$state) {
                            $component->state($record->quantity);
                        }
                    })
                    ->rules(['required', 'numeric', 'min:1'])
                    ->validationMessages([
                        'required' => 'Please enter an allocation amount',
                        'numeric' => 'The allocation must be a number',
                        'min' => 'The allocation must be at least 1',
                    ])
                    ->live()
                    ->dehydrateStateUsing(function ($state, StockRequest $record) {
                        if ($state > $record->quantity) {
                            throw new \Exception("Cannot allocate more than requested quantity ({$record->quantity} units)");
                        }

                        $item = $record->item;
                        $currentAllocated = $record->allocated_quantity;
                        $availableStock = $item->quantity + $currentAllocated;

                        if ($state > $availableStock) {
                            throw new \Exception("Cannot allocate more than available stock ({$availableStock} units available)");
                        }

                        return $state;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('shop.name'),
                TextColumn::make('item.name'),
                TextColumn::make('quantity'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'pending' => 'warning',
                    }),
                TextColumn::make('allocated_quantity'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockRequests::route('/'),
            'create' => Pages\CreateStockRequest::route('/create'),
            'edit' => Pages\EditStockRequest::route('/{record}/edit'),
        ];
    }

    public static function beforeSave(Model $record): void
    {
        if ($record->isDirty('status') && $record->status === 'approved') {
            // Update item stock
            $item = $record->item;
            if ($record->getOriginal('allocated_quantity') > 0) {
                // Return previously allocated stock
                $item->increment('quantity', $record->getOriginal('allocated_quantity'));
            }
            // Deduct newly allocated stock
            $item->decrement('quantity', $record->allocated_quantity);
        }

        // If status changes to rejected or pending, return any allocated stock
        if ($record->isDirty('status') &&
            ($record->status === 'rejected' || $record->status === 'pending') &&
            $record->getOriginal('allocated_quantity') > 0) {

            $record->item->increment('quantity', $record->getOriginal('allocated_quantity'));
            $record->allocated_quantity = 0;
        }
    }
}
