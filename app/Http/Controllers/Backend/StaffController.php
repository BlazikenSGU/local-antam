<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Models\Address;
use App\Models\Branch;
use App\Models\Files;
use App\Models\Orders;
use App\Models\SettingFee;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\CoreUsers;
use App\Utils\Common as Utils;
use App\Utils\Avatar;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use Spatie\Permission\Models\Permission;

class StaffController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Quản trị viên',
        'subtitle' => 'Quản trị viên',
    );

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'fullname'         => null,
            'phone'            => null,
            'email'            => null,
            'account_position' => null,
            'status'           => null,
        ), Utils::J_filterEntities($request->all()));

        $objU = new CoreUsers();

        $params['pagin_path'] = Utils::get_pagin_path($filter);
        $params['is_staff'] = 1;
        // $users = $objU->get_by_where($params);

        $users = CoreUsers::orderBy('created_at', 'desc')->paginate(50);

        $start = ($users->currentPage() - 1) * config('constants.item_perpage');

        $this->_data['users'] = $users;
        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        $this->_data['account_position'] = CoreUsers::ACCOUNT_POSITION;

        return view('backend.staff.index', $this->_data);
    }

    public function add(Request $request)
    {

        $validator_rule = [
            'status'            => ['required', 'string', Rule::in([CoreUsers::STATUS_REGISTERED, CoreUsers::STATUS_BANNED])],
            'account_position'  => 'required|string',
            'grant_permissions' => 'nullable|array',
            'fullname' => ['nullable', 'string'],
            'email'    => ['required', 'email', Rule::unique('lck_core_users'),],
            'phone'    => ['required',  Rule::unique('lck_core_users'),],
            'password' => ['required', 'string'],
        ];

        $form_init = array_merge(
            array(
                'phone'             => null,
                'fullname'             => null,
                'email'             => null,
                'password'             => null,
                'province_id'             => null,
                'district_id'             => null,
                'ward_id'             => null,
                'province_name'             => null,
                'district_name'             => null,
                'ward_name'             => null,
                'address'             => null,
                'cost'            => null,
                'status'            => null,
                'account_position'  => null,
                'grant_permissions' => null,
            ),
            Utils::J_filterEntities($request->all())
        );

        $form_init = array_merge($form_init, $request->old());

        $this->_data['form_init'] = (object)$form_init;

        if ($request->getMethod() == 'POST') {


            Validator::make($request->all(), $validator_rule)->validate();
            $form_init['company_id'] = config('constants.company_id');

            $form_init['password'] = Hash::make($form_init['password']);
            $full_dir = config('constants.upload_dir.root') . '/backend';

            $form_init['avatar_file_id'] = 1040;
            $form_init['avatar_file_path'] = Files::find(1084)->file_path;

            $user = CoreUsers::create($form_init);


            $params['name'] = $request->get('fullname');
            $params['company_id'] = $request->get('5');
            $params['province_id'] = $request->get('province_id');
            $params['district_id'] = $request->get('district_id');
            $params['ward_id'] = $request->get('ward_id');
            $params['street_name'] = $request->get('street_name');
            $params['province_name'] = $request->get('province_name');
            $params['district_name'] = $request->get('district_name');
            $params['ward_name'] = $request->get('ward_name');
            $params['user_id'] = $user->id;
            $params['product_type'] = $request->get('product_type', '31');
            $params['is_default'] = 1;
            Address::create($params);


            //            $user = CoreUsers::where('phone', $form_init['phone'])->first();

            if (empty($user) || $user->status == CoreUsers::STATUS_UNREGISTERED) {
                return redirect()->back()
                    ->withInput($request->all())
                    ->withErrors(['message' => "Tài khoản với số điện thoại này không tồn tại, vui lòng tạo tài khoản trước khi thêm quản trị viên!"]);
            }

            try {
                DB::beginTransaction();
                $user->status = $form_init['status'];
                $user->account_position = $form_init['account_position'];
                $user->save();



                if ($request->get('fee')) {
                    $fee = $request->get('fee');
                    $shop_ids = $request->get('shop_ids');
                    foreach ($shop_ids as $k => $shop_id) {
                        SettingFee::create([
                            'user_id' => $user->id,
                            'shop_id' => $shop_id,
                            'cost' =>  $fee[$k],
                        ]);
                    }
                }

                foreach ($request->get('grant_permissions', [63, 64, 65, 66, 86, 87, 88, 89, 90]) as $permissions_id) {
                    $user->givePermissionTo($permissions_id);
                }
                DB::commit();
                $request->session()->flash('msg', ['info', 'Thêm quản trị viên thành công!']);
            } catch (\Exception $e) {
                DB::rollBack();
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
            }
            return redirect(Route('backend.staff.index'));
        }

        $this->_data['subtitle'] = 'Thêm quản trị viên';
        $this->_data['account_position'] = CoreUsers::ACCOUNT_POSITION;
        $this->_data['all_permissions'] = Permission::where('status', 1)->get()->groupBy('group');

        return view('backend.staff.add', $this->_data);
    }

    public function edit(Request $request, $id)
    {
        $user = CoreUsers::find($id);

        $branch = Branch::all();
        $this->_data['user'] = $user;
        $this->_data['branchs'] = $branch;


        if (empty($user) || empty($user->account_position)) {
            $request->session()->flash('msg', ['danger', 'Nhân viên không tồn tại!']);
            return redirect($this->_ref ? $this->_ref : Route('backend.staff.index'));
        }

        if (Auth()->guard('backend')->user()->id == $id) {
            $request->session()->flash('msg', ['danger', 'Không thể cập nhật Nhân viên này!']);
            return redirect($this->_ref ? $this->_ref : Route('backend.staff.index'));
        }

        $validator_rule = [
            'status'            => ['required', 'string', Rule::in([CoreUsers::STATUS_REGISTERED, CoreUsers::STATUS_BANNED, CoreUsers::STATUS_NEWACCOUNT])],
            'grant_permissions' => 'nullable|array',
        ];

        $permissions = $user->getAllPermissions();

        $form_init = array_merge(
            array(
                'status'            => null,
                'account_position'  => null,
                'grant_permissions' => $permissions->pluck('id')->toArray(),
            ),
            Utils::J_filterEntities($request->all())
        );

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();

            try {

                $user->province_id = $request->get('province_id') ?? $user->province_id;
                $user->district_id = $request->get('district_id') ?? $user->district_id;
                $user->ward_id = $request->get('ward_id') ?? $user->ward_id;
                $user->province_name = $request->get('province_name') ?? $user->province_name;
                $user->district_name = $request->get('district_name') ?? $user->district_name;
                $user->ward_name = $request->get('ward_name') ?? $user->ward_name;
                $user->address = $request->get('address') ?? $user->address;
                $user->cost = $request->get('cost') ?? $user->cost;
                $user->account_type = $request->get('account_type') ?: '';

                $user->status = $form_init['status'];

                $user->save();

                if ($request->get('fee')) {
                    $fee = $request->get('fee');
                    $shop_ids = $request->get('shop_ids');
                    foreach ($shop_ids as $k => $shop_id) {
                        SettingFee::updateOrCreate(
                            ['user_id' => $user->id, 'shop_id' => $shop_id],
                            ['cost' => $fee[$k]]
                        );
                    }
                }

                foreach ($permissions as $permission) {
                    $user->revokePermissionTo($permission->id);
                }

                foreach ($request->get('grant_permissions', [63, 64, 65, 66, 86, 87, 88, 89, 90]) as $permissions_id) {
                    $user->givePermissionTo($permissions_id);
                }

                return redirect()->route('backend.staff.index')->with('success', 'Cập nhật tài khoản thành công');
            } catch (\Exception $e) {
                $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
            }
            return redirect(Route('backend.staff.index'));
        }

        $form_init = array_merge($form_init, $user->toArray());

        $this->_data['form_init'] = (object)$form_init;

        $this->_data['subtitle'] = 'Cập nhật';
        $this->_data['account_position'] = CoreUsers::ACCOUNT_POSITION;
        $this->_data['all_permissions'] = Permission::where('status', 1)->get()->groupBy('group');

        return view('backend.staff.edit', $this->_data);
    }

    public function delete(Request $request, $id)
    {
        try {
            $user = CoreUsers::find($id);

            if (empty($user)) {
                $request->session()->flash('msg', ['danger', 'Tài khoản quản trị viên không tồn tại!']);
            } else {
                if (Auth()->guard('backend')->user()->id == $id) {
                    $request->session()->flash('msg', ['danger', 'Không thể quản trị viên này!']);
                } else {
                    $user->account_position = null;
                    $user->save();
                    $user->destroy($id);
                    foreach ($request->get('grant_permissions', [63, 64, 65, 66, 86, 87, 88, 89, 90]) as $permissions_id) {
                        $user->givePermissionTo($permissions_id);
                    }

                    $request->session()->flash('msg', ['info', 'Xóa quản trị viên thành công!']);
                }
            }
        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        return redirect($this->_ref ? $this->_ref : Route('backend.staff.index'));
    }
}
