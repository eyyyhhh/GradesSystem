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
    public function showGrade(Request $request){
        $subjects = subjectModel::paginate(4,['*'], 'subjects_page');
        $subjectDropdown = subjectModel::all();

        // Base query
        $query = DB::table('grades')
            ->join('subject', 'grades.subject_id', '=', 'subject.id')
            ->select('grades.*', 'subject.subject_name');

        // SEARCH FILTER (student name OR subject name)
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('grades.student', 'like', '%' . $request->search . '%')
                ->orWhere('subject.subject_name', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->subject_id) {
            $query->where('grades.subject_id', $request->subject_id);
        }
         $subjectDropdowns = DB::table('subject')->get();

    
        // Pagination (keeps search filter)
        $grades = $query->paginate(4)->withQueryString();

        return view('welcome', compact('grades', 'subjects', 'subjectDropdown','subjectDropdowns'));

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
        $grade-> subject_id= request ('subject');
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
