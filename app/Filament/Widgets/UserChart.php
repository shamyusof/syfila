<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class UserChart extends ChartWidget
{
  protected static ?string $heading = 'Users';

  protected function getData(): array
  {

    $userCounts = DB::table('users')
      ->select(DB::raw('MONTH(created_at) as month'), DB::raw('COUNT(*) as count'))
      ->whereYear('created_at', Carbon::now()->year)
      ->groupBy(DB::raw('MONTH(created_at)'))
      ->orderBy(DB::raw('MONTH(created_at)'))
      ->get();

    // include months with no users
    $userCounts = $userCounts->mapWithKeys(function ($item) {
      return [$item->month => $item->count];
    });

    $userCounts = collect(range(1, 12))->map(function ($month) use ($userCounts) {
      return $userCounts->get($month, 0);
    });

    return [
      'datasets' => [
        [
          'label' => 'Registered User',
          'data' => $userCounts,
        ],
      ],
      'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
    ];
  }

  protected function getType(): string
  {
    return 'bar';
  }
}
