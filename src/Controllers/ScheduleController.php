<?php
namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use budisteikul\tourcms\DataTables\ScheduleDataTable;

class ScheduleController extends Controller
{
	public function index(ScheduleDataTable $dataTable)
    {
        return $dataTable->render('tourcms::schedule.index');
    }
}
?>