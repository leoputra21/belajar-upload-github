<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Home;
use App\Models\Home as ModelsHome;

class HomeController extends Controller
{
    public function index(Request $request)
	{
		$iot = DB::table('iot')
			->latest('datetime')
			->take(1)
			->get();

		$grafik=DB::table('iot')
				->selectRaw('MAX(temp) as temp , DATE_FORMAT(datetime, "%a") as datetime')
				// ->selectRaw('MAX(ntu) as ntu , DATE_FORMAT(datetime, "%a") as datetime')
				->groupByRaw('DATE_FORMAT(datetime, "%a")')
				->get();
		$grafik2=DB::table('iot')
				->selectRaw('MAX(ntu) as ntu , DATE_FORMAT(datetime, "%a") as datetime')
				// ->selectRaw('MAX(ntu) as ntu , DATE_FORMAT(datetime, "%a") as datetime')
				->groupByRaw('DATE_FORMAT(datetime, "%a")')
				->get();


				
		$tabel=DB::table('iot')
				->paginate(10);
				
			//  return $grafik;

		// $grafiksuhu = DB::raw("Select temp, DATE_FORMAT(datetime, '%d') as datetime GROUP BY ('datetime') FROM iot");
			// return $grafik;
		

		// $iot = ModelsHome::latest('temp','ntu')->offset(10)->take(1)->get();
		// $iot = ModelsHome::selectRaw('TIME(waktu) AS jam')->first()->get();
    	return view('dashboard', [
			'iot' => $iot,
		'temp' => $grafik->pluck('temp')->toArray(),
		'ntu' => $grafik2->pluck('ntu')->toArray(),
		'datetime' => $grafik->pluck('datetime')->toArray(),
		'tabel' => $tabel,
		]);

    	        // mengambil data dari table iot
		// $iot = DB::table('iot')->where('temp')->latest();
 
    	//         // mengirim data iot ke view dashboard
		// return view('dashboard',['iot' => $iot]);
 
	}

	public function filter(Request $request)
	{
		// menangkap data pencarian
		$filter = $request->filter;
 
    		// mengambil data dari table siswa sesuai pencarian data
		$search = DB::table('iot')
		->where('datetime',"==", $request->get('filter'))
		->paginate();
 
    		// mengirim data siswa ke view index
		return view('dashboard',['search' => $search]);
	}
}
