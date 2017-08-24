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

class ImageController extends Controller
{


  public function postAdd(Request $request)
  {


    // $file = $request->file('file');
    // $filename = uniqid() . $file->getClientOriginalName();
    //
    // $file->move('gallery/images', $filename);
    // $gallery1 = Album::find('name');
    // $gallery = Album::find($request->input('album_id'));
    // $image = $gallery->images()->create([
    //   'gallery_id' => $request->input('album_id'),
    //   'image' => $filename,
    //   'description' => "อัลบั้ม",
    //   // 'file_path' => 'gallery/images' . $filename,


        $image = $request->file('file');
        $filename = uniqid() . time() . '.' . $image->getClientOriginalExtension();
        $location = public_path('gallery/images/' . $filename);
        $locaresize = public_path('gallery/images/resize/' . $filename);
        // Image::make($image)->resize(700, 400)->save($location);
         Image::make($image)->resize(200, 200)->save($locaresize);
        // $test = Image::make($image->getRealPath());
        $test = Image::make($image->getRealPath())->resize(1280, 720, function ($constraint) {
          $constraint->aspectRatio();
          $constraint->upsize();
      });
        $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
        $gallery = Album::find($request->input('album_id'));
        $image = $gallery->images()->create([
          'gallery_id' => $request->input('album_id'),
          'image' => $filename,
          'description' => "อัลบั้ม",


    ]);





  }
  public function destroy($id)
  {
    $Images = Images::find($id);

    $oldFilename = $Images->image;



      {            Storage::delete($oldFilename);
         File::delete(public_path() . '\\gallery\images\\' . $oldFilename);
            File::delete(public_path() . '\\gallery\images\resize\\' . $oldFilename);
                      }
    $Images->delete();

    return redirect()->route('gallery.show',$Images->album_id)
        ->with('status', 'ลบข้อมูลเรียบร้อย');
  }
}
