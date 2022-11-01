<?php

namespace App\Observers;

use App\Models\Order;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\MessageBag;
use QrCode;
use \PDF;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        //
    }

    /**
     * Handle the Order "updated" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */

    public function saving(Order $order)
    {
        if ($order->type == 'offline' && empty($order->order_no)) {
            $order->order_no = uniqid();
        }

        // pdf文件处理
        $oldUrl = $order->express_sheet_pdf_url;

        $filesUrl = public_path('uploads') . '/files/';
        $username = Admin::user()->name;

        // 生成相关目录
        if(!is_dir($filesUrl . date('Y-m-d'))) {
            mkdir($filesUrl . date('Y-m-d'));
        }

        if(!is_dir($filesUrl . date('Y-m-d') . '/' . $username)) {
            mkdir($filesUrl. date('Y-m-d') . '/' . $username);
        }

        if (!empty($oldUrl)) {

            $oldFile = $filesUrl . ltrim($oldUrl, 'files/');

            $deleteOldFile = true;

            // 文件不存在时不进行删除
            if (!file_exists($oldFile)) {
                $deleteOldFile = false;
                $oldFile = $filesUrl . $oldUrl;
            }

            // 如果内容没有变更，则不进行删除
            if ($order->isDirty('express_sheet_pdf_url')) {

                // 入庫成功之後，將PDF重名名，名字格式為(虾皮訂單号+系統用戶名字-物品描述.pdf）
                $order->express_sheet_pdf_url = date('Y-m-d') . '/' . $username . '/' .  $order->order_no . '.pdf';

                $newBakerFilePath = $filesUrl . date('Y-m-d') . '/' . $username . '/' .  $order->order_no . '-baker.pdf';

                $newFilePath = $filesUrl . $order->express_sheet_pdf_url;

                copy($oldFile, $newBakerFilePath);

                $sh = "gs -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -dDownsampleColorImages=true -dColorImageResolution=300 -dNOPAUSE -dBATCH -sOutputFile=$newFilePath $newBakerFilePath";
                $res = exec($sh);

                Log::info("gs return: {$res} path: {$newFilePath}");

                if ($deleteOldFile) {
                    unlink($oldFile);
                }

            }
        }

        // 生成二维码
        $qrCodeUrl = $filesUrl . date('Y-m-d') . '/' . $username . '/' .  $order->order_no . '.svg';
        $qrCodePdf = $filesUrl . date('Y-m-d') . '/' . $username . '/' .  $order->order_no . '-qr.pdf';
//        QrCode::generate($order->order_no, $qrCodeUrl);

        // 生成二维码pdf
//        PDF::loadView('admin.qr.qr-pdf-template', ['imageUrl' => $qrCodeUrl, 'orderNo' => $order->order_no, 'recipients' => $order->recipients, 'sourceName' => $order->internetChannel->name])->save($qrCodePdf);

        // 管理员处理过的单据，无法再次变更
        if ($order->isDirty('is_dispose')) {
            if (Admin::user()->isAdministrator()) {
                $order->dispose_rule = 'administrator';
            } else {
                if ($order->dispose_rule = 'administrator') {
                    return response()->json([
                        'status'  => false,
                        'message' => '管理员处理过的单据，无法再次变更',
                    ]);
                }

                $order->dispose_rule = 'not_administrator';
            }

            $order->dispose_time = date('Y-m-d H:i:s');
        }

        // 更新时的唯一值校验
        if ($order->isDirty('xiapi_waybill_no')) {
            $orderModel = new Order();
            $res = $orderModel->where('xiapi_waybill_no', $order->xiapi_waybill_no)->where('id', '!=', $order->id)->first();

            if ($res) {
                $error = new MessageBag([
                    'title'   => '请求失败',
                    'message' => '面單條碼重复',
                ]);

                return back()->with(compact('error'));
            }
        }

        if ($order->isDirty('order_no')) {
            $orderModel = new Order();
            $res = $orderModel->where('order_no', $order->order_no)->where('id', '!=', $order->id)->first();

            if ($res) {
                $error = new MessageBag([
                    'title'   => '请求失败',
                    'message' => '虾皮订单号重复',
                ]);

                return back()->with(compact('error'));
            }
        }

        if (Admin::user()->isAdministrator()) {
            $order->is_interior = 1;
        } else {
            $order->is_interior = 0;
        }

    }

    /**
     * Handle the Order "deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        $path = public_path('uploads') . '/files/' . $order->express_sheet_pdf_url;
        if (file_exists($path) && $order->express_sheet_pdf_url) {
            unlink($path);
        }
    }

    /**
     * Handle the Order "restored" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     *
     * @param  \App\Models\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
