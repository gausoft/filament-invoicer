<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContactResource\Pages;
use App\Filament\Resources\ContactResource\RelationManagers;
use App\Models\Client;
use App\Models\Contact;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ContactResource extends Resource
{
    protected static ?string $model = Contact::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::createContactFormSchema(clientField: [
                Forms\Components\Select::make('client_id')
                    ->label('Client')
                    ->native(false)
                    ->searchable()
                    ->options(Client::all()->pluck('name', 'id'))
            ]));
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('full_name')
                    ->label('Full name')
                    ->searchable(['firstname', 'lastname'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('client.name')
                    ->label('Client')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('Phone Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('job')
                    ->label('Job')
                    ->searchable()
                    ->sortable(),
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
                            $body = "Are you sure you want to delete the contact: <br /> « {$record->name} »";

                            $action->modalHeading('Really delete this contact?');
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



    public static function createContactFormSchema(array $clientField = []): array
    {
        return [
            Forms\Components\TextInput::make('firstname')
                ->label('Firstname')
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('lastname')
                ->label('Lastname')
                ->required()
                ->maxLength(255),
            ...$clientField,
            Forms\Components\TextInput::make('phone')
                ->label('Phone Number')
                ->required(),
            Forms\Components\TextInput::make('email')
                ->label('Email')
                ->required()
                ->email(),
            Forms\Components\TextInput::make('job')
                ->label('Job Title'),
        ];
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
            'index' => Pages\ListContacts::route('/'),
        ];
    }
}
