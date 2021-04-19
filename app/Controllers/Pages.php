<?php

namespace App\Controllers;

class Pages extends BaseController
{
	public function index(){

		$data = [
			'title' => 'Home | Zanpakuto',
		];
		return view('pages/home', $data);
	}

	public function about(){
		$data = [
			'title'=>'About | Zanpakuto'
		];	
		return view('pages/about', $data);
	}

	public function contact(){
		$data = [
			'title'=>'Contact Us | Zanpakuto',
			'alamat'=> [
			[
				'tipe' => 'kediaman',
				'alamat' => 'jl 04 km kaligawe',
				'kota' => 'semarang'
			],
			[
				'tipe' => 'kantor',
				'alamat' => 'perumahan genuk indah',
				'kota' => 'semarang'
			]
			]
			
		];	

		return view('pages/contact', $data);
	}
}
