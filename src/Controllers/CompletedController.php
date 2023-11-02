<?php
namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use budisteikul\tourcms\DataTables\CompletedDataTable;

class CompletedController extends Controller
{
	public function index(CompletedDataTable $dataTable)
    {
        return $dataTable->render('tourcms::completed.index');
    }
}
?>