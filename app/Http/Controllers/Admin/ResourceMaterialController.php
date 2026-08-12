<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ResourceMaterialRequest;
use App\Models\ResourceMaterial;
use App\Models\Subject;
use Illuminate\Support\Facades\Storage;

class ResourceMaterialController extends Controller
{
    /** Shared: load subjects with active topics (for Select2 optgroup dropdown) */
    private function subjectsWithTopics()
    {
        return Subject::with(['topics' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function index()
    {
        $materials = ResourceMaterial::with('topic.subject')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.resource-materials.index', compact('materials'));
    }

    public function create()
    {
        $subjects = $this->subjectsWithTopics();
        return view('admin.resource-materials.create', compact('subjects'));
    }

    public function store(ResourceMaterialRequest $request)
    {
        $data = $request->validated();

        // Handle file upload — takes priority over file_url
        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('resource-materials', 'public');
            $data['file_url'] = null; // clear URL if file was uploaded
        }

        ResourceMaterial::create($data);
        return redirect()->route('admin.resource-materials.index')
            ->with('toast_success', 'Resource material created successfully.');
    }

    public function edit(ResourceMaterial $resourceMaterial)
    {
        $subjects = $this->subjectsWithTopics();
        return view('admin.resource-materials.edit', compact('resourceMaterial', 'subjects'));
    }

    public function update(ResourceMaterialRequest $request, ResourceMaterial $resourceMaterial)
    {
        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('file')) {
            // Delete old stored file if one exists
            if ($resourceMaterial->file_path) {
                Storage::disk('public')->delete($resourceMaterial->file_path);
            }
            $data['file_path'] = $request->file('file')->store('resource-materials', 'public');
            $data['file_url'] = null;
        }

        $resourceMaterial->update($data);
        return redirect()->route('admin.resource-materials.index')
            ->with('toast_success', 'Resource material updated successfully.');
    }

    public function destroy(ResourceMaterial $resourceMaterial)
    {
        // Delete the stored file if one exists
        if ($resourceMaterial->file_path) {
            Storage::disk('public')->delete($resourceMaterial->file_path);
        }

        $resourceMaterial->delete();
        return redirect()->route('admin.resource-materials.index')
            ->with('toast_success', 'Resource material deleted successfully.');
    }
}
