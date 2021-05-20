<?php

namespace Modules\Admin\Http\Controllers\Api\FbNotification;

use App\Events\AdminNotiCreated;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Modules\Mon\Entities\FbNotification;
use Modules\Admin\Http\Requests\FbNotification\CreateFbNotificationRequest;
use Modules\Admin\Transformers\FbNotificationTransformer;
use Modules\Admin\Http\Requests\FbNotification\UpdateFbNotificationRequest;
use Modules\Admin\Repositories\FbNotificationRepository;
use Illuminate\Routing\Controller;
use Modules\Mon\Http\Controllers\ApiController;
use Modules\Mon\Auth\Contracts\Authentication;

class FbNotificationController extends ApiController
{
    /**
     * @var FbNotificationRepository
     */
    private $fbnotificationRepository;

    public function __construct(Authentication $auth, FbNotificationRepository $fbnotification)
    {
        parent::__construct($auth);

        $this->fbnotificationRepository = $fbnotification;
    }


    public function index(Request $request)
    {
        return FbNotificationTransformer::collection($this->fbnotificationRepository->serverPagingFor($request));
    }


    public function all(Request $request)
    {
        return FbNotificationTransformer::collection($this->fbnotificationRepository->newQueryBuilder()->get());
    }


    public function store(CreateFbNotificationRequest $request)
    {
	    $data = $request->all();
	    if (empty($request->get('scheduled_at'))) {
		    $data['sent']=1;
	    }
	    $noti = $this->fbnotificationRepository->create($data);
	    if (empty($request->get('scheduled_at'))) {
		    event(new AdminNotiCreated($noti->toArray()));
	    } else {

	    }

        return response()->json([
            'errors' => false,
            'message' => trans('backend::fbnotification.message.create success'),
        ]);
    }


    public function find(FbNotification $fbnotification)
    {
        return new  FbNotificationTransformer($fbnotification);
    }

    public function update(FbNotification $fbnotification, UpdateFbNotificationRequest $request)
    {
	    if ($fbnotification->scheduled_at) {
		    $scheduledAt= Carbon::createFromFormat('Y-m-d H:i:s', $fbnotification->scheduled_at);

		    $scheduledAt->addMinutes(5);
		    if ($scheduledAt->addMinutes(5)->greaterThan(Carbon::now()) || $fbnotification->sent) {
			    return response()->json([
				    'errors' => [
					    'scheduled_at' => ['Tin đã được lên lịch, không cho phép cập nhật.']
				    ],
				    'message' => 'Tin đã được lên lịch, không cho phép cập nhật.',
			    ], 422);
		    }
	    }
        $this->fbnotificationRepository->update($fbnotification, $request->all());

        return response()->json([
            'errors' => false,
            'message' => trans('backend::fbnotification.message.update success'),
        ]);
    }

    public function destroy(FbNotification $fbnotification)
    {
        $this->fbnotificationRepository->destroy($fbnotification);

        return response()->json([
            'errors' => false,
            'message' => trans('backend::fbnotification.message.delete success'),
        ]);
    }
}
