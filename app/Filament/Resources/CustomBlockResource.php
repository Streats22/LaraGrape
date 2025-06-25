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
use Wiebenieuwenhuis\FilamentCodeEditor\Components\CodeEditor;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\ViewField;

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
                        
                        Tabs\Tab::make('HTML Content')
                            ->schema([
                                Section::make('HTML Structure')
                                    ->description('Write the HTML structure for your block. Use data-gjs attributes for GrapesJS integration.')
                                    ->schema([
                                        Textarea::make('html_content')
                                            ->label('HTML Content')
                                            ->required()
                                            ->rows(10)
                                            ->helperText('Use data-gjs-type="text" and data-gjs-name="unique-name" to make elements editable. 

Example templates:

1. Custom Card:
<div class="custom-card bg-white rounded-lg shadow-md p-6">
    <h3 data-gjs-type="text" data-gjs-name="card-title">Card Title</h3>
    <p data-gjs-type="text" data-gjs-name="card-content">Card content goes here</p>
    <button data-gjs-type="text" data-gjs-name="button-text" class="btn-primary">Click Me</button>
</div>

2. Hero Section:
<div class="hero-section bg-gradient-to-r from-blue-500 to-purple-600 text-white p-12 rounded-lg">
    <h1 data-gjs-type="text" data-gjs-name="hero-title" class="text-4xl font-bold mb-4">Hero Title</h1>
    <p data-gjs-type="text" data-gjs-name="hero-subtitle" class="text-xl mb-6">Hero subtitle goes here</p>
    <button data-gjs-type="text" data-gjs-name="hero-button" class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold">Get Started</button>
</div>

3. Feature Box:
<div class="feature-box bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
    <h3 data-gjs-type="text" data-gjs-name="feature-title" class="text-xl font-semibold mb-3">Feature Title</h3>
    <p data-gjs-type="text" data-gjs-name="feature-description" class="text-gray-600">Feature description goes here.</p>
</div>'),
                                    ]),
                            ]),
                        
                        Tabs\Tab::make('CSS Styling')
                            ->schema([
                                Section::make('Custom CSS')
                                    ->description('Add custom CSS styles for your block (optional)')
                                    ->schema([
                                        Textarea::make('css_content')
                                            ->label('CSS Content')
                                            ->rows(8)
                                            ->helperText('CSS will be scoped to this block. Keep it simple and safe.'),
                                    ]),
                            ]),
                        
                        Tabs\Tab::make('JavaScript')
                            ->schema([
                                Section::make('Custom JavaScript')
                                    ->description('Add simple interactive functionality (optional)')
                                    ->schema([
                                        Textarea::make('js_content')
                                            ->label('JavaScript Content')
                                            ->rows(8)
                                            ->helperText('Keep JavaScript simple and safe. Avoid complex operations.'),
                                    ]),
                            ]),
                        
                        Tabs\Tab::make('GrapesJS Settings')
                            ->schema([
                                Section::make('Block Attributes')
                                    ->description('Configure how this block behaves in GrapesJS')
                                    ->schema([
                                        KeyValue::make('attributes')
                                            ->label('GrapesJS Attributes')
                                            ->keyLabel('Attribute')
                                            ->valueLabel('Value')
                                            ->addActionLabel('Add Attribute')
                                            ->helperText('Common attributes: draggable, droppable, removable, copyable'),
                                    ]),
                                
                                Section::make('Block Settings')
                                    ->description('Additional configuration for the block')
                                    ->schema([
                                        KeyValue::make('settings')
                                            ->label('Block Settings')
                                            ->keyLabel('Setting')
                                            ->valueLabel('Value')
                                            ->addActionLabel('Add Setting')
                                            ->helperText('These can be used in your JavaScript or CSS'),
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
                                                    return '<div class="text-gray-500 text-center p-8">No content to preview</div>';
                                                }
                                                
                                                $content = $record->getCompleteContent();
                                                return view('filament.components.block-preview', ['content' => $content]);
                                            })
                                            ->columnSpanFull(),
                                    ]),
                            ]),
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
