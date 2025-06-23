<div 
    x-data="grapejsEditBar()"
    class="grapejs-edit-bar"
    style="background:#1e293b;color:white;padding:8px 0;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,0.08);font-size:15px;"
>
    <span>Edit this page with GrapesJS</span>
    <button 
        @click="startEditing()"
        x-show="!isEditing"
        class="grapejs-btn grapejs-btn-primary"
    >
        Edit
    </button>
    <button 
        @click="saveContent()"
        x-show="isEditing"
        class="grapejs-btn grapejs-btn-success"
        :disabled="isSaving"
    >
        <span x-text="isSaving ? 'Saving...' : 'Save'"></span>
    </button>
    <button 
        @click="exitEditing()"
        x-show="isEditing"
        class="grapejs-btn grapejs-btn-danger"
    >
        Exit
    </button>
</div>

<style>
    .grapejs-btn {
        margin-left: 8px;
        padding: 6px 18px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .grapejs-btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .grapejs-btn-primary {
        background: #3b82f6;
        color: white;
    }
    
    .grapejs-btn-primary:hover:not(:disabled) {
        background: #2563eb;
    }
    
    .grapejs-btn-success {
        background: #10b981;
        color: white;
    }
    
    .grapejs-btn-success:hover:not(:disabled) {
        background: #059669;
    }
    
    .grapejs-btn-danger {
        background: #ef4444;
        color: white;
    }
    
    .grapejs-btn-danger:hover:not(:disabled) {
        background: #dc2626;
    }
</style> 