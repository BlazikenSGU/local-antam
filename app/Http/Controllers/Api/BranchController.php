<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAPIController;
use App\Models\Branch;
use App\Models\CoreUsers;
use App\Models\Orders;
use App\Models\Product;
use App\VNShipping\Ahamove;
use Illuminate\Http\Request;

class BranchController extends BaseAPIController
{
    /**
     * @OA\Get(
     *   path="/branch/getAll",
     *   tags={"Branch"},
     *   summary="Get all Branch",
     *   description="",
     *   operationId="BranchGetAll",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *   @OA\Response(response="200", description="Success operation"),
     *   @OA\Response(response="500", description="Server error")
     * )
     */
    public function getAll(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $return = Branch::get();

        // $return = $return->limit(30)->get();
        return $this->returnResult($return);
    }


    /**
     * @OA\Get(
     *   path="/branch/getTableByIdBranch",
     *   tags={"Branch"},
     *   summary="Get all Table By Id Branch",
     *   description="",
     *   operationId="GetTableByIdBranch",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="idbranch", description="idbranch", required=true, in="query",
     *         @OA\Schema( type="integer", )
     *     ),
     *   @OA\Response(response="200", description="Success operation"),
     *   @OA\Response(response="500", description="Server error")
     * )
     */
    public function getTableByIdBranch(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $return = Branch::Join('lck_warehouse', 'lck_branch.id', '=', 'lck_warehouse.branch_id')
            ->where('lck_branch.id', $request->get('idbranch'))
            ->orderBy('lck_warehouse.id', 'asc')->get();

        // $return = $return->limit(30)->get();
        return $this->returnResult($return);
    }

    /**
     * @OA\Get(
     *   path="/branch/getbyfee",
     *   tags={"Branch"},
     *   summary="getbyfee ",
     *   description="",
     *   operationId="getbyfee",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="idbranch", description="idbranch", required=true, in="query",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\Parameter( name="token", description="token form login", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="fulladdress", description="79/11 Trần Văn Đang, Phường 9, QUận 3", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="lat", description="điểm đầu", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="long", description="điểm cuồi", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *   @OA\Response(response="200", description="Success operation"),
     *   @OA\Response(response="500", description="Server error")
     * )
     */
    public function getbyfee(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        $branch = Branch::find($request->get('idbranch'));
        if (!$branch) {
            return $this->throwError(400, 'Chi nhánh không tồn tại. Vui lòng thử lại!');
        }


        $data = [
            [
                'lat' => (double)$branch->o_lat,
                'lng' => (double)$branch->o_long,
                'address' => $branch->address,
                'name' => $branch->name,
                'mobile' => (string)$branch->phone,
                'remarks' => "call me"
            ],
            [
                "lat" => (double)$request->get('lat'),
                "lng" => (double)$request->get('long'),
                "address" => $request->get('address'),
                "name" => $user->fullname,
                "mobile" => $user->phone
            ]
        ];
        try {
            $ahamove = new Ahamove();
            $shipment_fee = $ahamove->getFee([
                'id' => $branch->id,
                'data' => $data,
            ]);
            $resulft = $shipment_fee;
            return $this->returnResult($resulft);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            preg_match('/"description": "([^"]+)"/', $errorMessage, $matches);
            $errorDescription = isset($matches[1]) ? $matches[1] : 'Không rõ lỗi cụ thể từ máy chủ Ahamove.';
            return $this->throwError('Có lỗi xảy ra: ' .$errorDescription);
        }





    }

    /**
     * @OA\Get(
     *   path="/branch/getAllOrdersBranch",
     *   tags={"Branch"},
     *   summary="Get all orders branch",
     *   description="",
     *   operationId="getAllOrdersBranch",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="token", description="token from api login", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     * )
     */
    public function getAllOrdersBranch(Request $request)
    {
        // kiểm tra api company và api key
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');
        $this->checkCompany($request->get('company_id'), $request->get('api_key'));
        // nếu liên quan đến user sẽ tiếp tục kiểm tra token
        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();
        // kiểm tra user có thuộc chi nhánh đó không
        $branch = Branch::where('id', $user->branch_id)->first();
        if (empty($branch)) {
            return $this->throwError('Không có chi nhánh');
        }
        // lấy tất cả các đơn hàng thuộc chi nhánh đó
        $orders = Orders::where('branch_id', $branch->id)->orderBy('id', 'DESC')->get();
        return $this->returnResult($orders);
    }


    /**
     * @OA\Get(
     *   path="/branch/getAllProductBranch",
     *   tags={"Branch"},
     *   summary="Get all product branch",
     *   description="",
     *   operationId="getAllProductBranch",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="token", description="token from api login", required=false, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="getAllProductBranchId", description="1. Lấy tất cả sản phẩm tồn tại trong chi nhánh đó | Không truyền là mặc định", required=false, in="query",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\Parameter( name="product_id", description="Id sản phẩm", required=false, in="query",
     *         @OA\Schema( type="interger", )
     *     ),
     *     @OA\Parameter( name="add_remove_product", description="1. là thêm sản phẩm vào chi nhánh | 0. là xóa sản phẩm khỏi chi nhánh", required=false, in="query",
     *         @OA\Schema( type="interger", )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     * )
     */
    public function getAllProductBranch(Request $request)
    {
        // kiểm tra api company và api key
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');
        $this->checkCompany($request->get('company_id'), $request->get('api_key'));
        // nếu liên quan đến user sẽ tiếp tục kiểm tra token
        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();
        // kiểm tra user có thuộc chi nhánh đó không
        $branch = Branch::where('id', $user->branch_id)->first();
        if (empty($branch)) {
            return $this->throwError('Không có chi nhánh');
        }
        // lấy đơn tất cả các đơn hàng thuộc chi nhánh đó
        $orders = Orders::where('branch_id', $branch->id)->get();
        // lấy tất cả sản phẩm thuộc chi nhánh đó
        $getAllProductBranchId = $request->get('getAllProductBranchId');
        if ($getAllProductBranchId == 1) {
            // Lấy danh sách ID sản phẩm từ cột list_product_id
            $productIds = explode(',', $branch->list_product_id);
            // Lấy thông tin các sản phẩm từ danh sách ID
            $products = Product::whereIn('id', $productIds)->get();
            return $this->returnResult($products);
        }
        // Lấy id sản phẩm
        $productId = $request->get('product_id');
        $addRemoveProduct = $request->get('add_remove_product');
        // Kiểm tra giá trị của add_remove_product và thực hiện thêm/xóa sản phẩm tương ứng
        if ($addRemoveProduct == 1) {
            // Thêm sản phẩm vào cột list_product_id
            $productIds = explode(',', $branch->list_product_id);
            $productIds[] = $productId;
            $branch->list_product_id = implode(',', $productIds);
            $branch->save();
        } elseif ($addRemoveProduct == 0) {
            // Xóa sản phẩm khỏi cột list_product_id
            $productIds = explode(',', $branch->list_product_id);
            $productIds = array_diff($productIds, [$productId]);
            $branch->list_product_id = implode(',', $productIds);
            $branch->save();
        }
        return $this->returnResult($orders);
    }

    /**
     * @OA\Post (
     *   path="/branch/turnOnTurnOff",
     *   tags={"Branch"},
     *   summary="turnOnTurnOff branch",
     *   description="",
     *   operationId="turnOnTurnOff",
     *     @OA\Parameter( name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="token", description="token from api login", required=true, in="query",
     *         @OA\Schema( type="string", )
     *     ),
     *     @OA\Parameter( name="typeturnOnTurnOff", description="1 bật 0 tắt", required=true, in="query",
     *         @OA\Schema( type="integer", )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     * )
     */
    public function TurnOnTurnOff(Request $request)
    {
        // kiểm tra api company và api key
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');
        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();
        // kiểm tra user có thuộc chi nhánh đó không
        $branch = Branch::where('id', $user->branch_id)->first();
        if (empty($branch)) {
            return $this->throwError('Token không phải là chi nhánh');
        }
        // lấy tất cả các đơn hàng thuộc chi nhánh đó
        $branch->turnOnTurnOff = $request->get('typeturnOnTurnOff');
        $branch->save();
        return $this->returnResult();
    }


}
