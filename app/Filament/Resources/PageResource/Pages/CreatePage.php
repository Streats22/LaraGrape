<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Debug logging
        \Log::info('CreatePage mutateFormDataBeforeCreate', [
            'data' => $data,
            'grapesjs_data_exists' => isset($data['grapesjs_data']),
            'grapesjs_data_type' => isset($data['grapesjs_data']) ? gettype($data['grapesjs_data']) : 'not set'
        ]);
        
        // Handle GrapesJS data
        if (isset($data['grapesjs_data']) && is_array($data['grapesjs_data'])) {
            $grapesjsData = $data['grapesjs_data'];
            
            // Get the converter service
            $converterService = app(\App\Services\GrapesJsConverterService::class);
            
            // Process the data for saving (convert to Blade components)
            $processedData = $converterService->processForSaving($grapesjsData);
            
            // Extract HTML and CSS from processed data
            $data['grapesjs_html'] = $processedData['html'] ?? null;
            $data['grapesjs_css'] = $processedData['css'] ?? null;
            
            // Keep the full processed data structure
            $data['grapesjs_data'] = $processedData;
            
            // Also save Blade content
            $data['blade_content'] = $converterService->convertToBlade($processedData);
            
            \Log::info('GrapesJS data processed', [
                'html' => $data['grapesjs_html'],
                'css' => $data['grapesjs_css'],
                'full_data' => $data['grapesjs_data'],
                'blade_content' => $data['blade_content'],
            ]);
        } else {
            \Log::warning('GrapesJS data not found or not array', [
                'grapesjs_data' => $data['grapesjs_data'] ?? 'not set'
            ]);
        }
        
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        // Redirect to the edit page using the id
        $record = $this->getRecord();
        return static::getResource()::getUrl('edit', ['record' => $record->getKey()]);
    }
}
