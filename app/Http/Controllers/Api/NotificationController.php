<?php
/**
 * User: Manh
 * Date: 2/24/2020
 * Time: 2:47 PM
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseAPIController;
use App\Models\Notification;
use App\Models\NotificationRead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class NotificationController extends BaseAPIController
{
    /**
     * @OA\Get(
     *     path="/notification",
     *     tags={"Notifications"},
     *     summary="get all notifications",
     *     description="",
     *     operationId="notification",
     *     @OA\Parameter(
     *         name="company_id",
     *         description="Company ID",
     *         required=true,
     *         in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(
     *         name="api_key",
     *         description="API Key",
     *         required=true,
     *         in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(
     *         name="token",
     *         description="access token",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         description="limit",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="integer",)
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         description="page",
     *         required=false,
     *         in="query",
     *         @OA\Schema(type="integer",)
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Server error"),
     * )
     */
    public function index(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        if ($request->get('token', null))
            $user = $this->getAuthenticatedUser();

        $aInit = [
            'limit' => null,
        ];

        $params = array_merge(
            $aInit, $request->only(array_keys($aInit))
        );

        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }

        $params = array_filter($params);
        $params['token'] = $request->get('token');
        $params['pagin_path'] = route('notification.index') . '?' . http_build_query($params);

        if (isset($user))
            $params['user_id'] = $user->id;

        $return = Notification::get_by_where($params);
        return $this->returnResult($return);
    }

    /**
     * @OA\Post(
     *     path="/notification/read",
     *     tags={"Notifications"},
     *     summary="read notification",
     *     description="",
     *     operationId="readNotification",
     *     @OA\Parameter(name="company_id", description="Company ID", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(name="api_key", description="API Key", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\Parameter(name="token", description="token from api login", required=true, in="query",
     *         @OA\Schema(type="string",)
     *     ),
     *     @OA\RequestBody(
     *       required=true,
     *       @OA\MediaType(
     *           mediaType="multipart/form-data",
     *           @OA\Schema(
     *     			@OA\Property(property="notification_id", description="notification_id", type="integer", ),
     *              required={"notification_id"}
     *          )
     *       )
     *     ),
     *     @OA\Response(response="200", description="Success"),
     *     @OA\Response(response="400", description="Missing/Invalid params"),
     *     @OA\Response(response="500", description="Server error"),
     *     @OA\Response(response="401", description="Unauthorized"),
     *     @OA\Response(response="403", description="Account is banned"),
     * )
     */
    public function read(Request $request)
    {
        if (!$request->get('company_id') || !$request->get('api_key'))
            return $this->throwError(400, 'Vui lòng nhập Company ID & API_Key!');

        $this->checkCompany($request->get('company_id'), $request->get('api_key'));

        $user = $this->getAuthenticatedUser();

        $validator = Validator::make($request->all(), [
            'notification_id' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->throwError($validator->errors()->first(), 400);
        }
        $notification_id = $request->get('notification_id');

        if ($notification_id != 0) {
            $notification = Notification::find($notification_id);

            if (!$notification)
                return $this->throwError('Thông báo không tồn tại!', 400);

            if (empty($notification->notification_read->toArray())) {
                NotificationRead::create([
                    'notification_id' => $notification_id,
                    'user_id'         => $user->id,
                ]);
            }

        } else {
            $notifications_id = DB::table('lck_notification')
                ->whereRaw("((to_user_id = {$user->id} and chanel=2) or chanel=1)")
                ->whereRaw("lck_notification.id not in(select notification_id from lck_notification_read where lck_notification_read.user_id={$user->id})")
                ->select('lck_notification.id')->get();

            foreach ($notifications_id as $notification) {
                $sql = "insert into lck_notification_read set user_id={$user->id}, notification_id={$notification->id};";
                DB::insert($sql);
            }
        }

        return $this->returnResult();
    }

}
