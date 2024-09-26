<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Filament\Resources\InvoiceResource\RelationManagers;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Invoice;
use App\Models\Mission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Invoice name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('custom_id')
                    ->label('Custom ID'),
                Forms\Components\Select::make('missions')
                    ->label('Missions')
                    ->options(Mission::all()->pluck('name', 'id'))
                    ->multiple()
                    ->searchable()
                    ->native(false)
                    ->columnSpanFull(),
                Forms\Components\Select::make('company_id')
                    ->label('Company')
                    ->options(Company::all()->pluck('name', 'id'))
                    ->searchable()
                    ->native(false),
                Forms\Components\Select::make('contact')
                    ->label('Contact')
                    ->options(Contact::all()->pluck('full_name', 'id'))
                    ->searchable(['firstname', 'lastname'])
                    ->native(false),
                Forms\Components\TextInput::make('discount')
                    ->numeric(),
                Forms\Components\TextInput::make('tax')
                    ->label('Tax(%)')
                    ->numeric(),
                Forms\Components\DatePicker::make('date')
                    ->label('Invoice Date')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListInvoices::route('/'),
        ];
    }
}
