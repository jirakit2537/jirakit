<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpabbot;
use Auth;
use Image;
use Storage;
use File;
use App\Post;
use App\Category;

class HelpabbotController extends Controller
{

    /**
    * Display a listing of the resource.
    *
    * @return Response
    */
   public function index()
   {
     $title = 'จัดการข้อมูลผู้ใช้';

     $help = Helpabbot::orderBy('id', 'DESC')->paginate(2);
     return view('helpabbot.index', compact('help','title','category'));

   }

   /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
   public function create()
   {


    //$tags = Tag::all();
    //$title = 'จัดการข้อมูลผู้ใช้';
     return view('helpabbot.create');
   }

   /**
    * Store a newly created resource in storage.
    *
    * @return Response
    */
   public function store(Request $request)
   {
    //dd($request);
     $this->validate($request, [
         'name' => 'required',
         'oldname' => 'required|unique:helpabbots,name' ,
        //  'ntname' => 'required',
        //  'btname' => 'required',
        //  'wtname' => 'required',
        //  'ptname' => 'required',
         'imgabbots' => 'required|mimes:jpg,jpeg,png|max:2500',

     ]);

     $help = new Helpabbot();
     $help->name = $request->input('name');
     $help->oldname = $request->input('oldname');
     $help->ntname = $request->input('ntname');
     $help->btname = $request->input('btname');
     $help->wtname = $request->input('wtname');
      $help->lwtname = $request->input('lwtname');
       $help->ptname = $request->input('ptname');
        $help->otname = $request->input('otname');
        $help->bodthday = $request->input('bodthday');
         $help->both = $request->input('both');
          //บันทึกรูป
  if($request->hasFile('imgabbots')) {
    $image = $request->file('imgabbots');
    $filename = time() . '.' . $image->getClientOriginalExtension();
    $location = public_path('images/help/' . $filename);

    // Image::make($image)->resize(700, 450)->save($location);
    $test = Image::make($image->getRealPath());
    $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);


    $help->imgabbots = $filename;
  }

     $help->save();

    $request->session()->flash('status', 'บันทึกข้อมูลเรียบร้อยแล้ว');
     return redirect()->route('helpabbot.index');

   }

   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
   public function show($id)
   {

     $help = Helpabbot::find($id);
     $title = $help->name;
     $help123 = Helpabbot::take(4)->inRandomOrder()->get();
     $cate = Category::all();
  $postre = Post::where('active', '1')->orderBy('id', 'DESC')->take(6)->get();
     return view('helpabbot.show', compact('help','title','postre','cate','help123'));
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return Response
    */
   public function edit($id)
   {
     //$tags = Tag::all();
      $help = Helpabbot::find($id);
     return view('helpabbot.edit', compact('help'));
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @return Response
    */
   public function update(Request $request, $id)
   {
     $user = Helpabbot::find($id);
     $this->validate($request, [
         'name' => 'required',
         'oldname' => 'required|unique:helpabbots,name,'.$user->id,
        //  'ntname' => 'required',
        //  'btname' => 'required',
        //  'wtname' => 'required',
        //  'ptname' => 'required',


     ]);

    $help = Helpabbot::find($id);


    if($request->hasFile('test123')) {
      $image = $request->file('test123');
      $filename = time() . '.' . $image->getClientOriginalExtension();
      $location = public_path('images/help/' . $filename);

      // Image::make($image)->resize(700, 450)->save($location);
      $test = Image::make($image->getRealPath());
      $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
      $oldFilename = $help->imgabbots;
      $help->imgabbots = $filename;



      Storage::delete($oldFilename);
      File::delete(public_path() . '\\images\help\\' . $oldFilename);
    }



      $help->update($request->all());



     return redirect()->route('helpabbot.index')
         ->with('status','อัพเดทข้อมูลเรียบร้อยแล้ว');
   }









   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return Response
    */
   public function destroy($id)
   {
     $help = Helpabbot::find($id);

     $oldFilename = $help->imgabbots;
      if ($oldFilename != 'testssd.jpg')


       {            Storage::delete($oldFilename);
           File::delete(public_path() . '\\images\help\\' . $oldFilename);

                       }
     Helpabbot::destroy($id);

     return redirect()->route('helpabbot.index')
         ->with('status', 'ลบข้อมูลเรียบร้อยแล้ว');
   }
}
