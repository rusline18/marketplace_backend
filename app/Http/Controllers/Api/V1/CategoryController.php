<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Listings\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\CategoryResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    /**
     * List all categories.
     */
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(Category::query()->orderBy('name')->get());
    }
}
