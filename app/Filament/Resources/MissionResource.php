<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MissionResource\Pages;
use App\Filament\Resources\MissionResource\RelationManagers;
use App\Models\Mission;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class MissionResource extends Resource
{
    protected static ?string $model = Mission::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Mission name')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->label('Mission description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('days')
                    ->label('Day(s)')
                    ->numeric()
                    ->required(),
                Forms\Components\TextInput::make('daily_rate')
                    ->label('Daily rate')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('days')
                    ->label('Days')
                    ->searchable(),
                Tables\Columns\TextColumn::make('daily_rate')
                    ->label('Daily Rate')
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->money()
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-o-pencil')
                        ->modalFooterActionsAlignment(Alignment::End),
                    Tables\Actions\ViewAction::make()
                        ->modalFooterActionsAlignment(Alignment::End),
                    Tables\Actions\DeleteAction::make()
                        ->requiresConfirmation(function (Tables\Actions\Action $action, $record) {
                            $body = "Are you sure you want to delete the mission: <br /> « {$record->name} »";

                            $action->modalHeading('Really delete this mission?');
                            $action->modalDescription(fn () => new HtmlString($body));

                            return $action;
                        }),
                ])->icon('heroicon-o-ellipsis-horizontal-circle'),
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
            'index' => Pages\ListMissions::route('/'),
        ];
    }
}
