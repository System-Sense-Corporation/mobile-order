<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <title>{{ $subject }}</title>
    </head>
    <body>
        <p>{{ $intro }}</p>

        @isset($statusLabel)
            <p>{{ __('messages.orders.notification.status_label', ['status' => $statusLabel]) }}</p>
        @endisset

        <table role="presentation" cellpadding="0" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 600px;">
            <tbody>
                <tr>
                    <th align="left" style="padding: 4px 8px; text-align: left;">{{ __('messages.orders.notification.order_date') }}</th>
                    <td style="padding: 4px 8px;">{{ $order->order_date->toDateString() }}</td>
                </tr>
                <tr>
                    <th align="left" style="padding: 4px 8px; text-align: left;">{{ __('messages.orders.notification.delivery_date') }}</th>
                    <td style="padding: 4px 8px;">{{ $order->delivery_date->toDateString() }}</td>
                </tr>
                <tr>
                    <th align="left" style="padding: 4px 8px; text-align: left;">{{ __('messages.orders.notification.customer') }}</th>
                    <td style="padding: 4px 8px;">{{ $order->customer->name }}</td>
                </tr>
                <tr>
                    <th align="left" style="padding: 4px 8px; text-align: left;">{{ __('messages.orders.notification.product') }}</th>
                    <td style="padding: 4px 8px;">{{ $order->product->name }} ({{ $order->product->code }})</td>
                </tr>
                <tr>
                    <th align="left" style="padding: 4px 8px; text-align: left;">{{ __('messages.orders.notification.quantity') }}</th>
                    <td style="padding: 4px 8px;">{{ number_format($order->quantity) }} {{ $order->product->unit }}</td>
                </tr>
                <tr>
                    <th align="left" style="padding: 4px 8px; text-align: left;">{{ __('messages.orders.notification.notes') }}</th>
                    <td style="padding: 4px 8px;">{{ filled($order->notes) ? $order->notes : __('messages.orders.notification.notes_empty') }}</td>
                </tr>
            </tbody>
        </table>

        <p>{{ $outro }}</p>
    </body>
</html>
