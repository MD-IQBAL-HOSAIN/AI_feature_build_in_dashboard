<?php

namespace App\Http\Controllers\Web\Backend\V1;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\User;
use Illuminate\Support\Facades\App;

class DashboardController extends Controller
{
    public function index()
    {

        $total_users = User::count();
        $total_language = Language::count();


        /* --- User chart data  start --- */
        $newUsers = User::whereYear('created_at', now()->year)
            ->get();

        // Define all months of the year
        $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        // Initialize all months with 0
        $userCountsByMonth = array_fill_keys($months, 0);

        // Group the users by the month they were created
        $usersGroupedByMonth = $newUsers->groupBy(function ($user) {
            return $user->created_at->format('F'); // Group by month name
        });

        // Populate the count of users in the correct month
        foreach ($usersGroupedByMonth as $month => $users) {
            $userCountsByMonth[$month] = count($users);
        }

        // Prepare chart data
        $chartData = [
            'labels' => $months,
            'data' => array_values($userCountsByMonth), // 12 values, all integers
        ];
        // dd($chartData);
        /* --- User chart data  end--- */





        /*Total Order chart data start */
        /*
        $totalOrders = Order::where('payment_status', 'paid')->whereYear('created_at', now()->year)->get();

        // Initialize all months with 0
        $orderCountsByMonth = array_fill_keys($months, 0);

        // Group the orders by the month they were created
        $ordersGroupedByMonth = $totalOrders->groupBy(function ($order) {
            return $order->created_at->format('F'); // Group by month name
        });

        // Populate the count of orders in the correct month
        foreach ($ordersGroupedByMonth as $month => $orders) {
            $orderCountsByMonth[$month] = count($orders);
        }

        // Prepare chart data
        $orderChartData = [
            'labels' => $months,
            'data' => array_values($orderCountsByMonth), // 12 values, all integers
        ]; */
        /*Total Order chart data end */


        /* Total payment chart data start */
        // Initialize months
        /*
      $months = [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ];

        // Initialize array to hold sums for each month
        $paymentSumByMonth = array_fill_keys($months, 0);

        // Get all paid orders for the current year
        $totalPaymentsByMonth = Order::where('payment_status', 'paid')
            ->whereYear('created_at', now()->year)
            ->get()
            ->groupBy(function ($order) {
                return $order->created_at->format('F'); // Group by month name
            })
            ->map(function ($orders) {
                return $orders->sum('total_amount'); // Sum the total_amount for each month
            });

        // Fill the array with payment sums for each month
        foreach ($paymentSumByMonth as $month => $sum) {
            // Check if the month exists in the grouped collection
            if ($totalPaymentsByMonth->has($month)) {
                $paymentSumByMonth[$month] = $totalPaymentsByMonth[$month];
            }
        }

        // Prepare chart data
        $paymentChartData = [
            'labels' => $months,
            'data' => array_values($paymentSumByMonth), // 12 values, total payments for each month
        ];

        */

        /* Total payment chart data end */

        return view('backend.dashboard', compact('total_users', 'chartData', 'total_language'));
    }
}







