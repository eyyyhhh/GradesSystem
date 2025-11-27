<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\gradeModel;
use App\Models\subjectModel;
use App\Models\sectionModel;
use Illuminate\Support\Facades\DB;

class gradeController extends Controller
{
    public function storeGrade()
    {
        $grade = new gradeModel();

        $grade-> grades = request ('grades');
        $grade-> subject= request ('subject');
        $grade->student= request('student');

        error_log($grade);
        $grade->save();

        return redirect('/');
    }
    public function showGrade(){
        $subjects = subjectModel::paginate(4,['*'], 'subjects_page');
        $subjectDropdown = subjectModel::all();

        $grades = DB::table('grades')
            ->join('subject', 'grades.subject', '=', 'subject.id')
            ->select('subject.*', 'grades.*')
            ->paginate(4,['*'], 'grades_page');


        return view('welcome', compact('grades', 'subjects', 'subjectDropdown'));

    }
    public function deleteGrade($id)   
    {
        $grade = gradeModel::findOrFail($id);
        $grade->delete();

        return redirect()->back()->with('success', 'Grade deleted successfully.');
    }
    public function updateGrade($id)
    {
        $grade = gradeModel::where('id', $id)->first();

        $grade-> grades = request ('grades');
        $grade-> subject= request ('subject');
        $grade->student= request('student');

        error_log($grade);
        $grade->save();

        return redirect('/');
    }
    public function addSubject(){
        $subject = new subjectModel();

        $subject -> subject_name = request ('subject');
        $subject-> description = request('description');

        error_log($subject);
        $subject->save();

        return redirect('/');
    }
    public function showSubject(){
        $subjects = subjectModel::paginate(4);
        return view('welcome', compact('subjects'));
    }
    public function addSection(){
        $section = new sectionModel();

        $section -> gradeLevel = request('gradeLevel');
        $section ->section = request('section');
        $section -> teacher = request('teacher');

         error_log($section);
        $section->save();
        return redirect('/');
    }
}
