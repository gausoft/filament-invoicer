<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CompanyResource\Pages;
use App\Filament\Resources\CompanyResource\RelationManagers;
use App\Models\Company;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\Alignment;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class CompanyResource extends Resource
{
    protected static ?string $model = Company::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('logo')
                    ->hiddenLabel()
                    ->image()
                    ->directory('companies')
                    ->avatar()
                    ->imageEditor()
                    ->circleCropper()
                    ->maxSize(1024)
                    ->columnSpanFull()
                    ->alignCenter(),
                Forms\Components\TextInput::make('name')
                    ->label('Company name')
                    ->required()
                    ->maxLength(255)
                    ->validationMessages([
                        'required' => 'Company name is required.',
                        'max' => 'Company name is too long.',
                    ]),
                Forms\Components\TextInput::make('email')
                    ->label('Company email')
                    ->required()
                    ->email()
                    ->validationMessages([
                        'required' => 'Company email is required.',
                        'email' => 'Company email is invalid.',
                    ]),
                Forms\Components\Textarea::make('address')
                    ->label('Company address')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('city')
                    ->label('Company city'),
                Forms\Components\TextInput::make('country')
                    ->label('Company country'),
                Forms\Components\TextInput::make('website')
                    ->label('Company website')
                    ->url()
                    ->validationMessages([
                        'url' => 'Company website is invalid.',
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Company name')
                    ->searchable()
                    ->sortable()
                    ->prefix(function ($record): HtmlString {
                        return new HtmlString('<img src="'. url($record->logo ?? 'placeholder.png') .'" class="rounded-full" alt="Image" style="display: inline-block; width: 32px; height: 32px; margin-right: 0.5rem;" />');
                    }),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')
                    ->searchable(),
                Tables\Columns\TextColumn::make('city')
                    ->searchable(),
                Tables\Columns\TextColumn::make('website')
                    ->searchable(),
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
                            $body = "Are you sure you want to delete the company: <br /> « {$record->name} »";

                            $action->modalHeading('Really delete this company?');
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
            'index' => Pages\ListCompanies::route('/'),
        ];
    }
}
