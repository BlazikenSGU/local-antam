<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAPIController;
use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;
use Validator;

class PostsController extends BaseAPIController
{
    /**
     * @OA\Get(
     *     path="/posts",
     *     tags={"Posts"},
     *     summary="get all posts",
     *     operationId="getAllPosts",
     *     @OA\Parameter( name="company_id", in="query", description="Company ID", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter( name="api_key", in="query", description="API Key", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter( name="category_id", in="query", description="Category ID", required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter( name="limit", in="query", description="total record return", required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(name="page", in="query", description="page number", required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */

    public function getAll(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $aInit = [
            'limit' => 10,
        ];
        $params = array_merge(
            $aInit, $request->only(array_keys($aInit))
        );
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1',
        ]);
        $params = array_filter($params);
        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }
        $params['pagin_path'] = route('posts.getAll') . '?' . http_build_query($params);
        $posts = Post::with(['thumbnail','province'])->where('company_id', $request->get('company_id'));

        if ($request->get('category_id'))
            $posts = $posts->where('category_id', $request->get('category_id'));

        $posts = $posts->orderBy('id', 'DESC')->paginate($params['limit'])->withPath($params['pagin_path']);
        return $this->returnResult($posts);
    }

    /**
     * @OA\Get(
     *     path="/posts/category/getAll",
     *     tags={"Posts"},
     *     summary="get all posts category",
     *     operationId="getAllPostsCategory",
     *     @OA\Parameter( name="company_id", in="query", description="Company ID", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter( name="api_key", in="query", description="API Key", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */

    public function getAllCategory(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1',
        ]);
        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }
        $posts_category = PostCategory::with(['thumbnail', 'icon'])
            ->where('company_id', $request->get('company_id'))
            ->get();

        return $this->returnResult($posts_category);
    }

    /**
     * @OA\Get(
     *     path="/posts/detail",
     *     tags={"Posts"},
     *     summary="get detail posts",
     *     operationId="getDetailPosts",
     *     @OA\Parameter( name="company_id", in="query", description="Company ID", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter( name="api_key", in="query", description="API Key", required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter( name="id", in="query", description="ID Tin tức", required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response="200", description="Success"),
     * )
     */
    public function getDetail(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $aInit = [
            'limit' => 10,
        ];
        $params = array_merge(
            $aInit, $request->only(array_keys($aInit))
        );
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
        ]);
        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }
        $return = Post::with('thumbnail')->where('company_id', $request->get('company_id'))
            ->where('id', $request->id)
            ->first();

        if (empty($return))
            return $this->throwError('Bài viết không tồn tại!', 400);

        $return->views++;
        $return->save();

        return $this->returnResult($return);
    }

}
