<?php
namespace App\Http\Controllers\Web\Backend\V2;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class SystemUserController extends Controller
{
    /**
     * Display the V2 preview page for backend testing.
     */
    public function index(): string
    {
        return 'Hello V2 System User Index!';
    }

}
