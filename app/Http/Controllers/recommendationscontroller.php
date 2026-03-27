<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;


class recommendationscontroller extends Controller
{
    public function index()
    {
        $recommendations = Recommendation::with(['user:id,name', 'categories:id,name'])->latest()->get();
        $categories = Category::orderBy('name')->get(['id', 'name']);

        return Inertia::render('recommendations', [
            'recommendations' => $recommendations,
            'categories' => $categories,
        ]);
    }

    public function filterall(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'search' => ['nullable', 'string']
        ]);

        $recommendationsQuery = Recommendation::query()
            ->with(['user:id,name', 'categories:id,name'])
            ->latest();

        if (!empty($validated['category_id'])) {
            $categoryId = (int) $validated['category_id'];

            $recommendationsQuery->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            });
        }

        if (!empty($validated['search'])) {
            $search = $validated['search'];

            $recommendationsQuery->where(function ($query) use ($search) {
                $query->where('city', 'LIKE', '%' . $search . '%')
                    ->orWhere('site_name', 'LIKE', '%' . $search . '%');
            });
        }

        return response()->json([
            'recommendations' => $recommendationsQuery->get(),
        ]);
    }

    public function filterme(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['nullable', 'integer', 'exists:categories,id'],
            'search' => ['nullable', 'string']
        ]);

        $recommendationsQuery = Recommendation::query()
            ->with(['categories:id,name'])
            ->where('user_id', Auth::id())
            ->latest();

        if (!empty($validated['category_id'])) {
            $categoryId = (int) $validated['category_id'];

            $recommendationsQuery->whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            });
        }

        if (!empty($validated['search'])) {
            $search = $validated['search'];

            $recommendationsQuery->where(function ($query) use ($search) {
                $query->where('city', 'LIKE', '%' . $search . '%')
                    ->orWhere('site_name', 'LIKE', '%' . $search . '%');
            });
        }

        return response()->json([
            'recommendations' => $recommendationsQuery->get(),
        ]);
    }

    public function myrecommendations()
    {
        $myrecommendations = Recommendation::with(['categories:id,name'])->where('user_id', Auth::id())
            ->latest()
            ->get([
                'id',
                'site_name',
                'city',
                'description',
                'valoration',
                'created_at',
            ]);

        $categories = Category::orderBy('name')->get(['id', 'name']);

        return Inertia::render('myrecommendations', [
            'recommendations' => $myrecommendations,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        return Inertia::render('createrecommendation', [
            'categories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'site_name' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'valoration' => ['required', 'integer', 'min:1', 'max:5'],
            'category_ids' => ['array'],
        ]);

        $recommendation = Recommendation::create([
            'user_id' => Auth::id(),
            'site_name' => $validated['site_name'],
            'city' => $validated['city'],
            'description' => $validated['description'],
            'valoration' => $validated['valoration'],
        ]);

        $recommendation->categories()->sync($validated['category_ids'] ?? []);

        return redirect()->route('my_recommendations');
    }
}
