<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\RestfulHandler;
use App\Http\Controllers\Api\TransformerTrait;
use App\Repositories\Orders\OrderRepository;
use App\Http\Transformers\OrderTransformer;

class StatisticController extends ApiController
{
	use TransformerTrait, RestfulHandler;

    protected $order;

    protected $validationRules = [
        'start_date'  => 'required|date_format:Y-m-d',
        'end_date'    => 'required|date_format:Y-m-d',
        'user_id'     => 'nullable|max:50|exists:users,id',
        'view'        => 'nullable|max:10'
    ];

    protected $validationMessages = [
        'start_date.required'    => 'Vui lòng nhập thời gian bắt đầu',
        'start_date.date_format' => 'Thời gian bắt đầu phải theo định dạng Y-m-d',
        'end_date.required'      => 'Vui lòng nhập thời gian kết thúc',
        'end_date.date_format'   => 'Thời gian bắt đầu phải theo định dạng Y-m-d',
        'user_id.exists'         => 'Nhân viên không tồn tại trên hệ thống'

    ];

    function __construct(OrderRepository $order, OrderTransformer $transformer)
    {
        $this->order = $order;
        $this->setTransformer($transformer);
        $this->checkPermission('statistic');
    }

    public function getResource()
    {
        return $this->order;
    }

    public function statisticByTime(Request $request)
    {
        try {
            $this->validate($request, $this->validationRules, $this->validationMessages);

            $params = $request->all();

            // Check User là merchant thì gán lại merchant_id
            $user = getCurrentUser();

            $datas = $this->getResource()->numberTicketsBooked($params);

            // Xử lý dữ liệu:
            $times                   = [];

            $count_TOTAL_ORDER     = [];
            $count_BOOKING_NEW       = [];
            $count_BOOKING_DESIGN   = [];
            $count_BOOKING_PRODUCING = [];
            $count_BOOKING_FINISH   = [];
            $count_BOOKING_CANCEL    = [];

            foreach ($datas as $key => $booking) {
                array_push($times, $booking->create_time);

                array_push($count_TOTAL_ORDER, (int) $booking->total_order);
                array_push($count_BOOKING_NEW, (int) $booking->total_new);
                array_push($count_BOOKING_DESIGN, (int) $booking->total_design);
                array_push($count_BOOKING_PRODUCING, (int) $booking->total_producing);
                array_push($count_BOOKING_FINISH, (int) $booking->total_finish);
                array_push($count_BOOKING_CANCEL, (int) $booking->total_cancel);
            }

            $series = [
                [
                    'name'    => "Tổng số",
                    'data'    => $count_TOTAL_ORDER,
                    'visible' => false
                ],
                [
                    'name'    => "Mới",
                    'data'    => $count_BOOKING_NEW,
                    'visible' => false
                ],
                [
                    'name'    => "Đang thiết kế",
                    'data'    => $count_BOOKING_DESIGN,
                    'visible' => false
                ],
                [
                    'name'    => "Đang làm",
                    'data'    => $count_BOOKING_PRODUCING,
                    'visible' => false
                ],
                [
                    'name'    => "Hoàn thành",
                    'data'    => $count_BOOKING_FINISH,
                    'visible' => true
                ],
                [
                    'name'    => "Bị hủy",
                    'data'    => $count_BOOKING_CANCEL,
                    'visible' => true
                ]
            ];

            $responses = [
                "days" => $times,
                "series" => $series
            ];
            return $this->infoResponse($responses);

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return $this->errorResponse([
                'errors' => $validationException->validator->errors(),
                'exception' => $validationException->getMessage()
            ]);
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
