<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
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

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Client name')
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Client name is required.',
                        'max' => 'Client name is too long.',
                    ])
                    ->columnSpanFull(),
                Forms\Components\Select::make('contacts')
                    ->label('Contacts')
//                    ->relationship('contacts', 'full_name')
                    ->options(Contact::all()->pluck('full_name', 'id'))
                    ->multiple()
                    ->searchable(['firstname', 'lastname'])
                    ->native(false)
                    ->columnSpanFull()
                    ->createOptionForm(ContactResource::createContactFormSchema())
                    ->createOptionAction(fn ($action) => $action->modalFooterActionsAlignment(Alignment::End))
                    ->createOptionModalHeading('Add a new contact')
                    ->createOptionUsing(function (array $data) {
                        $contact = Contact::create($data);

                        return $contact->id;
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Client name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contacts.full_name')
                    ->label('Contacts')
                    ->searchable(['firstname', 'lastname'])
                    ->badge()
                    ->separator(','),
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
                            $body = "Are you sure you want to delete the client: <br /> « {$record->name} »";

                            $action->modalHeading('Really delete this client?');
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
            'index' => Pages\ListClients::route('/'),
        ];
    }
}
