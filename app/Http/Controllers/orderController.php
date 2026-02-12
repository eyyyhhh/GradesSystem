<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\orderModel;


class orderController extends Controller
{
    public function showOrder(){

        $tblOrder = orderModel::paginate(5);

        return view('order', compact('tblOrder'));
    }
}
