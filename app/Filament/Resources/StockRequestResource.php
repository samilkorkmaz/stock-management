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
use Filament\Tables\Actions\Action;
use Illuminate\Support\Facades\DB;

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
                    ->required(),
                Select::make('item_id')
                    ->relationship('item', 'name')
                    ->required(),
                TextInput::make('quantity')
                    ->numeric()
                    ->required(),
                Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required(),
                TextInput::make('allocated_quantity')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('shop.name'),
                TextColumn::make('item.name'),
                TextColumn::make('quantity'),
                TextColumn::make('status'),
                TextColumn::make('allocated_quantity'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('allocate')
                    ->form([
                        TextInput::make('allocated_quantity')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                    ])
                    ->action(function (StockRequest $record, array $data): void {
                        DB::transaction(function () use ($record, $data) {
                            // Calculate the change in allocation
                            $allocationDifference = $data['allocated_quantity'] - $record->allocated_quantity;

                            // Load the item with fresh data
                            $item = $record->item()->lockForUpdate()->first();

                            // Update item quantity
                            $item->quantity -= $allocationDifference;
                            $item->save();

                            // Update stock request
                            $record->update([
                                'allocated_quantity' => $data['allocated_quantity'],
                                'status' => 'approved'
                            ]);
                        });
                    })
                    ->visible(fn (StockRequest $record): bool => $record->status !== 'rejected')
                    ->requiresConfirmation(),
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
}
