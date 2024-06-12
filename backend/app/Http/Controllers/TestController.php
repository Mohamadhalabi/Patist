<?php

namespace App\Http\Controllers;

use App\Helpers\CustomReminder;
use App\Models\Renewal;
use App\Workflows\MyWorkflow\MyWorkflow;
use App\Workflows\Renewal\RenewalPaymentWorkflow;
use App\Workflows\VerifyEmail\VerifyEmailWorkflow;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Workflow\WorkflowStub;
use Spatie\Permission\Models\Role;

class TestController extends Controller
{
    public function index()
    {
        return "hi";
    }
}
