<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for Products"
 * )
 */
class ProductController extends Controller
{
    /**
     * @OA\Get(
     *      path="/products/search",
     *      operationId="productsSearch",
     *      tags={"Products"},
     *      summary="Get list of searched products",
     *      description="Returns list of products",
     *        @OA\Parameter(
     *          name="term",
     *          description="Search term",
     *          required=true,
     *          in="query",
     *          example="blue",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(response="200", description="Ok"),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *     )
     */
    public function searchByterm(SearchProductRequest $request)
    {
        $validated = $request->safe()->only('term');
        $term = $validated['term'];

        $products = Product::search($term)->query(fn ($query) => $query->with('categories'))
            ->paginate()
            ->withQueryString();

        return ProductResource::collection($products);
    }
}
