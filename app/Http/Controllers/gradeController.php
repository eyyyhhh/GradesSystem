<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\productModel;
use App\Models\ingridientModel;
use App\Models\recipeModel;
use App\Models\orderModel;
use Illuminate\Support\Facades\DB;

class gradeController extends Controller
{
    
    // public function showGrade(Request $request){
    //     $product = productModel::paginate(4,['*'], 'product_page');
    //     $subjectDropdown = productModel::all();

    //     // Base query
    //     $query = DB::table('grades')
    //         ->join('subject', 'grades.subject_id', '=', 'subject.id')
    //         ->select('grades.*', 'subject.subject_name');

    //     // SEARCH FILTER (student name OR subject name)
    //     if ($request->search) {
    //         $query->where(function ($q) use ($request) {
    //             $q->where('grades.student', 'like', '%' . $request->search . '%')
    //             ->orWhere('subject.subject_name', 'like', '%' . $request->search . '%');
    //         });
    //     }
        
    //     if ($request->subject_id) {
    //         $query->where('grades.subject_id', $request->subject_id);
    //     }
    //      $subjectDropdowns = DB::table('subject')->get();

    
    //     // Pagination (keeps search filter)
    //     $grades = $query->paginate(4)->withQueryString();

    //     return view('welcome', compact('grades', 'subjects', 'subjectDropdown','subjectDropdowns'));

    // }
    // public function showSubject(){
    //     $subjects = subjectModel::paginate(4);
    //     return view('welcome', compact('subjects'));
    // }
    public function showProduct() {
        $product =  productModel::paginate(4);

        $queryRecipe = DB::table('tblRecipe')
            ->join('tblIngridient', 'tblRecipe.ingridientId', '=', 'tblIngridient.id')
            ->select('tblRecipe.*', 'tblIngridient.ingridientName', 'tblIngridient.id as ingredient_id')
            ->get();

        $recipes = DB::table('tblRecipe')
            ->join('tblIngridient', 'tblRecipe.ingridientId', '=', 'tblIngridient.id')
            ->select('tblRecipe.recipeId', 'tblIngridient.ingridientName')
            ->get()
            ->groupBy('recipeId'); // group ingredients per product

        $products = DB::table('tblProduct')
            ->join('tblRecipe', 'tblProduct.Id', '=', 'tblRecipe.recipeId')
            ->join('tblIngridient', 'tblRecipe.ingridientId', '=', 'tblIngridient.id')
            ->select(
                'tblProduct.*',
                'tblRecipe.ingridientId',
                'tblIngridient.ingridientName'
            )
            ->paginate(4);

        $queryIngridient = ingridientModel::all();

        return view('welcome', compact('product', 'queryRecipe', 'queryIngridient', 'products', 'recipes'));

    }
    public function addProduct(){
        $product = new productModel();

        $product -> productName = request ('productName');
        $product-> price = request('price');
        $product-> description = request('description');

        error_log($product);
        $product->save();

        $recipeId = $product->id;

        $ingredientId = request('ingridientId'); // array
        $quantities = request('qty');     

        foreach ($ingredientId as $index => $ingId) {
            $recipe = new recipeModel();
            $recipe->recipeId      = $recipeId;
            $recipe->ingridientId  = $ingId;
            $recipe->qty           = $quantities[$index];
            $recipe->save();
        }

        return redirect('/');
    }
    public function deleteProduct($id)   
    {
        $product = productModel::findOrFail($id);
        $product->delete();

        return redirect()->back()->with('success', 'Grade deleted successfully.');
    }
    public function updateProduct($id){
        $product = productModel::where('id', $id)->first();

        $product-> productName = request ('productName');
        $product-> price= request ('price');
        $product->recipeId= request('recipeId');
        $product->description= request('description');


        error_log($product);
        $product->save();

        return redirect('/');
    }
    public function addRecipe(){
      
        $recipeId = request('recipeId');
        $ingredientId = request('ingridientId'); // array
        $quantities = request('qty');     

        foreach ($ingredientId as $index => $ingId) {
            $recipe = new recipeModel();
            $recipe->recipeId      = $recipeId;
            $recipe->ingridientId  = $ingId;
            $recipe->qty           = $quantities[$index];
            $recipe->save();
        }
        return redirect('/');
    }
    public function addIngridient(){
        $ingridient = new ingridientModel();

        $ingridient -> ingridientName = request ('ingridientName');
        $ingridient-> stock = request('stock');
        $ingridient -> price = request ('price');

        error_log($ingridient);
        $ingridient->save();

        return redirect('/');
    }
}
