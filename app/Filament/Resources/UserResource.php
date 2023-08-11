<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\Pages\CreateUser;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Filament\Resources\UserResource\RelationManagers\RolesRelationManager;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
  protected static ?string $model = User::class;

  protected static ?string $navigationIcon = 'heroicon-o-user';

  protected static ?string $navigationGroup = 'Admin Management';

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
                  ->dehydrateStateUsing(
                    static fn (null|string $state): null|string => filled($state)
                    ? Hash::make($state) : null)
                  ->required(
                    fn (Page $livewire): bool => $livewire instanceof CreateUser
                  )
                  ->dehydrated(
                    fn (null|string $state) : bool => filled($state)
                  )
                  ->label(
                    fn (Page $livewire): string => $livewire instanceof CreateUser
                      ? 'Password' : 'New password'
                  )
                  ->confirmed(),
                Forms\Components\TextInput::make('password_confirmation')
                  ->password()
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
        TrashedFilter::make('trashed')
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
      RolesRelationManager::class,
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
