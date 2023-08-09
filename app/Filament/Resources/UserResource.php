<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Grid::make()
                            ->columns(2)
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                Forms\Components\Toggle::make('is_admin')
                                    ->required()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('password')
                                    ->password()
                                    ->required(
                                        fn(string $context): bool => $context === 'create'
                                    )
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->maxLength(255)
                                    ->confirmed(),
                                Forms\Components\TextInput::make('password_confirmation')
                                    ->password()
                                    ->required(
                                        fn(string $context): bool => $context === 'create'
                                    )
                                    ->maxLength(255),
                                Forms\Components\TextInput::make('profile_photo_path')
                                    ->maxLength(2048),
                                CheckboxList::make('roles')
                                    ->relationship('roles', 'name')
                                    ->columns(2)
                                    ->columnSpan(2)
                                    ->required()
                                // select only one role
                            ])

                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                IconColumn::make('is_admin')
                    ->boolean()
                    ->label('Admin')
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('profile_photo_path')
                    ->label('Avatar')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
