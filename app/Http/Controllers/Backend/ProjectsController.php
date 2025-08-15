<?php

namespace App\Http\Controllers\Backend;

use App\Events\UpdateProject;
use App\Http\Controllers\BaseBackendController;
use App\Models\CallRequest;
use App\Models\Location\District;
use App\Models\Location\Province;
use App\Models\Location\Street;
use App\Models\Location\Ward;
use App\Models\ProjectFrontageType;
use App\Models\ProjectNote;
use App\Utils\File;
use App\Utils\Filter;
use App\Utils\GoogleMaps;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\CoreUsers;
use App\Models\Project;
use App\Utils\Common as Utils;
use App\Utils\Avatar;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use App\Models\Convenience;
use App\Models\Files;
use App\Models\Exterior;
use App\Models\FrontageType;
use App\Models\ProjectImages;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ProjectsController extends BaseBackendController
{
    protected $_data = array(
        'title'    => 'Dự án',
        'subtitle' => '',
    );
    protected $_limits = [
        10, 30, 50, 100, 500, 1000, 5000, 10000
    ];

    public function __construct()
    {
        $this->_data['status'] = Project::STATUS;
        $this->_data['categories'] = Project::CATEGORY;
    }

    public function index(Request $request)
    {
        $filter = $params = array_merge(array(
            'id'            => null,
            'name'          => null,
            'province_id'   => null,
            'province_name' => null,
            'district_id'   => null,
            'district_name' => null,
            'ward_id'       => null,
            'ward_name'     => null,
            'address'       => null,
            'sort'          => null,
            'status'        => null,
            'limit'         => config('constants.item_per_page_admin'),
        ), $request->all());

        $objProject = new Project();

        $params['limit'] = (int)$params['limit'] > 0 ? (int)$params['limit'] : config('constants.item_per_page_admin');
        $params['pagin_path'] = Utils::get_pagin_path($filter);
        $params['order_by'] = 'created_at';
        $params['order_by_direction'] = 'DESC';

        $projects = $objProject->get_by_where($params);

        $start = ($projects->currentPage() - 1) * $params['limit'];

        $this->_data['projects'] = $projects;
        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        $this->_data['sort'] = $params['sort'];

        $province_id = $filter['province_id'];
        $district_id = $filter['district_id'];

        $this->_data['provinces'] = Province::all();
        $this->_data['districts'] = $province_id ? District::where('province_id', $province_id)->get() : [];
        $this->_data['wards'] = $district_id ? Ward::where('district_id', $district_id)->get() : [];
        $this->_data['_limits'] = $this->_limits;

        if ($request->ajax()) {
            $view = \View::make('backend.projects.ajaxList', $this->_data);

            $return = [
                'e' => 0,
                'r' => $view->render()
            ];
            return \Response::json($return);
        }

        return view('backend.projects.index', $this->_data);
    }

    public function add(Request $request)
    {
        $aInit = Project::get_validation_admin();

        $province_id = old('province_id');
        $district_id = old('district_id');

        $image_ids = old('image_ids');

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $aInit)->validate();

            DB::beginTransaction();
            try {
                $params = array_fill_keys(array_keys($aInit), null);
                $params = array_merge(
                    $params, $request->only(array_keys($params))
                );

                $params['thumbnail_file_id'] = $params['image_ids'][0];

                //$params['address'] = $this->_getAddress($params);

                $params['slug'] = Filter::setSeoLink($params['name']);

                $params['user_id'] = Auth()->guard('backend')->user()->id;

                $project = Project::create($params);

                if ($project) {
                    $image_ids = [];

                    if (!empty($params['image_ids']))
                        $image_ids = Files::select(DB::raw('distinct id'))
                            ->where('is_temp', Files::IS_TEMP)
                            ->where('type', Files::TYPE_PROJECT)
                            ->whereIn('id', $params['image_ids'])
                            ->pluck('id')->toArray();

                    foreach ($image_ids as $id)
                        ProjectImages::create([
                            'project_id' => $project->id,
                            'file_id'    => $id,
                        ]);

                    Files::whereIn('id', $image_ids)->update(['is_temp' => null]);
                }

                DB::commit();
                $request->session()->flash('msg', ['info', 'Thêm dự án thành công!']);
                return redirect(Route('backend.projects.add'));
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->withInput()->withErrors(['messagge' => $e->getMessage()]);
            }
        }

        $this->_data['title'] = 'Thêm dự án';
        $this->_data['subtitle'] = 'Thêm dự án';

        $this->_data['relate_data'] = [
            'provinces'      => Province::orderBy('name', 'ASC')->get(),
            'districts'      => $province_id ? District::where('province_id', $province_id)->orderBy('position', 'ASC')->get() : [],
            'wards'          => $district_id ? Ward::where('district_id', $district_id)->orderBy('name', 'ASC')->get() : [],
            'file_image_ids' => $image_ids ? Files::whereIn('id', $image_ids)->get() : [],
            'status'         => Project::STATUS,
            'categories'     => Project::CATEGORY,
        ];

        return view('backend.projects.add', $this->_data);
    }

    public function edit(Request $request, $project_id)
    {
        $project = Project::select(DB::raw("*"))
            ->find($project_id);

        if (!$project)
            abort(404);

        $aInit = Project::get_validation_admin();

        $province_id = old('province_id', isset($project->province->id) ? $project->province->id : null);
        $district_id = old('district_id', isset($project->district->id) ? $project->district->id : null);
        $ward_id = old('ward_id', isset($project->ward->id) ? $project->ward->id : null);

        $image_ids = old('image_ids', isset($project->images) ? $project->images->pluck('id') : []);

        if ($request->getMethod() == 'POST') {
            Validator::make($request->all(), $aInit)->validate();

            DB::beginTransaction();
            try {
                $params = array_fill_keys(array_keys($aInit), null);
                $params = array_merge(
                    $params, $request->only(array_keys($params))
                );

                $params['thumbnail_file_id'] = $params['image_ids'][0];

                //$params['address'] = $this->_getAddress($params);

                $params['slug'] = Filter::setSeoLink($params['name']);
                $params['user_id_updated'] = Auth()->guard('backend')->user()->id;

                $bolUpdate = $project->update($params);

                if ($bolUpdate) {
                    $image_ids = [];

                    if (!empty($params['image_ids']))
                        $image_ids = Files::select(DB::raw('distinct id'))
//                            ->where('is_temp', Files::IS_TEMP)
                            ->where('type', Files::TYPE_PROJECT)
                            ->whereIn('id', $params['image_ids'])
                            ->pluck('id')->toArray();

                    ProjectImages::where('project_id', $project_id)->delete();
                    foreach ($image_ids as $id)
                        ProjectImages::create([
                            'project_id' => $project_id,
                            'file_id'    => $id,
                        ]);

                    Files::whereIn('id', $image_ids)->update(['is_temp' => null]);
                }

                DB::commit();

                $request->session()->flash('msg', ['info', 'Sửa dự án thành công!']);
                return redirect(url()->current());

            } catch (\Exception $e) {
                DB::rollBack();
                \Log::debug($e);
                return redirect()->back()->withInput()->withErrors(['messagge' => $e->getMessage()]);
            }
        }

        $this->_data['title'] = 'Sửa dự án';
        $this->_data['subtitle'] = 'Sửa dự án';

        $this->_data['project'] = $project;

        $this->_data['relate_data'] = [
            'provinces'      => Province::orderBy('name', 'ASC')->get(),
            'districts'      => $province_id ? District::where('province_id', $province_id)->orderBy('position', 'ASC')->get() : [],
            'wards'          => $district_id ? Ward::where('district_id', $district_id)->orderBy('name', 'ASC')->get() : [],
            'file_image_ids' => $image_ids ? Files::whereIn('id', $image_ids)->get() : [],
            'status'         => Project::STATUS,
            'categories'     => Project::CATEGORY,
        ];

        return view('backend.projects.edit', $this->_data);
    }

    public function ajaxdelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_ids' => 'required|array',
        ]);

        if ($validator->fails()) {
            return \Response::json([
                'e' => 1,
                'r' => $validator->errors()->first()
            ]);
        }
        $project_ids = $request->get('project_ids');

        Project::whereIn('id', $project_ids)->delete();
        $return = [
            'e' => 0,
            'r' => ''
        ];
        return \Response::json($return);
    }

    public function _getAddress($params)
    {
        $province = Province::findOrFail($params['province_id']);
        $district = District::findOrFail($params['district_id']);
        $ward = Ward::findOrFail($params['ward_id']);

        $address = $params['address'] . ' ' . $ward->name . ', ' . $district->name . ', ' . $province->name;
        return $address;
    }
}