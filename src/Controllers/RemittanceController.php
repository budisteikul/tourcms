<?php
namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use budisteikul\tourcms\DataTables\RemittanceDataTable;

class RemittanceController extends Controller
{
	public function index(RemittanceDataTable $dataTable)
    {
        return $dataTable->render('tourcms::remittance.index');
    }
}
?>