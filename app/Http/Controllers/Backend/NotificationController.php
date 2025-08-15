<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\BaseBackendController;
use App\Jobs\PushNotification;
use App\Models\Banner;
use App\Models\Notification;
use App\ReportsHourly;
use App\Utils\Firebase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Utils\Common as Utils;
use Illuminate\Support\Facades\Validator;

class NotificationController extends BaseBackendController
{
    protected $_data = [
        'title' => 'Thông báo',
    ];

    public function index(Request $request)
    {
        $this->_data['subtitle'] = 'Thông báo';

        $filter = $params = array_merge(array(
            'title'  => null,
            'chanel' => null,
        ), $request->all());

        $params['pagin_path'] = Utils::get_pagin_path($filter);

        $notification = Notification::with('to_user')
            ->orderByDesc('created_at')
            ->whereNotNull('user_id_created')
            ->where('company_id', config('constants.company_id'));

        $notification = $notification->paginate(50)->withPath($params['pagin_path']);

        $start = ($notification->currentPage() - 1) * config('constants.item_perpage');

        $this->_data['notifications'] = $notification;
        $this->_data['start'] = $start;
        $this->_data['filter'] = $filter;
        $this->_data['chanels'] = [
            1 => 'Chung',
            2 => 'Riêng',
        ];

        return view('backend.notification.index', $this->_data);
    }

    public function add(Request $request)
    {
        $validator_rule = [
            'title'    => 'required|string|max:200',
            'content'  => 'required|string|max:2000',
            'user_ids' => 'nullable',
            'chanel'   => 'required|in:1,2',
        ];

        $form_init = array_merge(
            array(
                'title'    => null,
                'content'  => null,
                'user_ids' => null,
                'chanel'   => null,
            ),
            $request->all()
        );

        $form_init = array_merge($form_init, $request->old());

        $this->_data['form_init'] = (object)$form_init;

        if ($request->getMethod() == 'POST') {

            Validator::make($request->all(), $validator_rule)->validate();
            $form_init['company_id'] = config('constants.company_id');

            if ($form_init['chanel'] == 2) {
                if (empty($form_init['user_ids'])) {
                    return redirect()->back()
                        ->withInput($request->all())
                        ->withErrors(['message' => "Vui lòng chọn sđt người nhận!"]);
                }
                $form_init['user_ids'] = array_unique($form_init['user_ids']);
                foreach ($form_init['user_ids'] as $user_id) {
                    $form_init['type'] = 1;
                    $form_init['to_user_id'] = $user_id;
                    $form_init['user_id_created'] = Auth()->guard('backend')->user()->id;
                    $notification = Notification::create($form_init);

                    $this->dispatch((new PushNotification($notification))->onQueue('push_notification'));
                }
            } else {
                $form_init['type'] = 1;
                $form_init['user_id_created'] = Auth()->guard('backend')->user()->id;
                $notification = Notification::create($form_init);
                $this->dispatch((new PushNotification($notification))->onQueue('push_notification'));
            }

            $request->session()->flash('msg', ['info', 'Push thông báo thành công!']);
            return redirect(Route('backend.notification.index'));
        }

        $this->_data['subtitle'] = 'Push thông báo';

        return view('backend.notification.form', $this->_data);
    }

    public function delete(Request $request, $id)
    {
        try {
            $data = Notification::findOrFail($id);
            $data->delete();
            $request->session()->flash('msg', ['info', 'Đã xóa thành công!']);
        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        return redirect()->back();
    }
    public function pushAgent(Request $request, $id)
    {
        try {
            $data = Notification::findOrFail($id);

            $form_init['company_id'] = config('constants.company_id');
            $form_init['chanel'] = $data->chanel;
            $form_init['title'] = $data->title;
            $form_init['content'] = $data->content;
            $form_init['type'] = 1;
            $form_init['to_user_id'] = $data->to_user_id;
            $form_init['user_id_created'] = $data->user_id_created;

            $notification = Notification::create($form_init);
            $this->dispatch((new PushNotification($notification))->onQueue('push_notification'));


            $request->session()->flash('msg', ['info', 'Push thông báo thành công!']);
        } catch (\Exception $e) {
            $request->session()->flash('msg', ['danger', 'Có lỗi xảy ra, vui lòng thử lại!']);
        }
        return redirect()->back();
    }
}
