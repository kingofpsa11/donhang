@extends('layouts.dashboard')

@section('content')
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Danh mục thông báo</h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                    <tbody>
                        @foreach ($notifications as $notification)
                            @php
                                $url = '';
                                $read = '?read=' . $notification->id;
                                if ($notification->type === 'App\\Notifications\\ManufacturerOrder') {
                                    $url =  '<a href="' . route('manufacturer-notes.show',$notification->data['manufacturer_order_id']). $read . '"><strong>Phòng Kế hoạch</strong> gửi LSX số ' . $notification->data['number'] . '</a>';
                                } elseif ($notification->type === 'App\\Notifications\\OutputOrder') {
                                    $url = '<a href="' . route('good-deliveries.edit',$notification->data['good_delivery_id']) . $read .'">Phòng KHKD đã gửi LXH số ' . $notification->data['output_order_number'] . '</a>';
                                } elseif ($notification->type === 'App\\Notifications\\Contract') {
                                    $text = '';
                                    if ($notification->data['status'] === 5) {
                                        $text = 'Lãnh đạo đã phê duyệt đơn hàng số ' . $notification->data['number'];
                                    } else {
                                        $text = 'Phòng KHKD trình đơn hàng số ' . $notification->data['number'];
                                    }
                                    $url = '<a href="' . route('contracts.show', $notification->data['contract_id']) . $read .'">' . $text . '</a>';
                                } elseif ($notification->type === 'App\\Notifications\\OutputOrderApproved') {
                                    $url = '<a href="./output-orders/'. $notification->data['output_order_id'] . $read .'">Lệnh xuất hàng số ' . $notification->data['output_order_number'] . '</a>';
                                }
                            @endphp
                            <tr class="@if(!$notification->read_at) info @endif">
                                <td>{!! $url !!}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- /.table -->
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@stop

@section('javascript')
    <script>
        $(document).ready(function () {

        });
    </script>
@stop