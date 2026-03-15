<?php

namespace App\Filament\Staff\Resources\Publications;

use App\Filament\Staff\Resources\Publications\Pages;
use App\Models\PenerbitanStaf;
use App\Models\PubAuthor;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Placeholder;

class PublicationResource extends Resource
{
    protected static ?string $model = PenerbitanStaf::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['nostaf'] = Auth::user()?->staff_id;

        return $data;
    }

    protected static string | \BackedEnum | null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'My Publications';
    protected static ?string $pluralLabel = 'Publications';
    protected static ?string $slug = 'penerbitan';

    public static function getEloquentQuery(): Builder
    {
        $staffId = Auth::user()?->staff_id;

        $pubIds = PubAuthor::where('nostaf', $staffId)
            ->pluck('pub_item_id')
            ->unique()
            ->toArray();

        return parent::getEloquentQuery()
            ->with('authors')
            ->whereIn('id', $pubIds)
            ->orderBy('publish_date', 'desc');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Publication Details')
                    ->schema([

                        TextInput::make('title')
                            ->label('Title')
                            ->required()
                            ->maxLength(500)
                            ->columnSpanFull(),

                        Select::make('type')
                            ->label('Type of Publication')
                            ->options([
                                'journal' => 'Journal',
                                'book' => 'Book',
                                'chapter-in-book' => 'Book Chapter',
                                'conference' => 'Conference Paper',
                                'other' => 'Other Publication',
                            ])
                            ->required()
                            ->native(false),

                        Grid::make(2)
                            ->schema([
                                DatePicker::make('publish_date')
                                    ->label('Publish Date')
                                    ->required()
                                    ->maxDate(now()),

                                TextInput::make('evidence')
                                    ->label('URL/Link Evidence')
                                    ->url()
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Additional Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('journal')
                                    ->label('Journal Name')
                                    ->visible(fn (Get $get) => $get('type') === 'journal'),

                                TextInput::make('volume')
                                    ->label('Volume')
                                    ->visible(fn (Get $get) => $get('type') === 'journal'),

                                TextInput::make('issue')
                                    ->label('Issue')
                                    ->visible(fn (Get $get) => $get('type') === 'journal'),

                                TextInput::make('pages')
                                    ->label('Pages')
                                    ->visible(fn (Get $get) => in_array($get('type'), ['journal', 'chapter-in-book', 'conference'])),

                                TextInput::make('publisher')
                                    ->label('Publisher')
                                    ->visible(fn (Get $get) => in_array($get('type'), ['book', 'chapter-in-book'])),

                                TextInput::make('conference')
                                    ->label('Conference Name')
                                    ->visible(fn (Get $get) => $get('type') === 'conference'),

                                TextInput::make('doi')
                                    ->label('DOI')
                                    ->maxLength(255),
                            ]),
                    ]),

                Section::make('Authors')
                    ->schema([
                        Placeholder::make('main_author_info')
                            ->label('Main Author Name (Staff ID)')
                            ->content(function (): string {
                                $user = Auth::user();

                                if (! $user) {
                                    return 'No staff information found.';
                                }

                                $name = $user->name ?? 'N/A';
                                $staffId = $user->staff_id ?? 'N/A';

                                return "{$name} ({$staffId})";
                            }),
                        Repeater::make('authors')
                            ->schema([
                                Grid::make(3)
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Author Name')
                                            ->required()
                                            ->maxLength(255),

                                        Select::make('is_staff')
                                            ->label('Status')
                                            ->options([
                                                1 => 'UniSHAMS Staff',
                                                0 => 'External Author',
                                            ])
                                            ->default(0)
                                            ->native(false)
                                            ->live(),

                                        TextInput::make('nostaf')
                                            ->label('Staff ID')
                                            ->visible(fn (Get $get) => (int) $get('is_staff') === 1)
                                            ->maxLength(50),
                                    ]),
                            ])
                            ->defaultItems(0)
                            ->maxItems(10)
                            ->addActionLabel('Add more authors')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->limit(50)
                    ->wrap(),

                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state)))
                    ->color(fn ($state) => match ($state) {
                        'journal' => 'success',
                        'book' => 'info',
                        'conference' => 'warning',
                        default => 'gray',
                    }),

                TextColumn::make('authors_count')
                    ->label('Authors')
                    ->getStateUsing(fn ($record) => $record->authors->count() . ' orang'),

                TextColumn::make('publish_date')
                    ->label('Year')
                    ->date('Y')
                    ->sortable(),

                TextColumn::make('indexes_list')
                    ->label('Index')
                    ->badge()
                    ->getStateUsing(fn ($record) => $record->indexes?->pluck('name')->join(', ')),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Type')
                    ->options([
                        'journal' => 'Journal',
                        'book' => 'Book',
                        'chapter-in-book' => 'Book Chapter',
                        'conference' => 'Conference',
                    ]),

                Tables\Filters\Filter::make('publish_date')
                    ->form([
                        DatePicker::make('published_from')
                            ->label('From'),
                        DatePicker::make('published_until')
                            ->label('Until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['published_from'] ?? null, fn (Builder $q, $date) => $q->whereDate('publish_date', '>=', $date))
                            ->when($data['published_until'] ?? null, fn (Builder $q, $date) => $q->whereDate('publish_date', '<=', $date));
                    }),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('View'),

                EditAction::make()
                    ->label('Edit')
                    ->visible(fn ($record) => $record->authors->contains('nostaf', Auth::user()?->staff_id)),

                DeleteAction::make()
                    ->label('Delete')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->authors->contains('nostaf', Auth::user()?->staff_id)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(false),
                ]),
            ])
            ->defaultSort('publish_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPublications::route('/'),
            'create' => Pages\CreatePublication::route('/create'),
            'view' => Pages\ViewPublication::route('/{record}'),
            'edit' => Pages\EditPublication::route('/{record}/edit'),
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->check() && ! is_null(auth()->user()?->staff_id);
    }
}
