<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\DocumentFolder;
use App\Models\DocumentFile;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class DocumentManager extends Component
{
    use WithFileUploads;

    public ?int $currentFolderId = null;
    public string $search = '';
    public string $viewMode = 'grid'; // grid, list, gallery

    // Folder Modal
    public bool $showFolderModal = false;
    public ?int $editingFolderId = null;
    public string $folderName = '';
    public string $folderColor = 'emerald';

    // Upload File Modal
    public bool $showUploadModal = false;
    public string $fileTitle = '';
    public $uploadedFile = null;
    public string $fileDescription = '';

    // Edit File Modal
    public bool $showFileEditModal = false;
    public ?int $editingFileId = null;
    public string $editFileTitle = '';
    public string $editFileDescription = '';

    // Delete Confirmation Modal
    public ?int $confirmDeleteFolderId = null;
    public ?int $confirmDeleteFileId = null;

    public function confirmDeleteFolder(int $id)
    {
        $this->confirmDeleteFolderId = $id;
    }

    public function confirmDeleteFile(int $id)
    {
        $this->confirmDeleteFileId = $id;
    }

    public function cancelDelete()
    {
        $this->confirmDeleteFolderId = null;
        $this->confirmDeleteFileId = null;
    }

    public function executeDeleteFolder()
    {
        if ($this->confirmDeleteFolderId) {
            $this->deleteFolder($this->confirmDeleteFolderId);
            $this->confirmDeleteFolderId = null;
        }
    }

    public function executeDeleteFile()
    {
        if ($this->confirmDeleteFileId) {
            $this->deleteFile($this->confirmDeleteFileId);
            $this->confirmDeleteFileId = null;
        }
    }

    public function navigateToFolder(?int $folderId = null)
    {
        $this->currentFolderId = $folderId;
    }

    public function openFolderModal(?int $id = null)
    {
        $this->resetValidation();
        $this->editingFolderId = $id;

        if ($id) {
            $folder = DocumentFolder::findOrFail($id);
            $this->folderName = $folder->name;
            $this->folderColor = $folder->color ?? 'emerald';
        } else {
            $this->folderName = '';
            $this->folderColor = 'emerald';
        }
        $this->showFolderModal = true;
    }

    public function saveFolder()
    {
        $this->validate([
            'folderName' => 'required|string|max:255',
        ], [
            'folderName.required' => 'ফোল্ডারের নাম দেওয়া আবশ্যক',
        ]);

        if ($this->editingFolderId) {
            $folder = DocumentFolder::findOrFail($this->editingFolderId);
            $folder->update([
                'name' => trim($this->folderName),
                'color' => $this->folderColor,
            ]);
            $msg = 'ফোল্ডার এডিট করা হয়েছে!';
        } else {
            DocumentFolder::create([
                'name' => trim($this->folderName),
                'parent_id' => $this->currentFolderId,
                'color' => $this->folderColor,
                'season' => Setting::get('season', '২৫-২৬'),
            ]);
            $msg = 'নতুন ফোল্ডার সফলভাবে তৈরি হয়েছে!';
        }

        $this->showFolderModal = false;
        $this->editingFolderId = null;
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function openUploadModal()
    {
        $this->resetValidation();
        $this->fileTitle = '';
        $this->uploadedFile = null;
        $this->fileDescription = '';
        $this->showUploadModal = true;
    }

    public function saveFile()
    {
        $this->validate([
            'fileTitle' => 'required|string|max:255',
            'uploadedFile' => 'required|file|max:51200', // 50MB max, any file type
        ], [
            'fileTitle.required' => 'ডকুমেন্টের টাইটেল দেওয়া আবশ্যক',
            'uploadedFile.required' => 'ফাইল সিলেক্ট করা আবশ্যক',
            'uploadedFile.max' => 'ফাইল সাইজ সর্বোচ্চ ৫০ মেগাবাইটের মধ্যে হতে হবে',
        ]);

        $originalName = $this->uploadedFile->getClientOriginalName();
        $extension = strtolower($this->uploadedFile->getClientOriginalExtension());
        $path = $this->uploadedFile->store('documents', 'public');
        $size = $this->uploadedFile->getSize();

        DocumentFile::create([
            'folder_id' => $this->currentFolderId,
            'title' => trim($this->fileTitle),
            'file_path' => $path,
            'file_name' => $originalName,
            'file_type' => $extension,
            'file_size' => $size,
            'description' => trim($this->fileDescription),
            'season' => Setting::get('season', '২৫-২৬'),
        ]);

        $this->showUploadModal = false;
        $this->reset(['fileTitle', 'uploadedFile', 'fileDescription']);
        $msg = 'নতুন ফাইল সফলভাবে আপলোড হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function openFileEditModal(int $id)
    {
        $this->resetValidation();
        $this->editingFileId = $id;
        $file = DocumentFile::findOrFail($id);
        $this->editFileTitle = $file->title;
        $this->editFileDescription = $file->description ?? '';
        $this->showFileEditModal = true;
    }

    public function updateFile()
    {
        $this->validate([
            'editFileTitle' => 'required|string|max:255',
        ], [
            'editFileTitle.required' => 'ডকুমেন্টের টাইটেল দেওয়া আবশ্যক',
        ]);

        $file = DocumentFile::findOrFail($this->editingFileId);
        $file->update([
            'title' => trim($this->editFileTitle),
            'description' => trim($this->editFileDescription),
        ]);

        $this->showFileEditModal = false;
        $this->editingFileId = null;
        $msg = 'ফাইল তথ্য আপডেট করা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function deleteFile(int $id)
    {
        $file = DocumentFile::findOrFail($id);
        if (Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }
        $file->delete();
        $msg = 'ফাইল মুছে ফেলা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function deleteFolder(int $id)
    {
        $folder = DocumentFolder::findOrFail($id);
        // Recursively delete files & physical files
        foreach ($folder->files as $f) {
            if (Storage::disk('public')->exists($f->file_path)) {
                Storage::disk('public')->delete($f->file_path);
            }
            $f->delete();
        }
        $folder->delete();
        $msg = 'ফোল্ডার মুছে ফেলা হয়েছে!';
        session()->flash('message', $msg);
        $this->dispatch('show-toast', message: $msg, type: 'success');
    }

    public function render()
    {
        $activeSeason = Setting::get('season', '২৫-২৬');

        $currentFolder = $this->currentFolderId ? DocumentFolder::find($this->currentFolderId) : null;

        // Build Breadcrumb Navigation Trail
        $breadcrumbs = [];
        $temp = $currentFolder;
        while ($temp) {
            array_unshift($breadcrumbs, $temp);
            $temp = $temp->parent;
        }

        // Query Folders
        $folderQuery = DocumentFolder::where('parent_id', $this->currentFolderId)
            ->where('season', $activeSeason);
        if (!empty($this->search)) {
            $folderQuery->where('name', 'like', "%{$this->search}%");
        }
        $folders = $folderQuery->orderBy('name', 'asc')->get();

        // Query Files
        $fileQuery = DocumentFile::where('folder_id', $this->currentFolderId)
            ->where('season', $activeSeason);
        if (!empty($this->search)) {
            $fileQuery->where(function($q) {
                $q->where('title', 'like', "%{$this->search}%")
                  ->orWhere('file_name', 'like', "%{$this->search}%");
            });
        }
        $files = $fileQuery->orderBy('created_at', 'desc')->get();

        return view('livewire.document-manager', [
            'currentFolder' => $currentFolder,
            'breadcrumbs' => $breadcrumbs,
            'folders' => $folders,
            'files' => $files,
        ])->layout('layouts.app');
    }
}
