<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use App\Models\Activity;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static ?int $navigationSort = 100;

    public static function getNavigationIcon(): string
    {
        return 'heroicon-o-clipboard-document-list';
    }

    public static function getNavigationGroup(): string
    {
        return 'System';
    }

    public static function getModelLabel(): string
    {
        return 'Activity Log';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Activity Logs';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Activity Details')
                    ->schema([
                        TextInput::make('log_name')
                            ->label('Log Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('description')
                            ->label('Description')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('event')
                            ->label('Event')
                            ->maxLength(255),
                    ]),
                Section::make('Subject')
                    ->schema([
                        TextInput::make('subject_type')
                            ->label('Subject Type')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('subject_id')
                            ->label('Subject ID')
                            ->required()
                            ->maxLength(36),
                    ]),
                Section::make('Causer')
                    ->schema([
                        TextInput::make('causer_type')
                            ->label('Causer Type')
                            ->maxLength(255),
                        TextInput::make('causer_id')
                            ->label('Causer ID')
                            ->maxLength(36),
                    ]),
                Section::make('Properties')
                    ->schema([
                        Textarea::make('properties')
                            ->label('Properties (JSON)')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date/Time')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Log')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('event')
                    ->label('Event')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'created' => 'success',
                        'updated' => 'info',
                        'deleted' => 'danger',
                        'restored' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->toggleable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Subject')
                    ->searchable()
                    ->toggleable()
                    ->format(fn ($state) => class_basename($state)),
                Tables\Columns\TextColumn::make('causer_type')
                    ->label('Causer')
                    ->searchable()
                    ->toggleable()
                    ->format(fn ($state) => $state ? class_basename($state) : 'System'),
            ])
            ->filters([
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('From'),
                        DatePicker::make('created_until')
                            ->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date)
                            );
                    }),
                Tables\Filters\SelectFilter::make('event')
                    ->label('Event')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                        'restored' => 'Restored',
                    ]),
                Tables\Filters\SelectFilter::make('log_name')
                    ->label('Log Name')
                    ->options(fn () => Activity::query()->distinct('log_name')->pluck('log_name', 'log_name')->toArray()),
                Tables\Filters\Filter::make('subject_type')
                    ->label('Subject Type')
                    ->form([
                        Select::make('subject_type')
                            ->label('Subject Type')
                            ->options([
                                'App\Models\Catalog\Product' => 'Product',
                                'App\Models\Catalog\ProductVariant' => 'Product Variant',
                                'App\Models\Order\Order' => 'Order',
                                'App\Models\Payment\Payment' => 'Payment',
                                'App\Models\Auth\Customer' => 'Customer',
                                'App\Models\Auth\Admin' => 'Admin',
                                'App\Models\Coupon\Coupon' => 'Coupon',
                                'App\Models\Inventory\StockMovement' => 'Stock Movement',
                            ]),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['subject_type'],
                            fn (Builder $query, $subjectType): Builder => $query->where('subject_type', $subjectType)
                        );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
            'view' => Pages\ViewActivityLog::route('/{record}'),
        ];
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getRoutes(): array
    {
        return [
            'index' => static::route('/'),
            'view' => static::route('/{record}'),
        ];
    }
}
