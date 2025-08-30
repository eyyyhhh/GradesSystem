<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\gradeModel;

class gradeController extends Controller
{
    public function showGrade(){
        $grades = gradeModel::paginate(2);

        return view('welcome', compact('grades'));

    }
}
