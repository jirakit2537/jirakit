<?php

namespace App\Http\Controllers\Object;

use Auth;
use Image;
use Storage;
use File;
use Carbon\Carbon;
use App\Model\Object;
use App\Model\Cateobject;
use App\Category;
use App\Post;
use App\Model\Subobject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class ObjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $title = 'จัดการข้อมูลวัตถุมงคล';
      $cate = Cateobject::all();
      $Object = Object::orderBy('id', 'DESC')->paginate(5);
      return view('object.index', compact('Object','title','cate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      $cate = Cateobject::pluck('name', 'id');
        //$tags = Tag::all();
        $title = 'สร้างประวัติวัตถุมงคลใหม่';
         return view('object.create', compact('cate','title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

       $this->validate($request, [
           'title' => 'required|unique:objects,title',
           'slug' => 'required|unique:objects,slug' ,
           'name' => 'required',
           'content' => 'required',
           'Cateobject_id' => 'required',
            'public' => 'required',
           'images' => 'required|mimes:jpg,jpeg,png|max:2500',

       ]);

       $object = new Object();
       $object->title = $request->input('title');
       $object->slug = $request->input('slug');
       $object->name = $request->input('name');
        $object->public = $request->input('public');
       $object->content = $request->input('content');
        $object->Cateobject_id = $request->input('Cateobject_id');
        $object->open = isset($request['open']);




            //บันทึกรูป
    if($request->hasFile('images')) {
      $image = $request->file('images');
      $filename = time() . '.' . $image->getClientOriginalExtension();
      $location = public_path('images/object/' . $filename);
      $locaresize = public_path('images/object/resize/' . $filename);
      // Image::make($image)->resize(700, 400)->save($location);
      $test = Image::make($image->getRealPath())->resize(1280, 720, function ($constraint) {
        $constraint->aspectRatio();
        $constraint->upsize();
    });
      $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);;
      Image::make($image)->resize(50, 50)->save($locaresize);

      $object->images = $filename;
    }
 $object->save();

// ส่วน เนื้อต่างๆๆ

$data = [];
$namepho = $request->input('namephoto');
if(!empty($namepho)){
foreach ($namepho as $key => $step) {
$data = new Subobject;

    if ($request->hasFile('imgfront.' . $key)) {
        $file = $request->file('imgfront.' . $key);
        // work with $file
        $extension = $file->getClientOriginalExtension();
        $filename = strval(time()).str_random(5).'.'.$extension;
          $location = public_path('images/object/' . $filename);
          //Image::make($file)->resize(700, 400)->save($location);
          $test = Image::make($file->getRealPath());
          $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
          $data->imgfront = $filename;
    }
    if ($request->hasFile('imgback.' . $key)) {
        $file = $request->file('imgback.' . $key);
        // work with $file
        $extension = $file->getClientOriginalExtension();
        $filename1 = strval(time()).str_random(5).'.'.$extension;
          $location = public_path('images/object/' . $filename1);
        //  Image::make($file)->resize(700, 400)->save($location);
        $test = Image::make($file->getRealPath());
        $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
          $data->imgback = $filename1;
    }
    if ($request->hasFile('imgleft.' . $key)) {
        $file = $request->file('imgleft.' . $key);
        // work with $file
        $extension = $file->getClientOriginalExtension();
        $filename2 = strval(time()).str_random(5).'.'.$extension;
          $location = public_path('images/object/' . $filename2);
        //  Image::make($file)->resize(700, 400)->save($location);
        $test = Image::make($file->getRealPath());
        $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
          $data->imgleft = $filename2;
    }
    if ($request->hasFile('imgright.' . $key)) {
        $file = $request->file('imgright.' . $key);
        // work with $file
        $extension = $file->getClientOriginalExtension();
        $filename3 = strval(time()).str_random(5).'.'.$extension;
          $location = public_path('images/object/' . $filename3);
        //  Image::make($file)->resize(700, 400)->save($location);
        $test = Image::make($file->getRealPath());
        $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
          $data->imgright = $filename3;
    }


    $data->Object_id = $object->id;
     $data->namephoto = $step;
      $data->save();

}
}elseif($namepho){
  // if ($request->hasFile('imgfront')) {
  //     $file = $request->file('imgfront');
  //     // work with $file
  //     $extension = $file->getClientOriginalExtension();
  //     $filename = strval(time()).str_random(5).'.'.$extension;
  //       $location = public_path('images/object/' . $filename);
  //       Image::make($file)->resize(700, 400)->save($location);
  //       $data->imgfront = $filename;
  // }
  // if ($request->hasFile('imgback')) {
  //     $file = $request->file('imgback');
  //     // work with $file
  //     $extension = $file->getClientOriginalExtension();
  //     $filename1 = strval(time()).str_random(5).'.'.$extension;
  //       $location = public_path('images/object/' . $filename1);
  //       Image::make($file)->resize(700, 400)->save($location);
  //       $data->imgback = $filename1;
  // }
  // if ($request->hasFile('imgleft')) {
  //     $file = $request->file('imgleft');
  //     // work with $file
  //     $extension = $file->getClientOriginalExtension();
  //     $filename2 = strval(time()).str_random(5).'.'.$extension;
  //       $location = public_path('images/object/' . $filename2);
  //       Image::make($file)->resize(700, 400)->save($location);
  //       $data->imgleft = $filename2;
  // }
  // if ($request->hasFile('imgright')) {
  //     $file = $request->file('imgright');
  //     // work with $file
  //     $extension = $file->getClientOriginalExtension();
  //     $filename3 = strval(time()).str_random(5).'.'.$extension;
  //       $location = public_path('images/object/' . $filename3);
  //       Image::make($file)->resize(700, 400)->save($location);
  //       $data->imgright = $filename3;
  // }
  //
  //
  // $data->Object_id = $object->id;
  //  $data->namephoto = $namepho;
  //   $data->save();
}else{

}





// dd($request);


      //
      //
      $request->session()->flash('status', 'บันทึกข้อมูลเรียบร้อยแล้ว');
       return redirect()->route('object.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {

        $object = Object::where('slug', '=',$slug)->first();
        $cateob = Cateobject::all();
        $cate = Category::all();
          $namephoto = Subobject::where('Object_id', $object->id)->get();
           $help123 = Object::where('open', '1')->take(4)->inRandomOrder()->get();
        $postre = Post::where('active', '1')->orderBy('id', 'DESC')->take(6)->get();
        $title = $object->title;
        return view('object.show', compact('object','title','cate','postre','cateob','help123','namephoto'));


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
      $object = Object::find($id);
      $cate = Cateobject::pluck('name', 'id');
      $namephoto = Subobject::where('Object_id', $id)->get();
        $title = 'สร้างประวัติวัตถุมงคลใหม่';
         return view('object.edit', compact('cate','title','object','namephoto'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


     public function ajaxImageUploadPost(Request $request)
        {

          $validator = Validator::make($request->all(), [
                 'namephoto' => 'required',

               ]);

               if ($validator->passes()) {



            $input = $request->all();
            $request->except('_token');
            $img1 = $request->file('imgfront');
            $img2 = $request->file('imgback');
            $img3 = $request->file('imgleft');
            $img4 = $request->file('imgright');
                   if($request->hasFile('imgfront')) {
            $input['imgfront'] = strval(time()).str_random(5).'.'.$request->imgfront->getClientOriginalExtension();
               $location = public_path('images/object/' . $input['imgfront']);
              //  Image::make($img1)->resize(700, 400)->save($location);
              $test = Image::make($img1->getRealPath());
              $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
}
 if($request->hasFile('imgback')) {
                $input['imgback'] = strval(time()).str_random(5).'.'.$request->imgback->getClientOriginalExtension();
                   $location = public_path('images/object/' . $input['imgback']);
                    //Image::make($img2)->resize(700, 400)->save($location);
                    $test = Image::make($img2->getRealPath());
                    $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
}
 if($request->hasFile('imgleft')) {
                    $input['imgleft'] = strval(time()).str_random(5).'.'.$request->imgleft->getClientOriginalExtension();
                       $location = public_path('images/object/' . $input['imgleft']);
                       $test = Image::make($img3->getRealPath());
                       $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
                      //  Image::make($img3)->resize(700, 400)->save($location);
}
 if($request->hasFile('imgright')) {
                        $input['imgright'] = strval(time()).str_random(5).'.'.$request->imgright->getClientOriginalExtension();
                           $location = public_path('images/object/' . $input['imgright']);
                        //    Image::make($img4)->resize(700, 400)->save($location);
                        $test = Image::make($img4->getRealPath());
                        $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
}
          $data =  Subobject::create($input);

            // return response ()->json (['return' => 'some data']);
            return back()
                  ->with('status','เพิ่มข้อมูลเรียบร้อย.');
          }

         return response()->json(['error'=>$validator->errors()->all()]);
       }






       public function editItem(Request $request, $id) {


   $data = Subobject::find($id);



            $request->except('_token');




               if($request->hasFile('ggwp')) {


                $image = $request->file('ggwp');
                $filename = strval(time()).str_random(5).'.'. $image->getClientOriginalExtension();
                $location = public_path('images/object/' . $filename);
              //  Image::make($image)->resize(700, 400)->save($location);
              $test = Image::make($image->getRealPath())->resize(1280, 720, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
              $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
                $oldFilename = $data->imgfront;
                $data->imgfront = $filename;

                Storage::delete($oldFilename);
                File::delete(public_path() . '\\images\object\\' . $oldFilename);


              }
                 if($request->hasFile('ggwp1')) {
                   $image2 = $request->file('ggwp1');
                   $filename = strval(time()).str_random(5).'.'. $image2->getClientOriginalExtension();
                   $location = public_path('images/object/' . $filename);
              //     Image::make($image2)->resize(700, 400)->save($location);
              $test = Image::make($image2->getRealPath())->resize(1280, 720, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });
              $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
                   $oldFilename = $data->imgback;
                   $data->imgback = $filename;

                   Storage::delete($oldFilename);
                   File::delete(public_path() . '\\images\object\\' . $oldFilename);

                  }
                       if($request->hasFile('epoch')) {
                         $image3 = $request->file('epoch');
                         $filename = strval(time()).str_random(5).'.'. $image3->getClientOriginalExtension();
                         $location = public_path('images/object/' . $filename);
                         //Image::make($image3)->resize(700, 400)->save($location);
                         $test = Image::make($image3->getRealPath())->resize(1280, 720, function ($constraint) {
                           $constraint->aspectRatio();
                           $constraint->upsize();
                       });
                         $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
                         $oldFilename = $data->imgleft;
                         $data->imgleft = $filename;

                         Storage::delete($oldFilename);
                         File::delete(public_path() . '\\images\object\\' . $oldFilename);

                      }
                           if($request->hasFile('arma')) {
                             $image4 = $request->file('arma');
                             $filename = strval(time()).str_random(5).'.'. $image4->getClientOriginalExtension();
                             $location = public_path('images/object/' . $filename);
                    //         Image::make($image4)->resize(700, 400)->save($location);
                    $test = Image::make($image4->getRealPath())->resize(1280, 720, function ($constraint) {
                      $constraint->aspectRatio();
                      $constraint->upsize();
                  });
                    $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
                             $oldFilename = $data->imgright;
                             $data->imgright = $filename;

                             Storage::delete($oldFilename);
                             File::delete(public_path() . '\\images\object\\' . $oldFilename);
                          }
                          // $request->imgfront = $data->imgfront;
                          //   $request->imgback = $data->imgback;
                          //     $request->imgleft = $data->imgleft;
                          //       $request->imgright = $data->imgright;

          $data->update($request->all());

            // return response ()->json (['return' => 'some data']);
            return back()->with('status','อัพเดทข้อมูลเรียบร้อยแล้ว.');
          }












    public function update(Request $request, $id)
    {
      $user = Object::find($id);

      $this->validate($request, [
   'title' => 'required',
   'slug' => 'required|unique:objects,slug,'.$user->id,
   'name' => 'required',
   'content' => 'required',
   'Cateobject_id' => 'required',
   'images' => 'mimes:png,jpg,jpeg|max:51200',
      ]);
     $object = Object::find($id);
  //  $request['open'] = isset($request['open']) ? 1 : 0;
     $object->open = isset($request['open']);

   if($request->hasFile('images')) {
     $image = $request->file('images');
     $filename = time() . '.' . $image->getClientOriginalExtension();
     $location = public_path('images/object/' . $filename);
     $locaresize = public_path('images/object/resize/' . $filename);
  //  Image::make($image)->resize(700, 550)->save($location);
  $test = Image::make($image->getRealPath())->resize(1280, 720, function ($constraint) {
    $constraint->aspectRatio();
    $constraint->upsize();
});

  $watermark = Image::make('Frame.png')->insert($test, 'center')->save($location);
  // $img->insert($watermark, 'center');

  //  $watermark->insert($test, 'center');
     // create new Intervention Image
    //  $img = Image::make($image);
     //
    //  // paste another image
    //  $img->insert('public/Frame.png');

    //  // create a new Image instance for inserting
    //  $watermark = Image::make('public/watermark.png');
    //  $img->insert($watermark, 'center');
     //
    //  // insert watermark at bottom-right corner with 10px offset
    //  $img->insert('public/watermark.png', 'bottom-right', 10, 10);






       Image::make($image)->resize(50, 50)->save($locaresize);
     $oldFilename = $object->images;

     $object->images = $filename;

     Storage::delete($oldFilename);
     File::delete(public_path() . '\\images\object\\' . $oldFilename);
    File::delete(public_path() . '\\images\object\resize\\' . $oldFilename);
   }


       $object->update($request->all());


      //  $datas = Subobject::whereObjectId($request->objectid)->get();
       //
      //  foreach($datas as $data) {
       //
      //    //ลบรูปเก่่า
      //    $oldFilename = $data->imgfront;
      //    $oldFilename1 = $data->imgback;
      //    $oldFilename2 = $data->imgleft;
      //    $oldFilename3 = $data->imgright;
      //         Storage::delete($oldFilename);
      //         Storage::delete($oldFilename1);
      //         Storage::delete($oldFilename2);
      //         Storage::delete($oldFilename3);
      //          File::delete(public_path() . '\\images\post\\' . $oldFilename);
      //          File::delete(public_path() . '\\images\post\\' . $oldFilename1);
      //          File::delete(public_path() . '\\images\post\\' . $oldFilename2);
      //          File::delete(public_path() . '\\images\post\\' . $oldFilename3);
       //
       //
      //    $data->delete();
      //  }

      //  foreach ($request->input('namephoto') as $key => $step) {
      //    $subobject = new Subobject;
       //
          //  if ($request->hasFile('imgfront.' . $key)) {
          //      $file = $request->file('imgfront.' . $key);
          //      // work with $file
          //      $extension = $file->getClientOriginalExtension();
          //      $filename = strval(time()).str_random(5).'.'.$extension;
          //        $location = public_path('images/post/' . $filename);
          //        Image::make($file)->resize(700, 400)->save($location);
          //        $subobject->imgfront = $filename;
          //  }
      //      if ($request->hasFile('imgback.' . $key)) {
      //          $file = $request->file('imgback.' . $key);
      //          // work with $file
      //          $extension = $file->getClientOriginalExtension();
      //          $filename1 = strval(time()).str_random(5).'.'.$extension;
      //            $location = public_path('images/post/' . $filename1);
      //            Image::make($file)->resize(700, 400)->save($location);
      //            $subobject->imgback = $filename1;
      //      }
      //      if ($request->hasFile('imgleft.' . $key)) {
      //          $file = $request->file('imgleft.' . $key);
      //          // work with $file
      //          $extension = $file->getClientOriginalExtension();
      //          $filename2 = strval(time()).str_random(5).'.'.$extension;
      //            $location = public_path('images/post/' . $filename2);
      //            Image::make($file)->resize(700, 400)->save($location);
      //            $subobject->imgleft = $filename2;
      //      }
      //      if ($request->hasFile('imgright.' . $key)) {
      //          $file = $request->file('imgright.' . $key);
      //          // work with $file
      //          $extension = $file->getClientOriginalExtension();
      //          $filename3 = strval(time()).str_random(5).'.'.$extension;
      //            $location = public_path('images/post/' . $filename3);
      //            Image::make($file)->resize(700, 400)->save($location);
      //            $subobject->imgright = $filename3;
      //      }
       //
       //
      //      $subobject->Object_id = $object->id;
      //      $subobject->namephoto = $step;
      //      $subobject->save();
      //  }



      return redirect()->route('object.index')->with('status','อัพเดทข้อมูลเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
     public function subobject($id)
     {
       $object = Subobject::find($id);

       //$post->Category()->detach();
      //  $data = Subobject::whereObjectId($object->id)->get();


       if($object->imgfront){
           $oldFilename = $object->imgfront;
       }
       if($object->imgback){
            $oldFilename2 = $object->imgback;
       }
       if($object->imgleft){
           $oldFilename3 = $object->imgleft;
       }
       if($object->imgright){
                $oldFilename4 = $object->imgright;
       }

        if ($oldFilename != 'test.jpg')
         {
           Storage::delete($oldFilename);
           File::delete(public_path() . '\\images\object\\' . $oldFilename);
                         }

                         if ($oldFilename2 != 'test.jpg')
                          {
                            Storage::delete($oldFilename2);
                            File::delete(public_path() . '\\images\object\\' . $oldFilename2);
                                          }
                                          if ($oldFilename3 != 'test.jpg')
                                           {
                                             Storage::delete($oldFilename3);
                                             File::delete(public_path() . '\\images\object\\' . $oldFilename3);
                                                           }
                                                           if ($oldFilename4 != 'test.jpg')
                                                            {
                                                              Storage::delete($oldFilename4);
                                                              File::delete(public_path() . '\\images\object\\' . $oldFilename4);
                                                                            }
       Subobject::destroy($id);

       return back()
           ->with('status', 'ลบข้อมูลเรียบร้อยแล้ว');

     }
    public function destroy($id)
    {
      $object = Object::find($id);

      if($object->images){
          $oldFilename = $object->images;
      }

       if ($oldFilename != 'testssd.jpg')


        {
          Storage::delete($oldFilename);
          File::delete(public_path() . '\\images\object\\' . $oldFilename);
         File::delete(public_path() . '\\images\object\resize\\' . $oldFilename);
                        }




                        $data = Subobject::whereObjectId($object->id)->get();



            if($data != null) {

                      foreach ($data as $key) {

                                            if($key->imgfront){
                                                $oldFilename1 = $key->imgfront;
                                            }
                                            if($key->imgback){
                                                 $oldFilename2 = $key->imgback;
                                            }
                                            if($key->imgleft){
                                                $oldFilename3 = $key->imgleft;
                                            }
                                            if($key->imgright){
                                                     $oldFilename4 = $key->imgright;
                                            }


                                             if ($oldFilename != 'test.jpg')
                                              {
                                                Storage::delete($oldFilename1);
                                                File::delete(public_path() . '\\images\object\\' . $oldFilename1);
                                                              }

                                                              if ($oldFilename2 != 'test.jpg')
                                                               {
                                                                 Storage::delete($oldFilename2);
                                                                 File::delete(public_path() . '\\images\object\\' . $oldFilename2);
                                                                               }
                                                                               if ($oldFilename3 != 'test.jpg')
                                                                                {
                                                                                  Storage::delete($oldFilename3);
                                                                                  File::delete(public_path() . '\\images\object\\' . $oldFilename3);
                                                                                                }
                                                                                                if ($oldFilename4 != 'test.jpg')
                                                                                                 {
                                                                                                   Storage::delete($oldFilename4);
                                                                                                   File::delete(public_path() . '\\images\object\\' . $oldFilename4);

                                                                           }




                                                                         }
                                                                         Subobject::whereObjectId($object->id)->delete();
                                                                        }

// dd($data);
   Object::destroy($id);

  return redirect()->route('object.index')->with('status','ลบข้อมูลเรียบร้อยแล้ว');

    }
}
