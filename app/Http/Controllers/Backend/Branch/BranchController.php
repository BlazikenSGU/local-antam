<?php

namespace App\Http\Controllers\Backend\Branch;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\BaseBackendController;
use App\Http\Requests\Backend\Branch\StoreBranchRequest;
use App\Models\Branch;
use App\Models\Order;
use App\Models\CoreUsers;
use App\Models\Orders;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class BranchController extends BaseBackendController
{

    private $data = [];

    /**
     * BranchController constructor.
     */
    public function __construct()
    {
        $this->data['title'] = 'ShopID';
        $this->data['subtitle'] = 'Chi nhánh';
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $this->data['data'] = Branch::getAll([]);
        $this->data['start'] = 0;
        return view('backend.branch.index', $this->data);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function create()
    {
        $this->data['isEditable'] = false;
        $this->data['branch'] = [];
        return view('backend.branch.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {

        try {
            $params = $request->all();

            $check = Branch::where('shopId', $params['shopId'])->exists();

            if ($check) {
                return redirect()->back()->with('error', 'ShopID đã tồn tại');
            }

            if ($request->input('type') == 2) {
                $params['type'] = 2;
                $params['from_weight'] = 0;
                $params['to_weight'] = 0;
            } else {
                $params['type'] = 1;
                $params['from_weight'] = $request->input('from_weight') ?: 0;
                $params['to_weight'] =  $request->input('to_weight') ?: 0;
            }
            $params['use_create_order'] = 0;
            $params['created_at'] = time();

            $branch = Branch::create($params);

            if (!$branch) {
                return redirect()->back()->with('error', 'vui lòng thử lại');
            }

            return redirect()->route('backend.brands.index')->with('success', 'Thêm thành công');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function edit($id)
    {
        $branch = Branch::find($id);
        return view('backend.branch.edit', compact('branch'));
    }

    /**
     * @param StoreBranchRequest $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(StoreBranchRequest $request, $id)
    {
        $params = $request->all();
        $branch = Branch::find($id);

        if (!$branch) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra, vui lòng thử lại!');
        }

        if ($request->input('type') == 2) {
            $params['type'] = 2;
            $params['from_weight'] = 0;
            $params['to_weight'] = 0;
        } else {
            $params['type'] = 1;
            $params['from_weight'] = $request->input('from_weight');
            $params['to_weight'] =  $request->input('to_weight');
        }

        $params['updated_at'] = now();
        $branch->update($params);
        return redirect()->route('backend.brands.index')->with('success', 'Cập nhật thành công');
    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $id = $request->id ?? 0;
        $branch = Branch::find($id);
        if (!$branch) {
            return ResponseHelper::error('Không thể Xóa', []);
        }

        $branch->delete();
        return ResponseHelper::success('Đã xóa thành công', []);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxWareHouse(Request $request)
    {
        $branch_id = $request->branch_id ?? 0;
        $branch = Branch::find($branch_id);
        $warehouse_id = $request->warehouse_id ?? 0;
        $warehouse = Warehouse::getAll(['branch_id' => $branch_id]);
        if (!$branch) {
            return ResponseHelper::error('Không thể tìm id branch', []);
        }
        $html = view('backend.branch.ajax', ['warehouse' => $warehouse, 'warehouse_id' => $warehouse_id])->render();
        return ResponseHelper::success('Đã xóa thành công', [
            'jsonData' => $html
        ]);
    }

    public function assignshopid(Request $request)
    {
        $users = CoreUsers::orderBy('created_at', 'desc')->get();
        return view('backend.branch.assignshopid', compact('users'));
    }

    public function editAssignshopid(Request $request, $id)
    {
        $user = CoreUsers::find($id);
        $shopIds = $user->shopId;
        $branchs = Branch::orderBy('created_at', 'desc')->get();

        return view('backend.branch.editassignshopid', compact('branchs', 'user'));
    }

    public function updateAssignshopid(Request $request, $id)
    {
        $users = CoreUsers::orderBy('created_at', 'desc')->get();

        $shopIds = $request->input('shopId');

        $params['shopId'] = $shopIds;
        $params['updated_at'] = now();

        $user = CoreUsers::find($id);

        if (!$user) {
            return redirect()->route('backend.branch.assignshopid', compact('users'))->with('error', 'Không tìm thấy user.');
        }

        $user->update($params);

        return redirect()->route('backend.brands.assignshopid', compact('users'))->with('success', 'Cập nhật shopId thành công.');
    }

    public function selectpicker()
    {

        $this->data['users'] = CoreUsers::orderBy('created_at', 'desc')->get();

        $this->data['orders'] = Orders::orderBy('created_at', 'desc')->paginate(50);
        return view('backend.branch.selectpicker', $this->data);
    }

    public function getByUser(Request $request)
    {
        $userId = $request->input('user_id');
        $orders = Orders::where('user_id', $userId)->orderByDesc('created_at')->get();

        return response()->json([
            'success' => true,
            'data' => $orders
        ]);
    }
}
