<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomBlockResource\Pages;
use App\Filament\Resources\CustomBlockResource\RelationManagers;
use App\Models\CustomBlock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ViewField;
use Wiebenieuwenhuis\FilamentCodeEditor\Components\CodeEditor;

class CustomBlockResource extends Resource
{
    protected static ?string $model = CustomBlock::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';
    
    protected static ?string $navigationLabel = 'Custom Blocks';
    
    protected static ?string $modelLabel = 'Custom Block';
    
    protected static ?string $pluralModelLabel = 'Custom Blocks';
    
    protected static ?string $navigationGroup = 'Design System';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Block Builder')
                    ->tabs([
                        Tabs\Tab::make('Basic Info')
                            ->schema([
                                Section::make('Block Details')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('name')
                                                    ->required()
                                                    ->maxLength(255)
                                                    ->placeholder('e.g., Feature Card, Hero Section')
                                                    ->helperText('A descriptive name for this block'),
                                                
                                                TextInput::make('slug')
                                                    ->maxLength(255)
                                                    ->helperText('Auto-generated from name, or customize')
                                                    ->unique(CustomBlock::class, 'slug', ignoreRecord: true),
                                            ]),
                                        
                                        Textarea::make('description')
                                            ->rows(3)
                                            ->placeholder('Describe what this block does...')
                                            ->helperText('Description shown in the block manager'),
                                        
                                        Grid::make(3)
                                            ->schema([
                                                Select::make('category')
                                                    ->options(CustomBlock::getCategories())
                                                    ->default('components')
                                                    ->required(),
                                                
                                                Select::make('icon')
                                                    ->options(CustomBlock::getIcons())
                                                    ->placeholder('Choose an icon')
                                                    ->helperText('Icon shown in block manager'),
                                                
                                                Toggle::make('is_active')
                                                    ->label('Active')
                                                    ->default(true)
                                                    ->helperText('Enable/disable this block'),
                                            ]),
                                        
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('sort_order')
                                                    ->numeric()
                                                    ->default(0)
                                                    ->helperText('Order in block manager'),
                                            ]),
                                    ]),
                            ]),
                        Tabs\Tab::make('HTML')
                            ->schema([
                                Section::make('HTML Structure')
                                    ->description('Write the HTML structure for your block. Use data-gjs attributes for GrapesJS integration.')
                                    ->schema([
                                        CodeEditor::make('html_content')
                                            ->label('HTML Content')
                                            ->helperText('Only HTML is allowed. Use data-gjs-type="text" and data-gjs-name="unique-name" to make elements editable'),
                                    ]),
                            ]),
                        Tabs\Tab::make('CSS Styling')
                            ->schema([
                                Section::make('Custom CSS')
                                    ->description('Add custom CSS styles for your block')
                                    ->schema([
                                        CodeEditor::make('css_content')
                                            ->label('CSS Content')
                                            ->helperText('CSS will be scoped to this block'),
                                    ]),
                            ]),
                        Tabs\Tab::make('PHP')
                            ->schema([
                                Section::make('Custom PHP')
                                    ->description('Add custom PHP (Blade) code for advanced use. This will not be executed in the admin preview.')
                                    ->schema([
                                        CodeEditor::make('php_content')
                                            ->label('PHP Content')
                                            ->helperText('Write your PHP/Blade code here. This will only be executed on the frontend, not in the admin preview.'),
                                    ]),
                            ]),
                        Tabs\Tab::make('Preview')
                            ->schema([
                                Section::make('Block Preview')
                                    ->description('See how your block will look')
                                    ->schema([
                                        Placeholder::make('preview')
                                            ->content(function ($record) {
                                                if (!$record || !$record->html_content) {
                                                    return view('filament.components.block-preview-empty');
                                                }
                                                $content = $record->getCompleteContent();
                                                return view('filament.components.block-preview', ['content' => $content]);
                                            })
                                            ->columnSpanFull(),
                                    ]),
                            ]),
                        // Other tabs temporarily removed for debugging
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'layouts' => 'primary',
                        'content' => 'success',
                        'media' => 'warning',
                        'forms' => 'info',
                        'components' => 'gray',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('description')
                    ->limit(50)
                    ->searchable(),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options(CustomBlock::getCategories()),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order', 'asc');
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
            'index' => Pages\ListCustomBlocks::route('/'),
            'create' => Pages\CreateCustomBlock::route('/create'),
            'edit' => Pages\EditCustomBlock::route('/{record}/edit'),
        ];
    }
}
