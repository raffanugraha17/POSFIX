<?php

namespace App\Filament\Resources;

use App\Models\ActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Filament\Tables\Columns\TextColumn; // Import the TextColumn class
use Filament\Tables\Actions\EditAction; // Import the EditAction class
use Filament\Tables\Actions\DeleteBulkAction; // Import the DeleteBulkAction class
use Filament\Tables\Actions\BulkActionGroup; // Import the BulkActionGroup class
use App\Filament\Resources\ActivityLogResource\Pages\ListActivityLogs; // Import the ListActivityLogs class

class ActivityLogResource extends Resource
{
    protected static ?string $model = ActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-lock-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Define your form fields here
            ])
            ->afterSave(function ($record, $context) {
                Log::channel('activitylog')->info("Record {$context} in ActivityLogResource", ['id' => $record->id]);
            })
            ->afterDelete(function ($record) {
                Log::channel('activitylog')->info('Record deleted in ActivityLogResource', ['id' => $record->id]);
            });
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('bank.name') // Use the imported TextColumn class directly
                    ->label('Bank')
                    ->getStateUsing(fn (ActivityLog $record) => $record->table === 'banks' ? $record->bank->name : null),
                TextColumn::make('paymentMethod.name') // Use the imported TextColumn class directly
                    ->label('Payment Method')
                    ->getStateUsing(fn (ActivityLog $record) => $record->table === 'payment_methods' ? $record->paymentMethod->name : null),
                TextColumn::make('shift.name') // Use the imported TextColumn class directly
                    ->label('Shift')
                    ->getStateUsing(fn (ActivityLog $record) => $record->table === 'shifts' ? $record->shift->name : null),
               TextColumn::make('cashierList.name') // Use the imported TextColumn class directly
                    ->label('Cashier List')
                    ->getStateUsing(fn (ActivityLog $record) => $record->table === 'cashier_lists' ? $record->cashierList->name : null),
               TextColumn::make('customerList.name') // Use the imported TextColumn class directly
                    ->label('Customer List')
                    ->getStateUsing(fn (ActivityLog $record) => $record->table === 'customer_lists' ? $record->customerList->name : null),
            
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(), // Use the imported EditAction class directly
            ])
            ->bulkActions([
                BulkActionGroup::make([ // Use the imported BulkActionGroup class directly
                    DeleteBulkAction::make(), // Use the imported DeleteBulkAction class directly
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListActivityLogs::route('/'),
        ];
    }
}
