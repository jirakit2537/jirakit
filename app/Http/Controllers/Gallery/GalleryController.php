<?php

namespace App\Http\Controllers\Gallery;

use App\Model\Album;
use App\Model\Images;
use View;
use Image;
use Storage;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GalleryController extends Controller
{
  public function index()
  {
    //$Post = Post::all();
    $title = 'จัดการข้อมูล Tag';
    $albums = Album::orderBy('id', 'DESC')->paginate(5);

    return view('gallery.index', compact('albums','title'));
  }


  public function create()
  {

   //$tags = Tag::all();
   $title = 'จัดการข้อมูลผู้ใช้';
    return view('gallery.create',compact('title'));
  }

  public function store(Request $request)
  {
    $this->validate($request, [

        'name' => 'required|unique:albums,name' ,
        'description' => 'required' ,


    ]);

    $albums = new Album();
    $albums->name = $request->input('name');
    $albums->description = $request->input('description');


    //บันทึกรูป
if($request->hasFile('cover_image')) {
$cover_image = $request->file('cover_image');
$filename = time() . '.' . $cover_image->getClientOriginalExtension();
$location = public_path('images/albums/' . $filename);
$locaresize = public_path('images/albums/resize/' . $filename);
// Image::make($cover_image)->resize(700, 400)->save($location);
 Image::make($cover_image)->resize(50, 50)->save($locaresize);
// $test = Image::make($cover_image->getRealPath());
$test = Image::make($cover_image->getRealPath())->resize(1280, 720, function ($constraint) {
  $constraint->aspectRatio();
  $constraint->upsize();
});
$watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);

$albums->cover_image = $filename;
}
    $albums->save();


    return redirect()->route('gallery.index')
        ->with('status','บันทึกข้อมูลเรียบร้อย');
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
   public function show($name)
   {
    //  $Post = Post::all();
     $album = Album::where('id', '=',$name)->first();
     $title = $album->name;
     return view('gallery.show', compact('Post','title','album'));
   }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    $title = 'จัดการข้อมูลผู้ใช้';
    $albums = Album::find($id);

    return view('gallery.edit', compact('albums','title'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update(Request $request, $id)
  {
    $user = Album::find($id);

    $this->validate($request, [


        'name' => 'required|unique:albums,name,'.$user->id,
        'description' => 'required' ,
        'cover_image' => 'mimes:jpg,jpeg,png|max:2500',
    ]);
    $albums = Album::find($id);


    if($request->hasFile('images')) {
    $cover_image = $request->file('images');
    $filename = time() . '.' . $cover_image->getClientOriginalExtension();
    $location = public_path('images/albums/' . $filename);
    $locaresize = public_path('images/albums/resize/' . $filename);
    // Image::make($cover_image)->resize(700, 400)->save($location);
    $test = Image::make($cover_image->getRealPath())->resize(1280, 720, function ($constraint) {
      $constraint->aspectRatio();
      $constraint->upsize();
    });
    $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
    Image::make($cover_image)->resize(50, 50)->save($locaresize);
  $oldFilename = $albums->cover_image;


    $albums->cover_image = $filename;

      Storage::delete($oldFilename);
      File::delete(public_path() . '\\images\albums\\' . $oldFilename);
         File::delete(public_path() . '\\images\albums\resize\\' . $oldFilename);
    }



    $albums->update($request->all());

    return redirect()->route('gallery.index')
        ->with('status','อัพเดทข้อมูลเรียบร้อย');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $albums = Album::find($id);



    $oldFilename = $albums->cover_image;
     if ($oldFilename != 'testssd.jpg')


      {            Storage::delete($oldFilename);
        File::delete(public_path() . '\\images\albums\\' . $oldFilename);
           File::delete(public_path() . '\\images\albums\resize\\' . $oldFilename);
                      }


                      foreach($albums->images as $object)
                              {
                                  $oldFilename2 = $object->image;
                                File::delete(public_path() . '\\gallery\images\\' . $oldFilename2);
                                   File::delete(public_path() . '\\gallery\images\resize\\' . $oldFilename2);

                              }
                    //   $oldFilename2 = $albums->images()->image;
                    // File::delete(public_path() . '\\gallery\images\\' . $oldFilename2);
                    //    File::delete(public_path() . '\\gallery\images\resize\\' . $oldFilename2);
                         $albums->images()->delete();


    Album::destroy($id);



    return redirect()->route('gallery.index')
        ->with('status', 'ลบข้อมูลเรียบร้อย');
  }






}
