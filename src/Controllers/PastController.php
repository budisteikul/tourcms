<?php
namespace budisteikul\tourcms\Controllers;

use App\Http\Controllers\Controller;
use budisteikul\tourcms\DataTables\PastDataTable;

class PastController extends Controller
{
	public function index(PastDataTable $dataTable)
    {
        return $dataTable->render('tourcms::past.index');
    }
}
?>