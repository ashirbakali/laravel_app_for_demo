<?php

namespace App\Http\Controllers\API\Categories;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories;

class CategoriesApiController extends Controller
{
    public function index()
    {
        $categories = Categories::where('parent_id', null)->paginate();

        return $this->sendResponse($categories, 'Categories Retrieved', 200);
    }

    public function show($id)
    {
        $categories = Categories::find($id);

        return $this->sendResponse($categories, 'Categories Retrieved', 200);
    }

    
}
